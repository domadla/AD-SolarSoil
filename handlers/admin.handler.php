<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/admin.util.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

// Initialize session
Auth::init();

$host = $pgConfig['host'];
$port = $pgConfig['port'];
$username = $pgConfig['user'];
$password = $pgConfig['pass'];
$dbname = $pgConfig['db'];

$redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
// Connect to Postgres
$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$action = $_REQUEST['action'] ?? null;

if ($action === 'add_plant' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $nameInput = trim($_POST['name'] ?? '');
    $priceInput = (float) trim($_POST['price'] ?? '');
    $stock_quantityInput = (int) trim($_POST['stock_quantity'] ?? '');
    $image_urlInput = trim($_POST['image_url'] ?? '');
    $descriptionInput = trim($_POST['description'] ?? '');

    if (empty($nameInput) || empty($priceInput) || empty($stock_quantityInput) || empty($descriptionInput)) {
        header("Location: {$redirect}?error=AllFieldsRequired");
        exit;
    }
    $result = Admin::add_plant($pdo, $nameInput, $priceInput, $stock_quantityInput, $image_urlInput, $descriptionInput);
    header("Location: /pages/Admin/index.php?" . http_build_query($result));
    exit;
}

if ($action === 'update_orders' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderIds = $_POST['order_id'] ?? [];
    $statuses = $_POST['status'] ?? [];

    foreach ($orderIds as $index => $id) {
        $completed = ($statuses[$index] === 'Completed');
        Admin::update_orders($pdo, (int)$id, $completed);
    }
    header("Location: {$redirect}?success=OrdersUpdated");
    exit;
}

?>