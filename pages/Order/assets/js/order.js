// Order Page JavaScript
document.addEventListener("DOMContentLoaded", function () {
  // Add interactivity to the order page

  // Track Order functionality
  window.trackOrder = function () {
    // Create a tracking modal or redirect to tracking page
    showTrackingModal();
  };

  // Download Invoice functionality
  window.downloadInvoice = function () {
    // Create a simple invoice download
    generateInvoice();
  };

  // Print Order functionality
  window.printOrder = function () {
    // Print the current page
    window.print();
  };

  function showTrackingModal() {
    // Get tracking number from the page
    const trackingNumber =
      document.querySelector(".tracking-number").textContent;

    const modalHTML = `
      <div id="tracking-modal" style="
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
          border: 2px solid #007bff;
          max-width: 500px;
          width: 90%;
          color: white;
          box-shadow: 0 0 30px rgba(0, 123, 255, 0.5);
          position: relative;
        ">
          <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
          ">
            <h3 style="margin: 0; color: white;"><i class="fas fa-satellite me-2"></i>Order Tracking</h3>
            <button onclick="closeTrackingModal()" style="
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
          
          <div style="padding: 2rem; text-align: center;">
            <div style="margin-bottom: 2rem;">
              <i class="fas fa-rocket fa-3x" style="color: #007bff; margin-bottom: 1rem;"></i>
              <h4 style="color: white; margin-bottom: 1rem;">Tracking Number</h4>
              <div style="
                background: rgba(0, 123, 255, 0.2);
                padding: 1rem;
                border-radius: 8px;
                border: 1px solid #007bff;
                font-family: 'Courier New', monospace;
                font-size: 1.2rem;
                color: #007bff;
                margin-bottom: 1rem;
              ">
                ${trackingNumber}
              </div>
            </div>
            
            <div style="margin-bottom: 2rem;">
              <h5 style="color: white; margin-bottom: 1rem;">Current Status</h5>
              <div style="
                background: rgba(40, 167, 69, 0.2);
                padding: 1rem;
                border-radius: 8px;
                border: 1px solid #28a745;
                color: #28a745;
              ">
                <i class="fas fa-check-circle me-2"></i>Order Confirmed - Being Prepared
              </div>
            </div>
            
            <div style="margin-bottom: 2rem;">
              <p style="color: #ccc; font-size: 0.9rem; margin: 0;">
                Your order is being prepared for cosmic delivery. 
                You will receive updates as your plants journey through space.
              </p>
            </div>
            
            <button onclick="closeTrackingModal()" style="
              width: 100%;
              padding: 12px;
              background: #007bff;
              color: white;
              border: none;
              border-radius: 8px;
              font-size: 1rem;
              cursor: pointer;
            ">
              <i class="fas fa-check me-2"></i>Close
            </button>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHTML);
  }

  window.closeTrackingModal = function () {
    const modal = document.getElementById("tracking-modal");
    if (modal) {
      modal.remove();
    }
  };

  function generateInvoice() {
    // Get order details from the page
    const orderId = document.querySelector(".order-info-item span").textContent;
    const orderDate = new Date().toLocaleDateString();

    // Create invoice content
    const invoiceContent = `
      <div style="padding: 2rem; font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #28a745; padding-bottom: 1rem;">
          <h1 style="color: #28a745; margin: 0;">SolarSoil</h1>
          <p style="color: #666; margin: 0.5rem 0 0 0;">Interstellar Plant Delivery</p>
        </div>
        
        <div style="margin-bottom: 2rem;">
          <h2 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Invoice</h2>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1rem;">
            <div>
              <h3 style="color: #555;">Order Details</h3>
              <p><strong>Order ID:</strong> ${orderId}</p>
              <p><strong>Date:</strong> ${orderDate}</p>
              <p><strong>Status:</strong> Confirmed</p>
            </div>
            <div>
              <h3 style="color: #555;">Customer Information</h3>
              <p><strong>Name:</strong> Marlo Veluz</p>
              <p><strong>Email:</strong> marloveluz@solarsoil.galaxy</p>
              <p><strong>Planet:</strong> Earth</p>
            </div>
          </div>
        </div>
        
        <div style="margin-bottom: 2rem;">
          <h3 style="color: #555; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Order Items</h3>
          <div id="invoice-items"></div>
        </div>
        
        <div style="text-align: right; margin-top: 2rem; border-top: 2px solid #28a745; padding-top: 1rem;">
          <p style="font-size: 1.2rem; color: #28a745;"><strong>Total: <span id="invoice-total"></span></strong></p>
        </div>
        
        <div style="text-align: center; margin-top: 2rem; color: #666; font-size: 0.9rem;">
          <p>Thank you for choosing SolarSoil for your interstellar gardening needs!</p>
          <p>For support, contact us at support@solarsoil.galaxy</p>
        </div>
      </div>
    `;

    // Create a new window for the invoice
    const invoiceWindow = window.open("", "_blank", "width=800,height=600");
    invoiceWindow.document.write(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>SolarSoil Invoice - ${orderId}</title>
        <style>
          body { margin: 0; padding: 20px; background: white; }
          @media print {
            body { margin: 0; }
          }
        </style>
      </head>
      <body>
        ${invoiceContent}
        <script>
          // Add order items to invoice
          const items = ${JSON.stringify(getOrderItemsFromPage())};
          const itemsContainer = document.getElementById('invoice-items');
          const totalElement = document.getElementById('invoice-total');
          
          let total = 0;
          items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            itemsContainer.innerHTML += \`
              <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                <span>\${item.name} × \${item.quantity}</span>
                <span>\${itemTotal.toFixed(0)} GC</span>
              </div>
            \`;
          });
          
          // Add shipping and tax
          const shipping = total > 200 ? 0 : 15;
          const tax = total * 0.08;
          const finalTotal = total + shipping + tax;
          
          itemsContainer.innerHTML += \`
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
              <span>Shipping</span>
              <span>\${shipping === 0 ? 'FREE' : shipping.toFixed(0) + ' GC'}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
              <span>Galactic Tax</span>
              <span>\${tax.toFixed(0)} GC</span>
            </div>
          \`;
          
          totalElement.textContent = finalTotal.toFixed(0) + ' GC';
          
          // Auto-print after content loads
          setTimeout(() => {
            window.print();
          }, 500);
        </script>
      </body>
      </html>
    `);
    invoiceWindow.document.close();
  }

  function getOrderItemsFromPage() {
    // Extract order items from the current page
    const items = [];
    const orderItems = document.querySelectorAll(".order-item");

    orderItems.forEach((item) => {
      const name = item.querySelector(".item-name").textContent;
      const priceText = item.querySelector(".item-price").textContent;
      const quantityText = item.querySelector(".item-quantity").textContent;

      const price = parseInt(priceText.replace(/[^\d]/g, ""));
      const quantity = parseInt(quantityText.replace(/[^\d]/g, ""));

      items.push({ name, price, quantity });
    });

    return items;
  }

  // Add some interactive effects
  const orderCards = document.querySelectorAll(".order-card");
  orderCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-5px)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });

  // Add loading animation to buttons
  const actionButtons = document.querySelectorAll(".btn-block");
  actionButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (this.onclick) return; // Skip if it has custom onclick

      const originalText = this.innerHTML;
      this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
      this.disabled = true;

      setTimeout(() => {
        this.innerHTML = originalText;
        this.disabled = false;
      }, 1500);
    });
  });

  // Animate timeline items on scroll
  const observerOptions = {
    threshold: 0.5,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateX(0)";
      }
    });
  }, observerOptions);

  const timelineItems = document.querySelectorAll(".timeline-item");
  timelineItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.transform = "translateX(-30px)";
    item.style.transition = `all 0.6s ease ${index * 0.2}s`;
    observer.observe(item);
  });
});
