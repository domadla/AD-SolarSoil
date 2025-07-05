<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(dirname(__DIR__)));
}
require_once VENDOR_PATH . 'autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();


$mongoConfig = [
    'uri' => $_ENV['MONGO_URI'],
    'db' => $_ENV['MONGO_DB']
];


$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS']
];