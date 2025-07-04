<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="/assets/img/Logo.png">
    <title>SolarSoil - Interstellar Plant Store</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script>
        function goToShop() {
            window.location.href = '/pages/shop/index.php';
        }

        // Initialize cart count on all pages
        document.addEventListener('DOMContentLoaded', function () {
            updateCartCountDisplay();
        });

        function updateCartCountDisplay() {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                const cart = JSON.parse(localStorage.getItem('solarsoil_cart')) || [];
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = totalItems;
                cartCount.style.display = totalItems > 0 ? 'block' : 'none';
            }
        }

        // Basic cart functions for all pages
        function toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            const cartOverlay = document.getElementById('cartOverlay');

            if (cartSidebar && cartOverlay) {
                cartSidebar.classList.toggle('active');
                cartOverlay.classList.toggle('active');
                document.body.classList.toggle('cart-open');
                updateCartDisplay();
            }
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            const cart = JSON.parse(localStorage.getItem('solarsoil_cart')) || [];

            // Update cart items
            if (cartItems) {
                if (cart.length === 0) {
                    cartItems.innerHTML = '<p class="empty-cart-message">Your cart is empty</p>';
                } else {
                    cartItems.innerHTML = cart.map(item => `
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="${item.image}" alt="${item.name}" onerror="this.style.display='none'">
                            </div>
                            <div class="cart-item-details">
                                <h4 class="cart-item-name">${item.name}</h4>
                                <p class="cart-item-price">${item.price} GC</p>
                                <div class="cart-item-controls">
                                    <button onclick="event.stopPropagation(); updateQuantity(${item.id}, ${item.quantity - 1}); return false;" class="quantity-btn">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button onclick="event.stopPropagation(); updateQuantity(${item.id}, ${item.quantity + 1}); return false;" class="quantity-btn">+</button>
                                </div>
                            </div>
                            <button onclick="event.stopPropagation(); removeFromCart(${item.id}); return false;" class="remove-item-btn" aria-label="Remove ${item.name}">×</button>
                        </div>
                    `).join('');
                }
            }

            // Update total
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            if (cartTotal) {
                cartTotal.textContent = total;
            }

            // Update cart count badge
            updateCartCountDisplay();
        }

        // Remove item from cart
        function removeFromCart(id) {
            let cart = JSON.parse(localStorage.getItem('solarsoil_cart')) || [];
            const itemIndex = cart.findIndex(item => item.id === id);
            if (itemIndex > -1) {
                const itemName = cart[itemIndex].name;
                cart.splice(itemIndex, 1);
                localStorage.setItem('solarsoil_cart', JSON.stringify(cart));

                // Update displays without closing sidebar
                updateCartDisplay();
                showNotification(`${itemName} removed from cart`);

                // Prevent event bubbling that might close sidebar
                event.stopPropagation();
            }
            return false; // Prevent default behavior
        }

        // Update item quantity
        function updateQuantity(id, quantity) {
            let cart = JSON.parse(localStorage.getItem('solarsoil_cart')) || [];
            const item = cart.find(item => item.id === id);
            if (item) {
                if (quantity <= 0) {
                    removeFromCart(id);
                } else {
                    item.quantity = quantity;
                    localStorage.setItem('solarsoil_cart', JSON.stringify(cart));
                    updateCartDisplay();
                }

                // Prevent event bubbling
                if (typeof event !== 'undefined') {
                    event.stopPropagation();
                }
            }
            return false; // Prevent default behavior
        }

        // Show notification
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'cart-notification';
            notification.textContent = message;

            // Add to page
            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
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

            // Check if we're on the shop page
            $currentPage = $_SERVER['REQUEST_URI'];
            $isShopPage = strpos($currentPage, '/shop') !== false;

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

    <!-- Cart Sidebar - Available on all pages -->
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
                <?php if (!$isShopPage): ?>
                    <button class="go-to-shop-btn" onclick="goToShop()"
                        style="width: 100%; background: linear-gradient(135deg, var(--color-primary), #ffff4d); color: #000; border: none; padding: 1rem; border-radius: var(--border-radius); font-weight: 600; font-size: 1.1rem; cursor: pointer; margin-bottom: 1rem;">
                        Go to Shop
                    </button>
                <?php endif; ?>
                <div class="cart-total">
                    <strong>Total: <span id="cartTotal">0</span> GC</strong>
                </div>
                <?php if ($isShopPage): ?>
                    <button class="checkout-btn" onclick="checkout()" disabled id="checkoutBtn">
                        Proceed to Checkout
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

    <main></main>