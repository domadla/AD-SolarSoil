<!-- Delete User Modal -->
<?
$id = $_GET['id'];
?>
<div class="text-center py-4">
    <p class="fs-5">Are you sure you want to delete this account?</p>
    <div class="d-flex justify-content-center gap-3 mt-4">
        <form action="/handlers/admin.handler.php" method="POST">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>