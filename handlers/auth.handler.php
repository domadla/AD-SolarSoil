<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

// Initialize session
Auth::init();

$host = $pgConfig['host'];
$port = $pgConfig['port'];
$username = $pgConfig['user'];
$password = $pgConfig['pass'];
$dbname = $pgConfig['db'];

// Connect to Postgres
$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$action = $_REQUEST['action'] ?? null;

// --- LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');
    if (Auth::login($pdo, $usernameInput, $passwordInput)) {
        $user = Auth::user();

        if ($user["role"] == "admin") {
            header('Location: /pages/Admin/index.php');
        } else {
            header('Location: /pages/Home/index.php');
        }
        exit;
    } else {
        header('Location: /index.php?error=InvalidCredentials');
        exit;
    }
}

elseif ($action === 'signup' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['first_name'] ?? '');
    $lastname = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? ''; // Don't trim password
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = trim($_POST['role'] ?? 'user');

    $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';

    // Basic server-side validation
    if (empty($firstname) || empty($lastname) || empty($username) || empty($address) || empty($password)) {
        header("Location: {$redirect}?error=AllFieldsRequired");
        exit;
    }
    if ($password !== $confirm_password) {
        header("Location: {$redirect}?error=PasswordsDoNotMatch");
        exit;
    }
    // Password complexity validation
    // Requires: 1 uppercase, 1 lowercase, 1 number, 1 special char, min 8 chars
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        header("Location: {$redirect}?error=PasswordComplexityFailed");
        exit;
    }
    $result = Auth::register($pdo, $firstname, $lastname, $username, $address, $password, $role);
    header("Location: {$redirect}?" . http_build_query($result));
    exit;
}

// --- LOGOUT ---
elseif ($action === 'logout') {
    Auth::init();
    Auth::logout();
    header('Location: /index.php?success=logout');
    exit;
}

// If no valid action, redirect to login
header('Location: /index.php');
exit;
