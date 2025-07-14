<?php
// Add User Modal Content
?>
<form id="addUserForm" method="POST" action="/handlers/auth.handler.php">
    <input type="hidden" name="action" value="signup">
    <div class="mb-2">
        <label class="form-label">First Name</label>
        <input type="text" class="form-control" name="first_name" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" name="last_name" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" required>
    </div>
    <div class="mb-2">
        <label class="form-label">Role</label>
        <select class="form-select" name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="mb-2">
        <label class="form-label">Address</label>
        <textarea class="form-control" name="address"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Add User</button>
</form>