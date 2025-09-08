<?php
require_once __DIR__ . '/../model/model.php';

class Controller
{
    private mysqli $db;
    private Model $model;
    private array $sanitizers = [
        ControllerSanitizersEnum::STRING => 'sanitize_string',
        ControllerSanitizersEnum::INT => 'sanitize_int',
        ControllerSanitizersEnum::FLOAT => 'sanitize_float',
        ControllerSanitizersEnum::MONETARY => 'sanitize_monetary',
        ControllerSanitizersEnum::BOOL => 'sanitize_bool',
        ControllerSanitizersEnum::ARRAY => 'sanitize_array',
    ];

    public function __construct(mysqli $db, Model $model)
    {
        $this->db = $db;
        $this->model = $model;
    }

    public function sanitize_string(?string $input): ?string
    {
        if (is_null($input)) return null;
        $input = trim($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function sanitize_int(?int $input): ?int
    {
        if (is_null($input)) return null;
        return $input;
    }

    public function sanitize_float(?float $input): ?float
    {
        if (is_null($input)) return null;
        return $input;
    }

    public function sanitize_monetary(?string $input): ?int
    {
        if (is_null(value: $input)) return null;

        // Remove os caracteres não numéricos exceto "," e "."
        $input = preg_replace(pattern: '/[^0-9,\.]/', replacement: '', subject: $input);

        // Se o input tem tanto "," como ".", calcula o separador decimal
        if (strpos(haystack: $input, needle: ',') !== false && strpos(haystack: $input, needle: '.') !== false) {
            if (strrpos(haystack: $input, needle: ',') > strrpos(haystack: $input, needle: '.')) {
                // "," é o separador decimal, remover todos os "." (separador de milahres)
                $input = str_replace(search: '.', replace: '', subject: $input);
                $input = str_replace(search: ',', replace: '.', subject: $input); // Normaliza para "."
            } else {
                // "." é o separador decimal, remover todos os "," (separador de milahres)
                $input = str_replace(search: ',', replace: '', subject: $input);
            }
        } else {
            // Se exister somente um entre "," e ".", tratar "," para "."
            if (strpos(haystack: $input, needle: ',') !== false) {
                $input = str_replace(search: ',', replace: '.', subject: $input);
            }
        }

        // Converte a string para float, multiplica por 100 para obter em centavos
        $value = (float)$input * 100;

        // Retorna o valor como inteiro
        return (int)round(num: $value);
    }


    public function sanitize_bool(?bool $input): ?bool
    {
        if (is_null($input)) return null;
        return $input;
    }

    public function sanitize_array(?array $input): ?array
    {
        if (is_null($input)) return null;
        return $input;
    }

    public function require_post_fields(array $fields): void
    {
        foreach ($fields as $value) {
            if (!isset($_POST[$value]) || $_POST[$value] === '') {
                throw new ModelException(ModelExceptionsEnum::REQUIRED_FIELD_MISSING);
            }
        }
    }

    public function require_get_fields(array $fields): void
    {
        foreach ($fields as $value) {
            if (!isset($_GET[$value]) || $_GET[$value] === '') {
                throw new ModelException(ModelExceptionsEnum::REQUIRED_FIELD_MISSING);
            }
        }
    }

    public function sanitize_from_array(array $array, string $key, string $sanitizer, $default = null)
    {
        if (!isset($this->sanitizers[$sanitizer], $this->sanitizers) || !method_exists($this, $this->sanitizers[$sanitizer])) {
            throw new ModelException(ModelExceptionsEnum::INVALID_CONTROLLER_SANITIZER);
        }

        $method = $this->sanitizers[$sanitizer];
        return isset($array[$key]) && (is_string($array[$key]) ? strlen($array[$key]) > 0 : !empty($array[$key])) ? $this->$method($array[$key]) : $default;
    }


    public function sanitize_post(string $key, string $sanitizer, $default = null): mixed
    {
        return $this->sanitize_from_array($_POST, $key, $sanitizer, $default);
    }

    public function sanitize_get(string $key, string $sanitizer, $default = null): mixed
    {
        return $this->sanitize_from_array($_GET, $key, $sanitizer, $default);
    }

    public function sanitize_files(string $key, $default = null)
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] == UPLOAD_ERR_OK ? $_FILES[$key] : $default;
    }

    public function display_pagination(int $total_items, int $items_per_page, $param_name = 'p'): void
    {
        if ($total_items <= $items_per_page) {
            return;
        }

        $params = $_GET;
        $current_page = isset($params[$param_name]) ? $this->sanitize_int($params[$param_name]) : 1;
        $total_pages = ceil($total_items / $items_per_page);
        $current_page = max(1, min($total_pages, $current_page));

        unset($params[$param_name]);
        $base_url = '?' . http_build_query($params) . ($params ? '&' : '');

        $disabled_backwards = $current_page <= 1 ? ' disabled' : '';
        $disabled_forwards = $current_page >= $total_pages ? ' disabled' : '';

    ?>
        <nav aria-label="Pagination">
            <ul class="pagination">
                <li class="page-item<?= $disabled_backwards ?>">
                    <a class="page-link" href="<?= $current_page > 1 ? $base_url . $param_name . '=' . ($current_page - 1) : '#' ?>">
                        <i class="bi bi-chevron-left"></i>
                        <span class="d-none d-md-inline">Anterior</span>
                    </a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item<?= ($i == $current_page) ? ' active' : '' ?>">
                        <a class="page-link" href="<?= $base_url . $param_name . '=' . $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item<?= $disabled_forwards ?>">
                    <a class="page-link" href="<?= $current_page < $total_pages ? $base_url . $param_name . '=' . ($current_page + 1) : '#' ?>">
                        <span class="d-none d-md-inline">Próxima</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php
    }

    public function retrieve(int $id): object
    {
        $id = $this->sanitize_int($id);
        return $this->model->retrieve($id);
    }

    public function true_retrieve(int $id): object
    {
        $id = $this->sanitize_int($id);
        return $this->model->true_retrieve($id);
    }

    public function list(?int $limit = 15, ?int $offset = null): array
    {
        $limit = $this->sanitize_int($limit);
        $offset = $this->sanitize_int($offset);
        return $this->model->list($limit, $offset);
    }

    public function paginated_list(int $items_per_page = 15, string $page_param = 'p'): array
    {
        $page = $this->sanitize_get($page_param, 'int', 1);
        return $this->list($items_per_page, $items_per_page * ($page - 1));
    }

    public function true_list(?int $limit = 15, ?int $offset = null): array
    {
        $limit = $this->sanitize_int($limit);
        $offset = $this->sanitize_int($offset);
        return $this->model->true_list($limit, $offset);
    }

    public function delete(int $id): void
    {
        $id = $this->sanitize_int($id);
        $this->model->delete($id);
    }

    public function id_exists(int $id): bool
    {
        $id = $this->sanitize_int($id);
        return $this->model->id_exists($id);
    }

    public function true_id_exists(int $id): bool
    {
        $id = $this->sanitize_int($id);
        return $this->model->true_id_exists($id);
    }

    public function get_total_entries(): int
    {
        return $this->model->get_total_entries();
    }

    public function true_get_total_entries(): int
    {
        return $this->model->true_get_total_entries();
    }
}

class ControllerSanitizersEnum
{
    const STRING = 'string';
    const INT = 'int';
    const FLOAT = 'float';
    const MONETARY = 'monetary';
    const BOOL = 'bool';
    const ARRAY = 'array';
}
