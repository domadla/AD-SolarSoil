<?php
declare(strict_types=1);

require_once '../../bootstrap.php';
require_once HANDLERS_PATH . '/cartItems.handler.php';

/**
 * Order Handler - Manages order data and operations
 * Focuses on database operations for storing cart items with inCart = FALSE
 */
class OrderHandler
{
    /**
     * Get all orders (demo data)
     * @return array Array of all orders
     */
    public static function getAllOrders()
    {
        // Demo orders (in a real app, this would come from database)
        $orders = [
            [
                'order_no' => 'SOL-1704567890',
                'customer' => 'Ronn Rosario',
                'item' => 'Galaxy Orchid',
                'qty' => 2,
                'date' => '2024-12-15 14:30:00',
                'status' => 'delivered',
                'total' => 156,
                'items_count' => 3
            ],
            [
                'order_no' => 'SOL-1704467890',
                'customer' => 'Mario Veluz',
                'item' => 'Meteor Fern',
                'qty' => 1,
                'date' => '2024-12-10 09:15:00',
                'status' => 'in_transit',
                'total' => 89,
                'items_count' => 2
            ],
            [
                'order_no' => 'SOL-1704367890',
                'customer' => 'Carl Mario',
                'item' => 'Solar Vine',
                'qty' => 5,
                'date' => '2024-12-05 16:45:00',
                'status' => 'delivered',
                'total' => 234,
                'items_count' => 5
            ]
        ];

        // Add current order from session if exists
        if (isset($_SESSION['last_order'])) {
            $last_order = $_SESSION['last_order'];
            array_unshift($orders, [
                'order_no' => $last_order['id'],
                'customer' => isset($last_order['customer']) ? $last_order['customer'] : 'Demo User',
                'item' => isset($last_order['items'][0]['name']) ? $last_order['items'][0]['name'] : 'Unknown',
                'qty' => isset($last_order['items'][0]['quantity']) ? $last_order['items'][0]['quantity'] : 1,
                'date' => $last_order['date'],
                'status' => $last_order['status'],
                'total' => $last_order['total'],
                'items_count' => isset($last_order['items']) ? count($last_order['items']) : 1
            ]);
        }
        return $orders;
    }

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
     * Create an order from the user's current cart
     * 
     * @param int $userId User ID
     * @return array Result with success status and message
     */
    public static function createOrderFromCart(int $userId): array
    {
        try {
            // Get database connection
            global $pgConfig;
            $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
            $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            
            // Start transaction
            $pdo->beginTransaction();
            
            // Get user's cart items
            $cartItems = CartItemsHandler::getUserCartItems($userId);
            
            if (empty($cartItems)) {
                $pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Cannot create order: Cart is empty'
                ];
            }
            
            // Get user's cart ID
            $cartStmt = $pdo->prepare("
                SELECT cart_id 
                FROM carts 
                WHERE user_id = :user_id 
                LIMIT 1
            ");
            $cartStmt->execute([':user_id' => $userId]);
            $cart = $cartStmt->fetch();
            
            if (!$cart) {
                $pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Cannot create order: Cart not found'
                ];
            }
            
            $cartId = $cart['cart_id'];
            
            // Create the order record
            $orderStmt = $pdo->prepare("
                INSERT INTO orders (user_id, cart_id, created_at, completed)
                VALUES (:user_id, :cart_id, CURRENT_TIMESTAMP, FALSE)
                RETURNING id
            ");
            
            $orderStmt->execute([
                ':user_id' => $userId,
                ':cart_id' => $cartId
            ]);
            
            $order = $orderStmt->fetch();
            $orderId = $order['id'];
            
            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shipping = 5; // Fixed shipping cost
            $total = $subtotal + $shipping;
            
            // Mark cart items as ordered (inCart = FALSE)
            // This stores the cart items with inCart = FALSE in the database
            $markOrderedStmt = $pdo->prepare("
                UPDATE cart_items 
                SET inCart = FALSE 
                WHERE cart_id = :cart_id AND inCart = TRUE
            ");
            $markOrderedStmt->execute([':cart_id' => $cartId]);
            $markedItemsCount = $markOrderedStmt->rowCount();
            
            // Commit transaction
            $pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Order created successfully!',
                'order_id' => $orderId,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'items_count' => count($cartItems),
                'items_marked_ordered' => $markedItemsCount
            ];
            
        } catch (PDOException $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log('[OrderHandler::createOrderFromCart] Database error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error occurred while creating order'
            ];
        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log('[OrderHandler::createOrderFromCart] Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while creating order'
            ];
        }
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