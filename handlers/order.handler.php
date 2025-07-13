<?php
/**
 * Order Handler - Manages order data and operations
 * In a real application, this would interact with a database
 */

class OrderHandler
{

    /**
     * Create a new order from cart data
     * @param array $cart_items Cart items
     * @param array $user_data User data
     * @return array Order data
     */
    public static function createOrder($cart_items, $user_data = null)
    {
        // Calculate order totals
        $subtotal = 0;
        foreach ($cart_items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal > 200 ? 0 : 15;
        $tax = $subtotal * 0.08; // 8% galactic tax
        $total = $subtotal + $shipping + $tax;

        // Generate order data
        $order = [
            'id' => 'SOL-' . time(),
            'date' => date('Y-m-d H:i:s'),
            'status' => 'confirmed',
            'items' => $cart_items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'estimated_delivery' => date('Y-m-d', strtotime('+5 days')),
            'tracking_number' => 'TRK-' . strtoupper(substr(md5(time()), 0, 8)),
            'shipping_method' => $subtotal > 200 ? 'Quantum Express (FREE)' : 'Standard Galactic Shipping',
            'payment_method' => 'Galactic Credit Card'
        ];

        // In a real app, this would save to database
        // For demo, we'll store in session
        $_SESSION['last_order'] = $order;

        return $order;
    }

    /**
     * Get order by ID
     * @param string $order_id Order ID
     * @return array|null Order data or null if not found
     */
    public static function getOrderById($order_id)
    {
        // In a real app, this would query database
        // For demo, we'll return from session
        if (isset($_SESSION['last_order']) && $_SESSION['last_order']['id'] === $order_id) {
            return $_SESSION['last_order'];
        }

        return null;
    }

    /**
     * Get orders for a user
     * @param int $user_id User ID
     * @return array Array of orders
     */
    public static function getUserOrders($user_id)
    {
        // Demo orders (in a real app, this would come from database)
        $orders = [
            [
                'id' => 'SOL-1704567890',
                'date' => '2024-12-15 14:30:00',
                'status' => 'delivered',
                'total' => 156,
                'items_count' => 3
            ],
            [
                'id' => 'SOL-1704467890',
                'date' => '2024-12-10 09:15:00',
                'status' => 'in_transit',
                'total' => 89,
                'items_count' => 2
            ],
            [
                'id' => 'SOL-1704367890',
                'date' => '2024-12-05 16:45:00',
                'status' => 'delivered',
                'total' => 234,
                'items_count' => 5
            ]
        ];

        // Add current order from session if exists
        if (isset($_SESSION['last_order'])) {
            array_unshift($orders, [
                'id' => $_SESSION['last_order']['id'],
                'date' => $_SESSION['last_order']['date'],
                'status' => $_SESSION['last_order']['status'],
                'total' => $_SESSION['last_order']['total'],
                'items_count' => count($_SESSION['last_order']['items'])
            ]);
        }

        return $orders;
    }

    /**
     * Get order status display information
     * @param string $status Order status
     * @return array Status display info
     */
    public static function getStatusInfo($status)
    {
        $statuses = [
            'confirmed' => [
                'label' => 'Order Confirmed',
                'icon' => 'fas fa-check-circle',
                'class' => 'success',
                'description' => 'Your order has been confirmed and is being prepared.'
            ],
            'processing' => [
                'label' => 'Processing',
                'icon' => 'fas fa-cog',
                'class' => 'warning',
                'description' => 'Your order is being processed by our cosmic gardeners.'
            ],
            'shipped' => [
                'label' => 'Shipped',
                'icon' => 'fas fa-rocket',
                'class' => 'info',
                'description' => 'Your order has been shipped via quantum transport.'
            ],
            'in_transit' => [
                'label' => 'In Transit',
                'icon' => 'fas fa-satellite',
                'class' => 'info',
                'description' => 'Your order is traveling through space to your location.'
            ],
            'delivered' => [
                'label' => 'Delivered',
                'icon' => 'fas fa-home',
                'class' => 'success',
                'description' => 'Your order has been successfully delivered!'
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'icon' => 'fas fa-times-circle',
                'class' => 'danger',
                'description' => 'This order has been cancelled.'
            ]
        ];

        return isset($statuses[$status]) ? $statuses[$status] : $statuses['confirmed'];
    }

    /**
     * Update order status
     * @param string $order_id Order ID
     * @param string $status New status
     * @return bool Success status
     */
    public static function updateOrderStatus($order_id, $status)
    {
        // In a real app, this would update the database
        // For demo purposes, we'll just return true
        return true;
    }

    /**
     * Cancel an order
     * @param string $order_id Order ID
     * @return bool Success status
     */
    public static function cancelOrder($order_id)
    {
        // In a real app, this would update the database
        // For demo purposes, we'll just return true
        return true;
    }
}
?>