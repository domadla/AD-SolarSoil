<?php
declare(strict_types=1);

include_once UTILS_PATH . "/envSetter.util.php";

class Cart
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
     * Get or create cart for a user
     * 
     * @param int $userId User ID
     * @return int Cart ID
     * @throws PDOException
     */
    public static function getOrCreateCart(int $userId): int
    {
        try {
            $pdo = self::getConnection();
            
            // First, try to get existing cart
            $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);
            $cart = $stmt->fetch();
            
            if ($cart) {
                error_log("[Cart::getOrCreateCart] Found existing cart_id={$cart['cart_id']} for user_id={$userId}");
                return (int)$cart['cart_id'];
            }
            
            // Create new cart if not exists
            $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (:user_id) RETURNING cart_id");
            $stmt->execute([':user_id' => $userId]);
            $cartId = (int)$stmt->fetchColumn();
            
            error_log("[Cart::getOrCreateCart] Created new cart_id={$cartId} for user_id={$userId}");
            return $cartId;
            
        } catch (\PDOException $e) {
            error_log('[Cart::getOrCreateCart] PDOException: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get cart ID for a user (without creating if not exists)
     * 
     * @param int $userId User ID
     * @return int|null Cart ID or null if not found
     */
    public static function getCartId(int $userId): ?int
    {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);
            $cart = $stmt->fetch();
            
            return $cart ? (int)$cart['cart_id'] : null;
            
        } catch (\PDOException $e) {
            error_log('[Cart::getCartId] PDOException: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create cart for newly registered user by username
     * 
     * @param string $username Username of the new user
     * @return int|null Cart ID if successful, null if user not found
     */
    public static function createCartForNewUser(string $username): ?int
    {
        try {
            $pdo = self::getConnection();
            
            // Get user ID from username
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $userData = $stmt->fetch();
            
            if (!$userData) {
                error_log("[Cart::createCartForNewUser] User not found: {$username}");
                return null;
            }
            
            $userId = (int)$userData['user_id'];
            
            // Create cart for the user
            $cartId = self::getOrCreateCart($userId);
            error_log("[Cart::createCartForNewUser] Cart ID {$cartId} created for new user {$userId} ({$username})");
            
            return $cartId;
            
        } catch (\PDOException $e) {
            error_log('[Cart::createCartForNewUser] PDOException: ' . $e->getMessage());
            return null;
        }
    }
}
