<?php
// Include handlers
require_once HANDLERS_PATH . '/order.handler.php';
require_once HANDLERS_PATH . '/user.handler.php';

// Start session for user authentication
session_start();

// Handle order creation from cart
$order = null;
$order_created = false;

if (isset($_POST['create_order']) && isset($_POST['cart_data'])) {
    // Decode cart data from POST
    $cart_items = json_decode($_POST['cart_data'], true);

    if ($cart_items && count($cart_items) > 0) {
        // Get user data
        $user_data = UserHandler::getCurrentUser();

        // Create order
        $order = OrderHandler::createOrder($cart_items, $user_data);
        $order_created = true;
    }
} elseif (isset($_GET['order_id'])) {
    // Load existing order
    $order = OrderHandler::getOrderById($_GET['order_id']);
}

// If no order found and no order created, check session for last order
if (!$order && !$order_created) {
    if (isset($_SESSION['last_order'])) {
        $order = $_SESSION['last_order'];
    } else {
        // Redirect to cart if no order data
        header('Location: ../Cart/index.php');
        exit;
    }
}

// Set page variables
$page_title = 'SolarSoil - Order Confirmation';
$page_description = 'Your cosmic plant order has been confirmed.';
$body_class = 'order-page';

// Capture page content for layout
ob_start();
?>

<!-- Order Content -->
<div class="order-container">
    <div class="container py-5">
        <!-- Order Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="order-header">
                    <?php if ($order_created): ?>
                        <div class="success-icon" style="color: #28a745;"> <!-- Bootstrap green -->
                            <i class="fas fa-check-circle fa-4x"></i>
                        </div>
                        <h1 class="order-title">
                            Order Confirmed!
                        </h1>
                        <p class="order-subtitle">
                            Thank you for your purchase! Your cosmic plants are being prepared for interstellar delivery.
                        </p>
                    <?php else: ?>
                        <h1 class="order-title">
                            <i class="fas fa-receipt me-3"></i>
                            Order Details
                        </h1>
                        <p class="order-subtitle">
                            Review your order information and tracking details.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Information -->
            <div class="col-lg-8">
                <!-- Order Summary Card -->
                <div class="order-card">
                    <div class="order-card-header">
                        <h3><i class="fas fa-clipboard-list me-2"></i>Order Summary</h3>
                        <div class="order-status">
                            <?php
                            $status_info = OrderHandler::getStatusInfo($order['status']);
                            ?>
                            <span class="status-badge status-<?php echo $status_info['class']; ?>"
                                <?php if ($order['status'] === 'confirmed'): ?>
                                    style="background-color: #28a745; color: #fff;"
                                <?php endif; ?>
                            >
                                <i class="<?php echo $status_info['icon']; ?> me-2"></i>
                                <?php echo $status_info['label']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-card-body">
                        <div class="order-info-grid">
                            <div class="order-info-item">
                                <label>Order ID</label>
                                <span><?php echo htmlspecialchars($order['id']); ?></span>
                            </div>
                            <div class="order-info-item">
                                <label>Order Date</label>
                                <span><?php echo date('F j, Y \a\t g:i A', strtotime($order['date'])); ?></span>
                            </div>
                            <div class="order-info-item">
                                <label>Estimated Delivery</label>
                                <span><?php echo date('F j, Y', strtotime($order['estimated_delivery'])); ?></span>
                            </div>
                            <div class="order-info-item">
                                <label>Tracking Number</label>
                                <span
                                    class="tracking-number"><?php echo htmlspecialchars($order['tracking_number']); ?></span>
                            </div>
                            <div class="order-info-item">
                                <label>Shipping Method</label>
                                <span><?php echo htmlspecialchars($order['shipping_method']); ?></span>
                            </div>
                            <div class="order-info-item">
                                <label>Payment Method</label>
                                <span><?php echo htmlspecialchars($order['payment_method']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-card">
                    <div class="order-card-header">
                        <h3><i class="fas fa-seedling me-2"></i>Ordered Items</h3>
                    </div>

                    <div class="order-card-body">
                        <div class="order-items">
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="order-item">
                                    <?php
                                    $imagePath = $item['image'];
                                    $basename = basename($imagePath);
                                    $imagePath = '../../pages/Shop/assets/img/plants/' . $basename;
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                                    <div class="item-details">
                                        <h4 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <p class="item-price"><?php echo number_format($item['price'], 0); ?> GC each</p>
                                        <p class="item-quantity">Quantity: <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="item-total">
                                        <span class="total-amount">
                                            <?php echo number_format($item['price'] * $item['quantity'], 0); ?> GC
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Totals & Actions -->
            <div class="col-lg-4">
                <!-- Order Totals -->
                <div class="order-card">
                    <div class="order-card-header">
                        <h3><i class="fas fa-calculator me-2"></i>Order Total</h3>
                    </div>

                    <div class="order-card-body">
                        <div class="totals-breakdown">
                            <div class="total-line">
                                <span>Subtotal</span>
                                <span><?php echo number_format($order['subtotal'], 0); ?> GC</span>
                            </div>
                            <div class="total-line">
                                <span>Shipping</span>
                                <span>
                                    <?php echo $order['shipping'] == 0 ? 'FREE' : number_format($order['shipping'], 0) . ' GC'; ?>
                                </span>
                            </div>
                            <div class="total-line">
                                <span>Galactic Tax</span>
                                <span><?php echo number_format($order['tax'], 0); ?> GC</span>
                            </div>
                            <hr>
                            <div class="total-line total-final">
                                <span><strong>Total</strong></span>
                                <span><strong><?php echo number_format($order['total'], 0); ?> GC</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="order-card">
                    <div class="order-card-header">
                        <h3><i class="fas fa-tools me-2"></i>Actions</h3>
                    </div>

                    <div class="order-card-body">
                        <div class="action-buttons">
                            <a href="../Shop/index.php" class="btn btn-success btn-block">
                                <i class="fas fa-store me-2"></i>Continue Shopping
                            </a>
                            <a href="../Home/index.php" class="btn btn-outline-success btn-block">
                                <i class="fas fa-home me-2"></i>Return to Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="order-card">
                    <div class="order-card-header">
                        <h3><i class="fas fa-history me-2"></i>Order Status</h3>
                    </div>

                    <div class="order-card-body">
                        <div class="status-timeline">
                            <div
                                class="timeline-item <?php echo $order['status'] == 'confirmed' ? 'active' : 'completed'; ?>">
                                <div class="timeline-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Order Confirmed</h5>
                                    <p><?php echo date('M j, Y g:i A', strtotime($order['date'])); ?></p>
                                </div>
                            </div>

                            <div
                                class="timeline-item <?php echo in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'active' : ''; ?>">
                                <div class="timeline-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Processing</h5>
                                    <p>Plants being prepared</p>
                                </div>
                            </div>

                            <div
                                class="timeline-item <?php echo in_array($order['status'], ['shipped', 'in_transit', 'delivered']) ? 'active' : ''; ?>">
                                <div class="timeline-icon">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Shipped</h5>
                                    <p>En route to destination</p>
                                </div>
                            </div>

                            <div class="timeline-item <?php echo $order['status'] == 'delivered' ? 'active' : ''; ?>">
                                <div class="timeline-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Delivered</h5>
                                    <p>Order completed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page-specific JavaScript -->
<script src="assets/js/order.js"></script>

<?php
$content = ob_get_clean();
// Use the shared page layout
include '../../layouts/page-layout.php';
?>