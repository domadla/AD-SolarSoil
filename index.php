<?php
session_start();

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: /pages/home/index.php');
    exit();
}

$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'missing_fields':
            $error_message = 'Please fill in all required fields.';
            break;
        case 'password_mismatch':
            $error_message = 'Passwords do not match.';
            break;
        case 'password_too_short':
            $error_message = 'Password must be at least 8 characters long.';
            break;
        case 'invalid_credentials':
            $error_message = 'Invalid email or password.';
            break;
    }
}

$success_message = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success_message = 'You have been successfully logged out.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SolarSoil - Login & Sign Up</title>
    <link rel="icon" type="image/x-icon" href="/assets/img/Logo.png" />
    <link rel="stylesheet" href="/assets/css/auth.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
</head>

<body>
    <div class="auth-container">
        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <div class="auth-wrapper">
            <div class="auth-form login-form active" id="loginForm">
                <h2 class="form-title">Welcome To SolarSoil</h2>
                <p class="form-subtitle">Sign in to your account</p>

                <form action="/auth/login.php" method="POST">
                    <div class="input-group">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" name="email" required />
                    </div>

                    <div class="input-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" required />
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" />
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="auth-btn">Sign In</button>
                </form>

                <div class="auth-switch">
                    <p>
                        Don't have an account?
                        <a href="#" onclick="switchToSignup()">Sign up</a>
                    </p>
                </div>
            </div>

            <div class="auth-form signup-form" id="signupForm">
                <h2 class="form-title">Join SolarSoil</h2>
                <p class="form-subtitle">Create your account</p>

                <form action="/auth/signup.php" method="POST">
                    <div class="input-group">
                        <label for="signupFirstName">First Name</label>
                        <input type="text" id="signupFirstName" name="first_name" required />
                    </div>

                    <div class="input-group">
                        <label for="signupLastName">Last Name</label>
                        <input type="text" id="signupLastName" name="last_name" required />
                    </div>

                    <div class="input-group">
                        <label for="signupEmail">Email</label>
                        <input type="email" id="signupEmail" name="email" required />
                    </div>

                    <div class="input-group">
                        <label for="signupPassword">Password</label>
                        <input type="password" id="signupPassword" name="password" required minlength="8" />
                    </div>

                    <div class="input-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirm_password" required />
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="terms" required />
                            <span class="checkmark"></span>
                            I agree to the
                            <a href="#" class="terms-link">Terms of Service</a>
                        </label>
                    </div>

                    <button type="submit" class="auth-btn">Create Account</button>
                </form>

                <div class="auth-switch">
                    <p>
                        Already have an account?
                        <a href="#" onclick="switchToLogin()">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="stars"></div>

    <script src="/assets/js/auth.js"></script>
</body>

</html>