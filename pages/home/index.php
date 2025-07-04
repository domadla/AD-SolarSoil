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
    <link rel="stylesheet" href="/pages/home/assets/css/style.css">
</head>

<section class="hero container" aria-label="Interstellar Agriculture Hero" id="home">
    <h1 class="hero-title">Discover the Wonders of Interstellar Agriculture</h1>
    <p class="hero-subtitle">Cultivating plants that thrive among the stars.</p>
    <a href="/pages/shop/index.php" class="btn btn-primary">Browse Our Plants</a>
</section>

<?php require_once COMPONENTS_PATH . 'templates/footer.component.php'; ?>