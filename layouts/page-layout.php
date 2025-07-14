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
<?php
$css_file = "assets/css/" . str_replace('-page', '', $body_class) . ".css";
if (file_exists($css_file)) {
    echo '<link rel="stylesheet" href="' . $css_file . '">';
}
// Add admin-modal.css for admin-page
if (isset($body_class) && $body_class === 'admin-page') {
    echo '<link rel="stylesheet" href="assets/css/admin-modal.css">';
}
?>

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

<!-- Cart Utils - Global JavaScript for cart functionality -->
<script src="/assets/js/main.js"></script>
<?php
// Dynamically load JS for the current page
if (isset($body_class)) {
    $js_file = null;
    if ($body_class === 'cart-page') {
        $js_file = '/pages/Cart/assets/js/cart.js';
    } elseif ($body_class === 'shop-page') {
        $js_file = '/pages/Shop/assets/js/shop.js';
    } elseif ($body_class === 'home-page') {
        $js_file = '/pages/Home/assets/js/home.js';
    }
    if ($js_file) {
        echo '<script src="' . $js_file . '"></script>';
    }
}
?>
<?php
// Include footer component
include TEMPLATES_PATH . '/footer.component.php';
?>