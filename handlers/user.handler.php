<?php

if (!class_exists('UserHandlerDemo')) {
    class UserHandlerDemo
    {
        public static function getAllUsers()
        {
            return [
                [
                    'id' => 1,
                    'first_name' => 'Marlo',
                    'last_name' => 'Veluz',
                    'username' => 'marlo.veluz',
                    'role' => 'Admin',
                ],
                [
                    'id' => 2,
                    'first_name' => 'Ronn',
                    'last_name' => 'Rosarito',
                    'username' => 'ronn.rosarito',
                    'role' => 'Admin',
                ],
                [
                    'id' => 3,
                    'first_name' => 'Dominic',
                    'last_name' => 'Madlangbayan',
                    'username' => 'dom.madla',
                    'role' => 'Admin',
                ],
                [
                    'id' => 4,
                    'first_name' => 'Carl',
                    'last_name' => 'Marino',
                    'username' => 'carl.marino',
                    'role' => 'Admin',
                ],
                [
                    'id' => 5,
                    'first_name' => 'Mark',
                    'last_name' => 'Gamboa',
                    'username' => 'mark.gamboa',
                    'role' => 'Admin',
                ],
            ];
        }
    }
}
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
                'address' => '123 Solar Street, Earth',
                'join_date' => '2025-07-13',
                'usertype' => 'User',
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
}

?>