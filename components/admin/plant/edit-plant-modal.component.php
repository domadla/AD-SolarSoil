<?php

?>
<form id="editPlantForm">
    <div class="mb-2">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Description</label>
        <input type="text" class="form-control" name="description">
    </div>
    <div class="mb-2">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control" name="price">
    </div>
    <div class="mb-2">
        <label class="form-label">Stock Quantity</label>
        <input type="number" class="form-control" name="stock_quantity" min="0">
    </div>
    <div class="mb-2">
        <label class="form-label">Image URL</label>
        <input type="text" class="form-control" name="image_url">
    </div>
    <button type="submit" class="btn btn-success">Edit Plant</button>
</form>