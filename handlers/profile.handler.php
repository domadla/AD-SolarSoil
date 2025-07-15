<?php
declare(strict_types=1);

require_once UTILS_PATH . '/profile.util.php';
require_once UTILS_PATH . '/auth.util.php';

class ProfileHandler
{
    /**
     * Get current logged-in user data from database
     * 
     * @return array|null User data or null if not found
     */
    public static function getCurrentUser(): ?array
    {
        // Initialize session if not started
        Auth::init();
        
        // Check if user is logged in and get user data from session
        $sessionUser = Auth::user();
        if (!$sessionUser || !isset($sessionUser['id'])) {
            error_log('ProfileHandler::getCurrentUser - No valid user session');
            return null;
        }
        
        $userId = (int)$sessionUser['id'];
        
        try {
            $userData = ProfileUtil::getUserById($userId);
            
            if (!$userData) {
                error_log("ProfileHandler::getCurrentUser - User not found for ID: {$userId}");
                return null;
            }
            
            return $userData;
            
        } catch (Exception $e) {
            error_log('ProfileHandler::getCurrentUser - Error: ' . $e->getMessage());
            return null;
        }
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
            return ProfileUtil::getUserById($userId);
        } catch (Exception $e) {
            error_log('ProfileHandler::getUserById - Error: ' . $e->getMessage());
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
            return ProfileUtil::getUserByUsername($username);
        } catch (Exception $e) {
            error_log('ProfileHandler::getUserByUsername - Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user exists and is active
     * 
     * @param int $userId User ID
     * @return bool True if user exists and is active
     */
    public static function userExists(int $userId): bool
    {
        try {
            return ProfileUtil::userExists($userId);
        } catch (Exception $e) {
            error_log('ProfileHandler::userExists - Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get formatted user display name
     * 
     * @param array $userData User data array
     * @return string Formatted display name
     */
    public static function getDisplayName(array $userData): string
    {
        if (empty($userData['first_name']) && empty($userData['last_name'])) {
            return $userData['username'] ?? 'Unknown User';
        }
        
        $firstName = $userData['first_name'] ?? '';
        $lastName = $userData['last_name'] ?? '';
        
        return trim($firstName . ' ' . $lastName);
    }

    /**
     * Get formatted join date
     * 
     * @param array $userData User data array
     * @return string Formatted join date
     */
    public static function getFormattedJoinDate(array $userData): string
    {
        if (empty($userData['join_date'])) {
            return 'Unknown';
        }
        
        try {
            return date('F j, Y', strtotime($userData['join_date']));
        } catch (Exception $e) {
            error_log('ProfileHandler::getFormattedJoinDate - Error: ' . $e->getMessage());
            return 'Unknown';
        }
    }

    /**
     * Validate user session and get user data
     * 
     * @return array|null User data if valid session, null otherwise
     */
    public static function validateSessionAndGetUser(): ?array
    {
        Auth::init();
        
        // Check if user is logged in
        if (!Auth::check()) {
            return null;
        }
        
        return self::getCurrentUser();
    }


}
?>