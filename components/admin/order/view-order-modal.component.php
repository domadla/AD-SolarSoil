<?php
// View Order Modal Content
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

$orders = Admin::display_orders($pdo);
$orderRows = '';
foreach ($orders as $order) {
    $badge = '';
    if ($order['completed'] === true)
        $badge = '<span class="badge bg-success">Completed</span>';
    elseif ($order['completed'] === false)
        $badge = '<span class="badge bg-warning text-dark">Processing</span>';
    else
        $badge = '<span class="badge bg-secondary">Pending</span>';
    $orderRows .= '<tr>'
        . '<td>' . htmlspecialchars($order['id']) . '</td>'
        . '<td>' . htmlspecialchars($order['firstname']) . ' ' . htmlspecialchars($order['lastname']) . '</td>'
        . '<td>' . htmlspecialchars($order['items']) . '</td>'
        . '<td>' . $badge . '</td>'
        . '</tr>';
}
$orderTable = '<table class="table table-striped table-bordered">' .
    '<thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Status</th></tr></thead>' .
    '<tbody>' . $orderRows . '</tbody></table>';
echo $orderTable;
