<?php
// Add Plant Modal Content
?>
<form id="addPlantForm" method="POST" action="/handlers/admin.handler.php">
    <input type="hidden" name="action" value="add_plant">
    <div class="mb-2">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Description</label>
        <input type="text" class="form-control" name="description" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control" name="price" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Stock Quantity</label>
        <input type="number" class="form-control" name="stock_quantity" min="0" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Image URL</label>
        <input type="text" class="form-control" name="image_url">
    </div>
    <button type="submit" class="btn btn-success">Add Plant</button>
</form>