<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/profile.util.php';
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

if ($action === 'edit_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['user_id'] ?? 0);
    if (!$id) {
        header("Location: /pages/Profile/index.php?error=InvalidID");
        exit;
    }
    $dataToUpdate = [];
    $possibleFields = ['first_name', 'last_name', 'address', 'username'];

    foreach ($possibleFields as $field) {
        if (isset($_POST[$field]) && trim($_POST[$field]) !== '') {
            $dataToUpdate[$field] = trim($_POST[$field]);
        }
    }

    $result = ProfileUtil::edit_profile($pdo, $id, $dataToUpdate);
    header("Location: /pages/Profile/index.php?" . http_build_query($result));
    exit;
}