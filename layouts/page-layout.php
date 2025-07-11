<?php
// Include bootstrap for path definitions
require_once '../../bootstrap.php';

// Start session for user authentication
session_start();

// Page variables - CUSTOMIZE THESE FOR EACH PAGE
$page_title = 'SolarSoil - Page Title';
$page_description = 'Page description for SEO and meta tags.';
$body_class = 'dashboard-page';

// Include header component
include TEMPLATES_PATH . '/header.component.php';
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="assets/css/page.css">

<!-- Animated Star Field Background -->
<div class="stars"></div>
<div class="stars2"></div>
<div class="stars3"></div>
<div class="stars4"></div>
<div class="stars5"></div>

<!-- PAGE CONTENT -->
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1>Page Title</h1>
            <p>Your content...</p>
        </div>
    </div>
</div>

<!-- Page-specific JavaScript -->
<script src="assets/js/page.js"></script>

<?php
// Include footer component
include TEMPLATES_PATH . '/footer.component.php';
?>