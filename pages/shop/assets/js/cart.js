// Shopping Cart Functionality
let cart = JSON.parse(localStorage.getItem("solarsoil_cart")) || [];

// Add item to cart
function addToCart(id, name, price, image) {
  const existingItem = cart.find((item) => item.id === id);

  if (existingItem) {
    existingItem.quantity += 1;
    showNotification(`Increased ${name} quantity to ${existingItem.quantity}`);
  } else {
    cart.push({
      id: id,
      name: name,
      price: price,
      image: image,
      quantity: 1,
    });
    showNotification(`${name} added to cart!`);
  }

  updateCart();
  saveCart();
}

// Remove item from cart
function removeFromCart(id) {
  const itemIndex = cart.findIndex((item) => item.id === id);
  if (itemIndex > -1) {
    const itemName = cart[itemIndex].name;
    cart.splice(itemIndex, 1);
    showNotification(`${itemName} removed from cart`);
    updateCart();
    saveCart();
  }
}

// Update item quantity
function updateQuantity(id, quantity) {
  const item = cart.find((item) => item.id === id);
  if (item) {
    if (quantity <= 0) {
      removeFromCart(id);
    } else {
      item.quantity = quantity;
      updateCart();
      saveCart();
    }
  }
}

// Toggle cart sidebar
function toggleCart() {
  const cartSidebar = document.getElementById("cartSidebar");
  const cartOverlay = document.getElementById("cartOverlay");

  if (cartSidebar && cartOverlay) {
    cartSidebar.classList.toggle("active");
    cartOverlay.classList.toggle("active");
    document.body.classList.toggle("cart-open");
  }
}

// Update cart display
function updateCart() {
  const cartCount = document.getElementById("cartCount");
  const cartItems = document.getElementById("cartItems");
  const cartTotal = document.getElementById("cartTotal");
  const checkoutBtn = document.getElementById("checkoutBtn");

  // Update cart count
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  if (cartCount) {
    cartCount.textContent = totalItems;
    cartCount.style.display = totalItems > 0 ? "block" : "none";
  }

  // Update cart items
  if (cartItems) {
    if (cart.length === 0) {
      cartItems.innerHTML =
        '<p class="empty-cart-message">Your cart is empty</p>';
    } else {
      cartItems.innerHTML = cart
        .map(
          (item) => `
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="${item.image}" alt="${
            item.name
          }" onerror="this.style.display='none'">
                    </div>
                    <div class="cart-item-details">
                        <h4 class="cart-item-name">${item.name}</h4>
                        <p class="cart-item-price">${item.price} GC</p>
                        <div class="cart-item-controls">
                            <button onclick="updateQuantity(${item.id}, ${
            item.quantity - 1
          })" class="quantity-btn">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, ${
            item.quantity + 1
          })" class="quantity-btn">+</button>
                        </div>
                    </div>
                    <button onclick="removeFromCart(${
                      item.id
                    })" class="remove-item-btn" aria-label="Remove ${
            item.name
          }">×</button>
                </div>
            `
        )
        .join("");
    }
  }

  // Update total
  const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  if (cartTotal) {
    cartTotal.textContent = total;
  }

  // Update checkout button
  if (checkoutBtn) {
    checkoutBtn.disabled = cart.length === 0;
  }
}

// Save cart to localStorage
function saveCart() {
  localStorage.setItem("solarsoil_cart", JSON.stringify(cart));
}

// Show notification
function showNotification(message) {
  // Create notification element
  const notification = document.createElement("div");
  notification.className = "cart-notification";
  notification.textContent = message;

  // Add to page
  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => {
    notification.classList.add("show");
  }, 100);

  // Remove notification after 3 seconds
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}

// Checkout function
function checkout() {
  if (cart.length === 0) {
    showNotification("Your cart is empty!");
    return;
  }

  // For now, just show a message - you can implement actual checkout later
  showNotification("Checkout functionality coming soon!");
  console.log("Checkout cart:", cart);
}

// Initialize cart on page load
document.addEventListener("DOMContentLoaded", function () {
  updateCart();

  // Close cart when clicking outside
  document.addEventListener("click", function (e) {
    const cartSidebar = document.getElementById("cartSidebar");
    const cartToggle = document.querySelector(".cart-toggle");

    if (
      cartSidebar &&
      cartSidebar.classList.contains("active") &&
      !cartSidebar.contains(e.target) &&
      !cartToggle.contains(e.target)
    ) {
      toggleCart();
    }
  });
});
