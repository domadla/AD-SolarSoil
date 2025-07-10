<?php
// Include bootstrap for path definitions
require_once '../../bootstrap.php';

// Start session for user authentication
session_start();

// Page variables - These should be set by the calling page
if (!isset($page_title))
    $page_title = 'SolarSoil - Page Title';
if (!isset($page_description))
    $page_description = 'Page description for SEO and meta tags.';
if (!isset($body_class))
    $body_class = 'dashboard-page';

// Include header component
include TEMPLATES_PATH . '/header.component.php';
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="assets/css/<?php echo str_replace('-page', '', $body_class); ?>.css">

<!-- Animated Star Field Background -->
<div class="stars"></div>
<div class="stars2"></div>
<div class="stars3"></div>
<div class="stars4"></div>
<div class="stars5"></div>

<!-- PAGE CONTENT -->
<?php echo isset($content) ? $content : '<div class="container py-5"><div class="row"><div class="col-12"><h1>Page Title</h1><p>Your content...</p></div></div></div>'; ?>

<!-- Page-specific JavaScript -->
<script src="assets/js/<?php echo str_replace('-page', '', $body_class); ?>.js"></script>

<?php
// Include footer component
include TEMPLATES_PATH . '/footer.component.php';
?>