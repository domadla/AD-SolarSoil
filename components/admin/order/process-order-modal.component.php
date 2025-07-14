<?php
// Process Order Modal Content
require_once __DIR__ . '/../../../handlers/order.handler.php';
$orders = OrderHandler::getAllOrders();
$orderRows = '';
foreach ($orders as $order) {
    $orderRows .= '<tr>' .
        '<td>' . htmlspecialchars($order['order_no']) . '</td>' .
        '<td>' . htmlspecialchars($order['customer']) . '</td>' .
        '<td>' . htmlspecialchars($order['item']) . '</td>' .
        '<td>' . htmlspecialchars($order['qty']) . '</td>' .
        '<td>' .
        '<select class="form-select form-select-sm order-status-select" data-order-no="' . htmlspecialchars($order['order_no']) . '">' .
        '<option value="Pending"' . ($order['status'] === 'Pending' ? ' selected' : '') . '>Pending</option>' .
        '<option value="Processing"' . ($order['status'] === 'Processing' ? ' selected' : '') . '>Processing</option>' .
        '<option value="Dispatched"' . ($order['status'] === 'Dispatched' ? ' selected' : '') . '>Dispatched</option>' .
        '</select>' .
        '</td>' .
        '</tr>';
}
$orderTable = '<form id="processOrderTableForm">' .
    '<table class="table table-striped table-bordered">' .
    '<thead><tr><th>Order #</th><th>Customer</th><th>Item</th><th>Qty</th><th>Status</th></tr></thead>' .
    '<tbody>' . $orderRows . '</tbody></table>' .
    '<button type="submit" class="btn btn-warning mt-2">Save Changes</button>' .
    '</form>';
echo $orderTable;
