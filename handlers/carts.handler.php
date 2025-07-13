<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
require_once UTILS_PATH . 'carts.util.php';

// Start session
session_start();

// Set JSON response header
header('Content-Type: application/json');

/**
 * Send JSON response and exit
 */
function sendResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Check if user is authenticated
 */
function checkAuth(): array
{
    if (!Auth::check()) {
        sendResponse(['error' => 'AuthenticationRequired'], 401);
    }
    return Auth::user();
}

/**
 * Get request method and handle CORS
 */
$method = $_SERVER['REQUEST_METHOD'];

// Handle CORS for frontend requests
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit;
}

try {
    if ($method === 'GET') {
        handleGetRequest();
    } else {
        sendResponse(['error' => 'MethodNotAllowed'], 405);
    }
} catch (Exception $e) {
    error_log('[Cart Handler] Exception: ' . $e->getMessage());
    sendResponse(['error' => 'InternalServerError'], 500);
}

/**
 * Handle GET requests - Get or create cart ID
 */
function handleGetRequest(): void
{
    $user = checkAuth();
    $userId = (int)$user['id'];
    
    try {
        // Get or create cart for user
        $cartId = Cart::getOrCreateCart($userId);
        sendResponse([
            'success' => true,
            'data' => ['cart_id' => $cartId]
        ]);
    } catch (Exception $e) {
        sendResponse(['error' => 'CartCreationFailed'], 500);
    }
}
?>
