// Global function to show toast notifications
function showAddedToCartMessage(plantName) {
  // Remove any existing notification before showing a new one
  const existingToast = document.querySelector(".toast-notification");
  if (existingToast) existingToast.remove();

  // Create and show a toast notification
  const toast = document.createElement("div");
  toast.className = "toast-notification";
  toast.innerHTML = `
    <div class="toast-content">
      <i class="fas fa-check-circle"></i>
      <span>${plantName} added to cart!</span>
    </div>
  `;

  // Add toast styles
  toast.style.cssText = `
    position: fixed;
    top: 100px;
    right: 20px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    z-index: 9999;
    animation: slideInRight 0.3s ease;
  `;

  document.body.appendChild(toast);

  // Remove toast after 3 seconds
  setTimeout(() => {
    toast.style.animation = "slideOutRight 0.3s ease";
    setTimeout(() => {
      if (document.body.contains(toast)) {
        document.body.removeChild(toast);
      }
    }, 300);
  }, 3000);
}

// Only add toast animation style if not already present
if (!document.getElementById("toast-animation-style")) {
  const toastStyle = document.createElement("style");
  toastStyle.id = "toast-animation-style";
  toastStyle.textContent = `
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
    .toast-content i {
        font-size: 1.2rem;
    }
  `;
  document.head.appendChild(toastStyle);
}

// Shop Page JavaScript
document.addEventListener("DOMContentLoaded", function () {
  // Cart management
  let cart = JSON.parse(localStorage.getItem("solarsoil_cart")) || [];

  // Update cart count on page load
  updateCartCount();

  // Search functionality
  const searchInput = document.getElementById("plant-search");
  const planetFilter = document.getElementById("planet-filter");
  const careFilter = document.getElementById("care-filter");
  const plantItems = document.querySelectorAll(".plant-item");
  const noResults = document.getElementById("no-results");

  // Add event listeners for filtering (only if elements exist)
  if (searchInput) searchInput.addEventListener("input", filterPlants);
  if (planetFilter) planetFilter.addEventListener("change", filterPlants);
  if (careFilter) careFilter.addEventListener("change", filterPlants);

  // Add to cart functionality
  const addToCartButtons = document.querySelectorAll(".add-to-cart");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent form submission

      const plantData = {
        id: parseInt(this.dataset.id),
        name: this.dataset.name,
        price: parseFloat(this.dataset.price),
        // Always store only the filename for the image
        image: this.dataset.image.split("/").pop(),
        quantity: 1,
      };

      // Add to localStorage first for immediate UI feedback
      if (window.CartUtils) {
        window.CartUtils.addToCart(plantData);
      }

      console.log("Sending to backend:", plantData);

      // Send to backend database via AJAX
      fetch("?action=add_to_cart", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          id: plantData.id,
          quantity: plantData.quantity,
        }),
      })
        .then((response) => {
          console.log("Response status:", response.status);
          return response.json();
        })
        .then((data) => {
          console.log("Backend response:", data);
          if (data.success) {
            showAddedToCartMessage(plantData.name);

            // Update stock display if new stock data is provided
            if (data.new_stock !== undefined && data.plant_id) {
              updateStockDisplay(data.plant_id, data.new_stock);
            }

            // Update cart count
            if (window.CartUtils && window.CartUtils.updateCartBadge) {
              window.CartUtils.updateCartBadge();
            }
          } else {
            console.error("Backend error:", data.message);
            alert("Failed to add item to cart: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error adding to cart:", error);
          // Still show the toast even if backend fails
          showAddedToCartMessage(plantData.name);
        });
    });
  });

  function filterPlants() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedPlanet = planetFilter ? planetFilter.value : "";
    const selectedCare = careFilter ? careFilter.value : "";
    let visibleCount = 0;

    plantItems.forEach((item) => {
      const plantName = item.dataset.name;
      const planetName = item.dataset.planet;
      const careLevel = item.dataset.care;

      const matchesSearch = plantName.includes(searchTerm);
      const matchesPlanet = !selectedPlanet || planetName === selectedPlanet;
      const matchesCare = !selectedCare || careLevel === selectedCare;

      if (matchesSearch && matchesPlanet && matchesCare) {
        item.classList.remove("hidden");
        visibleCount++;
      } else {
        item.classList.add("hidden");
      }
    });

    // Show/hide no results message
    if (noResults) {
      if (visibleCount === 0) {
        noResults.style.display = "block";
      } else {
        noResults.style.display = "none";
      }
    }
  }

  function updateCartCount() {
    if (window.CartUtils) {
      window.CartUtils.updateCartBadge();
    }
  }

  // Function to update stock display after adding to cart
  function updateStockDisplay(plantId, newStock) {
    // Find the plant card with the specific ID
    const plantCard = document
      .querySelector(`[data-id="${plantId}"]`)
      .closest(".plant-item");
    if (!plantCard) return;

    // Find the stock badge within this plant card
    const stockBadge = plantCard.querySelector(".badge");
    if (!stockBadge) return;

    // Update badge content and style based on new stock level
    if (newStock <= 0) {
      stockBadge.className = "badge bg-danger";
      stockBadge.style.cssText =
        "position: absolute; top: 10px; left: 10px; font-size: 0.7rem; padding: 0.2rem 0.4rem; min-width: 60px; text-align: center;";
      stockBadge.textContent = "Out of Stock";

      // Disable the add button
      const addButton = plantCard.querySelector(".add-to-cart");
      if (addButton) {
        addButton.disabled = true;
        addButton.classList.add("disabled");
        addButton.innerHTML =
          '<i class="fas fa-cart-plus me-2"></i>Out of Stock';
      }

      // Update plant item class
      plantCard.className =
        plantCard.className.replace(
          /\b(in-stock|low-stock|out-of-stock)\b/g,
          ""
        ) + " out-of-stock";
    } else if (newStock <= 5) {
      stockBadge.className = "badge bg-warning";
      stockBadge.style.cssText =
        "position: absolute; top: 10px; left: 10px; font-size: 0.7rem; padding: 0.2rem 0.4rem; min-width: 60px; text-align: center;";
      stockBadge.textContent = `Low Stock (${newStock})`;

      // Update plant item class
      plantCard.className =
        plantCard.className.replace(
          /\b(in-stock|low-stock|out-of-stock)\b/g,
          ""
        ) + " low-stock";
    } else {
      stockBadge.className = "badge bg-success";
      stockBadge.style.cssText =
        "position: absolute; top: 10px; left: 10px; font-size: 0.7rem; padding: 0.2rem 0.4rem; min-width: 60px; text-align: center;";
      stockBadge.textContent = `In Stock (${newStock})`;

      // Update plant item class
      plantCard.className =
        plantCard.className.replace(
          /\b(in-stock|low-stock|out-of-stock)\b/g,
          ""
        ) + " in-stock";
    }

    console.log(`Stock updated for plant ${plantId}: ${newStock} remaining`);
  }
});
