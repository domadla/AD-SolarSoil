<?php
declare(strict_types=1);

require_once UTILS_PATH . "/envSetter.util.php";

class ProfileUtil
{
    /**
     * Soft delete user (set isDeleted=true)
     *
     * @param int $userId
     * @return bool True on success, false on failure
     */
    public static function softDeleteUser(int $userId): bool
    {
        try {
            $pdo = self::getConnection();
            $pdo->beginTransaction();




            // Delete from cart_items (by cart_id)
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)");
            $stmt->execute([':user_id' => $userId]);

            // Delete from orders
            $stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);

            // Delete from carts
            $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);

            // Soft delete user
            $stmt = $pdo->prepare("UPDATE users SET isDeleted = TRUE WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Error soft deleting user and related data: ' . $e->getMessage());
            return false;
        }
    }
    private static $pdo = null;

    /**
     * Get PDO connection instance
     * 
     * @return PDO
     */
    private static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            global $pgConfig;
            
            $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
            
            try {
                self::$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                throw new Exception('Database connection failed');
            }
        }
        
        return self::$pdo;
    }

    /**
     * Get user data by user ID from database
     * 
     * @param int $userId User ID
     * @return array|null User data or null if not found
     */
    public static function getUserById(int $userId): ?array
    {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("
                SELECT 
                    user_id,
                    username,
                    firstname,
                    lastname,
                    role,
                    address,
                    created_at,
                    isDeleted
                FROM users 
                WHERE user_id = :user_id AND isDeleted = FALSE
            ");
            
            $stmt->execute([':user_id' => $userId]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return null;
            }
            
            // Format the data to match frontend expectations
            return [
                'id' => $user['user_id'],
                'user_id' => $user['user_id'],
                'first_name' => $user['firstname'],
                'last_name' => $user['lastname'],
                'username' => $user['username'],
                'address' => $user['address'] ?? 'Not provided',
                'usertype' => ucfirst($user['role']),
                'join_date' => $user['created_at'],
                'role' => $user['role']
            ];
            
        } catch (PDOException $e) {
            error_log('Error fetching user by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user data by username from database
     * 
     * @param string $username Username
     * @return array|null User data or null if not found
     */
    public static function getUserByUsername(string $username): ?array
    {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("
                SELECT 
                    user_id,
                    username,
                    firstname,
                    lastname,
                    role,
                    address,
                    created_at,
                    isDeleted
                FROM users 
                WHERE username = :username AND isDeleted = FALSE
            ");
            
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return null;
            }
            
            // Format the data to match frontend expectations
            return [
                'id' => $user['user_id'],
                'user_id' => $user['user_id'],
                'first_name' => $user['firstname'],
                'last_name' => $user['lastname'],
                'username' => $user['username'],
                'address' => $user['address'] ?? 'Not provided',
                'usertype' => ucfirst($user['role']),
                'join_date' => $user['created_at'],
                'role' => $user['role'],
                'isDeleted' => $user['isDeleted']
            ];
            
        } catch (PDOException $e) {
            error_log('Error fetching user by username: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user exists and is not deleted
     * 
     * @param int $userId User ID
     * @return bool True if user exists and is active
     */
    public static function userExists(int $userId): bool
    {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE user_id = :user_id AND isDeleted = FALSE
            ");
            
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch();
            
            return (int)$result['count'] > 0;
            
        } catch (PDOException $e) {
            error_log('Error checking if user exists: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get total number of active users
     * 
     * @return int Total count of active users
     */
    public static function getTotalUsersCount(): int
    {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE isDeleted = FALSE
            ");
            
            $stmt->execute();
            $result = $stmt->fetch();
            
            return (int)$result['count'];
            
        } catch (PDOException $e) {
            error_log('Error getting total users count: ' . $e->getMessage());
            return 0;
        }
    }
    public static function edit_profile(PDO $pdo, int $id, array $data) {
        try {
            $updates = [];
            $params = [':id' => $id];

            if (isset($data['first_name'])) {
                $updates[] = 'firstname = :firstname';
                $params[':firstname'] = $data['first_name'];
            }
            if (isset($data['last_name'])) {
                $updates[] = 'lastname = :lastname';
                $params[':lastname'] = $data['last_name'];
            }
            if (isset($data['address'])) {
                $updates[] = 'address = :address';
                $params[':address'] = $data['address'];
            }
            if (isset($data['username'])) {
                $updates[] = 'username = :username';
                $params[':username'] = $data['username'];
            }

            if (empty($updates)) {
                return ['error' => 'NoFieldsToUpdate'];
            }

            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return ['success' => 'UserUpdatedSuccessfully'];
        } catch (\PDOException $e) {
            error_log('[ProfileUtil::edit_user] PDOException on update: ' . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
    }
}
?>