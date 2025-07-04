<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="/assets/img/Logo.png">
    <title>SolarSoil - Interstellar Plant Store</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header class="header" role="banner">
        <div class="container nav-container">
            <a href="/pages/home/index.php" class="logo" id="home">
                SolarSoil
            </a>
            <nav class="nav" id="nav-menu" aria-label="Primary Navigation">
                <ul class="nav-list">
                    <li><a href="/pages/home/index.php" class="nav-link">Home</a></li>
                    <li><a href="/pages/shop/index.php" class="nav-link">Shop</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                </ul>
            </nav>
            <?php
            require_once UTILS_PATH . 'session.util.php';

            if (isUserLoggedIn()): ?>
                <div class="header-actions">
                    <button class="cart-toggle" onclick="toggleCart()" aria-label="Toggle shopping cart">
                        <span class="cart-icon">🛒</span>
                        <span class="cart-count" id="cartCount">0</span>
                    </button>
                    <div class="user-dropdown">
                        <a href="#" class="user-link" onclick="toggleUserDropdown()">
                            <span class="user-icon">&#128100;</span>
                            <?php echo htmlspecialchars(getUserFirstName()); ?>
                            <span class="dropdown-arrow">&#9662;</span>
                        </a>
                        <div class="user-dropdown-menu" id="userDropdown">
                            <a href="/auth/profile.php" class="dropdown-item">Profile</a>
                            <a href="/auth/logout.php" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="/index.php" class="user-link">
                    <span class="user-icon">&#128100;</span>
                    Login
                </a>
            <?php endif; ?>


            </nav>
            <button id="nav-toggle" class="nav-toggle" aria-controls="nav-menu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="hamburger"></span>
            </button>
        </div>
    </header>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3>Shopping Cart</h3>
            <button class="cart-close" onclick="toggleCart()" aria-label="Close cart">×</button>
        </div>
        <div class="cart-content">
            <div class="cart-items" id="cartItems">
                <p class="empty-cart-message">Your cart is empty</p>
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <strong>Total: <span id="cartTotal">0</span> GC</strong>
                </div>
                <button class="checkout-btn" onclick="checkout()" disabled id="checkoutBtn">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    </div>
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

    <main></main>