<?php
require_once 'bootstrap.php';

// Start session for user authentication
session_start();

// Page variables
$page_title = 'SolarSoil - Interstellar Agriculture Hub';
$page_description = 'Join SolarSoil - Sustainable Agriculture Solutions for the Cosmos. Login to your account or create a new one.';
$body_class = 'login-page';

// Error/success message handling
$message = '';
$message_type = '';

// Capture the auth forms content
ob_start();
include 'components/authForms.php';
$content = ob_get_clean();

// Include the layout
include 'layouts/login-layout.php';
?>