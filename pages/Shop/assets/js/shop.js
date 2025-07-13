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
      document.body.removeChild(toast);
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
    button.addEventListener("click", function () {
      const plantData = {
        id: parseInt(this.dataset.id),
        name: this.dataset.name,
        price: parseFloat(this.dataset.price),
        // Always store only the filename for the image
        image: this.dataset.image.split("/").pop(),
        quantity: 1,
      };
      
      if (window.CartUtils) {
        window.CartUtils.addToCart(plantData);
      }
      
      showAddedToCartMessage(plantData.name);
    });
  });

  function filterPlants() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedPlanet = planetFilter ? planetFilter.value : '';
    const selectedCare = careFilter ? careFilter.value : '';
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
});
