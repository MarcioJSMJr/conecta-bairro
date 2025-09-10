<?php

// ===================================================================
// CONFIGURAÇÃO E LÓGICA PRINCIPAL - PAINEL ADMIN CONECTA-BAIRRO
// ===================================================================

// --- 1. SETUP: CAMINHOS E CONSTANTES ---

// Define a raiz do painel administrativo
if (!defined('ADMIN_ROOT')) {
    define('ADMIN_ROOT', __DIR__);
}
// Define a raiz do projeto
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(ADMIN_ROOT));
}

// Define a URL base do site principal
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $base_path = dirname(dirname($_SERVER['SCRIPT_NAME']));
    define('BASE_URL', $protocol . $host . rtrim($base_path, '/') . '/');
}

// Define a URL base do painel admin
if (!defined('ADMIN_URL')) {
    define('ADMIN_URL', BASE_URL . 'admin/');
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Conecta-Bairro');
}

// --- 2. CORE: CARREGAMENTO DE CLASSES E ENGINE ---

// Carrega arquivos essenciais da pasta /src
require_once ADMIN_ROOT . '/src/model/model.php';
require_once ADMIN_ROOT . '/src/model/session.php';
require_once ADMIN_ROOT . '/src/helpers/auth.php';
require_once ADMIN_ROOT . '/src/helpers/logger.php';
require_once ADMIN_ROOT . '/src/helpers/utils.php';

// Carrega o arquivo de configuração do banco de dados (que está na raiz do projeto)
$app_config = parse_ini_file(PROJECT_ROOT . '/app.ini.php', true);

// Define uma constante de segurança para a criptografia de senhas
define('PASSWORD_PEPPER', $app_config['secret']['pepper']);

$db = require_once ADMIN_ROOT . '/src/model/db.php';

// Carrega todos os Models
require_once ADMIN_ROOT . '/src/model/admin_user.php';
require_once ADMIN_ROOT . '/src/model/donation.php';
require_once ADMIN_ROOT . '/src/model/donation_category.php';
require_once ADMIN_ROOT . '/src/model/admin_activity_log.php';
require_once ADMIN_ROOT . '/src/model/collection_point.php';

// Carrega todos os Controllers
require_once ADMIN_ROOT . '/src/controller/controller.php';
require_once ADMIN_ROOT . '/src/controller/admin_user_controller.php';
require_once ADMIN_ROOT . '/src/controller/donation_controller.php';
require_once ADMIN_ROOT . '/src/controller/donation_category_controller.php';
require_once ADMIN_ROOT . '/src/controller/admin_activity_log_controller.php';
require_once ADMIN_ROOT . '/src/controller/collection_point_controller.php';
require_once ADMIN_ROOT . '/src/controller/site_user_controller.php';

// --- 3. SESSÃO: INICIALIZAÇÃO ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- 4. OBJETOS: INSTANCIAÇÃO DOS CONTROLLERS ---
$admin_user_controller = new AdminUserController($db);
$donation_controller = new DonationController($db);
$donation_category_controller = new DonationCategoryController($db);
$log_controller = new AdminActivityLogController($db);
$collection_point_controller = new CollectionPointController($db);
$site_user_controller = new SiteUserController($db);


// --- 5. ROTEAMENTO: ANÁLISE DA URL ADMIN ---
$url = $_GET['url'] ?? '';
$url_parts = explode('/', rtrim($url, '/'));
$resource = $url_parts[0] ?: 'dashboard';

// --- 6. AÇÕES: PROCESSAMENTO DE FORMULÁRIOS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? null;

    // Processa o formulário de LOGIN
    if ($resource === 'login') {
        $user_object = $admin_user_controller->login();
        if ($user_object !== null) {
            header('Location: ' . ADMIN_URL . 'dashboard');
            exit;
        } else {
            set_status(SessionStatusCode::FORM_ERROR(), 'Nome de usuário ou senha incorretos.');
            header('Location: ' . ADMIN_URL . 'login');
            exit;
        }
    }

    if ($resource === 'register-super-admin') {
        $admin_user_controller->register_initial_user();
    }

    // Processa ações para USUÁRIOS ADMIN
    if ($resource === 'users') {
        if ($action_type === 'create') {
            $admin_user_controller->create();
        } elseif ($action_type === 'edit') {
            $id = $_POST['user_id'] ?? null;
            if ($id) $admin_user_controller->modify($id);
        } elseif ($action_type === 'delete') {
            $id = $_POST['user_id'] ?? null;
            if ($id) $admin_user_controller->delete($id);
        }
    }

    // Processa ações para CATEGORIAS DE DOAÇÃO
    if ($resource === 'donation-categories') {
        if ($action_type === 'create') {
            $donation_category_controller->create();
        } elseif ($action_type === 'edit') {
            $id = $_POST['category_id'] ?? null;
            if ($id) $donation_category_controller->modify($id);
        } elseif ($action_type === 'delete') {
            $id = $_POST['category_id'] ?? null;
            if ($id) $donation_category_controller->delete($id);
        }
    }

    // Processa ações para DOAÇÕES
    if ($resource === 'donations') {
        if ($action_type === 'create') {
            $donation_controller->create_from_site();
        } elseif ($action_type === 'edit') {
            $id = $_POST['donation_id'] ?? null;
            if ($id) $donation_controller->modify($id);
        } elseif ($action_type === 'delete') {
            $id = $_POST['donation_id'] ?? null;
            if ($id) $donation_controller->delete($id);
        }
    }

    // Processa ações para PONTOS DE COLETA
    if ($resource === 'collection-points') {
        $action_type = $_POST['action_type'] ?? null;
        if ($action_type === 'create') {
            $collection_point_controller->create();
        } elseif ($action_type === 'edit') {
            $id = $_POST['point_id'] ?? null;
            if ($id) $collection_point_controller->modify($id);
        } elseif ($action_type === 'delete') {
            $id = $_POST['point_id'] ?? null;
            if ($id) $collection_point_controller->delete($id);
        }
    }

    // Processa ações para USUÁRIOS ADMIN
    if ($resource === 'users') {
        $action_type = $_POST['action_type'] ?? null;
        if ($action_type === 'create') {
            $admin_user_controller->create();
        } elseif ($action_type === 'edit') {
            $id = $_POST['user_id'] ?? null;
            if ($id) $admin_user_controller->modify($id);
        } elseif ($action_type === 'delete') {
            $id = $_POST['user_id'] ?? null;
            if ($id) $admin_user_controller->delete($id);
        }
    }

    // Processa ações para USUÁRIOS DO SITE
    if ($resource === 'site-users') {
        $action_type = $_POST['action_type'] ?? null;
        if ($action_type === 'edit') {
            $id = $_POST['user_id'] ?? null;
            if ($id) $site_user_controller->admin_modify($id);
        } elseif ($action_type === 'delete') {
            $id = $_POST['user_id'] ?? null;
            if ($id) $site_user_controller->admin_delete($id);
        }
    }
}


// --- 7. PERMISSÕES ---
$page_permissions = [
    'collection-points'   => [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_EDITOR],
    'donations'           => [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_EDITOR],
    'donation-categories' => [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_EDITOR],
    'users'               => [ROLE_SUPER_ADMIN, ROLE_ADMIN],
    'logs'                => [ROLE_SUPER_ADMIN],
    'dashboard'           => [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_EDITOR],
];

// Se o recurso atual (página) estiver na lista de permissões, executa a autorização
if (isset($page_permissions[$resource])) {
    authorize($page_permissions[$resource]);
}
