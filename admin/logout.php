<?php

require_once 'config.php';

$admin_user_controller->logout();

header('Location: ' . ADMIN_URL . 'login');
exit;