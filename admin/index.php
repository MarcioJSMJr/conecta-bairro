<?php

require_once 'config.php';

$view_data = [];
$view_file = '';

switch ($resource) {
    case 'dashboard':
        $view_data['donations_today'] = $donation_controller->count_today();
        $view_data['total_donations'] = $donation_controller->get_total_entries();
        $view_data['users_today'] = $site_user_controller->count_today();
        $view_data['total_collection_points'] = $collection_point_controller->get_total_entries();

        $weekly_activity = $donation_controller->get_weekly_activity();
        $view_data['weekly_activity_labels'] = json_encode($weekly_activity['labels']);
        $view_data['weekly_activity_data'] = json_encode($weekly_activity['data']);

        $view_file = 'dashboard.php';
        break;

    case 'donations':
        $items_per_page = 10;
        $result = $donation_controller->get_paginated_list_with_filters($items_per_page);

        $view_data['donations'] = $result['items'];
        $view_data['total_items'] = $result['total'];
        $view_data['items_per_page'] = $items_per_page;
        $view_data['categories'] = $donation_category_controller->list_all();

        $view_file = 'doacao.php';
        break;

    case 'donation-categories':
        $items_per_page = 10;
        $result = $donation_category_controller->get_paginated_list_with_search($items_per_page);

        $view_data['categories'] = $result['items'];
        $view_data['total_items'] = $result['total'];
        $view_data['items_per_page'] = $items_per_page;
        $view_data['search_term'] = $_GET['search'] ?? '';

        $view_file = 'categoria_doacao.php';
        break;

    case 'collection-points':
        $items_per_page = 10;
        $result = $collection_point_controller->get_paginated_list_with_search($items_per_page);

        $view_data['points'] = $result['items'];
        $view_data['total_items'] = $result['total'];
        $view_data['items_per_page'] = $items_per_page;
        $view_data['search_term'] = $_GET['search'] ?? '';

        $view_file = 'ponto-coleta.php';
        break;

    case 'users':
        $items_per_page = 10;
        $result = $admin_user_controller->get_paginated_list_with_search($items_per_page);

        $view_data['users'] = $result['items'];
        $view_data['total_items'] = $result['total'];
        $view_data['items_per_page'] = $items_per_page;

        $view_file = 'users.php';
        break;

    case 'site-users':
        $items_per_page = 10;
        $result = $site_user_controller->get_paginated_list_with_search($items_per_page);

        $view_data['site_users'] = $result['items'];
        $view_data['total_items'] = $result['total'];
        $view_data['items_per_page'] = $items_per_page;

        $view_file = 'site-users.php';
        break;

    case 'logs':
        $items_per_page = 10;
        $result = $log_controller->get_paginated_list_with_search($items_per_page);

        $view_data['logs'] = $result['items'];
        $view_data['total_items'] = $result['total'];
        $view_data['items_per_page'] = $items_per_page;

        $view_file = 'logs.php';
        break;

    case 'login':
        $view_data['show_register_link'] = !$admin_user_controller->has_super_admin();
        $view_file = 'login.php';
        break;

    case 'register':
        $view_file = 'register.php';
        break;

    default:
        $view_file = '404.php';
        break;
}

$path_to_view = ADMIN_ROOT . '/src/view/' . $view_file;

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Conecta-Bairro</title>

    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="<?php echo ADMIN_URL; ?>assets/css/admin-style.css" rel="stylesheet">

    <?php if ($resource == 'login' || $resource == 'register') : ?>
        <link href="<?php echo ADMIN_URL; ?>assets/css/admin-login.css" rel="stylesheet" />
    <?php endif; ?>
</head>

<body>

    <?php

    if ($resource === 'login' || $resource === 'register') {

        if (file_exists($path_to_view)) {
            extract($view_data);
            include($path_to_view);
        }
    } else {
    ?>
        <div class="d-flex" id="wrapper">
            <?php
            include_once ADMIN_ROOT . '/src/view/includes/sidebar.php';
            ?>

            <div class="main-content-wrapper">
                <?php
                include_once ADMIN_ROOT . '/src/view/includes/header.php';
                ?>

                <main class="container-fluid p-3 p-md-4">
                    <?php
                    if (file_exists($path_to_view)) {
                        extract($view_data);
                        include($path_to_view);
                    } else {
                        include(ADMIN_ROOT . '/src/view/404.php');
                    }
                    ?>
                </main>
            </div>
        </div>
    <?php
    }
    ?>

    <?php if ($resource === 'dashboard') : ?>
        <script>
            const weeklyActivityLabels = <?php echo $weekly_activity_labels; ?>;
            const weeklyActivityData = <?php echo $weekly_activity_data; ?>;
        </script>
    <?php endif; ?>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>assets/js/chart.js"></script>
    <script src="<?php echo ADMIN_URL; ?>assets/js/dashboard.js"></script>
    <script src="<?php echo ADMIN_URL; ?>assets/js/admin-main.js"></script>

</body>

</html>