<?php
require_once 'bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
require_once UTILS_PATH . 'carts.util.php';

// Page variables
$page_title = 'SolarSoil - Interstellar Agriculture Hub';
$page_description = 'Join SolarSoil - Sustainable Agriculture Solutions for the Cosmos. Login to your account or create a new one.';
$body_class = 'login-page';

// Start session to track registration state
Auth::init();

// Error/success message handling
$message = '';
$message_type = '';

// Check if user is already logged in (a form of "successful login" state)
if (Auth::check()) {
    $user = Auth::user();
    $message = "Welcome back, " . htmlspecialchars($user['username']) . "! You are already logged in.";
    $message_type = 'info';

    // Check if this is a fresh login after registration
    if (isset($_SESSION['just_registered']) && $_SESSION['just_registered'] === true) {
        unset($_SESSION['just_registered']); // Clear the flag

        // Create cart for the newly logged in user
        $userId = (int)$user['id'];
        try {
            $cartId = Cart::getOrCreateCart($userId);
            error_log("[Index] Cart ID {$cartId} created for newly registered user {$userId} on first login");
            $message = "Welcome to SolarSoil, " . htmlspecialchars($user['username']) . "! Your cart is ready for cosmic shopping!";
            $message_type = 'success';
        } catch (Exception $e) {
            error_log("[Index] Failed to create cart for newly registered user {$userId}: " . $e->getMessage());
        }
    }
    if ($user['role'] === 'admin') {
        header('Location: /pages/Admin/index.php');
        exit;
    } else {
        header('Location: /pages/Home/index.php');
        exit;
    }
} else {
    // Handle messages from redirects (e.g., login failure, successful logout)
    if (isset($_GET['error'])) {
        $message_type = 'danger';
        $error_code = $_GET['error'];
        switch ($error_code) {
            case 'LoginRequired':
                $message = 'You must be logged in to view that page.';
                break;
            case 'InvalidCredentials':
                $message = 'Invalid username or password. Please try again.';
                break;
            case 'UsernameAlreadyTaken':
                $message = 'That username is already taken. Please choose another.';
                break;
            case 'DatabaseError':
                $message = 'An error occurred while processing your request. Please try again later.';
                break;
            case 'AllFieldsRequired':
            case 'PasswordsDoNotMatch':
                $message = 'Please correct the errors on the form and try again.';
                break;
            case 'PasswordComplexityFailed':
                $message = 'Password must be at least 6 characters long and include one uppercase letter (A-Z), one lowercase letter (a-z), one number (0-9)), and one special character (!@#$%^&*).';
                break;
            default:
                $message = 'An unexpected error occurred. Please try again.';
        }
    } elseif (isset($_GET['success'])) {
        $message_type = 'success';
        $success_code = $_GET['success'];
        switch ($success_code) {
            case 'logout':
                $message = 'You have been successfully logged out.';
                break;
            case 'SignupComplete':
                $message = 'Your account has been successfully created. You can now log in.';
                // Set a session flag to track that user just registered
                $_SESSION['just_registered'] = true;
                break;
        }
    }
}

// Capture the auth forms content
ob_start();
include 'components/forms/authForms.php';
$content = ob_get_clean();

// Include the layout
include 'layouts/login-layout.php';
