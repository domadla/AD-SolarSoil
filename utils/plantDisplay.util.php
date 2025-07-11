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
        return '
        <div class="col-lg-3 col-md-6 col-sm-6 plant-item">
            <div class="plant-card">
                <div class="plant-image">
                    <img src="' . htmlEscape($plant['img']) . '" alt="' . htmlEscape($plant['name']) . '">
                </div>
                <div class="plant-info">
                    <h3 class="plant-name">' . htmlEscape($plant['name']) . '</h3>
                    <p class="plant-description">' . htmlEscape($plant['desc']) . '</p>
                    <div class="plant-footer">
                        <span class="plant-price">' . number_format($plant['price'], 0) . ' GC</span>
                        <button class="btn btn-primary add-to-cart" 
                                data-id="' . htmlEscape($plant['id']) . '"
                                data-name="' . htmlEscape($plant['name']) . '"
                                data-price="' . htmlEscape($plant['price']) . '"
                                data-image="' . htmlEscape($plant['img']) . '">
                            <i class="fas fa-cart-plus me-2"></i>Add
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