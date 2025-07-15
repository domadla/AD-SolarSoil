<?php
declare(strict_types=1);

require_once '../../bootstrap.php';
require_once UTILS_PATH . '/carts.util.php';
require_once UTILS_PATH . '/cartItems.util.php';
require_once UTILS_PATH . '/auth.util.php';

header('Content-Type: application/json');

// Initialize session
Auth::init();

/**
 * Cart Handler - Handles all cart-related API requests
 */
class CartHandler
{
    private static function sendJsonResponse(bool $success, string $message, $data = null, int $httpCode = 200): void
    {
        http_response_code($httpCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    private static function authenticateUser(): int
    {
        if (!Auth::check()) {
            self::sendJsonResponse(false, 'User not authenticated', null, 401);
        }
        $user = Auth::user();
        return (int)$user['id'];
    }

    /**
     * Get all items in user's cart
     */
    public static function getCartItems(): void
    {
        try {
            $userId = self::authenticateUser();
            error_log("[CartHandler::getCartItems] Loading cart for user_id={$userId}");
            
            $cartId = Cart::getCartId($userId);
            error_log("[CartHandler::getCartItems] Cart ID: " . ($cartId ?: 'null'));

            if (!$cartId) {
                error_log("[CartHandler::getCartItems] No cart found for user, returning empty cart");
                self::sendJsonResponse(true, 'Cart is empty', [
                    'items' => [],
                    'subtotal' => 0,
                    'total' => 5 // 5 GC shipping cost
                ]);
            }

            $items = self::fetchCartItemsWithDetails($cartId);
            error_log("[CartHandler::getCartItems] Found " . count($items) . " items in cart");
            
            $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $items));
            $total = $subtotal + 5; // Add shipping cost

            self::sendJsonResponse(true, 'Cart items retrieved successfully', [
                'items' => $items,
                'subtotal' => $subtotal,
                'total' => $total,
                'itemCount' => count($items)
            ]);

        } catch (Exception $e) {
            error_log('[CartHandler::getCartItems] Error: ' . $e->getMessage());
            self::sendJsonResponse(false, 'Failed to retrieve cart items', null, 500);
        }
    }

    /**
     * Add item to cart with stock validation
     */
    public static function addToCart(): void
    {
        try {
            $userId = self::authenticateUser();
            
            // Get POST data
            $input = json_decode(file_get_contents('php://input'), true);
            $plantId = (int)($input['plant_id'] ?? 0);
            $quantity = (int)($input['quantity'] ?? 1);

            if (!$plantId || $quantity <= 0) {
                self::sendJsonResponse(false, 'Invalid plant ID or quantity', null, 400);
            }

            $cartId = Cart::getOrCreateCart($userId);
            
            // Check if item already exists in cart
            $existingQuantity = self::getCartItemQuantity($cartId, $plantId);
            $newQuantity = $existingQuantity + $quantity;

            // Verify plant exists and total quantity is available
            if (!self::isPlantAvailable($plantId, $newQuantity)) {
                self::sendJsonResponse(false, 'Plant not available or insufficient stock', null, 400);
            }

            if ($existingQuantity > 0) {
                self::updateCartItemQuantity($cartId, $plantId, $newQuantity);
            } else {
                self::insertCartItem($cartId, $plantId, $quantity);
            }

            self::sendJsonResponse(true, 'Item added to cart successfully');

        } catch (Exception $e) {
            error_log('[CartHandler::addToCart] Error: ' . $e->getMessage());
            self::sendJsonResponse(false, 'Failed to add item to cart', null, 500);
        }
    }

    /**
     * Update cart item quantity with stock validation
     */
    public static function updateCartItem(): void
    {
        try {
            $userId = self::authenticateUser();
            
            $input = json_decode(file_get_contents('php://input'), true);
            $plantId = (int)($input['plant_id'] ?? 0);
            $quantity = (int)($input['quantity'] ?? 0);

            if (!$plantId || $quantity < 0) {
                self::sendJsonResponse(false, 'Invalid plant ID or quantity', null, 400);
            }

            $cartId = Cart::getCartId($userId);
            if (!$cartId) {
                self::sendJsonResponse(false, 'Cart not found', null, 404);
            }

            $currentQuantity = self::getCartItemQuantity($cartId, $plantId);
            
            if ($quantity === 0) {
                // Remove item completely
                self::removeCartItem($cartId, $plantId);
                self::sendJsonResponse(true, 'Item removed from cart');
            } else {
                // Check if new quantity is available (considering current cart reservations)
                if (!self::isPlantAvailableForUpdate($plantId, $quantity, $cartId)) {
                    self::sendJsonResponse(false, 'Insufficient stock for requested quantity', null, 400);
                }
                
                if ($currentQuantity > 0) {
                    self::updateCartItemQuantity($cartId, $plantId, $quantity);
                } else {
                    self::insertCartItem($cartId, $plantId, $quantity);
                }
                
                self::sendJsonResponse(true, 'Cart item updated successfully');
            }

        } catch (Exception $e) {
            error_log('[CartHandler::updateCartItem] Error: ' . $e->getMessage());
            self::sendJsonResponse(false, 'Failed to update cart item', null, 500);
        }
    }

    /**
     * Remove item from cart
     */
    public static function removeFromCart(): void
    {
        try {
            $userId = self::authenticateUser();
            
            $input = json_decode(file_get_contents('php://input'), true);
            $plantId = (int)($input['plant_id'] ?? 0);

            if (!$plantId) {
                self::sendJsonResponse(false, 'Invalid plant ID', null, 400);
            }

            $cartId = Cart::getCartId($userId);
            if (!$cartId) {
                self::sendJsonResponse(false, 'Cart not found', null, 404);
            }

            self::removeCartItem($cartId, $plantId);
            self::sendJsonResponse(true, 'Item removed from cart successfully');

        } catch (Exception $e) {
            error_log('[CartHandler::removeFromCart] Error: ' . $e->getMessage());
            self::sendJsonResponse(false, 'Failed to remove item from cart', null, 500);
        }
    }

    /**
     * Clear entire cart
     */
    public static function clearCart(): void
    {
        try {
            $userId = self::authenticateUser();
            $cartId = Cart::getCartId($userId);

            if (!$cartId) {
                self::sendJsonResponse(true, 'Cart is already empty');
            }

            global $pgConfig;
            $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
            $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id AND incart = TRUE");
            $stmt->execute([':cart_id' => $cartId]);

            self::sendJsonResponse(true, 'Cart cleared successfully');

        } catch (Exception $e) {
            error_log('[CartHandler::clearCart] Error: ' . $e->getMessage());
            self::sendJsonResponse(false, 'Failed to clear cart', null, 500);
        }
    }

    /**
     * Mark cart items as ordered by creating an order record
     */
    public static function orderCartItems(): void
    {
        try {
            $userId = self::authenticateUser();
            
            // Use the OrderHandler to create a proper order
            require_once __DIR__ . '/order.handler.php';
            $result = OrderHandler::createOrderFromCart($userId);
            
            if ($result['success']) {
                self::sendJsonResponse(true, $result['message'], [
                    'order_id' => $result['order_id'],
                    'items_ordered' => $result['items_marked_ordered']
                ]);
            } else {
                self::sendJsonResponse(false, $result['message'], null, 400);
            }

        } catch (Exception $e) {
            error_log('[CartHandler::orderCartItems] Error: ' . $e->getMessage());
            self::sendJsonResponse(false, 'Failed to order cart items', null, 500);
        }
    }

    // Helper methods

    private static function fetchCartItemsWithDetails(int $cartId): array
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $stmt = $pdo->prepare("
            SELECT 
                ci.cart_item_id,
                ci.plant_id,
                ci.quantity,
                p.name,
                p.price,
                p.image_url,
                p.description,
                p.stock_quantity
            FROM cart_items ci
            JOIN plants p ON ci.plant_id = p.plant_id
            WHERE ci.cart_id = :cart_id 
            AND p.isDeleted = FALSE
            AND ci.incart = TRUE
            ORDER BY ci.cart_item_id
        ");
        $stmt->execute([':cart_id' => $cartId]);
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("[CartHandler::fetchCartItemsWithDetails] Query executed for cart_id={$cartId}, found " . count($items) . " items");
        
        return $items;
    }

    private static function isPlantAvailable(int $plantId, int $requestedQuantity): bool
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        // Get available stock (total stock minus all items currently in all carts)
        $stmt = $pdo->prepare("
            SELECT 
                p.stock_quantity,
                COALESCE(SUM(ci.quantity), 0) as reserved_quantity
            FROM plants p
            LEFT JOIN cart_items ci ON p.plant_id = ci.plant_id AND ci.incart = TRUE
            WHERE p.plant_id = :plant_id 
            AND p.isDeleted = FALSE
            GROUP BY p.plant_id, p.stock_quantity
        ");
        $stmt->execute([':plant_id' => $plantId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $availableStock = $result['stock_quantity'] - $result['reserved_quantity'];
        return $availableStock >= $requestedQuantity;
    }

    private static function getCartItemQuantity(int $cartId, int $plantId): int
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $stmt = $pdo->prepare("
            SELECT quantity 
            FROM cart_items 
            WHERE cart_id = :cart_id AND plant_id = :plant_id AND incart = TRUE
        ");
        $stmt->execute([':cart_id' => $cartId, ':plant_id' => $plantId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['quantity'] : 0;
    }

    private static function updateCartItemQuantity(int $cartId, int $plantId, int $quantity): void
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $stmt = $pdo->prepare("
            UPDATE cart_items 
            SET quantity = :quantity 
            WHERE cart_id = :cart_id AND plant_id = :plant_id AND incart = TRUE
        ");
        $stmt->execute([
            ':quantity' => $quantity,
            ':cart_id' => $cartId,
            ':plant_id' => $plantId
        ]);
    }

    private static function insertCartItem(int $cartId, int $plantId, int $quantity): void
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $stmt = $pdo->prepare("
            INSERT INTO cart_items (cart_id, plant_id, quantity, incart)
            VALUES (:cart_id, :plant_id, :quantity, TRUE)
        ");
        $stmt->execute([
            ':cart_id' => $cartId,
            ':plant_id' => $plantId,
            ':quantity' => $quantity
        ]);
    }

    private static function removeCartItem(int $cartId, int $plantId): void
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $stmt = $pdo->prepare("
            DELETE FROM cart_items 
            WHERE cart_id = :cart_id AND plant_id = :plant_id AND incart = TRUE
        ");
        $stmt->execute([':cart_id' => $cartId, ':plant_id' => $plantId]);
    }

    /**
     * Check if plant is available for update (excluding current cart item)
     */
    private static function isPlantAvailableForUpdate(int $plantId, int $requestedQuantity, int $excludeCartId): bool
    {
        global $pgConfig;
        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        // Get available stock (total stock minus all items in other carts)
        $stmt = $pdo->prepare("
            SELECT 
                p.stock_quantity,
                COALESCE(SUM(ci.quantity), 0) as reserved_quantity
            FROM plants p
            LEFT JOIN cart_items ci ON p.plant_id = ci.plant_id AND ci.cart_id != :exclude_cart_id AND ci.incart = TRUE
            WHERE p.plant_id = :plant_id 
            AND p.isDeleted = FALSE
            GROUP BY p.plant_id, p.stock_quantity
        ");
        $stmt->execute([
            ':plant_id' => $plantId,
            ':exclude_cart_id' => $excludeCartId
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $availableStock = $result['stock_quantity'] - $result['reserved_quantity'];
        return $availableStock >= $requestedQuantity;
    }
}

// Handle HTTP requests
try {
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';

    switch ($method) {
        case 'GET':
            if ($action === 'get') {
                CartHandler::getCartItems();
            } else {
                CartHandler::sendJsonResponse(false, 'Invalid action', null, 400);
            }
            break;

        case 'POST':
            if ($action === 'add') {
                CartHandler::addToCart();
            } elseif ($action === 'order') {
                CartHandler::orderCartItems();
            } else {
                CartHandler::sendJsonResponse(false, 'Invalid action', null, 400);
            }
            break;

        case 'PUT':
            if ($action === 'update') {
                CartHandler::updateCartItem();
            } else {
                CartHandler::sendJsonResponse(false, 'Invalid action', null, 400);
            }
            break;

        case 'DELETE':
            if ($action === 'remove') {
                CartHandler::removeFromCart();
            } elseif ($action === 'clear') {
                CartHandler::clearCart();
            } else {
                CartHandler::sendJsonResponse(false, 'Invalid action', null, 400);
            }
            break;

        default:
            CartHandler::sendJsonResponse(false, 'Method not allowed', null, 405);
    }

} catch (Exception $e) {
    error_log('[cart.handler.php] Unexpected error: ' . $e->getMessage());
    CartHandler::sendJsonResponse(false, 'Internal server error', null, 500);
}