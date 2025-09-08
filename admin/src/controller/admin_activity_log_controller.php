<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../model/admin_activity_log.php';

class AdminActivityLogController extends Controller
{
    private $model;

    public function __construct(mysqli $db)
    {
        $model = new AdminActivityLogModel($db);
        parent::__construct($db, $model);
        $this->model = $model;
    }

    public function get_paginated_logs(int $items_per_page = 20): array
    {
        return $this->paginated_list($items_per_page);
    }

    public function get_paginated_list_with_search(int $items_per_page = 20): array
    {
        $search = $this->sanitize_get('search', 'string', null);
        $action_type = $this->sanitize_get('action_type', 'string', null);
        $page = $this->sanitize_get('p', 'int', 1);
        $offset = $items_per_page * ($page - 1);

        $items = $this->model->list_filtered($search, $action_type, $items_per_page, $offset);
        $total = $this->model->count_filtered($search, $action_type);

        return [
            'items' => $items,
            'total' => $total
        ];
    }
}
