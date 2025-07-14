<?php
// View Plant Modal Content
require_once __DIR__ . '/../../../handlers/plants.handler.php';
$plants = PlantsHandler::getAllPlants();
$plantRows = '';
foreach ($plants as $plant) {
    $plantRows .= '<tr>' .
        '<td>' . htmlspecialchars($plant['id']) . '</td>' .
        '<td>' . htmlspecialchars($plant['name']) . '</td>' .
        '<td>' . (isset($plant['stock']) ? htmlspecialchars($plant['stock']) : '-') . '</td>' .
        '<td>' . (isset($plant['price']) ? htmlspecialchars($plant['price']) : '-') . '</td>' .
        '</tr>';
}
$plantTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>ID</th><th>Name</th><th>Stock</th><th>Price</th></tr></thead>'
    . '<tbody>' . $plantRows . '</tbody></table>';
echo $plantTable;
