<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../model/donation.php';
require_once __DIR__ . '/../helpers/utils.php';
require_once __DIR__ . '/../helpers/logger.php';

class DonationController extends Controller
{
    private DonationModel $model;
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $model = new DonationModel($db);
        parent::__construct($db, $model);
        $this->model = $model;
        $this->db = $db;
    }

    public function create_from_site(): void
    {
        if (!is_site_user_logged_in()) {
            set_status(SessionStatusCode::DANGER(), "Você precisa estar logado para cadastrar uma doação.");
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $this->require_post_fields(['title', 'description', 'category_id', 'condition', 'neighborhood']);
        $image = $this->sanitize_files('image');
        if ($image === null) {
            set_status(SessionStatusCode::FORM_ERROR(), "Por favor, envie uma imagem do item.");
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
            exit;
        }
        $user_id = get_site_user_id();
        $title = $this->sanitize_post('title', 'string');
        $description = $this->sanitize_post('description', 'string');
        $category_id = $this->sanitize_post('category_id', 'int');
        $condition = $this->sanitize_post('condition', 'string');
        $neighborhood = $this->sanitize_post('neighborhood', 'string');
        $slug = create_slug($title);
        if ($this->model->slug_exists($slug)) {
            $slug = $slug . '-' . time();
        }

        $this->model->create($user_id, $category_id, $title, $description, $slug, $condition, 'Disponível', $neighborhood, $image);

        set_status(SessionStatusCode::SUCCESS(), "Sua doação foi cadastrada com sucesso! Obrigado por ajudar.");
        header('Location: ' . BASE_URL . 'minha-conta?tab=donations');
        exit;
    }

    public function modify(int $id): void
    {
        $original_donation = $this->model->retrieve($id);

        $category_id = $this->sanitize_post('category_id', 'int', null);
        $title = $this->sanitize_post('title', 'string', null);
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $condition = $this->sanitize_post('condition', 'string', null);
        $status = $this->sanitize_post('status', 'string', null);
        $neighborhood = $this->sanitize_post('neighborhood', 'string', null);
        $image = $this->sanitize_files('image', null);
        $slug = ($title !== null) ? create_slug($title) : null;

        $this->model->modify($id, $category_id, $title, $description, $slug, $condition, $status, $neighborhood, $image);

        Logger::log_update($this->db, 'update_donation', $original_donation, $_POST);

        set_status(SessionStatusCode::SUCCESS(), "Doação atualizada com sucesso!");
        header('Location: ' . ADMIN_URL . 'donations');
        exit;
    }

    public function delete(int $id): void
    {
        $donation = $this->model->retrieve($id);
        parent::delete($id);

        Logger::log($this->db, 'delete_donation', 'Donation', $id, ['title' => $donation->title]);

        set_status(SessionStatusCode::SUCCESS(), "Doação excluída com sucesso!");
        header('Location: ' . ADMIN_URL . 'donations');
        exit;
    }

    public function get_paginated_list_with_filters(int $items_per_page = 10): array
    {
        $search = $this->sanitize_get('search', 'string', null);
        $category_id = $this->sanitize_get('category_id', 'int', null);
        $page = $this->sanitize_get('p', 'int', 1);
        $offset = $items_per_page * ($page - 1);

        $items = $this->model->list_filtered($search, $category_id, $items_per_page, $offset);
        $total = $this->model->count_filtered($search, $category_id);

        return [
            'items' => $items,
            'total' => $total
        ];
    }

    public function list_by_user(int $user_id): array
    {
        return $this->model->list_by_user_id($user_id);
    }

    public function delete_from_site(int $id, int $user_id): void
    {
        $donation = $this->model->retrieve($id);

        if ($donation->user_id !== $user_id) {
            set_status(SessionStatusCode::DANGER(), "Você não tem permissão para excluir esta doação.");
            header('Location: ' . BASE_URL . 'minha-conta?tab=donations');
            exit;
        }

        parent::delete($id);

        Logger::log($this->db, 'delete_donation_from_site', 'Donation', $id, ['title' => $donation->title]);

        set_status(SessionStatusCode::SUCCESS(), "Doação excluída com sucesso!");
        header('Location: ' . BASE_URL . 'minha-conta?tab=donations');
        exit;
    }

    public function modify_from_site(int $id, int $user_id): void
    {
        $original_donation = $this->model->retrieve($id);

        if ($original_donation->user_id !== $user_id) {
            set_status(SessionStatusCode::DANGER(), "Você não tem permissão para editar esta doação.");
            header('Location: ' . BASE_URL . 'minha-conta?tab=donations');
            exit;
        }

        $category_id = $this->sanitize_post('category_id', 'int', null);
        $title = $this->sanitize_post('title', 'string', null);
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $condition = $this->sanitize_post('condition', 'string', null);
        $status = $this->sanitize_post('status', 'string', null);
        $neighborhood = $this->sanitize_post('neighborhood', 'string', null);
        $image = $this->sanitize_files('image', null);

        $slug = null;
        if ($title !== null && $title !== $original_donation->title) {
            $slug = create_slug($title);
            if ($this->model->slug_exists($slug)) {
                $slug = $slug . '-' . time();
            }
        }


        $this->model->modify($id, $category_id, $title, $description, $slug, $condition, $status, $neighborhood, $image);

        set_status(SessionStatusCode::SUCCESS(), "Doação atualizada com sucesso!");
        header('Location: ' . BASE_URL . 'minha-conta?tab=donations');
        exit;
    }

    public function retrieve_by_slug(string $slug): ?Donation
    {
        return $this->model->retrieve_by_slug($slug);
    }

    public function list_all(): array
    {
        return $this->model->list(null);
    }

    public function count_today(): int
    {
        return $this->model->count_today();
    }

    public function get_weekly_activity(): array
    {
        return $this->model->get_weekly_activity();
    }
}
