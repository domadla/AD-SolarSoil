<?php
// Include bootstrap for path definitions if not already included
if (!defined('TEMPLATES_PATH')) {
    require_once '../../bootstrap.php';
}

// Start session for user authentication if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set default page variables if not already set
if (!isset($page_title))
    $page_title = 'SolarSoil - Cosmic Agriculture';
if (!isset($page_description))
    $page_description = 'Your interstellar farming platform.';
if (!isset($body_class))
    $body_class = 'page';

// Check if we need to capture content from a content file
if (isset($content_file) && file_exists($content_file)) {
    ob_start();
    include $content_file;
    $content = ob_get_clean();
}

// Include header component
include TEMPLATES_PATH . '/header.component.php';
?>

<!-- Page-specific CSS (dynamically loaded based on body_class) -->
<?php if (file_exists("assets/css/" . str_replace('-page', '', $body_class) . ".css")): ?>
    <link rel="stylesheet" href="assets/css/<?php echo str_replace('-page', '', $body_class); ?>.css">
<?php endif; ?>

<!-- Animated Star Field Background -->
<div class="stars"></div>
<div class="stars2"></div>
<div class="stars3"></div>
<div class="stars4"></div>
<div class="stars5"></div>

<!-- DYNAMIC PAGE CONTENT -->
<?php if (isset($content) && !empty($content)): ?>
    <?php echo $content; ?>
<?php else: ?>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1>Welcome to SolarSoil</h1>
                <p>Your cosmic agriculture platform awaits...</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Page-specific JavaScript (dynamically loaded based on body_class) -->
<?php if (file_exists("assets/js/" . str_replace('-page', '', $body_class) . ".js")): ?>
    <script src="assets/js/<?php echo str_replace('-page', '', $body_class); ?>.js"></script>
<?php endif; ?>

<!-- Cart Utils - Global JavaScript for cart functionality -->
<script src="../../assets/js/cart-utils.js"></script>

<?php
// Include footer component
include TEMPLATES_PATH . '/footer.component.php';
?>