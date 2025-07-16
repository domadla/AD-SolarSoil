<?php
// Page variables
$page_title = 'SolarSoil - Shopping Cart';
$page_description = 'Review your selected cosmic plants and complete your galactic garden.';
$body_class = 'cart-page';


// Include necessary utilities for authentication
require_once '../../bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';

// Initialize session
Auth::init();
if (!Auth::check()) {
    header('Location: ../../index.php?error=LoginRequired');
    exit;
}

// Get user information for cart operations
$user = Auth::user();
if (!$user || !isset($user['id'])) {
    // Something is wrong with the user session, redirect to login
    header('Location: ../../index.php?error=SessionError');
    exit;
}
$userId = $user['id'];

// Handle AJAX requests for cart operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    require_once HANDLERS_PATH . '/cartItems.handler.php';

    header('Content-Type: application/json');

    $action = $_GET['action'];

    // Add debug action for testing
    if ($action === 'debug_test') {
        echo json_encode([
            'success' => true,
            'message' => 'Debug test successful',
            'user_id' => $userId,
            'session_data' => isset($_SESSION) ? 'Session active' : 'No session',
            'handlers_path' => HANDLERS_PATH,
            'cartitems_class' => class_exists('CartItemsHandler') ? 'Found' : 'Not found'
        ]);
        exit;
    }

    switch ($action) {
        case 'get_cart_items':
            try {
                $items = CartItemsHandler::getUserCartItems($userId);
                echo json_encode([
                    'success' => true,
                    'items' => $items,
                    'message' => 'Cart items retrieved successfully'
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to retrieve cart items: ' . $e->getMessage()
                ]);
            }
            exit;

        case 'update_quantity':
            $input = json_decode(file_get_contents('php://input'), true);
            $plantId = (int)($input['plant_id'] ?? 0);
            $quantity = (int)($input['quantity'] ?? 0);

            if (!$plantId || $quantity < 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
                exit;
            }

            try {
                if ($quantity === 0) {
                    $result = CartItemsHandler::removeFromCart($userId, $plantId);
                } else {
                    $result = CartItemsHandler::updateCartItemQuantity($userId, $plantId, $quantity);
                }
                echo json_encode($result);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to update cart: ' . $e->getMessage()]);
            }
            exit;

        case 'remove_item':
            $input = json_decode(file_get_contents('php://input'), true);
            $plantId = (int)($input['plant_id'] ?? 0);

            if (!$plantId) {
                echo json_encode(['success' => false, 'message' => 'Invalid plant ID']);
                exit;
            }

            try {
                $result = CartItemsHandler::removeFromCart($userId, $plantId);
                echo json_encode($result);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to remove item: ' . $e->getMessage()]);
            }
            exit;

        case 'create_order':
            try {
                // Include required handlers
                require_once HANDLERS_PATH . '/cartItems.handler.php';
                require_once HANDLERS_PATH . '/order.handler.php';

                // Debug: Log the user ID
                error_log("[Cart::create_order] Starting order creation for user_id: $userId");

                // Check if CartItemsHandler class exists
                if (!class_exists('CartItemsHandler')) {
                    error_log("[Cart::create_order] CartItemsHandler class not found");
                    echo json_encode(['success' => false, 'message' => 'Cart handler not available']);
                    exit;
                }

                // Check if OrderHandler class exists
                if (!class_exists('OrderHandler')) {
                    error_log("[Cart::create_order] OrderHandler class not found");
                    echo json_encode(['success' => false, 'message' => 'Order handler not available']);
                    exit;
                }

                // Get user's cart items
                error_log("[Cart::create_order] Getting cart items for user $userId");
                $cartItems = CartItemsHandler::getUserCartItems($userId);
                error_log("[Cart::create_order] Found " . count($cartItems) . " cart items");

                if (empty($cartItems)) {
                    error_log("[Cart::create_order] Cart is empty, silently returning");
                    // Don't show error notification for empty cart - just return quietly
                    echo json_encode(['success' => false, 'message' => '', 'silent' => true]);
                    exit;
                }

                // Create the order
                error_log("[Cart::create_order] Creating order from cart");
                $result = OrderHandler::createOrderFromCart($userId);
                error_log("[Cart::create_order] Order creation result: " . json_encode($result));

                if ($result['success']) {
                    // Return success with redirect URL for frontend
                    echo json_encode([
                        'success' => true,
                        'message' => 'Order created successfully',
                        'redirect_url' => '../Order/index.php',
                        'order_id' => $result['order_id'],
                        'debug_info' => [
                            'cart_items_count' => count($cartItems),
                            'user_id' => $userId,
                            'order_details' => $result
                        ]
                    ]);
                } else {
                    error_log("[Cart::create_order] Order creation failed: " . $result['message']);
                    echo json_encode($result);
                }
            } catch (PDOException $e) {
                error_log("[Cart::create_order] Database Exception: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            } catch (Exception $e) {
                error_log("[Cart::create_order] General Exception: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Failed to create order: ' . $e->getMessage()]);
            }
            exit;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
    }
}

// Capture page content
ob_start();
?>

<!-- Cart Content -->
<div class="cart-container">
    <div class="container py-5">
        <!-- Cart Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="cart-header">
                    <h1 class="cart-title">
                        <i class="fas fa-shopping-cart me-3"></i>
                        Your Cosmic Cart
                    </h1>
                </div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-items" id="cartItems">
                    <!-- Cart items will be loaded here by JavaScript -->
                    <div class="empty-cart text-center py-5">
                        <i class="fas fa-seedling fa-3x text-white mb-3"></i>
                        <h3>Your cart is empty</h3>
                        <p class="text-white">Add some cosmic plants from our shop to get started!</p>
                        <a href="../Shop/index.php" class="btn btn-primary">
                            <i class="fas fa-store me-2"></i>
                            Browse Plants
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="cartSubtotal">0 GC</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>5 GC</span>
                            </div>
                            <div class="summary-row total">
                                <strong>
                                    <span>Total:</span>
                                    <span id="cartTotal">5 GC</span>
                                </strong>
                            </div>
                            <button class="btn btn-success w-100 mt-3" id="checkoutBtn" disabled>
                                <i class="fas fa-rocket me-2"></i>
                                Launch Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Include the single layout
include '../../layouts/page-layout.php';
?>