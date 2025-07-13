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
        // Demo user data
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
        // Demo stats
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
}
