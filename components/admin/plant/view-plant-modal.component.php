<?php
// View Plant Modal Content
require_once __DIR__ . '/../../../handlers/plants.handler.php';
$plants = PlantsHandler::getAllPlants();

$plantRows = '';
foreach ($plants as $plant) {
    $plantRows .= '<tr>' .
        '<td>' . htmlspecialchars($plant['id']) . '</td>' .
        '<td>' . htmlspecialchars($plant['name']) . '</td>' .
        '<td>' . (isset($plant['stock_quantity']) ? htmlspecialchars($plant['stock_quantity']) : '-') . '</td>' .
        '<td>' . (isset($plant['price']) ? htmlspecialchars($plant['price']) : '-') . '</td>' .
        '<td><button class="btn btn-primary btn-sm" onclick="editPlant(' . htmlspecialchars($plant['id']) . ')">Edit</button></td>' .
        '</tr>';
}
$plantTable = '<table class="table table-striped table-bordered">'
    . '<thead><tr><th>ID</th><th>Name</th><th>Stock</th><th>Price</th><th>Action</th></tr></thead>'
    . '<tbody>' . $plantRows . '</tbody></table>';
echo $plantTable;

?>
<script>
    function editPlant(id) {
        fetch('../../components/admin/plant/edit-plant-modal.component.php?id=' + id)
            .then(response => response.text())
            .then(html => showAdminModal('Edit Plant', html));
    }
</script>