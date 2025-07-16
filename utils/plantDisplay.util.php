<?php

require_once UTILS_PATH . '/htmlEscape.util.php';

class PlantDisplayUtil
{

    /**
     * Render plant card
     * @param array $plant Plant data
     * @return string HTML for complete plant card
     */
    public static function renderPlantCard($plant)
    {
        $stockStatus = '';
        $buttonDisabled = '';
        $stockClass = '';

        // Handle stock display
        if (isset($plant['stock_quantity'])) {
            if ($plant['stock_quantity'] <= 0) {
                $stockStatus = '<span class="badge bg-danger" style="position: absolute; top: 10px; left: 10px; font-size: 0.7rem; padding: 0.2rem 0.4rem; min-width: 60px; text-align: center;">Out of Stock</span>';
                $buttonDisabled = 'disabled';
                $stockClass = 'out-of-stock';
            } elseif ($plant['stock_quantity'] <= 5) {
                $stockStatus = '<span class="badge bg-warning" style="position: absolute; top: 10px; left: 10px; font-size: 0.7rem; padding: 0.2rem 0.4rem; min-width: 60px; text-align: center;">Low Stock (' . $plant['stock_quantity'] . ')</span>';
                $stockClass = 'low-stock';
            } else {
                $stockStatus = '<span class="badge bg-success" style="position: absolute; top: 10px; left: 10px; font-size: 0.7rem; padding: 0.2rem 0.4rem; min-width: 60px; text-align: center;">In Stock (' . $plant['stock_quantity'] . ')</span>';
                $stockClass = 'in-stock';
            }
        }

        return '
        <div class="col-lg-3 col-md-6 col-sm-6 plant-item ' . $stockClass . '">
            <div class="plant-card">
                <div class="plant-image">
                    <img src="' . htmlEscape($plant['img']) . '" alt="' . htmlEscape($plant['name']) . '" 
                         onerror="this.src=\'assets/img/plants/default.png\'">
                </div>
                <div class="plant-info" style="position: relative;">
                    ' . $stockStatus . '
                    <h3 class="plant-name" style="margin-top: 25px;">' . htmlEscape($plant['name']) . '</h3>
                    <p class="plant-description">' . htmlEscape($plant['desc']) . '</p>
                    <div class="plant-footer">
                        <span class="plant-price">' . number_format($plant['price'], 0) . ' GC</span>
                        <form method="POST" action="?action=add_to_cart" style="display: inline-block;">
                            <input type="hidden" name="id" value="' . htmlEscape($plant['id']) . '">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-success add-to-cart ' . $buttonDisabled . '" 
                                    data-id="' . htmlEscape($plant['id']) . '"
                                    data-name="' . htmlEscape($plant['name']) . '"
                                    data-price="' . htmlEscape($plant['price']) . '"
                                    data-image="' . htmlEscape($plant['img']) . '"
                                    ' . ($buttonDisabled ? 'disabled' : '') . '>
                                <i class="fas fa-cart-plus me-2"></i>
                                ' . ($buttonDisabled ? 'Out of Stock' : 'Add') . '
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Render all plants grid
     * @param array $plants Array of plant data
     * @return string HTML for plants grid
     */
    public static function renderPlantsGrid($plants)
    {
        $html = '<div class="row g-4" id="plants-grid">';

        if (empty($plants)) {
            $html .= '
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-seedling fa-3x mb-3"></i>
                    <h4>No Plants Available</h4>
                    <p>Sorry, there are currently no plants available in our cosmic garden. Please check back later!</p>
                </div>
            </div>';
        } else {
            foreach ($plants as $plant) {
                $html .= self::renderPlantCard($plant);
            }
        }

        $html .= '</div>';
        return $html;
    }
}
