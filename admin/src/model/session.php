<?php

class SessionStatusCode
{
    public string $code;
    public string $message;

    private function __construct(string $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
    public static function SUCCESS(): self
    {
        return new self('success', 'Operação realizada com sucesso!');
    }
    public static function DB_ERROR(): self
    {
        return new self('danger', 'Erro no Banco de Dados');
    }
    public static function FORM_ERROR(): self
    {
        return new self('warning', 'Erro no Formulário');
    }
    public static function INFO(): self
    {
        return new self('info', 'Informação');
    }
    public static function DANGER(): self
    {
        return new self('danger', 'Ocorreu um erro.');
    }
}

class SessionStatus
{
    public SessionStatusCode $status_code;
    public ?string $message;

    public function __construct(SessionStatusCode $status_code, ?string $message = null)
    {
        $this->status_code = $status_code;
        $this->message = $message;
    }
}

function set_status(SessionStatusCode $status_code, ?string $message = null): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['status'] = new SessionStatus($status_code, $message);
}

function consume_status(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['status'])) {
        $status = $_SESSION['status'];
        $message = $status->message ?? $status->status_code->message;
        echo '<div class="alert alert-' . $status->status_code->code . '"><b>' . htmlspecialchars($message) . '</b></div>';
        unset($_SESSION['status']);
    }
}

const ADMIN_SESSION = 'admin_user';
const SITE_USER_SESSION = 'site_user';

function admin_login(AdminUser $admin_user): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION[ADMIN_SESSION] = [
        'id' => $admin_user->id,
        'name' => $admin_user->name,
        'role' => $admin_user->auth_level
    ];
}

function admin_logout(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION[ADMIN_SESSION]);
}

function is_admin_logged_in(): bool
{
    return isset($_SESSION[ADMIN_SESSION]);
}

function get_admin_user_role(): ?string
{
    return is_admin_logged_in() ? $_SESSION[ADMIN_SESSION]['role'] : null;
}

function site_user_login(SiteUser $site_user): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION[SITE_USER_SESSION] = [
        'id' => $site_user->id,
        'full_name' => $site_user->full_name,
        'email' => $site_user->email
    ];
}

function site_user_logout(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION[SITE_USER_SESSION]);
}

function is_site_user_logged_in(): bool
{
    return isset($_SESSION[SITE_USER_SESSION]);
}

function get_site_user_id(): ?int
{
    return is_site_user_logged_in() ? $_SESSION[SITE_USER_SESSION]['id'] : null;
}
