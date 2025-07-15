<?php
/**
 * Cart Items Utility - Helper functions for cart operations
 * Provides utility functions for cart management
 */

class CartItemsUtil
{
    /**
     * Get or create a cart for the user
     * 
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @return int|false Cart ID on success, false on failure
     */
    public static function getOrCreateUserCart(PDO $pdo, int $userId)
    {
        try {
            // First, try to get existing cart
            $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);
            $cart = $stmt->fetch();
            
            if ($cart) {
                return $cart['cart_id'];
            }
            
            // Create new cart if doesn't exist
            $insertStmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (:user_id) RETURNING cart_id");
            $insertStmt->execute([':user_id' => $userId]);
            $newCart = $insertStmt->fetch();
            
            if ($newCart && isset($newCart['cart_id'])) {
                error_log("[CartItemsUtil::getOrCreateUserCart] Created new cart for user_id={$userId}, cart_id={$newCart['cart_id']}");
                return $newCart['cart_id'];
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::getOrCreateUserCart] Database error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate plant exists and is available
     * 
     * @param PDO $pdo Database connection
     * @param int $plantId Plant ID
     * @return array Plant data if valid, empty array if invalid
     */
    public static function validatePlant(PDO $pdo, int $plantId): array
    {
        try {
            $stmt = $pdo->prepare("
                SELECT plant_id, name, price, stock_quantity, isDeleted 
                FROM plants 
                WHERE plant_id = :plant_id
            ");
            $stmt->execute([':plant_id' => $plantId]);
            $plant = $stmt->fetch();
            
            if (!$plant || (isset($plant['isDeleted']) && $plant['isDeleted'])) {
                return [];
            }
            
            return $plant;
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::validatePlant] Database error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get current quantity of a specific plant in user's cart
     * 
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @param int $plantId Plant ID
     * @return int Current quantity in cart
     */
    public static function getCurrentCartQuantity(PDO $pdo, int $userId, int $plantId): int
    {
        try {
            $stmt = $pdo->prepare("
                SELECT ci.quantity
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                WHERE c.user_id = :user_id AND ci.plant_id = :plant_id AND ci.incart = TRUE
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':plant_id' => $plantId
            ]);
            $result = $stmt->fetch();
            
            return ($result && isset($result['quantity'])) ? (int)$result['quantity'] : 0;
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::getCurrentCartQuantity] Database error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if adding quantity would exceed stock
     * 
     * @param PDO $pdo Database connection
     * @param int $plantId Plant ID
     * @param int $requestedQuantity Quantity to add
     * @param int $currentCartQuantity Current quantity in cart (optional)
     * @return bool True if stock is sufficient, false otherwise
     */
    public static function checkStockAvailability(PDO $pdo, int $plantId, int $requestedQuantity, int $currentCartQuantity = 0): bool
    {
        try {
            $stmt = $pdo->prepare("SELECT stock_quantity FROM plants WHERE plant_id = :plant_id");
            $stmt->execute([':plant_id' => $plantId]);
            $plant = $stmt->fetch();
            
            if (!$plant) {
                return false;
            }
            
            // Since stock is now immediately decreased when added to cart, 
            // we only need to check if we have enough for the requested quantity
            return isset($plant['stock_quantity']) && $plant['stock_quantity'] >= $requestedQuantity;
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::checkStockAvailability] Database error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get total cart value for a user
     * 
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @return float Total cart value
     */
    public static function getCartTotal(PDO $pdo, int $userId): float
    {
        try {
            $stmt = $pdo->prepare("
                SELECT SUM(ci.quantity * p.price) as total
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                JOIN plants p ON ci.plant_id = p.plant_id
                WHERE c.user_id = :user_id 
                AND p.isDeleted = FALSE
                AND ci.incart = TRUE
            ");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch();
            
            return ($result && isset($result['total'])) ? (float)$result['total'] : 0.0;
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::getCartTotal] Database error: ' . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Get cart item count for a user
     * 
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @return int Total item count in cart
     */
    public static function getCartItemCount(PDO $pdo, int $userId): int
    {
        try {
            $stmt = $pdo->prepare("
                SELECT COUNT(ci.cart_item_id) as count
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                JOIN plants p ON ci.plant_id = p.plant_id
                WHERE c.user_id = :user_id 
                AND p.isDeleted = FALSE
                AND ci.incart = TRUE
            ");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch();
            
            return ($result && isset($result['count'])) ? (int)$result['count'] : 0;
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::getCartItemCount] Database error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Validate cart operation request
     * 
     * @param array $data Request data
     * @param array $requiredFields Required fields
     * @return array Validation result
     */
    public static function validateRequest(array $data, array $requiredFields): array
    {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            return [
                'valid' => false,
                'message' => 'Missing required fields: ' . implode(', ', $missing)
            ];
        }
        
        // Validate plant_id is numeric and positive
        if (isset($data['plant_id']) && (!is_numeric($data['plant_id']) || $data['plant_id'] <= 0)) {
            return [
                'valid' => false,
                'message' => 'Invalid plant ID'
            ];
        }
        
        // Validate quantity is numeric and positive
        if (isset($data['quantity']) && (!is_numeric($data['quantity']) || $data['quantity'] <= 0)) {
            return [
                'valid' => false,
                'message' => 'Invalid quantity'
            ];
        }
        
        return ['valid' => true];
    }

    /**
     * Format response for AJAX requests
     * 
     * @param bool $success Success status
     * @param string $message Response message
     * @param array $data Additional data (optional)
     * @return array Formatted response
     */
    public static function formatResponse(bool $success, string $message, array $data = []): array
    {
        $response = [
            'success' => $success,
            'message' => $message
        ];
        
        if (!empty($data)) {
            $response['data'] = $data;
        }
        
        return $response;
    }

    /**
     * Log cart operation
     * 
     * @param string $operation Operation type
     * @param int $userId User ID
     * @param array $details Additional details
     */
    public static function logCartOperation(string $operation, int $userId, array $details = []): void
    {
        $logData = [
            'operation' => $operation,
            'user_id' => $userId,
            'timestamp' => date('Y-m-d H:i:s'),
            'details' => $details
        ];
        
        error_log('[CartItemsUtil] ' . json_encode($logData));
    }

    /**
     * Get ordered items (items with incart = FALSE) for a user
     * 
     * @param PDO $pdo Database connection
     * @param int $userId User ID
     * @return array Ordered items with plant details
     */
    public static function getOrderedItems(PDO $pdo, int $userId): array
    {
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    ci.cart_item_id,
                    ci.quantity,
                    p.plant_id,
                    p.name,
                    p.price,
                    p.image_url,
                    p.description,
                    ci.created_at
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                JOIN plants p ON ci.plant_id = p.plant_id
                WHERE c.user_id = :user_id 
                AND p.isDeleted = FALSE
                AND ci.incart = FALSE
                ORDER BY ci.created_at DESC
            ");
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log('[CartItemsUtil::getOrderedItems] Database error: ' . $e->getMessage());
            return [];
        }
    }

}
?>