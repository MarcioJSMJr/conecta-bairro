<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../model/site_user.php';

class SiteUserController extends Controller
{
    private SiteUserModel $model;
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $model = new SiteUserModel($db);
        parent::__construct($db, $model);
        $this->model = $model;
        $this->db = $db;
    }

    public function register(): void
    {
        $this->require_post_fields(['full_name', 'email', 'password']);

        $full_name = $this->sanitize_post('full_name', 'string');
        $email = $this->sanitize_post('email', 'string');
        $password = $this->sanitize_post('password', 'string');
        $phone_number = $this->sanitize_post('phone_number', 'string', null);

        if ($this->model->email_exists($email)) {
            set_status(SessionStatusCode::FORM_ERROR(), "Este e-mail já está cadastrado.");
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        $this->model->create($full_name, $email, $password, $phone_number);

        set_status(SessionStatusCode::SUCCESS(), "Conta criada com sucesso! Agora você já pode fazer o login.");
        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    public function update_profile(): void
    {

        if (!is_site_user_logged_in()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $user_id = get_site_user_id();
        $original_user = $this->model->retrieve($user_id);

        $full_name = $this->sanitize_post('full_name', 'string', null);
        $email = $this->sanitize_post('email', 'string', null);
        $phone_number = $this->sanitize_post('phone_number', 'string', null);
        $password = $this->sanitize_post('password', 'string', null);

        if ($email && $email !== $original_user->email && $this->model->email_exists($email)) {
            set_status(SessionStatusCode::FORM_ERROR(), "Este e-mail já está sendo usado por outra conta.");
            header('Location: ' . BASE_URL . 'minha-conta');
            exit;
        }

        $this->model->modify($user_id, $full_name, $email, $password, $phone_number);

        set_status(SessionStatusCode::SUCCESS(), "Seus dados foram atualizados com sucesso!");
        header('Location: ' . BASE_URL . 'minha-conta');
        exit;
    }

    public function login(): void
    {
        $this->require_post_fields(['email', 'password']);
        $email = $this->sanitize_post('email', 'string');
        $password = $this->sanitize_post('password', 'string');

        $user = $this->model->retrieve_by_email($email);

        if ($user && password_verify($this->model->pepper_password($password), $user->password)) {
            site_user_login($user);
            header('Location: ' . BASE_URL . 'minha-conta');
            exit;
        } else {
            set_status(SessionStatusCode::FORM_ERROR(), 'E-mail ou senha incorretos.');
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function admin_modify(int $id): void
    {
        $original_user = $this->model->retrieve($id);

        $full_name = $this->sanitize_post('full_name', 'string');
        $email = $this->sanitize_post('email', 'string', $original_user->email);
        $password = $this->sanitize_post('password', 'string', null);
        $phone_number = $this->sanitize_post('phone_number', 'string', null);

        $this->model->modify($id, $full_name, $email, $password, $phone_number);
        Logger::log_update($this->db, 'admin_update_site_user', $original_user, $_POST);

        set_status(SessionStatusCode::SUCCESS(), "Usuário atualizado com sucesso!");
        header('Location: ' . ADMIN_URL . 'site-users');
        exit;
    }

    public function admin_delete(int $id): void
    {
        $user = $this->model->retrieve($id);
        parent::delete($id);
        Logger::log($this->db, 'admin_delete_site_user', 'SiteUser', $id, ['email' => $user->email]);
        set_status(SessionStatusCode::SUCCESS(), "Usuário do site excluído com sucesso!");
        header('Location: ' . ADMIN_URL . 'site-users');
        exit;
    }

    public function get_paginated_list_with_search(int $items_per_page = 12): array
    {
        $search_term = $this->sanitize_get('search', 'string', null);
        $page = $this->sanitize_get('p', 'int', 1);
        $offset = $items_per_page * ($page - 1);

        $items = $this->model->list_filtered($search_term, $items_per_page, $offset);
        $total = $this->model->count_filtered($search_term);

        return ['items' => $items, 'total' => $total];
    }

    public function count_today(): int
    {
        return $this->model->count_today();
    }

    public function logout(): void
    {
        site_user_logout();
        header('Location: ' . BASE_URL);
        exit;
    }
}
