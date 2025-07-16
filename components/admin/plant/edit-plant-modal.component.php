<?php
$id = $_GET['id'];
?>
<form id="editPlantForm" method="POST" action="/handlers/admin.handler.php">
    <input type="hidden" name="action" value="edit_plant">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id)?>">
    <div class="mb-2">
        <label class="form-label fs-5">Edit Plant with ID: <?= htmlspecialchars($id)?></label>
    </div>
    <div class="mb-2">
        <label class="form-label">New Name</label>
        <input type="text" class="form-control" name="name">
    </div>
    <div class="mb-2">
        <label class="form-label">New Description</label>
        <input type="text" class="form-control" name="description">
    </div>
    <div class="mb-2">
        <label class="form-label">New Price</label>
        <input type="number" step="0.01" class="form-control" name="price">
    </div>
    <div class="mb-2">
        <label class="form-label">New Stock Quantity</label>
        <input type="number" class="form-control" name="stock_quantity" min="0">
    </div>
    <div class="mb-2">
        <label class="form-label">New Image URL</label>
        <input type="text" class="form-control" name="image_url">
    </div>
    <button type="submit" class="btn btn-success">Edit Plant</button>
</form>