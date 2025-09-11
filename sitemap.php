<?php

require_once 'config.php';
require_once 'includes/info.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// --- PÁGINAS ESTÁTICAS ---
$static_pages = ['home', 'sobre', 'doacoes', 'contato', 'pontos-coleta', 'politica-de-privacidade'];
foreach ($static_pages as $page) {
    if (isset($seo_data[$page])) {
        $sufix = ($page === 'home') ? '' : $seo_data[$page]['url_sufix'];
        $url = BASE_URL . $sufix;
        echo '<url><loc>' . htmlspecialchars($url) . '</loc><changefreq>weekly</changefreq><priority>' . ($page === 'home' ? '1.0' : '0.8') . '</priority></url>';
    }
}

// --- PÁGINAS DINÂMICAS DE DOAÇÕES ---
$all_donations = $donation_controller->list_all();
if (!empty($all_donations)) {
    foreach ($all_donations as $donation) {
        $url = BASE_URL . 'doacao/' . $donation->slug;
        echo '<url><loc>' . htmlspecialchars($url) . '</loc><changefreq>monthly</changefreq><priority>0.9</priority></url>';
    }
}

echo '</urlset>';
exit;
