<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light" style="background-color: #212529 !important; color: #f8f9fa;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" method="POST" action="/handlers/profile-edit.handler.php">
                    <input type="hidden" name="action" value="edit_password">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_data['user_id']);?>">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label" style="color:#fff;"><i
                                class="fas fa-lock me-2"></i>Current Password</label>
                        <input type="password" class="form-control bg-secondary text-light border-0"
                            id="currentPassword" name="current_password" autocomplete="current-password">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label" style="color:#fff;"><i
                                class="fas fa-key me-2"></i>New Password</label>
                        <input type="password" class="form-control bg-secondary text-light border-0" id="newPassword"
                            name="new_password" autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="confirmNewPassword" class="form-label" style="color:#fff;"><i
                                class="fas fa-key me-2"></i>Confirm New Password</label>
                        <input type="password" class="form-control bg-secondary text-light border-0"
                            id="confirmNewPassword" name="confirm_new_password" autocomplete="new-password">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>