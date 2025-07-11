<?php
// Page variables
$page_title = 'SolarSoil - Shopping Cart';
$page_description = 'Review your selected cosmic plants and complete your galactic garden.';
$body_class = 'cart-page';

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