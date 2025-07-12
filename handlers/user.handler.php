<?php
/**
 * User Handler - Manages user data and operations
 * In a real application, this would interact with a database
 */

class UserHandler
{

    /**
     * Get user data by user ID (demo data)
     * @param int $user_id User ID
     * @return array User data
     */
    public static function getUserById($user_id = 1)
    {
        // Demo user data (in a real app, this would come from database)
        $users = [
            1 => [
                'id' => 1,
                'first_name' => 'Marlo',
                'last_name' => 'Veluz',
                'username' => 'MarloVeluz',
                'email' => 'marloveluz@solarsoil.galaxy',
                'join_date' => '2024-03-15',
                'planet' => 'Earth',
                'rank' => 'Cosmic Gardener',
                'avatar' => null
            ]
        ];

        return isset($users[$user_id]) ? $users[$user_id] : null;
    }

    /**
     * Get current logged-in user data
     * @return array User data
     */
    public static function getCurrentUser()
    {
        // In a real app, this would get user ID from session
        // For demo purposes, we'll always return user ID 1
        return self::getUserById(1);
    }

    /**
     * Get user statistics
     * @param int $user_id User ID
     * @return array User statistics
     */
    public static function getUserStats($user_id = 1)
    {
        // Demo stats (in a real app, this would come from database)
        $stats = [
            1 => [
                'plants_owned' => 47,
                'orders_placed' => 12,
                'total_spent' => 1247,
                'favorite_plant_type' => 'Cosmic Orchids',
                'days_active' => 125
            ]
        ];

        return isset($stats[$user_id]) ? $stats[$user_id] : [
            'plants_owned' => 0,
            'orders_placed' => 0,
            'total_spent' => 0,
            'favorite_plant_type' => 'None',
            'days_active' => 0
        ];
    }

    /**
     * Update user profile data
     * @param int $user_id User ID
     * @param array $data Updated user data
     * @return bool Success status
     */
    public static function updateUser($user_id, $data)
    {
        // In a real app, this would update the database
        // For demo purposes, we'll just return true
        return true;
    }

    /**
     * Validate user credentials (demo)
     * @param string $username Username
     * @param string $password Password
     * @return array|false User data if valid, false if invalid
     */
    public static function validateCredentials($username, $password)
    {
        // Demo validation (in a real app, this would check against database)
        $valid_users = [
            'MarloVeluz' => [
                'password' => 'demo123', // In real app, this would be hashed
                'user_id' => 1
            ]
        ];

        if (isset($valid_users[$username]) && $valid_users[$username]['password'] === $password) {
            return self::getUserById($valid_users[$username]['user_id']);
        }

        return false;
    }

    /**
     * Check if user is logged in
     * @return bool Login status
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
    }

    /**
     * Login user
     * @param int $user_id User ID
     * @return bool Success status
     */
    public static function loginUser($user_id)
    {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user_id;
        return true;
    }

    /**
     * Logout user
     * @return bool Success status
     */
    public static function logoutUser()
    {
        unset($_SESSION['user_logged_in']);
        unset($_SESSION['user_id']);
        return true;
    }
}
?>