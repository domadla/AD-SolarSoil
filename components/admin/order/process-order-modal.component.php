<?php
// Process Order Modal Content
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
    $orderRows .= '<tr>' .
        '<td>' . htmlspecialchars($order['id']) . '</td>' .
        '<td>' . htmlspecialchars($order['firstname']) . htmlspecialchars($order['lastname']) . '</td>' .
        '<td>' . htmlspecialchars($order['name']) . '</td>' .
        '<td>' . htmlspecialchars($order['quantity']) . '</td>' .
        '<td>' .
        '<input type="hidden" name="order_id[]" value="' . htmlspecialchars($order['id']) . '">' .
        '<select class="form-select form-select-sm" name="status[]">' .
        '<option value="Processing"' . ($order['completed'] === false ? ' selected' : '') . '>Processing</option>' .
        '<option value="Completed"' . ($order['completed'] === true ? ' selected' : '') . '>Completed</option>' .
        '</select>' .
        '</td>' .
        '</tr>';
}
$orderTable = '<form id="processOrderTableForm" method="POST" action="/handlers/admin.handler.php">' .
    '<input type="hidden" name="action" value="update_orders">' .
    '<table class="table table-striped table-bordered">' .
    '<thead><tr><th>Order #</th><th>Customer</th><th>Item</th><th>Qty</th><th>Status</th></tr></thead>' .
    '<tbody>' . $orderRows . '</tbody></table>' .
    '<button type="submit" class="btn btn-warning mt-2">Save Changes</button>' .
    '</form>';
echo $orderTable;
