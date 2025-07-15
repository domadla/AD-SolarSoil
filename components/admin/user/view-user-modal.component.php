<?php
// View User Modal Content
require_once BASE_PATH . '/bootstrap.php';
require_once VENDOR_PATH . 'autoload.php';
require_once UTILS_PATH . 'admin.util.php';
require_once UTILS_PATH . 'auth.util.php';
require_once UTILS_PATH . 'envSetter.util.php';

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


$users = Admin::display_users($pdo);
$userRows = '';
foreach ($users as $user) {
    $userRows .= '<tr>' .
        '<td>' . htmlspecialchars($user['id']) . '</td>' .
        '<td>' . htmlspecialchars($user['firstname']) . '</td>' .
        '<td>' . htmlspecialchars($user['lastname']) . '</td>' .
        '<td>' . htmlspecialchars($user['username']) . '</td>' .
        '<td>' . htmlspecialchars(ucfirst($user['role'])) . '</td>' .
        '<td><button class="btn btn-sm btn-danger" onclick="deleteUser(\'' . htmlspecialchars($user['id']) . '\')">Delete</button></td>' .
        '</tr>';
}
$userTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Username</th><th>Role</th><th>Action</th></tr></thead>'
    . '<tbody>' . $userRows . '</tbody></table>';
echo $userTable;
