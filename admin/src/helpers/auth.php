<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/session.php';

define('ROLE_SUPER_ADMIN', 'super');
define('ROLE_ADMIN', 'admin');
define('ROLE_EDITOR', 'editor');

/**
 * Função principal de autorização para o painel administrativo.
 * Protege uma página ou ação, verificando se um administrador está logado
 * e se ele tem a permissão necessária.
 *
 * @param array $allowed_roles Um array com as roles permitidas (ex: [ROLE_ADMIN]).
 */
function authorize(array $allowed_roles): void {
    if (!is_admin_logged_in()) {
        set_status(SessionStatusCode::DANGER(), 'Você precisa estar logado como administrador para acessar esta página.');
        header('Location: ' . ADMIN_URL . 'login'); 
        exit;
    }

    $admin_role = get_admin_user_role();
    if (!in_array($admin_role, $allowed_roles, true)) {
        set_status(SessionStatusCode::DANGER(), 'Você não tem permissão para acessar este recurso.');
        header('Location: ' . ADMIN_URL . 'dashboard'); 
        exit;
    }
}