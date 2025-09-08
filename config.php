<?php
// ===================================================================
// ARQUIVO DE CONFIGURAÇÃO PRINCIPAL - SITE CONECTA-BAIRRO
// ===================================================================

// --- 1. SETUP INICIAL E CONSTANTES ---

// Define as constantes de caminho primeiro
define('PROJECT_ROOT', __DIR__);
define('ADMIN_ROOT', PROJECT_ROOT . '/admin');

// CARREGA AS DEFINIÇÕES DE CLASSE ANTES DE INICIAR A SESSÃO
require_once ADMIN_ROOT . '/src/model/exceptions.php';
require_once ADMIN_ROOT . '/src/model/session.php';
require_once ADMIN_ROOT . '/src/helpers/utils.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// URL Base do Site (ex: http://localhost/conectabairro/)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('BASE_URL', $protocol . $host . $base_path . '/');
define('ADMIN_URL', BASE_URL . 'admin/');


// --- 2. CARREGAMENTO DO BACKEND (Models e Controllers) ---

// Carrega as configurações de banco de dados e o "pepper" do app.ini.php
$app_config = parse_ini_file(PROJECT_ROOT . '/app.ini.php', true);
define('PASSWORD_PEPPER', $app_config['secret']['pepper']);

// Carrega a conexão com o banco de dados
$db = require_once ADMIN_ROOT . '/src/model/db.php';

// Carrega a base para Models e Controllers
require_once ADMIN_ROOT . '/src/model/model.php';
require_once ADMIN_ROOT . '/src/controller/controller.php';

// Carrega os Models específicos que a Home precisa
require_once ADMIN_ROOT . '/src/model/donation.php';
require_once ADMIN_ROOT . '/src/model/collection_point.php';
require_once ADMIN_ROOT . '/src/model/donation_category.php';
require_once ADMIN_ROOT . '/src/model/site_user.php';

// Carrega os Controllers específicos que a Home precisa
require_once ADMIN_ROOT . '/src/controller/donation_controller.php';
require_once ADMIN_ROOT . '/src/controller/collection_point_controller.php';
require_once ADMIN_ROOT . '/src/controller/donation_category_controller.php';
require_once ADMIN_ROOT . '/src/controller/site_user_controller.php';


// --- 3. INSTANCIAÇÃO DOS CONTROLLERS ---
$donation_controller = new DonationController($db);
$collection_point_controller = new CollectionPointController($db);
$donation_category_controller = new DonationCategoryController($db);
$site_user_controller = new SiteUserController($db);


// --- 4. ROTEAMENTO SIMPLES ---
$request_uri = $_SERVER['REQUEST_URI'];
$base_path_len = strlen($base_path);
$path = substr($request_uri, $base_path_len);
if (strpos($path, '?') !== false) {
    $path = substr($path, 0, strpos($path, '?'));
}
$path = trim($path, '/');

$url_parts = explode('/', $path);

$page = !empty($url_parts[0]) ? $url_parts[0] : 'home';
$slug = $url_parts[1] ?? null;

$view_data = [];
