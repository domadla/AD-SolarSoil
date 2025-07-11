// cart.js - Handles cart page logic, syncs with localStorage

document.addEventListener("DOMContentLoaded", function () {
  const cartItemsContainer = document.getElementById("cartItems");
  const cartSubtotal = document.getElementById("cartSubtotal");
  const cartTotal = document.getElementById("cartTotal");
  const checkoutBtn = document.getElementById("checkoutBtn");

  function renderCart() {
    const cart = window.CartUtils.getCart();
    cartItemsContainer.innerHTML = "";
    if (!cart.length) {
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
      cartTotal.textContent = "5 GC";
      checkoutBtn.disabled = true;
      return;
    }
    let subtotal = 0;
    cart.forEach((item) => {
      subtotal += item.price * item.quantity;
      cartItemsContainer.innerHTML += `
        <div class="cart-item d-flex align-items-center mb-4" style="background: none; border: none;">
          <img src="../Shop/assets/img/plants/${item.image}" alt="${item.name}" class="cart-item-img me-4" style="width:140px;height:140px;object-fit:cover;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.18);background:#181c2a;">
          <div class="flex-grow-1 d-flex flex-column justify-content-center">
            <div class="cart-item-name fw-bold text-white mb-1" style="font-size:1.5rem;">${item.name}</div>
            <div class="cart-item-price text-success mb-3" style="font-size:1.15rem;">${item.price} GC</div>
          </div>
          <div class="d-flex align-items-center ms-auto gap-2" style="min-width:220px;">
            <button class="cart-qty-btn quantity-btn me-1" data-action="decrement" data-id="${item.id}">-</button>
            <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-id="${item.id}" style="width:48px;">
            <button class="cart-qty-btn quantity-btn ms-1" data-action="increment" data-id="${item.id}">+</button>
            <button class="cart-item-remove ms-3" data-id="${item.id}" title="Remove"><i class="fas fa-trash"></i></button>
          </div>
        </div>
      `;
    });
    cartSubtotal.textContent = subtotal + " GC";
    cartTotal.textContent = subtotal + 5 + " GC";
    checkoutBtn.disabled = false;
  }
  // Event delegation for cart actions
  cartItemsContainer.addEventListener("click", function (e) {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = btn.getAttribute("data-id");
    if (btn.classList.contains("cart-qty-btn")) {
      const action = btn.getAttribute("data-action");
      if (action === "increment") {
        window.CartUtils.incrementQuantity(id);
      } else if (action === "decrement") {
        const cartBefore = window.CartUtils.getCart();
        window.CartUtils.decrementQuantity(id);
        const cartAfter = window.CartUtils.getCart();
        const itemStillExists = cartAfter.some(item => item.id === parseInt(id));
        renderCart();
        if (!itemStillExists) {
          showCartNotification("Item removed from cart!", "danger");
        }
      }
      renderCart();
    } else if (btn.classList.contains("cart-item-remove")) {
      const itemName = btn.closest('.cart-item').querySelector('.cart-item-name').textContent;
      window.CartUtils.removeFromCart(id);
      renderCart();
      showCartNotification(`${itemName} removed from cart!`, 'danger');
    }
  });
  // Event for direct quantity input
  cartItemsContainer.addEventListener("change", function (e) {
    if (e.target.classList.contains("quantity-input")) {
      const id = e.target.getAttribute("data-id");
      let value = parseInt(e.target.value, 10);
      if (isNaN(value) || value < 1) value = 1;
      window.CartUtils.setQuantity(id, value);
      renderCart();
    }
  });
  // Initial render
  renderCart();
  window.CartUtils.updateCartBadge();

  // Notification for delete
  function showCartNotification(message, type = 'success') {
    // Remove any existing notification
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
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
        document.body.removeChild(toast);
      }, 300);
    }, 2000);
  }
});
