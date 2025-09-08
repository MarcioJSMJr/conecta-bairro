<?php

$servername = $app_config['database']['servername'];
$username = $app_config['database']['username'];
$password = $app_config['database']['password'];
$database = $app_config['database']['database'];

$db = new mysqli($servername, $username, $password, $database);

if ($db->connect_error) {
    die("Erro de conexão com o banco de dados: " . $db->connect_error);
}

$db->set_charset("utf8");

return $db;