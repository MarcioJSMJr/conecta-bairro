<?php
require_once __DIR__ . '/exceptions.php';

/**
 * @template T
 */
class Model
{
    protected mysqli $db;
    protected string $table_name;
    protected string $entry_class;
    protected string $exceptions_enum_class;
    protected bool $uses_soft_delete;

    public function __construct(mysqli $db, string $table_name, string $entry_class, string $exceptions_enum_class, bool $uses_soft_delete = true)
    {
        $this->db = $db;
        $this->table_name = $table_name;
        $this->entry_class = $entry_class;
        $this->exceptions_enum_class = $exceptions_enum_class;
        $this->uses_soft_delete = $uses_soft_delete;
    }

    public function insert(array $fields): int
    {
        $columns = [];
        $values = [];
        foreach ($fields as $key => $value) {
            if ($value !== null) {
                $columns[] = "`$key`";
                $values[] = $value;
            }
        }
        if (empty($values)) {
            throw new InvalidArgumentException("Nenhum dado fornecido para inserção no banco de dados.");
        }
        $query_columns = implode(', ', $columns);
        $placeholders = array_fill(0, count($values), '?');
        $query_values = implode(', ', $placeholders);
        $query = "INSERT INTO `$this->table_name` ($query_columns) VALUES ($query_values)";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erro ao preparar a query SQL: " . $this->db->error);
        }
        $types = $this->get_params_string($values);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Erro na inserção dos dados: " . $stmt->error);
        }
        $insert_id = $stmt->insert_id;
        $stmt->close();
        return $insert_id;
    }

    /**
     * @return T
     */
    public function retrieve(int $id): object
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table_name WHERE id=? AND deleted_at IS NULL");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            return new $this->entry_class($result->fetch_assoc());
        } else {
            if (!defined("{$this->exceptions_enum_class}::ID_NOT_FOUND")) {
                throw new ModelException(ModelExceptionsEnum::MISSING_ID_NOT_FOUND_CONSTANT);
            }
            throw new ModelException($this->exceptions_enum_class::ID_NOT_FOUND);
        }
    }

    /**
     * @return T
     */
    public function true_retrieve(int $id): object
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table_name WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            return new $this->entry_class($result->fetch_assoc());
        } else {
            if (!defined("{$this->exceptions_enum_class}::ID_NOT_FOUND")) {
                throw new ModelException(ModelExceptionsEnum::MISSING_ID_NOT_FOUND_CONSTANT);
            }
            throw new ModelException($this->exceptions_enum_class::ID_NOT_FOUND);
        }
    }

    function select(string $query, array $parameters = [], string $types = '', ?int $limit = null, ?int $offset = null): array
    {
        $array = ["LIMIT" => $limit, "OFFSET" => $offset];
        $query_parameters = [];
        $bind_params = $parameters;

        foreach ($array as $key => $value) {
            if ($value !== null) {
                $query_parameters[] = "$key ?";
                $types .= 'i';
                $bind_params[] = $value;
            }
        }
        if (!empty($query_parameters)) {
            $query .= " " . implode(" ", $query_parameters);
        }

        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erro ao preparar a query (select): " . $this->db->error);
        }
        if (!empty($bind_params)) {
            if (empty($types)) {
                $types = $this->get_params_string($bind_params);
            }
            $stmt->bind_param($types, ...$bind_params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $output = [];
        while ($row = $result->fetch_assoc()) {
            $output[] = new $this->entry_class($row);
        }
        return $output;
    }

    public function update(int $id, array $fields): void
    {
        if (!$this->id_exists($id)) {
            if (!defined("{$this->exceptions_enum_class}::ID_NOT_FOUND")) {
                throw new ModelException(ModelExceptionsEnum::MISSING_ID_NOT_FOUND_CONSTANT);
            }
            throw new ModelException($this->exceptions_enum_class::ID_NOT_FOUND);
        };
        $columns = [];
        $values = [];
        foreach ($fields as $key => $value) {
            if ($value !== null) {
                $columns[] = "`$key`=?";
                $values[] = $value;
            }
        }
        if (empty($values)) {
            return;
        }
        $query_columns = implode(", ", $columns);
        $query = "UPDATE `$this->table_name` SET $query_columns WHERE id=?";
        $values[] = $id;
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erro ao preparar a query (update): " . $this->db->error);
        }
        $types = $this->get_params_string($values);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        $stmt->close();
    }

    public function delete(int $id): void
    {
        if (!$this->id_exists($id)) {
            if (!empty($this->exceptions_enum_class) && defined("{$this->exceptions_enum_class}::ID_NOT_FOUND")) {
                throw new ModelException($this->exceptions_enum_class::ID_NOT_FOUND);
            }
            throw new ModelException(ModelExceptionsEnum::MISSING_ID_NOT_FOUND_CONSTANT);
        }

        if ($this->uses_soft_delete) {
            $stmt = $this->db->prepare("UPDATE $this->table_name SET deleted_at=NOW() WHERE id=?");
        } else {
            $stmt = $this->db->prepare("DELETE FROM $this->table_name WHERE id=?");
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function id_exists(int $id): bool
    {
        $query = "SELECT COUNT(t.id) AS total FROM $this->table_name t WHERE id=?";
        $params = [$id];

        if ($this->uses_soft_delete) {
            $query .= " AND deleted_at IS NULL";
        }

        return $this->count($query, $params) > 0;
    }

    public function true_id_exists(int $id): bool
    {
        return $this->count("SELECT COUNT(t.id) AS total FROM $this->table_name t WHERE id=?", [$id]) > 0;
    }

    function count(string $query, array $parameters = [], string $types = '', string $count_field = 'total'): int
    {
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erro ao preparar a query (count): " . $this->db->error);
        }
        if (!empty($parameters)) {
            if (empty($types)) {
                $types = $this->get_params_string($parameters);
            }
            $stmt->bind_param($types, ...$parameters);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data ? (int)$data[$count_field] : 0;
    }

    function exists(string $query, array $parameters = [], string $types = ''): bool
    {
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erro ao preparar a query (exists): " . $this->db->error);
        }
        if (!empty($parameters)) {
            if (empty($types)) {
                $types = $this->get_params_string($parameters);
            }
            $stmt->bind_param($types, ...$parameters);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows > 0;
    }

    public function list(?int $limit = 15, ?int $offset = null): array
    {
        $query = "SELECT * FROM $this->table_name";
        if ($this->uses_soft_delete) {
            $query .= " WHERE deleted_at IS NULL";
        }
        $query .= " ORDER BY id DESC";
        return $this->select($query, [], '', $limit, $offset);
    }

    public function true_list(?int $limit = 15, ?int $offset = null): array
    {
        return $this->select("SELECT * FROM $this->table_name ORDER BY id DESC", [], $limit, $offset);
    }

    public static function run(mysqli $db, callable $closure): void
    {
        try {
            $db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $closure();
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            if ($e instanceof ModelException) {
                ModelException::handle_exception($e);
            } else {
                set_status(SessionStatusCode::DB_ERROR(), 'Ocorreu um erro inesperado no servidor. Tente novamente.');
                header('Location: ' . ADMIN_ROOT . 'register');
                exit;
            }
        }
    }
    function validate_file_extension(array $file, array $valid_extensions): bool|null
    {
        if ($file === null) return null;
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $valid_extensions)) {
            return false;
        }
        $mimeType = mime_content_type($file['tmp_name']);
        $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/bmp'];
        if (!in_array($mimeType, $validMimeTypes)) {
            return false;
        }
        return true;
    }
    function upload_file(string $folder, array $file): ?string
    {
        $filename = pathinfo($file['name'], PATHINFO_FILENAME);
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = create_slug($filename) . '.' . $extension;
        $file_path = $folder . $new_filename;
        $i = 0;
        while (file_exists($file_path)) {
            $i++;
            $new_filename = create_slug($filename) . '_' . $i . '.' . $extension;
            $file_path = $folder . $new_filename;
        }
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return $new_filename;
        } else {
            return null;
        }
    }

    public function pepper_password(string $plain_text): string
    {
        if (!defined('PASSWORD_PEPPER')) {
            throw new Exception("Constante de segurança PASSWORD_PEPPER não definida.");
        }
        return hash_hmac("sha256", $plain_text, PASSWORD_PEPPER);
    }

    public function encrypt_password(?string $plain_text): ?string
    {
        if (empty($plain_text)) return null;
        $peppered = $this->pepper_password($plain_text);
        return password_hash($peppered, PASSWORD_ARGON2I);
    }

    function get_params_string(array $values): string
    {
        $output = '';
        foreach ($values as $value) {
            if (is_string($value)) {
                $output .= 's';
            } elseif (is_int($value) || is_bool($value)) {
                $output .= 'i';
            } elseif (is_double($value) || is_float($value)) {
                $output .= 'd';
            } else {
                $output .= 's';
            }
        }
        return $output;
    }
    function get_total_entries(): int
    {
        $query = "SELECT COUNT(t.id) AS total FROM $this->table_name t";
        if ($this->uses_soft_delete) {
            $query .= " WHERE deleted_at IS NULL";
        }
        return $this->count($query);
    }

    function true_get_total_entries(): int
    {
        return $this->count("SELECT COUNT(t.id) AS total FROM $this->table_name t");
    }
}
