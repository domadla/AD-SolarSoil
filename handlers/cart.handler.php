<?php
/**
 * Cart Handler - Manages shopping cart operations
 * In a real application, this would interact with a database and user sessions
 */

require_once __DIR__ . '/plants.handler.php';

class CartHandler
{

    /**
     * Calculate cart subtotal
     * @param array $cart_items Cart items from localStorage/session
     * @return float Subtotal amount
     */
    public static function calculateSubtotal($cart_items)
    {
        $subtotal = 0;
        foreach ($cart_items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    /**
     * Calculate shipping cost
     * @param float $subtotal Order subtotal
     * @return float Shipping cost (free over $200)
     */
    public static function calculateShipping($subtotal)
    {
        return $subtotal >= 200 ? 0 : 15.00;
    }

    /**
     * Calculate tax
     * @param float $subtotal Order subtotal
     * @return float Tax amount (8% galactic tax)
     */
    public static function calculateTax($subtotal)
    {
        return $subtotal * 0.08;
    }

    /**
     * Calculate order total
     * @param array $cart_items Cart items
     * @return array Order summary with breakdown
     */
    public static function calculateOrderTotal($cart_items)
    {
        $subtotal = self::calculateSubtotal($cart_items);
        $shipping = self::calculateShipping($subtotal);
        $tax = self::calculateTax($subtotal);
        $total = $subtotal + $shipping + $tax;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'free_shipping' => $shipping === 0
        ];
    }

    /**
     * Validate cart item
     * @param array $item Cart item data
     * @return bool True if valid
     */
    public static function validateCartItem($item)
    {
        // Check if plant exists and is in stock
        $plant = PlantsHandler::getPlantById($item['id']);

        if (!$plant) {
            return false;
        }

        if (!$plant['in_stock']) {
            return false;
        }

        // Validate quantity
        if (!isset($item['quantity']) || $item['quantity'] < 1 || $item['quantity'] > 99) {
            return false;
        }

        return true;
    }

    /**
     * Clean and validate cart items
     * @param array $cart_items Raw cart items
     * @return array Cleaned and validated cart items
     */
    public static function validateCart($cart_items)
    {
        $valid_items = [];

        foreach ($cart_items as $item) {
            if (self::validateCartItem($item)) {
                // Ensure we have current plant data
                $plant = PlantsHandler::getPlantById($item['id']);
                $item['name'] = $plant['name'];
                $item['price'] = $plant['price'];
                $item['image'] = $plant['image'];
                $item['in_stock'] = $plant['in_stock'];

                $valid_items[] = $item;
            }
        }

        return $valid_items;
    }

    /**
     * Get recommended products (simple implementation)
     * @param array $cart_items Current cart items
     * @param int $limit Number of recommendations
     * @return array Recommended plants
     */
    public static function getRecommendedProducts($cart_items, $limit = 3)
    {
        $all_plants = PlantsHandler::getAllPlants();
        $cart_plant_ids = array_column($cart_items, 'id');

        // Filter out plants already in cart and out of stock items
        $available_plants = array_filter($all_plants, function ($plant) use ($cart_plant_ids) {
            return !in_array($plant['id'], $cart_plant_ids) && $plant['in_stock'];
        });

        // Sort by rating and return limited results
        usort($available_plants, function ($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });

        return array_slice($available_plants, 0, $limit);
    }

    /**
     * Generate order ID
     * @return string Unique order ID
     */
    public static function generateOrderId()
    {
        return 'SOL-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Process order (simulation)
     * @param array $cart_items Cart items
     * @param array $customer_info Customer information
     * @return array Order result
     */
    public static function processOrder($cart_items, $customer_info = [])
    {
        $order_summary = self::calculateOrderTotal($cart_items);
        $order_id = self::generateOrderId();

        // In a real application, this would:
        // - Save order to database
        // - Process payment
        // - Send confirmation emails
        // - Update inventory

        return [
            'success' => true,
            'order_id' => $order_id,
            'order_summary' => $order_summary,
            'estimated_delivery' => date('Y-m-d', strtotime('+5 days')),
            'message' => 'Your interstellar plants are being prepared for cosmic delivery!'
        ];
    }
}
?>