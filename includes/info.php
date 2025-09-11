<?php

// --- INFORMAÇÕES GERAIS DO PROJETO CONECTA-BAIRRO ---

// Nome do Site/Projeto
define('SITE_NAME', 'Conecta-Bairro');

// Informações de Contato (Genéricas para o projeto)
define('CONTACT_PHONE', '(15) 99999-9999');
define('CONTACT_WHATSAPP_NUMBER', '55159XXXXXXXX');
define('CONTACT_EMAIL', 'contato@conectabairro.com.br');
define('CONTACT_ADDRESS', 'Conecta-Bairro - Itapetininga, SP');

// Horário de Atendimento
define('BUSINESS_HOURS', 'Segunda a Sexta: 09:00 - 17:00');

// Links das Redes Sociais
define('SOCIAL_INSTAGRAM', 'https://www.instagram.com/seu_projeto/');
define('SOCIAL_FACEBOOK', 'https://www.facebook.com/seu_projeto/');

// Mensagem Padrão do WhatsApp
$whatsappMessage = "Olá! Vi o site do projeto " . SITE_NAME . " e gostaria de mais informações.";
define('CONTACT_WHATSAPP_MESSAGE', urlencode($whatsappMessage));


// ===================================================================
// "BANCO DE DADOS" DE SEO 
// ===================================================================
$seo_data = [
    'home' => [
        'title' => SITE_NAME . ' | Doe, Recicle, Conecte-se!',
        'description' => 'Plataforma comunitária para doação de itens e gestão da coleta seletiva em Itapetininga. Participe!',
        'og_image' => 'og-image-home.png',
        'url_sufix' => ''
    ],
    'sobre' => [
        'title' => 'Sobre o Projeto | ' . SITE_NAME,
        'description' => 'Conheça a missão do Conecta-Bairro e como nossa plataforma ajuda a promover a sustentabilidade e a solidariedade.',
        'og_image' => 'og-image-sobre.png',
        'url_sufix' => 'sobre'
    ],
    'doacoes' => [
        'title' => 'Itens para Doação | ' . SITE_NAME,
        'description' => 'Veja os itens disponíveis para doação na sua comunidade. Móveis, eletrônicos, roupas e muito mais.',
        'og_image' => 'og-image-doacoes.png',
        'url_sufix' => 'doacoes'
    ],
    'doacao-detalhe' => [
        'title' => '%s | ' . SITE_NAME,
        'description' => '',
        'og_image' => '',
        'url_sufix' => 'doacao/%s'
    ],
    'contato' => [
        'title' => 'Contato | Fale Conosco - ' . SITE_NAME,
        'description' => 'Entre em contato para tirar dúvidas, sugerir parcerias ou obter ajuda com a plataforma.',
        'og_image' => 'og-image-contato.png',
        'url_sufix' => 'contato'
    ],
    'pontos-coleta' => [
        'title' => 'Pontos de Coleta | ' . SITE_NAME,
        'description' => 'Encontre os ecopontos e locais de coleta seletiva em Itapetininga para descartar corretamente seus recicláveis.',
        'og_image' => 'og-image-coleta.jpg',
        'url_sufix' => 'pontos-coleta'
    ],
    'politica-de-privacidade' => [
        'title' => 'Política de Privacidade | ' . SITE_NAME,
        'description' => 'Conheça nossas práticas de privacidade e como lidamos com os dados dos usuários na plataforma Conecta-Bairro.',
        'og_image' => 'og-image-default.png',
        'url_sufix' => 'politica-de-privacidade'
    ],
];
