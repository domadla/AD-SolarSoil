// Cart Page JavaScript
document.addEventListener("DOMContentLoaded", function () {
  let cart = JSON.parse(localStorage.getItem("solarsoil_cart")) || [];

  // DOM elements
  const cartItemsContainer = document.getElementById("cart-items");
  const emptyCartMessage = document.getElementById("empty-cart");
  const cartSummary = document.getElementById("cart-summary");
  const checkoutBtn = document.getElementById("checkout-btn");

  // Load cart on page load
  loadCart();
  updateCartCount();

  // Checkout button event
  checkoutBtn.addEventListener("click", function () {
    if (cart.length > 0) {
      showOrderPreviewModal();
    }
  });

  function loadCart() {
    if (cart.length === 0) {
      showEmptyCart();
      return;
    }

    showCartContent();
    renderCartItems();
    updateSummary();
  }

  function showEmptyCart() {
    cartItemsContainer.innerHTML = "";
    emptyCartMessage.style.display = "block";
    cartSummary.style.display = "none";
  }

  function showCartContent() {
    emptyCartMessage.style.display = "none";
    cartSummary.style.display = "block";
  }

  function renderCartItems() {
    cartItemsContainer.innerHTML = "";

    cart.forEach((item, index) => {
      const cartItemHTML = `
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="${item.image}" alt="${
        item.name
      }" class="item-image">
                        </div>
                        <div class="col-md-4">
                            <div class="item-details">
                                <h4 class="item-name">${item.name}</h4>
                                <p class="item-price">${item.price.toFixed(
                                  0
                                )} GC each</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${index}, ${
        item.quantity - 1
      })">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="quantity-input" value="${
                                  item.quantity
                                }" 
                                       onchange="updateQuantity(${index}, this.value)" min="1">
                                <button class="quantity-btn" onclick="updateQuantity(${index}, ${
        item.quantity + 1
      })">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-end">
                                <div class="item-total">${(
                                  item.price * item.quantity
                                ).toFixed(0)} GC</div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="remove-item" onclick="removeItem(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
      cartItemsContainer.innerHTML += cartItemHTML;
    });
  }

  function updateSummary() {
    const subtotal = cart.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
    const shipping = subtotal > 200 ? 0 : 15;
    const tax = subtotal * 0.08; // 8% galactic tax
    const total = subtotal + shipping + tax;

    document.getElementById("subtotal").textContent = `${subtotal.toFixed(
      0
    )} GC`;
    document.getElementById("shipping").textContent =
      shipping === 0 ? "FREE" : `${shipping.toFixed(0)} GC`;
    document.getElementById("tax").textContent = `${tax.toFixed(0)} GC`;
    document.getElementById("total").textContent = `${total.toFixed(0)} GC`;
  }

  function showOrderPreviewModal() {
    // Calculate totals
    const subtotal = cart.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
    const shipping = subtotal > 200 ? 0 : 15;
    const tax = subtotal * 0.08;
    const total = subtotal + shipping + tax;

    // Create modal HTML as a simple string with inline styles
    const modalHTML = `
      <div id="order-preview-modal" style="
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.8);
      ">
        <div style="
          background: rgba(10, 10, 10, 0.95);
          border-radius: 15px;
          border: 2px solid #28a745;
          max-width: 600px;
          width: 90%;
          max-height: 80vh;
          overflow-y: auto;
          color: white;
          box-shadow: 0 0 30px rgba(40, 167, 69, 0.5);
          position: relative;
        ">
          <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
          ">
            <h3 style="margin: 0; color: white;"><i class="fas fa-clipboard-list me-2"></i>Order Preview</h3>
            <button onclick="closeOrderPreviewModal()" style="
              background: none;
              border: none;
              color: white;
              font-size: 1.2rem;
              cursor: pointer;
              padding: 0.5rem;
              border-radius: 50%;
            ">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div style="padding: 2rem;">
            <h4 style="margin-bottom: 1rem; color: white;">Order Summary</h4>
            
            <div style="
              max-height: 200px;
              overflow-y: auto;
              margin-bottom: 1.5rem;
              border: 1px solid rgba(255, 255, 255, 0.1);
              border-radius: 8px;
              padding: 1rem;
            ">
              ${cart
                .map(
                  (item) => `
                <div style="
                  display: flex;
                  align-items: center;
                  margin-bottom: 1rem;
                  padding-bottom: 1rem;
                  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                ">
                  <img src="${item.image}" alt="${item.name}" style="
                    width: 50px;
                    height: 50px;
                    object-fit: cover;
                    border-radius: 8px;
                    margin-right: 1rem;
                  ">
                  <div style="flex: 1;">
                    <h6 style="margin: 0 0 0.25rem 0; font-size: 0.9rem; color: white;">${
                      item.name
                    }</h6>
                    <p style="margin: 0; font-size: 0.8rem; color: #ccc;">${item.price.toFixed(
                      0
                    )} GC × ${item.quantity}</p>
                  </div>
                  <div style="font-weight: bold; color: #28a745;">
                    ${(item.price * item.quantity).toFixed(0)} GC
                  </div>
                </div>
              `
                )
                .join("")}
            </div>
            
            <div style="
              background: rgba(255, 255, 255, 0.05);
              padding: 1rem;
              border-radius: 8px;
              margin-bottom: 1rem;
            ">
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Subtotal:</span>
                <span>${subtotal.toFixed(0)} GC</span>
              </div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Shipping:</span>
                <span>${
                  shipping === 0 ? "FREE" : shipping.toFixed(0) + " GC"
                }</span>
              </div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Galactic Tax:</span>
                <span>${tax.toFixed(0)} GC</span>
              </div>
              <hr style="border-color: rgba(255, 255, 255, 0.2);">
              <div style="display: flex; justify-content: space-between; font-size: 1.1rem; color: #28a745; font-weight: bold;">
                <span>Total:</span>
                <span>${total.toFixed(0)} GC</span>
              </div>
            </div>
            
            <button onclick="confirmOrder()" style="
              width: 100%;
              padding: 12px;
              background: #28a745;
              color: white;
              border: none;
              border-radius: 8px;
              font-size: 1rem;
              cursor: pointer;
              margin-bottom: 0.5rem;
            ">
              <i class="fas fa-check me-2"></i>Confirm Order
            </button>
            
            <button onclick="closeOrderPreviewModal()" style="
              width: 100%;
              padding: 12px;
              background: transparent;
              color: white;
              border: 1px solid rgba(255, 255, 255, 0.3);
              border-radius: 8px;
              font-size: 1rem;
              cursor: pointer;
            ">
              <i class="fas fa-arrow-left me-2"></i>Back to Cart
            </button>
          </div>
        </div>
      </div>
    `;

    // Insert at the very end of the body
    document.body.insertAdjacentHTML("beforeend", modalHTML);
  }

  // Global functions for cart manipulation
  window.updateQuantity = function (index, newQuantity) {
    newQuantity = parseInt(newQuantity);
    if (newQuantity < 1) {
      removeItem(index);
      return;
    }

    cart[index].quantity = newQuantity;
    localStorage.setItem("solarsoil_cart", JSON.stringify(cart));
    loadCart();
    updateCartCount();
  };

  window.removeItem = function (index) {
    const itemName = cart[index].name;
    cart.splice(index, 1);
    localStorage.setItem("solarsoil_cart", JSON.stringify(cart));
    loadCart();
    updateCartCount();
    showRemovedMessage(itemName);
  };

  window.closeOrderPreviewModal = function () {
    const modal = document.getElementById("order-preview-modal");
    if (modal) {
      modal.remove();
    }
  };

  window.confirmOrder = function () {
    // Create a form to POST cart data to Order page
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "../Order/index.php";

    // Add cart data as hidden input
    const cartInput = document.createElement("input");
    cartInput.type = "hidden";
    cartInput.name = "cart_data";
    cartInput.value = JSON.stringify(cart);
    form.appendChild(cartInput);

    // Add create_order flag
    const createOrderInput = document.createElement("input");
    createOrderInput.type = "hidden";
    createOrderInput.name = "create_order";
    createOrderInput.value = "1";
    form.appendChild(createOrderInput);

    // Add form to body and submit
    document.body.appendChild(form);
    form.submit();

    // Clear cart after submitting
    cart = [];
    localStorage.setItem("solarsoil_cart", JSON.stringify(cart));
  };

  function updateCartCount() {
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    const cartCountElement = document.getElementById("cart-count");
    if (cartCountElement) {
      cartCountElement.textContent = cartCount;
    }
  }

  function showRemovedMessage(itemName) {
    const toast = document.createElement("div");
    toast.className = "toast-notification";
    toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-trash"></i>
                <span>${itemName} removed from cart</span>
            </div>
        `;

    toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: linear-gradient(135deg, #dc3545, #c82333);
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
    }, 3000);
  }
});

// Add CSS animations
const style = document.createElement("style");
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .item-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #28a745;
    }
`;
document.head.appendChild(style);
