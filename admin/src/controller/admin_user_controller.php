<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../model/admin_user.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/logger.php';

class AdminUserController extends Controller
{
    protected AdminUserModel $model;
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $userModel = new AdminUserModel($db);
        parent::__construct($db, $userModel);
        $this->model = $userModel;
        $this->db = $db;
    }

    public function login(): ?AdminUser
    {
        $this->require_post_fields(['username', 'password']);
        $username = $this->sanitize_post('username', 'string');
        $password = $this->sanitize_post('password', 'string');

        $user = $this->model->retrieve_by_username($username);

        if ($user && password_verify($this->model->pepper_password($password), $user->password)) {
            admin_login($user);
            Logger::log($this->db, 'login_success', 'AdminUser', $user->id, ['username' => $username]);
            return $user;
        } else {
            Logger::log($this->db, 'login_failed', null, null, ['attempted_username' => $username]);
            return null;
        }
    }

    public function logout(): void
    {
        if (is_admin_logged_in()) {
            $admin_id = $_SESSION[ADMIN_SESSION]['id'] ?? null;
            Logger::log($this->db, 'logout', 'AdminUser', $admin_id);
        }
        admin_logout();
    }

    public function create(): void
    {
        authorize([ROLE_SUPER_ADMIN, ROLE_ADMIN]);

        $this->require_post_fields(['name', 'username', 'password', 'auth_level']);
        $name = $this->sanitize_post('name', 'string');
        $username = $this->sanitize_post('username', 'string');
        $password = $this->sanitize_post('password', 'string');
        $auth_level = $this->sanitize_post('auth_level', 'string');

        $new_id = $this->model->create($name, $username, $password, $auth_level);
        Logger::log($this->db, 'create_admin_user', 'AdminUser', $new_id, ['username' => $username]);

        set_status(SessionStatusCode::SUCCESS(), "Usuário criado com sucesso!");
        header('Location: ' . ADMIN_URL . 'users');
        exit;
    }

    public function modify(int $id): void
    {
        authorize([ROLE_SUPER_ADMIN, ROLE_ADMIN]);
        $original_user = $this->model->retrieve($id);

        $name = $this->sanitize_post('name', 'string', null);
        $username = $this->sanitize_post('username', 'string', null);
        $password = $this->sanitize_post('password', 'string', null);
        $auth_level = $this->sanitize_post('auth_level', 'string', null);

        $this->model->modify($id, $name, $username, $password, $auth_level);
        Logger::log_update($this->db, 'update_admin_user', $original_user, $_POST);

        set_status(SessionStatusCode::SUCCESS(), "Usuário atualizado com sucesso!");
        header('Location: ' . ADMIN_URL . 'users');
        exit;
    }

    public function register_initial_user(): void
    {
        if ($this->model->has_super_admin()) {
            set_status(SessionStatusCode::DANGER(), 'O registro de administrador já foi concluído.');
            header('Location: ' . ADMIN_URL . 'login');
            exit;
        }

        $this->require_post_fields(['name', 'username', 'password']);
        $name = $this->sanitize_post('name', 'string');
        $username = $this->sanitize_post('username', 'string');
        $password = $this->sanitize_post('password', 'string');

        if ($this->model->username_exists($username)) {
            set_status(SessionStatusCode::FORM_ERROR(), 'Este nome de usuário já está em uso.');
            header('Location: ' . ADMIN_URL . 'register');
            exit;
        }

        $new_user_id = $this->model->create($name, $username, $password, ROLE_SUPER_ADMIN);
        Logger::log($this->db, 'register_super_admin', 'AdminUser', $new_user_id, ['username' => $username]);

        set_status(SessionStatusCode::SUCCESS(), 'Conta Super Admin criada! Faça seu primeiro login.');
        header('Location: ' . ADMIN_URL . 'login');
        exit;
    }

    public function delete(int $id): void
    {
        authorize([ROLE_SUPER_ADMIN]);

        $user_to_delete = $this->model->retrieve($id);

        if ($user_to_delete->auth_level === 'super') {
            set_status(SessionStatusCode::DANGER(), "O usuário Super Admin não pode ser excluído.");
            header('Location: ' . ADMIN_URL . 'users');
            exit;
        }

        parent::delete($id);
        Logger::log($this->db, 'delete_admin_user', 'AdminUser', $id, ['username' => $user_to_delete->username]);
        set_status(SessionStatusCode::SUCCESS(), "Usuário excluído com sucesso!");
        header('Location: ' . ADMIN_URL . 'users');
        exit;
    }

    public function get_paginated_list_with_search(int $items_per_page = 12): array
    {
        $search_term = $this->sanitize_get('search', 'string', null);
        $role = $this->sanitize_get('role', 'string', null);
        $page = $this->sanitize_get('p', 'int', 1);
        $offset = $items_per_page * ($page - 1);

        $items = $this->model->list_filtered($search_term, $role, $items_per_page, $offset);
        $total = $this->model->count_filtered($search_term, $role);

        return ['items' => $items, 'total' => $total];
    }

    public function has_super_admin(): bool
    {
        return $this->model->has_super_admin();
    }
}
