<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__));
    define('UTILS_PATH', BASE_PATH . '/utils/');
    define('VENDOR_PATH', BASE_PATH . '/vendor/');
    define('HANDLERS_PATH', BASE_PATH . '/handlers/');
    define('COMPONENTS_PATH', BASE_PATH . '/components/');
}

chdir(BASE_PATH);
