<?php
class UserHandler
{
    /**
     * Get user data by user ID (demo data)
     * @param int $user_id User ID
     * @return array User data
     */
    public static function getUserById($user_id = 1)
    {
        // Demo user data (always include all expected keys)
        $default = [
            'id' => null,
            'first_name' => '',
            'last_name' => '',
            'username' => '',
            'address' => '',
            'join_date' => '',
            'usertype' => '',
            'avatar' => null
        ];
        $users = [
            1 => [
                'id' => 1,
                'first_name' => 'Marlo',
                'last_name' => 'Veluz',
                'username' => 'MarloVeluz',
                'address' => '123 Solar Street, Earth',
                'join_date' => '2025-07-13',
                'usertype' => 'User',
                'avatar' => null
            ]
        ];
        $user = isset($users[$user_id]) ? $users[$user_id] : $default;
        // Ensure all keys exist
        return array_merge($default, $user);
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
}
