<?php
require_once 'bootstrap.php';

// Start session for user authentication
session_start();

// require_once HANDLERS_PATH . 'mongodbChecker.handler.php';
// require_once HANDLERS_PATH . 'postgreChecker.handler.php';

// page variables
$page_title = 'SolarSoil - Interstellar Agriculture Hub';
$page_description = 'Join SolarSoil - Sustainable Agriculture Solutions for the Cosmos. Login to your account or create a new one.';

// error/success message handling
$message = '';
$message_type = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'SolarSoil'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <meta name="description"
        content="<?php echo isset($page_description) ? $page_description : 'SolarSoil - Sustainable Agriculture Solutions'; ?>">
    <meta name="keywords" content="solar, soil, agriculture, sustainable, farming, space, interstellar">
    <meta name="author" content="SolarSoil Team">
</head>

<body class="login-page">

    <!-- Moving stars animations -->
    <div class="stars"></div>
    <div class="stars2"></div>
    <div class="stars3"></div>
    <div class="stars4"></div>
    <div class="stars5"></div>

    <div class="auth-container">
        <div class="container">
            <div class="auth-card">
                <div class="row g-0">
                    <!-- Left Container -->
                    <div class="col-lg-5 auth-left">
                        <div class="animate-on-scroll">
                            <div class="feature-icon">
                                <i class="fas fa-leaf me-2"></i>
                            </div>
                            <h2 class="mb-3">Welcome to SolarSoil</h2>
                            <p class="lead mb-4">
                                Join our interstellar community of cosmic agriculture pioneers.
                                Discover innovative solutions for sustainable farming across the galaxy.
                            </p>
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="fas fa-satellite fa-2x mb-2 d-block" style="opacity: 0.8;"></i>
                                    <small>Space Tech</small>
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-seedling fa-2x mb-2 d-block" style="opacity: 0.8;"></i>
                                    <small>Bio-Fusion</small>
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-atom fa-2x mb-2 d-block" style="opacity: 0.8;"></i>
                                    <small>Quantum Growth</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Container -->
                    <div class="col-lg-7 auth-right">
                        <!-- Alert Container -->
                        <div id="alert-container">
                            <?php if ($message): ?>
                                <div
                                    class="alert alert-<?php echo $message_type === 'info' ? 'primary' : $message_type; ?>">
                                    <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tab Navigation -->
                        <div class="auth-tabs">
                            <button class="auth-tab active" data-tab="login-form">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                            <button class="auth-tab" data-tab="signup-form">
                                <i class="fas fa-user-plus me-2"></i>Sign Up
                            </button>
                        </div>

                        <!-- Login Form -->
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
                                        placeholder="Enter your password" required>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Signup Form -->
                        <div id="signup-form" class="tab-content d-none">
                            <h3 class="mb-4">Create Account</h3>
                            <form class="auth-form" data-form="signup" method="GET" action="index.php">
                                <input type="hidden" name="demo" value="signup">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signup-firstname" class="form-label">
                                                <i class="fas fa-user me-2"></i>First Name
                                            </label>
                                            <input type="text" class="form-control" id="signup-firstname"
                                                name="first_name" placeholder="Enter your first name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signup-lastname" class="form-label">
                                                <i class="fas fa-user me-2"></i>Last Name
                                            </label>
                                            <input type="text" class="form-control" id="signup-lastname"
                                                name="last_name" placeholder="Enter your last name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="signup-username" class="form-label">
                                        <i class="fas fa-user me-2"></i>Username
                                    </label>
                                    <input type="text" class="form-control" id="signup-username" name="username"
                                        placeholder="Choose a username" required>
                                </div>

                                <div class="form-group">
                                    <label for="signup-password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <input type="password" class="form-control" id="signup-password" name="password"
                                        placeholder="Create a password (min. 6 characters)" required>
                                    <small class="form-text text-muted">
                                        Password must be at least 6 characters long
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="signup-confirm-password" class="form-label">
                                        <i class="fas fa-shield-alt me-2"></i>Confirm Password
                                    </label>
                                    <input type="password" class="form-control" id="signup-confirm-password"
                                        name="confirm_password" placeholder="Confirm your password" required>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS -->
    <script src="assets/js/main.js"></script>

</body>

</html>