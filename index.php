<?php
require_once 'bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

// Page variables
$page_title = 'SolarSoil - Interstellar Agriculture Hub';
$page_description = 'Join SolarSoil - Sustainable Agriculture Solutions for the Cosmos. Login to your account or create a new one.';
$body_class = 'login-page';

// Error/success message handling
$message = '';
$message_type = '';

// Check if user is already logged in (a form of "successful login" state)
if (Auth::check()) {
    $user = Auth::user();
    $message = "Welcome back, " . htmlspecialchars($user['username']) . "! You are already logged in.";
    $message_type = 'info';
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
            case 'AllFieldsRequired':
            case 'PasswordsDoNotMatch':
                $message = 'Please correct the errors on the form and try again.';
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
            case 'signup':
                $message = 'Your account has been successfully created. You can now log in.';
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
?>