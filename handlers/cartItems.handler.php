<?php
/**
 * Cart Items Handler - Updated for incart column functionality
 * 
 * IMPORTANT CHANGES:
 * - Added incart column support to cart_items table
 * - Cart items are no longer deleted when ordered, instead incart is set to FALSE
 * - Only items with incart = TRUE are shown in cart
 * - Items with incart = FALSE represent order history
 * 
 * New Methods:
 * - markCartItemsAsOrdered(): Sets incart = FALSE instead of deleting items
 * 
 * Usage:
 * - To place an order: Call markCartItemsAsOrdered() or use order.handler.php
 * - To view cart: Existing methods now filter by incart = TRUE
 * - To view order history: Use getOrderedItems() from cartItems.util.php
 */

require_once UTILS_PATH . '/envSetter.util.php';
require_once UTILS_PATH . '/cartItems.util.php';

class CartItemsHandler
{
    private static ?PDO $pdo = null;

    /**
     * Initialize database connection
     * 
     * @return PDO
     */
    private static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            global $pgConfig;
            $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
            self::$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }

    /**
     * Add item to user's cart
     * 
     * @param int $userId User ID
     * @param int $plantId Plant ID
     * @param int $quantity Quantity to add
     * @return array Response with success/error status
     */
    public static function addToCart(int $userId, int $plantId, int $quantity = 1): array
    {
        error_log("[CartItemsHandler::addToCart] Starting - user_id={$userId}, plant_id={$plantId}, quantity={$quantity}");
        
        try {
            $pdo = self::getConnection();
            error_log("[CartItemsHandler::addToCart] Database connection successful");
            
            // First, validate the plant exists and is available
            $plant = CartItemsUtil::validatePlant($pdo, $plantId);
            if (empty($plant)) {
                error_log("[CartItemsHandler::addToCart] Invalid plant_id={$plantId} for user_id={$userId}");
                return ['success' => false, 'message' => 'Plant not found or unavailable'];
            }
            error_log("[CartItemsHandler::addToCart] Plant validation successful: " . $plant['name']);
            
            // Check stock availability - now simplified since stock is managed directly
            if (!CartItemsUtil::checkStockAvailability($pdo, $plantId, $quantity)) {
                error_log("[CartItemsHandler::addToCart] Insufficient stock for plant_id={$plantId}, requested={$quantity}");
                return ['success' => false, 'message' => 'Insufficient stock available'];
            }
            error_log("[CartItemsHandler::addToCart] Stock availability check passed");
            
            // Ensure user has a cart
            $cartId = CartItemsUtil::getOrCreateUserCart($pdo, $userId);
            
            if (!$cartId) {
                error_log("[CartItemsHandler::addToCart] Failed to get/create cart for user_id={$userId}");
                return ['success' => false, 'message' => 'Failed to access cart'];
            }
            error_log("[CartItemsHandler::addToCart] Cart ID: {$cartId}");

            // Check if item already exists in cart
            $stmt = $pdo->prepare("
                SELECT cart_item_id, quantity 
                FROM cart_items 
                WHERE cart_id = :cart_id AND plant_id = :plant_id AND incart = TRUE
            ");
            $stmt->execute([
                ':cart_id' => $cartId,
                ':plant_id' => $plantId
            ]);
            $existingItem = $stmt->fetch();

            if ($existingItem) {
                // Update existing item quantity
                $newQuantity = $existingItem['quantity'] + $quantity;
                $updateStmt = $pdo->prepare("
                    UPDATE cart_items 
                    SET quantity = :quantity 
                    WHERE cart_item_id = :cart_item_id
                ");
                $updateStmt->execute([
                    ':quantity' => $newQuantity,
                    ':cart_item_id' => $existingItem['cart_item_id']
                ]);
                
                // Decrease stock quantity in plants table
                $stockStmt = $pdo->prepare("
                    UPDATE plants 
                    SET stock_quantity = stock_quantity - :quantity 
                    WHERE plant_id = :plant_id AND stock_quantity >= :quantity
                ");
                $stockUpdated = $stockStmt->execute([
                    ':quantity' => $quantity,
                    ':plant_id' => $plantId
                ]);
                
                if ($stockStmt->rowCount() === 0) {
                    error_log("[CartItemsHandler::addToCart] Failed to update stock for plant_id={$plantId}, quantity={$quantity}");
                    return ['success' => false, 'message' => 'Failed to update stock - insufficient quantity available'];
                }
                
                // Get updated stock quantity
                $updatedStockStmt = $pdo->prepare("SELECT stock_quantity FROM plants WHERE plant_id = :plant_id");
                $updatedStockStmt->execute([':plant_id' => $plantId]);
                $updatedStock = $updatedStockStmt->fetch();
                $newStockQuantity = $updatedStock ? $updatedStock['stock_quantity'] : 0;
                
                error_log("[CartItemsHandler::addToCart] Updated existing cart item and decreased stock for user_id={$userId}, plant_id={$plantId}, new_quantity={$newQuantity}, stock_decreased={$quantity}");
                return ['success' => true, 'message' => 'Item quantity updated in cart', 'action' => 'updated', 'new_stock' => $newStockQuantity, 'plant_id' => $plantId];
            } else {
                // Add new item to cart
                $insertStmt = $pdo->prepare("
                    INSERT INTO cart_items (cart_id, plant_id, quantity, incart) 
                    VALUES (:cart_id, :plant_id, :quantity, TRUE)
                ");
                $insertStmt->execute([
                    ':cart_id' => $cartId,
                    ':plant_id' => $plantId,
                    ':quantity' => $quantity
                ]);
                
                // Decrease stock quantity in plants table
                $stockStmt = $pdo->prepare("
                    UPDATE plants 
                    SET stock_quantity = stock_quantity - :quantity 
                    WHERE plant_id = :plant_id AND stock_quantity >= :quantity
                ");
                $stockUpdated = $stockStmt->execute([
                    ':quantity' => $quantity,
                    ':plant_id' => $plantId
                ]);
                
                if ($stockStmt->rowCount() === 0) {
                    error_log("[CartItemsHandler::addToCart] Failed to update stock for plant_id={$plantId}, quantity={$quantity}");
                    return ['success' => false, 'message' => 'Failed to update stock - insufficient quantity available'];
                }
                
                // Get updated stock quantity
                $updatedStockStmt = $pdo->prepare("SELECT stock_quantity FROM plants WHERE plant_id = :plant_id");
                $updatedStockStmt->execute([':plant_id' => $plantId]);
                $updatedStock = $updatedStockStmt->fetch();
                $newStockQuantity = $updatedStock ? $updatedStock['stock_quantity'] : 0;
                
                error_log("[CartItemsHandler::addToCart] Added new cart item and decreased stock for user_id={$userId}, plant_id={$plantId}, quantity={$quantity}");
                return ['success' => true, 'message' => 'Item added to cart', 'action' => 'added', 'new_stock' => $newStockQuantity, 'plant_id' => $plantId];
            }

        } catch (PDOException $e) {
            error_log('[CartItemsHandler::addToCart] Database error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        } catch (Exception $e) {
            error_log('[CartItemsHandler::addToCart] General error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred'];
        }
    }

    /**
     * Get all cart items for a user
     * 
     * @param int $userId User ID
     * @return array Cart items with plant details
     */
    public static function getUserCartItems(int $userId): array
    {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("
                SELECT 
                    ci.cart_item_id,
                    ci.quantity,
                    p.plant_id,
                    p.name,
                    p.price,
                    p.image_url,
                    p.stock_quantity
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                JOIN plants p ON ci.plant_id = p.plant_id
                WHERE c.user_id = :user_id 
                AND p.isDeleted = FALSE
                AND ci.incart = TRUE
                ORDER BY ci.cart_item_id DESC
            ");
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log('[CartItemsHandler::getUserCartItems] Database error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Remove item from cart
     * 
     * @param int $userId User ID
     * @param int $plantId Plant ID
     * @return array Response with success/error status
     */
    public static function removeFromCart(int $userId, int $plantId): array
    {
        try {
            $pdo = self::getConnection();
            
            // First, get the quantity of the item being removed
            $selectStmt = $pdo->prepare("
                SELECT ci.quantity 
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                WHERE c.user_id = :user_id AND ci.plant_id = :plant_id AND ci.incart = TRUE
            ");
            $selectStmt->execute([
                ':user_id' => $userId,
                ':plant_id' => $plantId
            ]);
            $cartItem = $selectStmt->fetch();
            
            if (!$cartItem) {
                return ['success' => false, 'message' => 'Item not found in cart'];
            }
            
            $quantityToRestore = $cartItem['quantity'];
            
            // Remove the item from cart
            $stmt = $pdo->prepare("
                DELETE FROM cart_items 
                WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
                AND plant_id = :plant_id AND incart = TRUE
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':plant_id' => $plantId
            ]);
            
            if ($stmt->rowCount() > 0) {
                // Restore stock quantity in plants table
                $stockStmt = $pdo->prepare("
                    UPDATE plants 
                    SET stock_quantity = stock_quantity + :quantity 
                    WHERE plant_id = :plant_id
                ");
                $stockStmt->execute([
                    ':quantity' => $quantityToRestore,
                    ':plant_id' => $plantId
                ]);
                
                error_log("[CartItemsHandler::removeFromCart] Removed cart item and restored stock for user_id={$userId}, plant_id={$plantId}, quantity_restored={$quantityToRestore}");
                return ['success' => true, 'message' => 'Item removed from cart'];
            } else {
                return ['success' => false, 'message' => 'Item not found in cart'];
            }
            
        } catch (PDOException $e) {
            error_log('[CartItemsHandler::removeFromCart] Database error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        }
    }

    /**
     * Update cart item quantity
     * 
     * @param int $userId User ID
     * @param int $plantId Plant ID
     * @param int $quantity New quantity
     * @return array Response with success/error status
     */
    public static function updateCartItemQuantity(int $userId, int $plantId, int $quantity): array
    {
        try {
            $pdo = self::getConnection();
            
            if ($quantity <= 0) {
                return self::removeFromCart($userId, $plantId);
            }
            
            // First, get the current quantity in cart
            $selectStmt = $pdo->prepare("
                SELECT ci.quantity 
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                WHERE c.user_id = :user_id AND ci.plant_id = :plant_id AND ci.incart = TRUE
            ");
            $selectStmt->execute([
                ':user_id' => $userId,
                ':plant_id' => $plantId
            ]);
            $cartItem = $selectStmt->fetch();
            
            if (!$cartItem) {
                return ['success' => false, 'message' => 'Item not found in cart'];
            }
            
            $currentQuantity = $cartItem['quantity'];
            $quantityDifference = $quantity - $currentQuantity;
            
            // Check if we have enough stock for increase
            if ($quantityDifference > 0) {
                $stockCheckStmt = $pdo->prepare("
                    SELECT stock_quantity 
                    FROM plants 
                    WHERE plant_id = :plant_id
                ");
                $stockCheckStmt->execute([':plant_id' => $plantId]);
                $plant = $stockCheckStmt->fetch();
                
                if (!$plant || $plant['stock_quantity'] < $quantityDifference) {
                    return ['success' => false, 'message' => 'Insufficient stock available'];
                }
            }
            
            // Update cart item quantity
            $stmt = $pdo->prepare("
                UPDATE cart_items 
                SET quantity = :quantity 
                WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
                AND plant_id = :plant_id AND incart = TRUE
            ");
            $stmt->execute([
                ':quantity' => $quantity,
                ':user_id' => $userId,
                ':plant_id' => $plantId
            ]);
            
            if ($stmt->rowCount() > 0) {
                // Update stock quantity based on the difference
                if ($quantityDifference != 0) {
                    $stockStmt = $pdo->prepare("
                        UPDATE plants 
                        SET stock_quantity = stock_quantity - :quantity_diff 
                        WHERE plant_id = :plant_id
                    ");
                    $stockStmt->execute([
                        ':quantity_diff' => $quantityDifference,
                        ':plant_id' => $plantId
                    ]);
                }
                
                error_log("[CartItemsHandler::updateCartItemQuantity] Updated cart item quantity for user_id={$userId}, plant_id={$plantId}, old_quantity={$currentQuantity}, new_quantity={$quantity}, stock_change={$quantityDifference}");
                return ['success' => true, 'message' => 'Cart item quantity updated'];
            } else {
                return ['success' => false, 'message' => 'Item not found in cart'];
            }
            
        } catch (PDOException $e) {
            error_log('[CartItemsHandler::updateCartItemQuantity] Database error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        }
    }

    /**
     * Clear all items from user's cart
     * 
     * @param int $userId User ID
     * @return array Response with success/error status
     */
    public static function clearCart(int $userId): array
    {
        try {
            $pdo = self::getConnection();
            
            // First, restore stock quantities for all items in cart
            $restoreStockStmt = $pdo->prepare("
                UPDATE plants 
                SET stock_quantity = stock_quantity + ci.quantity
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                WHERE plants.plant_id = ci.plant_id 
                AND c.user_id = :user_id
                AND ci.incart = TRUE
            ");
            $restoreStockStmt->execute([':user_id' => $userId]);
            $stockRestoredCount = $restoreStockStmt->rowCount();
            
            // Then clear the cart
            $stmt = $pdo->prepare("
                DELETE FROM cart_items 
                WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
                AND incart = TRUE
            ");
            $stmt->execute([':user_id' => $userId]);
            
            error_log("[CartItemsHandler::clearCart] Cleared cart for user_id={$userId}, items_removed={$stmt->rowCount()}, stock_restored_for={$stockRestoredCount}_plants");
            return ['success' => true, 'message' => 'Cart cleared successfully'];
            
        } catch (PDOException $e) {
            error_log('[CartItemsHandler::clearCart] Database error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        }
    }

    /**
     * Mark cart items as ordered (set incart to FALSE) instead of deleting them
     * 
     * @param int $userId User ID
     * @param int|null $orderId Optional order ID to assign to cart items
     * @return array Response with success/error status
     */
    public static function markCartItemsAsOrdered(int $userId, ?int $orderId = null): array
    {
        try {
            $pdo = self::getConnection();
            
            // Update all cart items to set incart = FALSE and optionally assign order_id
            if ($orderId !== null) {
                $stmt = $pdo->prepare("
                    UPDATE cart_items 
                    SET incart = FALSE, order_id = :order_id 
                    WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
                    AND incart = TRUE
                ");
                $stmt->execute([':user_id' => $userId, ':order_id' => $orderId]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE cart_items 
                    SET incart = FALSE 
                    WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
                    AND incart = TRUE
                ");
                $stmt->execute([':user_id' => $userId]);
            }
            $updatedCount = $stmt->rowCount();
            
            error_log("[CartItemsHandler::markCartItemsAsOrdered] Marked {$updatedCount} cart items as ordered for user_id={$userId}" . ($orderId ? " with order_id={$orderId}" : ""));
            return ['success' => true, 'message' => 'Cart items marked as ordered', 'items_updated' => $updatedCount];
            
        } catch (PDOException $e) {
            error_log('[CartItemsHandler::markCartItemsAsOrdered] Database error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        }
    }
}
?>