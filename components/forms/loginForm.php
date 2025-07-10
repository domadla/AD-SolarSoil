<div id="login-form" class="tab-content">
    <h3 class="mb-4">Welcome Back</h3>
    <form class="auth-form" data-form="login" method="GET" action="index.php">
        <input type="hidden" name="demo" value="login">
        <div class="form-group">
            <label for="login-username" class="form-label">
                <i class="fas fa-user me-2"></i>Username
            </label>
            <input type="text" class="form-control" id="login-username" name="username"
                placeholder="Enter your username" required>
        </div>

        <div class="form-group">
            <label for="login-password" class="form-label">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            <input type="password" class="form-control" id="login-password" name="password"
                placeholder="Enter your password" required minlength="6">
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </div>
    </form>
</div>