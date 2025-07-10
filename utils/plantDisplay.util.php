<?php
/**
 * Simple Plant Display Utility - Helper functions for rendering plant data
 */

require_once __DIR__ . '/htmlEscape.util.php';

class PlantDisplayUtil
{

    /**
     * Render plant card (simplified version)
     * @param array $plant Plant data
     * @return string HTML for complete plant card
     */
    public static function renderPlantCard($plant)
    {
        // Ensure we have all required fields with defaults
        $id = isset($plant['id']) ? $plant['id'] : 0;
        $name = isset($plant['name']) ? $plant['name'] : 'Unknown Plant';
        $description = isset($plant['desc']) ? $plant['desc'] : 'No description available';
        $price = isset($plant['price']) ? $plant['price'] : 0;
        $image = isset($plant['img']) ? $plant['img'] : '/assets/img/placeholder-plant.jpg';
        $imgClass = isset($plant['imgClass']) ? $plant['imgClass'] : '';

        return '
        <div class="col-lg-3 col-md-6 col-sm-6 plant-item">
            <div class="plant-card ' . htmlEscape($imgClass) . '">
                <div class="plant-image">
                    <img src="' . htmlEscape($image) . '" alt="' . htmlEscape($name) . '" loading="lazy">
                </div>
                <div class="plant-info">
                    <h3 class="plant-name">' . htmlEscape($name) . '</h3>
                    <p class="plant-description">' . htmlEscape($description) . '</p>
                    <div class="plant-footer">
                        <span class="plant-price">' . number_format($price, 0) . ' GC</span>
                        <button class="btn btn-primary btn-sm add-to-cart" 
                                data-id="' . htmlEscape($id) . '"
                                data-name="' . htmlEscape($name) . '"
                                data-price="' . htmlEscape($price) . '"
                                data-image="' . htmlEscape($image) . '">
                            <i class="fas fa-cart-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Render all plants grid (simplified)
     * @param array $plants Array of plant data
     * @return string HTML for plants grid
     */
    public static function renderPlantsGrid($plants)
    {
        $html = '<div class="row g-4" id="plants-grid">';

        foreach ($plants as $plant) {
            $html .= self::renderPlantCard($plant);
        }

        $html .= '</div>';
        return $html;
    }
}
?>