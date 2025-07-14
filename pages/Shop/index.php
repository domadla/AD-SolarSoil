<?php
// Include handlers and utilities
require_once '../../bootstrap.php';
require_once HANDLERS_PATH . '/plants.handler.php';
require_once HANDLERS_PATH . '/cartItems.handler.php';
require_once UTILS_PATH . '/plantDisplay.util.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/cartItems.util.php';
require_once UTILS_PATH . '/htmlEscape.util.php';

Auth::init();
if (!Auth::check()){
    header('Location: ../../index.php?error=LoginRequired');
    exit;
}
// Handle cart operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $user = Auth::user();
    if (!$user) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            // AJAX request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not authenticated']);
            exit;
        } else {
            // Regular form submission
            header('Location: ../../index.php?error=LoginRequired');
            exit;
        }
    }
    
    $action = $_GET['action'];
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
    // Debug logging
    error_log('[Shop] Action: ' . $action . ', isAjax: ' . ($isAjax ? 'true' : 'false'));
    error_log('[Shop] Request method: ' . $_SERVER['REQUEST_METHOD']);
    
    if ($isAjax) {
        header('Content-Type: application/json');
    }
    
    $response = ['success' => false, 'message' => 'Invalid action'];
    
    switch ($action) {
        case 'add_to_cart':
            // Handle both AJAX and form submission
            if ($isAjax) {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input) {
                    $input = $_POST;
                }
                error_log('[Shop] AJAX input: ' . json_encode($input));
            } else {
                $input = $_POST;
                error_log('[Shop] Form input: ' . json_encode($input));
            }
            
            // Validate request - expect 'id' field
            if (!isset($input['id']) || !is_numeric($input['id']) || $input['id'] <= 0) {
                $response = ['success' => false, 'message' => 'Invalid plant ID'];
                if (!$isAjax) {
                    // Redirect with error for form submission
                    header('Location: ?error=invalid_plant');
                    exit;
                }
                break;
            }
            
            $plantId = (int)$input['id'];
            $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;
            
            // Validate quantity
            if ($quantity <= 0) {
                $response = ['success' => false, 'message' => 'Invalid quantity'];
                if (!$isAjax) {
                    header('Location: ?error=invalid_quantity');
                    exit;
                }
                break;
            }
            
            // Add to cart
            $response = CartItemsHandler::addToCart($user['id'], $plantId, $quantity);
            
            // Debug logging
            error_log('[Shop] Cart operation result: ' . json_encode($response));
            
            // Log the operation
            CartItemsUtil::logCartOperation('add_to_cart', $user['id'], [
                'plant_id' => $plantId,
                'quantity' => $quantity,
                'success' => $response['success']
            ]);
            
            if (!$isAjax) {
                // Redirect with success/error message for form submission
                if ($response['success']) {
                    header('Location: ?success=item_added');
                } else {
                    header('Location: ?error=add_failed');
                }
                exit;
            }
            break;
            
        case 'sync_cart':
            // AJAX only - sync cart from frontend localStorage to database
            if (!$isAjax) {
                $response = ['success' => false, 'message' => 'This action requires AJAX'];
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['cartItems'])) {
                $response = ['success' => false, 'message' => 'No cart data provided'];
                break;
            }
            
            $cartItems = $input['cartItems'];
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($cartItems as $item) {
                if (isset($item['id']) && isset($item['quantity'])) {
                    $result = CartItemsHandler::addToCart($user['id'], (int)$item['id'], (int)$item['quantity']);
                    if ($result['success']) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                }
            }
            
            $response = [
                'success' => true, 
                'message' => "Cart synced: {$successCount} items added, {$errorCount} errors",
                'synced' => $successCount,
                'errors' => $errorCount
            ];
            
            CartItemsUtil::logCartOperation('sync_cart', $user['id'], [
                'items_synced' => $successCount,
                'errors' => $errorCount
            ]);
            break;
            
        case 'get_cart_count':
            // AJAX only - get cart count
            if (!$isAjax) {
                $response = ['success' => false, 'message' => 'This action requires AJAX'];
                break;
            }
            
            try {
                require_once UTILS_PATH . '/envSetter.util.php';
                global $pgConfig;
                $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
                $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                
                $count = CartItemsUtil::getCartItemCount($pdo, $user['id']);
                $response = ['success' => true, 'count' => $count];
            } catch (Exception $e) {
                error_log('[Shop::get_cart_count] Error: ' . $e->getMessage());
                $response = ['success' => false, 'message' => 'Failed to get cart count'];
            }
            break;
            
        case 'get_cart_items':
            // AJAX only - get all cart items for current user
            if (!$isAjax) {
                $response = ['success' => false, 'message' => 'This action requires AJAX'];
                break;
            }
            
            try {
                require_once UTILS_PATH . '/envSetter.util.php';
                global $pgConfig;
                $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
                $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                
                $cartItems = CartItemsHandler::getUserCartItems($user['id']);
                $response = ['success' => true, 'cart' => $cartItems];
            } catch (Exception $e) {
                error_log('[Shop::get_cart_items] Error: ' . $e->getMessage());
                $response = ['success' => false, 'message' => 'Failed to get cart items'];
            }
            break;

        case 'remove_from_cart':
            // AJAX only - remove item from cart
            if (!$isAjax) {
                $response = ['success' => false, 'message' => 'This action requires AJAX'];
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                $response = ['success' => false, 'message' => 'Plant ID required'];
                break;
            }
            
            $plantId = (int)$input['id'];
            $response = CartItemsHandler::removeFromCart($user['id'], $plantId);
            break;

        case 'update_cart_quantity':
            // AJAX only - update item quantity in cart
            if (!$isAjax) {
                $response = ['success' => false, 'message' => 'This action requires AJAX'];
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id']) || !isset($input['quantity'])) {
                $response = ['success' => false, 'message' => 'Plant ID and quantity required'];
                break;
            }
            
            $plantId = (int)$input['id'];
            $quantity = (int)$input['quantity'];
            
            if ($quantity <= 0) {
                $response = CartItemsHandler::removeFromCart($user['id'], $plantId);
            } else {
                $response = CartItemsHandler::updateCartItemQuantity($user['id'], $plantId, $quantity);
            }
            break;
            
        default:
            $response = ['success' => false, 'message' => 'Unknown action'];
            if (!$isAjax) {
                header('Location: ?error=unknown_action');
                exit;
            }
            break;
    }
    
    if ($isAjax) {
        echo json_encode($response);
    }
    exit;
}
// Page variables
$page_title = 'SolarSoil - Plant Shop';
$page_description = 'Discover exotic plants from across the galaxy.';
$body_class = 'shop-page';

// Get plant data from handler with error handling
try {
    $plants = PlantsHandler::getAllPlants();
    $plantCount = count($plants);
    $shopStatus = $plantCount > 0 ? 'success' : 'empty';
    
    if ($plantCount === 0) {
        error_log('[Shop Index] Database is empty - no plants found');
    }
} catch (Exception $e) {
    error_log('[Shop Index] Error getting plants: ' . $e->getMessage());
    $plants = [];
    $plantCount = 0;
    $shopStatus = 'error';
}

// Capture page content
ob_start();

// Display success/error messages from form submissions
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'item_added':
            // Success message will be handled by JavaScript on the frontend
            break;
    }
}

if (isset($_GET['error'])) {
    $errorMessage = 'An error occurred';
    switch ($_GET['error']) {
        case 'invalid_plant':
            $errorMessage = 'Invalid plant selected';
            break;
        case 'invalid_quantity':
            $errorMessage = 'Invalid quantity specified';
            break;
        case 'add_failed':
            $errorMessage = 'Failed to add item to cart. Please try again.';
            break;
        case 'unknown_action':
            $errorMessage = 'Unknown action requested';
            break;
    }
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ' . htmlEscape($errorMessage) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>

<!-- Shop Content -->
<div class="shop-container">
    <div class="container py-5">
        <!-- Shop Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="shop-header">
                    <h1 class="shop-title">
                        <i class="fas fa-store me-3"></i>
                        Cosmic Plant Marketplace
                    </h1>
                    <?php if ($shopStatus === 'success'): ?>
                        <p class="shop-subtitle text-muted">
                            <i class="fas fa-seedling me-2"></i>
                            Showing <?php echo $plantCount; ?> exotic plant<?php echo $plantCount !== 1 ? 's' : ''; ?> from our cosmic garden
                        </p>
                    <?php elseif ($shopStatus === 'empty'): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Our cosmic garden is currently empty. Please check back later for new arrivals!
                        </div>
                    <?php elseif ($shopStatus === 'error'): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            We're experiencing some technical difficulties. Please try again later.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Display plants -->
        <?php echo PlantDisplayUtil::renderPlantsGrid($plants); ?>
    </div>
</div>

<?php
$content = ob_get_clean();

// Include the single layout
include '../../layouts/page-layout.php';
?>