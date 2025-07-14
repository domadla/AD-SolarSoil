<?php
// View Order Modal Content
require_once __DIR__ . '/../../../handlers/order.handler.php';
$orders = OrderHandler::getAllOrders();
$orderRows = '';
foreach ($orders as $order) {
    $badge = '';
    if ($order['status'] === 'Dispatched')
        $badge = '<span class="badge bg-success">Dispatched</span>';
    elseif ($order['status'] === 'Processing')
        $badge = '<span class="badge bg-warning text-dark">Processing</span>';
    else
        $badge = '<span class="badge bg-secondary">Pending</span>';
    $orderRows .= '<tr>' .
        '<td>' . htmlspecialchars($order['order_no']) . '</td>' .
        '<td>' . htmlspecialchars($order['customer']) . '</td>' .
        '<td>' . htmlspecialchars($order['item']) . '</td>' .
        '<td>' . htmlspecialchars($order['qty']) . '</td>' .
        '<td>' . $badge . '</td>' .
        '</tr>';
}
$orderTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>Order #</th><th>Customer</th><th>Item</th><th>Qty</th><th>Status</th></tr></thead>'
    . '<tbody>' . $orderRows . '</tbody></table>';
echo $orderTable;
