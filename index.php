<?php

require_once 'config.php';
require_once 'includes/info.php';

$current_seo = $seo_data[$page] ?? $seo_data['home'];
$page_file_to_include = 'pages/' . $page . '.php';
$slug = $slug ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'login':
            $site_user_controller->login();
            break;
        case 'register':
            $site_user_controller->register();
            break;
        case 'update_profile':
            $site_user_controller->update_profile();
            break;
        case 'create_donation':
            $donation_controller->create_from_site();
            break;
        case 'update_donation':
            if (is_site_user_logged_in()) {
                $donation_id = $_POST['donation_id'] ?? null;
                $user_id = get_site_user_id();
                if ($donation_id && $user_id) {
                    $donation_controller->modify_from_site($donation_id, $user_id);
                }
            }
            break;
        case 'delete_donation':
            if (is_site_user_logged_in()) {
                $donation_id = $_POST['donation_id'] ?? null;
                $user_id = get_site_user_id();
                if ($donation_id && $user_id) {
                    $donation_controller->delete_from_site($donation_id, $user_id);
                }
            }
            break;
    }
}

switch ($page) {

    case 'home':
        $view_data['latest_donations'] = $donation_controller->list(3);
        $view_data['featured_collection_points'] = $collection_point_controller->list(3);
        break;

    case 'doacoes':
        $items_per_page = 9;
        $donations_result = $donation_controller->get_paginated_list_with_filters($items_per_page);

        $view_data['donations_result'] = $donations_result;
        $view_data['all_categories'] = $donation_category_controller->list_all();
        $view_data['items_per_page'] = $items_per_page;
        break;

    case 'pontos-coleta':
        $all_points = $collection_point_controller->list_all();
        $points_by_category = [];

        foreach ($all_points as $point) {
            $points_by_category[$point->category][] = $point;
        }

        $view_data['points_by_category'] = $points_by_category;
        break;

    case 'login':
    case 'register':
        break;

    case 'minha-conta':
        if (!is_site_user_logged_in()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $user_id = get_site_user_id();
        $view_data['current_user'] = $site_user_controller->retrieve($user_id);
        $view_data['user_donations'] = $donation_controller->list_by_user($user_id);
        $view_data['categories'] = $donation_category_controller->list_all();
        break;
}

if ($page == 'doacao' && isset($slug)) {

    $donation_object = $donation_controller->retrieve_by_slug($slug);

    if ($donation_object) {

        $page = 'doacao-detalhe';

        $current_seo = [
            'title'       => sprintf($seo_data['doacao-detalhe']['title'], $donation_object->title),
            'description' => substr(strip_tags($donation_object->description), 0, 155),
            'og_image'    => $donation_object->image_url,
            'url_sufix'   => sprintf($seo_data['doacao-detalhe']['url_sufix'], $slug)
        ];

        $view_data['donation'] = $donation_object;
    } else {
        $page = '404';
    }
}

if (!file_exists('pages/' . $page . '.php')) {
    $page = '404';
}

$page_file_to_include = 'pages/' . $page . '.php';

if (!empty($view_data)) {
    extract($view_data);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($current_seo['title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($current_seo['description'] ?? ''); ?>">
    <link rel="canonical" href="<?php echo BASE_URL . ($current_seo['url_sufix'] ?? ''); ?>">

    <meta property="og:title" content="<?php echo htmlspecialchars($current_seo['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($current_seo['description'] ?? ''); ?>">
    <meta property="og:image" content="<?php echo ADMIN_URL . ($current_seo['og_image'] ?? 'media/default-og-image.png'); ?>">
    <meta property="og:url" content="<?php echo BASE_URL . ($current_seo['url_sufix'] ?? ''); ?>">

    <meta name="robots" content="index, follow">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico" type="image/x-icon">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($current_seo['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($current_seo['description'] ?? ''); ?>">
    <meta name="twitter:image" content="<?php echo ADMIN_URL . ($current_seo['og_image'] ?? 'media/default-og-image.png'); ?>">

    <script type="application/ld+json">
        <?php
        $json_ld_data = [];
        if (isset($view_data['donation'])) {
            $donation_ld = $view_data['donation'];
            $json_ld_data = [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $donation_ld->title,
                'description' => $donation_ld->description,
                'image' => ADMIN_URL . $donation_ld->image_url,
                'brand' => ['@type' => 'Brand', 'name' => SITE_NAME],
                'url' => BASE_URL . 'doacao/' . $donation_ld->slug
            ];
        } else {
            $json_ld_data = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => SITE_NAME,
                'url' => BASE_URL,
                'logo' => BASE_URL . 'assets/images/logo.png',
                'telephone' => CONTACT_PHONE,
                'email' => CONTACT_EMAIL,
                'address' => ['@type' => 'PostalAddress', 'streetAddress' => strip_tags(CONTACT_ADDRESS)]
            ];
        }
        echo json_encode($json_ld_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        ?>
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/glightbox.min.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/cookie.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/auth.css">

    <?php

    $page_css_path = 'assets/css/' . $page . '.css';
    if (file_exists($page_css_path)) {
        echo '<link rel="stylesheet" href="' . BASE_URL . $page_css_path . '">';
    }

    ?>


</head>

<body class="<?php echo ($page == 'login' || $page == 'register') ? 'auth-page' : ''; ?>">

    <?php require_once 'includes/navbar.php'; ?>

    <main>

        <?php include($page_file_to_include); ?>

    </main>

    <?php require_once 'includes/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/aos.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/cookie.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>

</body>

</html>