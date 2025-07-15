<?php
// View Plant Modal Content
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/admin.util.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

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

$plants = Admin::display_plants($pdo);

$plantRows = '';
foreach ($plants as $plant) {
    $plantRows .= '<tr>' .
        '<td>' . htmlspecialchars($plant['id']) . '</td>' .
        '<td>' . htmlspecialchars($plant['name']) . '</td>' .
        '<td>' . (isset($plant['stock_quantity']) ? htmlspecialchars($plant['stock_quantity']) : '-') . '</td>' .
        '<td>' . (isset($plant['price']) ? htmlspecialchars($plant['price']) : '-') . '</td>' .
        '<td><button class="btn btn-primary btn-sm" onclick="editPlant(' . htmlspecialchars($plant['id']) . ')">Edit</button>
        <button class="btn btn-primary btn-sm" onclick="deletePlant(' . htmlspecialchars($plant['id']) . ')">Delete</button></td>' .
        '</tr>';
}
$plantTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>ID</th><th>Name</th><th>Stock</th><th>Price</th><th>Action</th></tr></thead>'
    . '<tbody>' . $plantRows . '</tbody></table>';
echo $plantTable;

?>