<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../model/collection_point.php';
require_once __DIR__ . '/../helpers/logger.php';

class CollectionPointController extends Controller
{
    private CollectionPointModel $model;
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $model = new CollectionPointModel($db);
        parent::__construct($db, $model);
        $this->model = $model;
        $this->db = $db;
    }

    public function create(): void
    {
        $this->require_post_fields(['name', 'street', 'neighborhood', 'city', 'state', 'category']);

        $name = $this->sanitize_post('name', 'string');
        $street = $this->sanitize_post('street', 'string');
        $number = $this->sanitize_post('number', 'string', null);
        $neighborhood = $this->sanitize_post('neighborhood', 'string');
        $city = $this->sanitize_post('city', 'string');
        $state = $this->sanitize_post('state', 'string');
        $accepted_materials = $this->sanitize_post('accepted_materials', 'string');
        $category = $this->sanitize_post('category', 'string');

        $new_id = $this->model->create($name, $street, $number, $neighborhood, $city, $state, $accepted_materials, $category);
        Logger::log($this->db, 'create_collection_point', 'CollectionPoint', $new_id, ['name' => $name]);

        set_status(SessionStatusCode::SUCCESS(), "Ponto de coleta criado com sucesso!");
        header('Location: ' . ADMIN_URL . 'collection-points');
        exit;
    }

    public function modify(int $id): void
    {
        $original = $this->model->retrieve($id);

        $name = $this->sanitize_post('name', 'string', null);
        $street = $this->sanitize_post('street', 'string', null);
        $number = $this->sanitize_post('number', 'string', null);
        $neighborhood = $this->sanitize_post('neighborhood', 'string', null);
        $city = $this->sanitize_post('city', 'string', null);
        $state = $this->sanitize_post('state', 'string', null);
        $accepted_materials = $this->sanitize_post('accepted_materials', 'string', null);
        $category = $this->sanitize_post('category', 'string', null);

        $this->model->modify($id, $name, $street, $number, $neighborhood, $city, $state, $accepted_materials, $category);
        Logger::log_update($this->db, 'update_collection_point', $original, $_POST);

        set_status(SessionStatusCode::SUCCESS(), "Ponto de coleta atualizado com sucesso!");
        header('Location: ' . ADMIN_URL . 'collection-points');
        exit;
    }

    public function delete(int $id): void
    {
        $point = $this->model->retrieve($id);
        parent::delete($id);
        Logger::log($this->db, 'delete_collection_point', 'CollectionPoint', $id, ['name' => $point->name]);
        set_status(SessionStatusCode::SUCCESS(), "Ponto de coleta excluído com sucesso!");
        header('Location: ' . ADMIN_URL . 'collection-points');
        exit;
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

    public function list_all(): array
    {
        return $this->model->list(null);
    }
}
