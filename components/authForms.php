<!-- Authentication Forms Component -->
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
                        <?php if (isset($message) && $message): ?>
                            <div class="alert alert-<?php echo $message_type === 'info' ? 'primary' : $message_type; ?>">
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
                    <?php include 'forms/loginForm.php'; ?>

                    <!-- Signup Form -->
                    <?php include 'forms/signupForm.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>