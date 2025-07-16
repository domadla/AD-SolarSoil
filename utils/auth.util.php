<?php
declare(strict_types=1);

include_once UTILS_PATH . "/envSetter.util.php";

class Auth
{
    /**
     * Initialize session if not already started
     * 
     * @return void
     */
    public static function init(): void{
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Attempt login; returns true if successful
     * 
     * @param PDO       $pdo        Used to check for existing users
     * @param string    $username   Key for user lookup
     * @param string    $password   User's password
     * @return bool                 True if login successful, false otherwise
     */
    public static function login(PDO $pdo, string $username, string $password): bool
    {
        try {
            // 1) Fetch the user record
            $stmt = $pdo->prepare("
                SELECT
                    *
                FROM USERS
                WHERE username = :username;
            ");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            // Log any SQL errors
            error_log('[Auth::login] PDOException: ' . $e->getMessage());
            return false;
        }

        // Debug output: did we get a row?
        if (!$user) {
            error_log("[Auth::login] No user found for username='{$username}'");
            return false;
        } else {
            if ($user['isdeleted'] === true) {
                error_log("[Auth::login] User is deleted: {$username}");
                return false;
            } else {
                error_log('[Auth::login] Retrieved user: ' . var_export([
                    'id' => $user['user_id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                ], true));
            }
        }

        // 2) Verify password
        if (!password_verify($password, $user['password'])) {
            error_log("[Auth::login] Password mismatch for user_id={$user['user_id']}");
            return false;
        }

        // 3) Success: regenerate session & store user + role
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['user_id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];
        error_log("[Auth::login] Login successful for user_id={$user['user_id']}");

        return true;
    }

    /**
     * Register a new user
     *
     * @param PDO $pdo
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $address
     * @param string $password
     * @return array ['success' => 'Message'] or ['error' => 'Message']
     */
    public static function register(PDO $pdo, string $firstname, string $lastname, string $username, string $address, string $password, string $role): array
    {
        // 1) Check if username already exists
        try {
            $stmt = $pdo->prepare("SELECT user_id FROM USERS WHERE username = :username");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                error_log("[Auth::register] Attempt to register existing username: {$username}");
                return ['error' => 'UsernameAlreadyTaken'];
            }
        } catch (\PDOException $e) {
            error_log('[Auth::register] PDOException on check: ' . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }

        // 2) Hash the password - This is a critical security step.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 3) Insert the new user
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO USERS (firstname, lastname, username, address, password, role)
                VALUES (:firstname, :lastname, :username, :address, :password, :role)"
            );
            $stmt->execute([
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':username' => $username,
                ':address' => $address,
                ':password' => $hashedPassword,
                ':role' => $role // Default role
            ]);

            error_log("[Auth::register] New user created: {$username}");
            return ['success' => 'SignupComplete'];
        } catch (\PDOException $e) {
            error_log('[Auth::register] PDOException on insert: ' . $e->getMessage());
            return ['error' => 'DatabaseError'];
        }
    }

    /**
     * Returns the currently logged-in user, or null if not logged in
     *
     */



    /**
     * Returns the currently logged-in user, or null if not logged in
     *
     * @return array|null   User data if logged in, null otherwise
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if a user is logged in
     * 
     * @return bool   True if logged in, false otherwise
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Log out the current user
     * 
     * @return void
     */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}
