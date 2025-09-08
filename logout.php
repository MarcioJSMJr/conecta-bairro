<?php

require_once 'config.php';

site_user_logout();

header('Location: ' . BASE_URL);
exit;