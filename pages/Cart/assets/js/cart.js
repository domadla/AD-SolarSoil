// cart.js - Handles cart page logic, syncs with database backend

document.addEventListener("DOMContentLoaded", function () {
  const cartItemsContainer = document.getElementById("cartItems");
  const cartSubtotal = document.getElementById("cartSubtotal");
  const cartTotal = document.getElementById("cartTotal");
  const checkoutBtn = document.getElementById("checkoutBtn");

  // Cart data loaded from database
  let cartData = {
    items: [],
    subtotal: 0,
    total: 0,
    itemCount: 0,
  };

  function loadCartFromDatabase() {
    // Show loading state
    cartItemsContainer.innerHTML = `
      <div class="text-center py-5">
        <i class="fas fa-spinner fa-spin fa-2x text-white mb-3"></i>
        <p class="text-white">Loading your cart...</p>
      </div>
    `;

    console.log("Loading cart from database...");

    fetch("?action=get_cart_items", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        console.log("Cart response status:", response.status);
        return response.json();
      })
      .then((data) => {
        console.log("Cart data received:", data);
        if (data.success) {
          // Transform data to match expected format
          cartData = {
            items: data.items || [],
            subtotal: 0,
            total: 0,
            itemCount: 0,
          };

          // Calculate subtotal
          if (cartData.items.length > 0) {
            cartData.subtotal = cartData.items.reduce(
              (sum, item) => sum + item.price * item.quantity,
              0
            );
            cartData.total = cartData.subtotal + 5; // Add shipping
            cartData.itemCount = cartData.items.length;
          } else {
            cartData.total = 5; // Just shipping cost
          }

          console.log("Processed cart data:", cartData);
          renderCart();
          updateCartBadge(); // Update the global cart badge
        } else {
          console.error("Error loading cart:", data.message);
          showErrorMessage(data.message || "Failed to load cart");
        }
      })
      .catch((error) => {
        console.error("Error fetching cart:", error);
        showErrorMessage("Failed to load cart. Please refresh the page.");
      });
  }

  function renderCart() {
    cartItemsContainer.innerHTML = "";

    if (!cartData.items || cartData.items.length === 0) {
      cartItemsContainer.innerHTML = `
        <div class="empty-cart text-center py-5">
          <i class="fas fa-seedling fa-3x text-white mb-3"></i>
          <h3>Your cart is empty</h3>
          <p class="text-white">Add some cosmic plants from our shop to get started!</p>
          <a href="../Shop/index.php" class="btn btn-primary">
            <i class="fas fa-store me-2"></i>
            Browse Plants
          </a>
        </div>
      `;
      cartSubtotal.textContent = "0 GC";
      cartTotal.textContent = "5 GC"; // Just shipping cost
      checkoutBtn.disabled = true;
      return;
    }

    cartData.items.forEach((item) => {
      // Images are stored in Shop folder, so navigate from Cart to Shop assets
      // Database stores 'assets/img/plants/NebulaBloom.png' but files are actually in pages/Shop/assets/img/plants/
      let imagePath;
      if (item.image_url && item.image_url.includes("assets/img/plants/")) {
        // Extract just the filename and build the correct path to Shop assets
        const filename = item.image_url.split("/").pop();
        imagePath = `../Shop/assets/img/plants/${filename}`;
      } else {
        imagePath = "../Shop/assets/img/plants/default.jpg";
      }

      cartItemsContainer.innerHTML += `
        <div class="cart-item d-flex align-items-center mb-4" style="background: none; border: none;">
          <img src="${imagePath}" alt="${item.name}" class="cart-item-img me-4" style="width:140px;height:140px;object-fit:cover;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.18);background:#181c2a;">
          <div class="flex-grow-1 d-flex flex-column justify-content-center">
            <div class="cart-item-name fw-bold text-white mb-1" style="font-size:1.5rem;">${item.name}</div>
            <div class="cart-item-price text-success mb-3" style="font-size:1.15rem;">${item.price} GC</div>
          </div>
          <div class="d-flex align-items-center ms-auto gap-2" style="min-width:220px;">
            <button class="cart-qty-btn quantity-btn me-1" data-action="decrement" data-id="${item.plant_id}">-</button>
            <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-id="${item.plant_id}" style="width:48px;">
            <button class="cart-qty-btn quantity-btn ms-1" data-action="increment" data-id="${item.plant_id}">+</button>
            <button class="cart-item-remove ms-3" data-id="${item.plant_id}" title="Remove"><i class="fas fa-trash"></i></button>
          </div>
        </div>
      `;
    });

    cartSubtotal.textContent = cartData.subtotal + " GC";
    cartTotal.textContent = cartData.total + " GC";
    checkoutBtn.disabled = cartData.items.length === 0;
  }
  // Event delegation for cart actions
  cartItemsContainer.addEventListener("click", function (e) {
    const btn = e.target.closest("button");
    if (!btn) return;

    const plantId = btn.getAttribute("data-id");

    if (btn.classList.contains("cart-qty-btn")) {
      const action = btn.getAttribute("data-action");
      const currentItem = cartData.items.find(
        (item) => item.plant_id == plantId
      );

      if (!currentItem) return;

      let newQuantity = currentItem.quantity;

      if (action === "increment") {
        newQuantity++;
        updateCartItemQuantity(plantId, newQuantity);
      } else if (action === "decrement") {
        newQuantity--;
        if (newQuantity <= 0) {
          removeCartItem(plantId);
        } else {
          updateCartItemQuantity(plantId, newQuantity);
        }
      }
    } else if (btn.classList.contains("cart-item-remove")) {
      const itemName = btn
        .closest(".cart-item")
        .querySelector(".cart-item-name").textContent;
      removeCartItem(plantId);
      showCartNotification(`${itemName} removed from cart!`, "danger");
    }
  });

  // Event for direct quantity input
  cartItemsContainer.addEventListener("change", function (e) {
    if (e.target.classList.contains("quantity-input")) {
      const plantId = e.target.getAttribute("data-id");
      let value = parseInt(e.target.value, 10);

      if (isNaN(value) || value < 1) {
        value = 1;
        e.target.value = value;
      }

      updateCartItemQuantity(plantId, value);
    }
  });

  function updateCartItemQuantity(plantId, quantity) {
    fetch("?action=update_quantity", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({
        plant_id: parseInt(plantId),
        quantity: quantity,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          loadCartFromDatabase(); // Reload cart from database
          updateCartBadge(); // Update global cart badge
        } else {
          console.error("Error updating cart:", data.message);
          showErrorMessage(data.message);
        }
      })
      .catch((error) => {
        console.error("Error updating cart:", error);
        showErrorMessage("Failed to update cart item.");
      });
  }

  function removeCartItem(plantId) {
    fetch("?action=remove_item", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({
        plant_id: parseInt(plantId),
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          loadCartFromDatabase(); // Reload cart from database
          updateCartBadge(); // Update global cart badge
        } else {
          console.error("Error removing from cart:", data.message);
          showErrorMessage(data.message);
        }
      })
      .catch((error) => {
        console.error("Error removing from cart:", error);
        showErrorMessage("Failed to remove cart item.");
      });
  }

  function updateCartBadge() {
    if (cartData && cartData.items) {
      // Count number of distinct items (not total quantity)
      const cartCount = cartData.items.length;
      const badge = document.getElementById("cart-badge");
      if (badge) badge.textContent = cartCount;
      // Also update old badge if present
      const cartCountElement = document.getElementById("cart-count");
      if (cartCountElement) cartCountElement.textContent = cartCount;
    }
  }

  function showErrorMessage(message) {
    cartItemsContainer.innerHTML = `
      <div class="text-center py-5">
        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
        <h4 class="text-white">Error</h4>
        <p class="text-white">${message}</p>
        <button class="btn btn-primary" onclick="location.reload()">
          <i class="fas fa-refresh me-2"></i>
          Retry
        </button>
      </div>
    `;
  }

  // Load cart data from database on page load
  loadCartFromDatabase();

  // Checkout/Launch Order button logic
  checkoutBtn.addEventListener("click", function () {
    if (!cartData.items || cartData.items.length === 0) return;
    
    // Disable button and show loading state
    checkoutBtn.disabled = true;
    const originalText = checkoutBtn.innerHTML;
    checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Order...';
    
    // Send order creation request
    fetch('?action=create_order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Show success message
        showOrderSuccessMessage(data);
        
        // Direct redirect to order page without passing data
        setTimeout(() => {
          window.location.href = '../Order/index.php';
        }, 2000);
      } else {
        console.error('Order creation failed:', data.message);
        showOrderErrorMessage(data.message);
        
        // Re-enable button
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = originalText;
      }
    })
    .catch(error => {
      console.error('Error creating order:', error);
      showOrderErrorMessage('Failed to create order. Please try again.');
      
      // Re-enable button
      checkoutBtn.disabled = false;
      checkoutBtn.innerHTML = originalText;
    });
  });
  
  // Show order success message
  function showOrderSuccessMessage(orderData) {
    const toast = document.createElement("div");
    toast.className = "order-success-notification";
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fas fa-check-circle"></i>
        <div class="order-details">
          <h5>Order Created Successfully! 🚀</h5>
          <p>Order #${orderData.order_id}</p>
          <p>${orderData.items_count} items • Total: ${orderData.total} GC</p>
          <p><small>Redirecting to order page...</small></p>
        </div>
      </div>
    `;
    toast.style.cssText = `
      position: fixed;
      top: 100px;
      right: 20px;
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      z-index: 9999;
      animation: slideInRight 0.3s ease;
      max-width: 350px;
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.style.animation = "slideOutRight 0.3s ease";
      setTimeout(() => {
        if (document.body.contains(toast)) {
          document.body.removeChild(toast);
        }
      }, 300);
    }, 5000);
  }
  
  // Show order error message
  function showOrderErrorMessage(message) {
    const toast = document.createElement("div");
    toast.className = "order-error-notification";
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fas fa-exclamation-triangle"></i>
        <div class="error-details">
          <h5>Order Failed</h5>
          <p>${message}</p>
        </div>
      </div>
    `;
    toast.style.cssText = `
      position: fixed;
      top: 100px;
      right: 20px;
      background: linear-gradient(135deg, #dc3545, #b52a37);
      color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      z-index: 9999;
      animation: slideInRight 0.3s ease;
      max-width: 350px;
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.style.animation = "slideOutRight 0.3s ease";
      setTimeout(() => {
        if (document.body.contains(toast)) {
          document.body.removeChild(toast);
        }
      }, 300);
    }, 4000);
  }

  // Add additional styles for order notifications
  if (!document.getElementById("order-notification-style")) {
    const orderStyle = document.createElement("style");
    orderStyle.id = "order-notification-style";
    orderStyle.textContent = `
      .order-success-notification .toast-content,
      .order-error-notification .toast-content {
        display: flex;
        align-items: flex-start;
        gap: 15px;
      }
      .order-success-notification .toast-content i,
      .order-error-notification .toast-content i {
        font-size: 1.5rem;
        margin-top: 5px;
      }
      .order-details h5,
      .error-details h5 {
        margin: 0 0 8px 0;
        font-size: 1.1rem;
      }
      .order-details p,
      .error-details p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.9;
      }
    `;
    document.head.appendChild(orderStyle);
  }

  // Notification for delete
  function showCartNotification(message, type = "success") {
    // Remove any existing notification
    const existingToast = document.querySelector(".toast-notification");
    if (existingToast) existingToast.remove();

    const toast = document.createElement("div");
    toast.className = "toast-notification";
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fas fa-trash-alt"></i>
        <span>${message}</span>
      </div>
    `;
    toast.style.cssText = `
      position: fixed;
      top: 100px;
      right: 20px;
      background: linear-gradient(135deg, #dc3545, #b52a37);
      color: white;
      padding: 15px 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      z-index: 9999;
      animation: slideInRight 0.3s ease;
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = "slideOutRight 0.3s ease";
      setTimeout(() => {
        if (document.body.contains(toast)) {
          document.body.removeChild(toast);
        }
      }, 300);
    }, 2000);
  }
});
