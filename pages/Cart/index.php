<?php
// Include handlers and utilities
require_once HANDLERS_PATH . '/cart.handler.php';
require_once HANDLERS_PATH . '/plants.handler.php';

// Page variables
$page_title = 'SolarSoil - Shopping Cart';
$page_description = 'Review your selected plants and proceed to checkout.';
$body_class = 'cart-page';

// Capture the page content
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
                    <p class="cart-subtitle">
                        Review your selected plants and prepare for interstellar delivery.
                    </p>
                </div>
            </div>
        </div>

        <!-- Cart Content -->
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items-section">
                    <div id="cart-items">
                        <!-- Cart items will be loaded here via JavaScript -->
                    </div>

                    <!-- Empty Cart Message -->
                    <div id="empty-cart" class="empty-cart-message" style="display: none;">
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x mb-4 text-muted"></i>
                            <h3>Your cart is empty</h3>
                            <p class="text-muted mb-4">Looks like you haven't added any plants to your cart yet.</p>
                            <a href="../Shop/index.php" class="btn btn-primary">
                                <i class="fas fa-store me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary" id="cart-summary" style="display: none;">
                    <h3>Order Summary</h3>
                    <div class="summary-line">
                        <span>Subtotal:</span>
                        <span id="subtotal">0 GC</span>
                    </div>
                    <div class="summary-line">
                        <span>Shipping:</span>
                        <span id="shipping">15 GC</span>
                    </div>
                    <div class="summary-line">
                        <span>Galactic Tax:</span>
                        <span id="tax">0 GC</span>
                    </div>
                    <hr>
                    <div class="summary-line total">
                        <span>Total:</span>
                        <span id="total">0 GC</span>
                    </div>

                    <div class="checkout-section">
                        <button class="btn btn-primary btn-lg w-100 mb-3" id="checkout-btn">
                            <i class="fas fa-rocket me-2"></i>Proceed to Checkout
                        </button>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="continue-shopping">
                    <a href="../Shop/index.php" class="btn btn-outline-light w-100">
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Include the layout
include '../../layouts/page-layout.php';
?>