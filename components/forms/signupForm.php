<div id="signup-form" class="tab-content d-none">
    <h3 class="mb-4">Create Account</h3>
    <form class="auth-form" data-form="signup" method="POST" action="/handlers/auth.handler.php">
        <input type="hidden" name="action" value="signup">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="signup-firstname" class="form-label">
                        <i class="fas fa-user me-2"></i>First Name
                    </label>
                    <input type="text" class="form-control" id="signup-firstname" name="first_name"
                        placeholder="Enter your first name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="signup-lastname" class="form-label">
                        <i class="fas fa-user me-2"></i>Last Name
                    </label>
                    <input type="text" class="form-control" id="signup-lastname" name="last_name"
                        placeholder="Enter your last name" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="signup-username" class="form-label">
                <i class="fas fa-user me-2"></i>Username
            </label>
            <input type="text" class="form-control" id="signup-username" name="username" placeholder="Choose a username"
                required>
        </div>

        <div class="form-group">
            <label for="signup-address" class="form-label">
                <i class="fas fa-map-marker-alt me-2"></i>Address
            </label>
            <input type="text" class="form-control" id="signup-address" name="address" placeholder="Enter your address"
                required>
        </div>

        <div class="form-group">
            <label for="signup-password" class="form-label">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            <input type="password" class="form-control" id="signup-password" name="password"
                placeholder="Create a password (min. 6 characters)" required minlength="6">
            <small class="form-text text-muted password-requirement" style="display: none;">
                Password must be at least 6 characters long
            </small>
        </div>

        <div class="form-group">
            <label for="signup-confirm-password" class="form-label">
                <i class="fas fa-shield-alt me-2"></i>Confirm Password
            </label>
            <input type="password" class="form-control" id="signup-confirm-password" name="confirm_password"
                placeholder="Confirm your password" required minlength="6">
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted">
                Join our sustainable agriculture community
            </small>
        </div>
    </form>
</div>