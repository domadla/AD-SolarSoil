<?php
session_start();

require_once '../../bootstrap.php';
require VENDOR_PATH . 'autoload.php';
require_once HANDLERS_PATH . 'plant.handler.php';
require_once UTILS_PATH . 'display.plants.util.php';
require_once UTILS_PATH . 'session.util.php';
require_once COMPONENTS_PATH . 'templates/header.component.php';
?>

<head>
    <link rel="stylesheet" href="/pages/shop/assets/css/style.css">
</head>

<section class="featured-plants container" aria-label="Featured Plants" id="products" tabindex="-1">
    <h2 class="section-title">Featured Plants</h2>
    <div class="plant-grid">
        <?php displayPlants($featuredPlants); ?>
    </div>
</section>


<?php require_once COMPONENTS_PATH . 'templates/footer.component.php'; ?>