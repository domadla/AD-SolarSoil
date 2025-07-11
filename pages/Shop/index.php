<?php
// Include handlers and utilities
require_once HANDLERS_PATH . '/plants.handler.php';
require_once UTILS_PATH . '/plantDisplay.util.php';

// Page variables
$page_title = 'SolarSoil - Plant Shop';
$page_description = 'Discover exotic plants from across the galaxy.';
$body_class = 'shop-page';

// Get plant data from handler
$plants = PlantsHandler::getAllPlants();

// Capture page content
ob_start();
?>

<!-- Shop Content -->
<div class="shop-container">
    <div class="container py-5">
        <!-- Shop Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="shop-header">
                    <h1 class="shop-title">
                        <i class="fas fa-store me-3"></i>
                        Cosmic Plant Marketplace
                    </h1>
                    <p class="shop-subtitle">
                        Discover rare and exotic plants from across the galaxy. Each species has been carefully
                        cultivated for interstellar environments.
                    </p>
                </div>
            </div>
        </div>

        <!-- Display plants -->
        <?php echo PlantDisplayUtil::renderPlantsGrid($plants); ?>
    </div>
</div>

<?php
$content = ob_get_clean();

// Include the single layout
include '../../layouts/page-layout.php';
?>