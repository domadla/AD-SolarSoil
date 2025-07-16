<?php
require_once '../bootstrap.php';
require_once HANDLERS_PATH . '/profile.handler.php';
require_once UTILS_PATH . '/auth.util.php';

header('Content-Type: application/json');

Auth::init();
if (!Auth::check()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = Auth::user();
if (!$user || !isset($user['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$userId = (int)$user['id'];

try {
    $result = ProfileHandler::deleteAccount($userId);
    if ($result) {
        Auth::logout();
        echo json_encode(['success' => true]);
    } else {
        // Log the error for debugging
        error_log('Delete account failed for user_id: ' . $userId);
        echo json_encode(['success' => false, 'message' => 'Failed to delete account. Check server logs for details.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
