<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::run($path);
