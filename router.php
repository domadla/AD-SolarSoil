<?php
require 'bootstrap.php';

if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Serve index.php for root or empty path
    if ($urlPath === '/' || $urlPath === '' || $urlPath === '/index.php') {
        require BASE_PATH . '/index.php';
        exit;
    }

    $file = BASE_PATH . $urlPath;
    if (is_file($file)) {
        return false; // Let the built-in server handle the file
    }
}

// If not found, show 404 error
require ERRORS_PATH . '404.error.php';