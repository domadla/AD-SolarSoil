<?php
declare(strict_types=1);

require_once '../../bootstrap.php';
require_once UTILS_PATH . '/envSetter.util.php';
require_once HANDLERS_PATH . '/cartItems.handler.php';

/**
 * Order Handler - Manages order data and operations
 * Focuses on database operations for storing cart items with inCart = FALSE
 */
class OrderHandler
{
    private static $pdo = null;

    /**
     * Get database connection
     * @return PDO|null
     */
    private static function getConnection(): ?PDO
    {
        if (self::$pdo === null) {
            global $pgConfig;
            if (!$pgConfig) {
                return null;
            }
            
            try {
                $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
                self::$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $e) {
                return null;
            }
        }
        
        return self::$pdo;
    }
    /**
     * Get all orders (demo data)
     * @return array Array of all orders
     */
    public static function getAllOrders()
    {
        // Return orders from session or empty array for admin demo
        $orders = [];
        
        // Add current order from session if exists
        if (isset($_SESSION['last_order'])) {
            $last_order = $_SESSION['last_order'];
            $orders[] = [
                'order_no' => $last_order['id'],
                'customer' => isset($last_order['customer']) ? $last_order['customer'] : 'User',
                'item' => isset($last_order['items'][0]['name']) ? $last_order['items'][0]['name'] : 'Unknown',
                'qty' => isset($last_order['items'][0]['quantity']) ? $last_order['items'][0]['quantity'] : 1,
                'date' => $last_order['date'],
                'status' => $last_order['status'],
                'total' => $last_order['total'],
                'items_count' => isset($last_order['items']) ? count($last_order['items']) : 1
            ];
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
            'shipping_method' => $subtotal > 200 ? 'Quantum Express' : 'Standard Galactic Shipping',
            'payment_method' => 'Galactic Credit Card'
        ];

        // In a real app, this would save to database
        // For production, store order in database
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
            $pdo = self::getConnection();
            if (!$pdo) {
                return [
                    'success' => false,
                    'message' => 'Database configuration not available'
                ];
            }
            
            // Start transaction
            $pdo->beginTransaction();
            
            // Get user's cart items
            $cartItems = CartItemsHandler::getUserCartItems($userId);
            
            if (empty($cartItems)) {
                $pdo->rollBack();
                return [
                    'success' => false,
                    'message' => '',
                    'silent' => true
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
            
            // Mark cart items as ordered (incart = FALSE) and assign order_id
            // This stores the cart items with incart = FALSE and order_id in the database
            $markOrderedStmt = $pdo->prepare("
                UPDATE cart_items 
                SET incart = FALSE, order_id = :order_id 
                WHERE cart_id = :cart_id AND incart = TRUE
            ");
            $markOrderedStmt->execute([
                ':cart_id' => $cartId,
                ':order_id' => $orderId
            ]);
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
            
            // Provide more specific error messages
            $errorMessage = 'Database error occurred while creating order';
            if (strpos($e->getMessage(), 'connection') !== false) {
                $errorMessage = 'Database connection failed. Please try again later.';
            } elseif (strpos($e->getMessage(), 'permission') !== false) {
                $errorMessage = 'Database permission error. Please contact support.';
            }
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'error_type' => 'database',
                'debug_info' => $e->getMessage()
            ];
        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            return [
                'success' => false,
                'message' => 'An error occurred while creating order'
            ];
        }
    }

    /**
     * Get the latest order for a user
     * @param int $userId User ID
     * @return array|null Order data or null if no order found
     */
    public static function getUserLatestOrder(int $userId): ?array
    {
        try {
            $pdo = self::getConnection();
            if (!$pdo) {
                return null;
            }
            
            // Get the latest order for the user
            $orderStmt = $pdo->prepare("
                SELECT o.id, o.user_id, o.cart_id, o.created_at, o.completed,
                       c.user_id as cart_user_id
                FROM orders o
                JOIN carts c ON o.cart_id = c.cart_id
                WHERE o.user_id = :user_id
                ORDER BY o.created_at DESC
                LIMIT 1
            ");
            $orderStmt->execute([':user_id' => $userId]);
            $order = $orderStmt->fetch();
            
            if (!$order) {
                return null;
            }
            
            // Get the order items (cart_items with incart = FALSE and this order_id)
            $itemsStmt = $pdo->prepare("
                SELECT ci.*, p.name, p.price, p.image_url
                FROM cart_items ci
                JOIN plants p ON ci.plant_id = p.plant_id
                WHERE ci.order_id = :order_id AND ci.incart = FALSE
                ORDER BY ci.cart_item_id
            ");
            $itemsStmt->execute([':order_id' => $order['id']]);
            $items = $itemsStmt->fetchAll();
            
            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shipping = 5; // Fixed shipping cost
            $tax = $subtotal * 0.08; // 8% galactic tax
            $total = $subtotal + $shipping + $tax;
            
            // Set status based on completed column
            $status = $order['completed'] ? 'delivered' : 'confirmed';
            
            return [
                'id' => 'SOL-' . $order['id'],
                'order_id' => $order['id'],
                'date' => $order['created_at'],
                'status' => $status,
                'completed' => $order['completed'],
                'items' => $items,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'estimated_delivery' => date('Y-m-d', strtotime($order['created_at'] . ' +5 days')),
                'tracking_number' => 'TRK-' . strtoupper(substr(md5((string)$order['id']), 0, 8)),
                'shipping_method' => $subtotal > 200 ? 'Quantum Express (FREE)' : 'Standard Galactic Shipping',
                'payment_method' => 'Galactic Credit Card'
            ];
            
        } catch (PDOException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get a specific order by ID
     * @param int $orderId Order ID
     * @param int $userId User ID (for security)
     * @return array|null Order data or null if not found
     */
    public static function getOrderById(int $orderId, int $userId): ?array
    {
        try {
            $pdo = self::getConnection();
            if (!$pdo) {
                return null;
            }
            
            // Get the specific order for the user
            $orderStmt = $pdo->prepare("
                SELECT o.id, o.user_id, o.cart_id, o.created_at, o.completed,
                       c.user_id as cart_user_id
                FROM orders o
                JOIN carts c ON o.cart_id = c.cart_id
                WHERE o.id = :order_id AND o.user_id = :user_id
                LIMIT 1
            ");
            $orderStmt->execute([':order_id' => $orderId, ':user_id' => $userId]);
            $order = $orderStmt->fetch();
            
            if (!$order) {
                return null;
            }
            
            // Get the order items (cart_items with incart = FALSE and this order_id)
            $itemsStmt = $pdo->prepare("
                SELECT ci.*, p.name, p.price, p.image_url
                FROM cart_items ci
                JOIN plants p ON ci.plant_id = p.plant_id
                WHERE ci.order_id = :order_id AND ci.incart = FALSE
                ORDER BY ci.cart_item_id
            ");
            $itemsStmt->execute([':order_id' => $order['id']]);
            $items = $itemsStmt->fetchAll();
            
            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shipping = 5; // Fixed shipping cost
            $tax = $subtotal * 0.08; // 8% galactic tax
            $total = $subtotal + $shipping + $tax;
            
            // Set status based on completed column
            $status = $order['completed'] ? 'delivered' : 'confirmed';
            
            return [
                'id' => 'SOL-' . $order['id'],
                'order_id' => $order['id'],
                'date' => $order['created_at'],
                'status' => $status,
                'completed' => $order['completed'],
                'items' => $items,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'estimated_delivery' => date('Y-m-d', strtotime($order['created_at'] . ' +5 days')),
                'tracking_number' => 'TRK-' . strtoupper(substr(md5((string)$order['id']), 0, 8)),
                'shipping_method' => $subtotal > 200 ? 'Quantum Express' : 'Standard Galactic Shipping',
                'payment_method' => 'Galactic Credit Card'
            ];
            
        } catch (PDOException $e) {
            return null;
        } catch (Exception $e) {
            return null;
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
            'delivered' => [
                'label' => 'Delivered',
                'icon' => 'fas fa-home',
                'class' => 'success',
                'description' => 'Your order has been successfully delivered!'
            ]
        ];

        return isset($statuses[$status]) ? $statuses[$status] : $statuses['confirmed'];
    }

    /**
     * Get all orders for a user, sorted by completion status and date
     * @param int $userId User ID
     * @return array Array of order data, incomplete orders first, then by date descending
     */
    public static function getUserAllOrders(int $userId): array
    {
        try {
            $pdo = self::getConnection();
            if (!$pdo) {
                return [];
            }
            
            // Get all orders for the user, sorted by completion status (incomplete first) then by date
            $orderStmt = $pdo->prepare("
                SELECT o.id, o.user_id, o.cart_id, o.created_at, o.completed,
                       c.user_id as cart_user_id
                FROM orders o
                JOIN carts c ON o.cart_id = c.cart_id
                WHERE o.user_id = :user_id
                ORDER BY o.completed ASC, o.created_at DESC
            ");
            $orderStmt->execute([':user_id' => $userId]);
            $orders = $orderStmt->fetchAll();
            
            if (empty($orders)) {
                return [];
            }
            
            $result = [];
            
            foreach ($orders as $order) {
                // Get the order items for each order
                $itemsStmt = $pdo->prepare("
                    SELECT ci.*, p.name, p.price, p.image_url
                    FROM cart_items ci
                    JOIN plants p ON ci.plant_id = p.plant_id
                    WHERE ci.order_id = :order_id AND ci.incart = FALSE
                    ORDER BY ci.cart_item_id
                ");
                $itemsStmt->execute([':order_id' => $order['id']]);
                $items = $itemsStmt->fetchAll();
                
                // Calculate totals
                $subtotal = 0;
                foreach ($items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }
                $shipping = 5; // Fixed shipping cost
                $tax = $subtotal * 0.08; // 8% galactic tax
                $total = $subtotal + $shipping + $tax;
                
                // Set status based on completed column
                $status = $order['completed'] ? 'delivered' : 'confirmed';
                
                $result[] = [
                    'id' => 'SOL-' . $order['id'],
                    'order_id' => $order['id'],
                    'date' => $order['created_at'],
                    'status' => $status,
                    'completed' => $order['completed'],
                    'items' => $items,
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'tax' => $tax,
                    'total' => $total,
                    'estimated_delivery' => date('Y-m-d', strtotime($order['created_at'] . ' +5 days')),
                    'tracking_number' => 'TRK-' . strtoupper(substr(md5((string)$order['id']), 0, 8)),
                    'shipping_method' => $subtotal > 200 ? 'Quantum Express (FREE)' : 'Standard Galactic Shipping',
                    'payment_method' => 'Galactic Credit Card'
                ];
            }
            
            return $result;
            
        } catch (PDOException $e) {
            return [];
        } catch (Exception $e) {
            return [];
        }
    }
}
?>