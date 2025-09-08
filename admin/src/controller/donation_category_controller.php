<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../model/donation_category.php';
require_once __DIR__ . '/../helpers/logger.php';
require_once __DIR__ . '/../helpers/utils.php';

class DonationCategoryController extends Controller
{
    private DonationCategoryModel $model;
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $model = new DonationCategoryModel($db);
        parent::__construct($db, $model);
        $this->model = $model;
        $this->db = $db;
    }

    public function create(): void
    {
        $this->require_post_fields(['name']);
        $name = $this->sanitize_post('name', 'string');
        $slug = create_slug($name);

        $new_id = $this->model->create($name, $slug);

        Logger::log($this->db, 'create_donation_category', 'DonationCategory', $new_id, ['name' => $name]);

        set_status(SessionStatusCode::SUCCESS(), "Categoria criada com sucesso!");
        header('Location: ' . ADMIN_URL . 'donation-categories');
        exit;
    }

    public function modify(int $id): void
    {
        $original_category = $this->model->retrieve($id);
        $name = $this->sanitize_post('name', 'string');
        $slug = create_slug($name);

        $this->model->modify($id, $name, $slug);

        Logger::log_update($this->db, 'update_donation_category', $original_category, $_POST);

        set_status(SessionStatusCode::SUCCESS(), "Categoria atualizada com sucesso!");
        header('Location: ' . ADMIN_URL . 'donation-categories');
        exit;
    }

    public function delete(int $id): void
    {
        $category = $this->model->retrieve($id);
        parent::delete($id);

        Logger::log($this->db, 'delete_donation_category', 'DonationCategory', $id, ['name' => $category->name]);

        set_status(SessionStatusCode::SUCCESS(), "Categoria excluída com sucesso!");
        header('Location: ' . ADMIN_URL . 'donation-categories');
        exit;
    }

    public function list_all(): array
    {
        return $this->model->list_all();
    }

    public function get_paginated_list_with_search(int $items_per_page = 12): array
    {
        $search_term = $this->sanitize_get('search', 'string', null);
        $page = $this->sanitize_get('p', 'int', 1);
        $offset = $items_per_page * ($page - 1);

        $items = $this->model->list_filtered($search_term, $items_per_page, $offset);
        $total = $this->model->count_filtered($search_term);

        return [
            'items' => $items,
            'total' => $total
        ];
    }
}
