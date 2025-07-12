// Admin Dashboard specific JavaScript

document.addEventListener("DOMContentLoaded", function () {
  // Initialize admin dashboard animations
  initAdminAnimations();

  // Initialize management buttons
  initManagementButtons();

  // Start admin data updates
  startAdminUpdates();

  // Initialize admin progress bars
  animateAdminProgressBars();
});

// Admin Dashboard Animations
function initAdminAnimations() {
  // Animate admin cards on load
  const adminCards = document.querySelectorAll(".admin-card");
  adminCards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";

    setTimeout(() => {
      card.style.transition = "all 0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 150);
  });

  // Animate management cards
  const managementCards = document.querySelectorAll(".management-card");
  managementCards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateX(-30px)";

    setTimeout(() => {
      card.style.transition = "all 0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateX(0)";
    }, (index + 4) * 150);
  });

  // Animate welcome card
  const welcomeCard = document.querySelector(".admin-welcome-card");
  if (welcomeCard) {
    welcomeCard.style.opacity = "0";
    welcomeCard.style.transform = "scale(0.95)";

    setTimeout(() => {
      welcomeCard.style.transition = "all 0.8s ease";
      welcomeCard.style.opacity = "1";
      welcomeCard.style.transform = "scale(1)";
    }, 200);
  }
}

// Management Button Handlers
function initManagementButtons() {
  // Add event listeners for action buttons
  const actionButtons = document.querySelectorAll(".action-buttons .btn");

  actionButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Add click animation
      this.style.transform = "scale(0.95)";
      setTimeout(() => {
        this.style.transform = "";
      }, 150);
    });
  });
}

// User Management Functions
function manageUsers() {
  showAdminAlert("Opening User Management Panel...", "info");
  // In a real application, this would navigate to user management page
  setTimeout(() => {
    console.log("Navigate to user management");
    // window.location.href = "users/index.php";
  }, 1000);
}

function addUser() {
  showAdminAlert("Opening Add User Form...", "success");
  // In a real application, this would open an add user modal or page
  setTimeout(() => {
    console.log("Open add user form");
  }, 1000);
}

// Plant Management Functions
function managePlants() {
  showAdminAlert("Loading Plant Inventory...", "info");
  // In a real application, this would navigate to plant management page
  setTimeout(() => {
    console.log("Navigate to plant management");
    // window.location.href = "plants/index.php";
  }, 1000);
}

function addPlant() {
  showAdminAlert("Opening Add Plant Form...", "success");
  // In a real application, this would open an add plant modal or page
  setTimeout(() => {
    console.log("Open add plant form");
  }, 1000);
}

// Order Management Functions
function manageOrders() {
  showAdminAlert("Loading Order Management...", "info");
  // In a real application, this would navigate to order management page
  setTimeout(() => {
    console.log("Navigate to order management");
    // window.location.href = "orders/index.php";
  }, 1000);
}

function processOrders() {
  showAdminAlert("Processing Pending Orders...", "warning");
  // In a real application, this would process pending orders
  setTimeout(() => {
    console.log("Process pending orders");
    showAdminAlert("Orders processed successfully!", "success");
  }, 2000);
}

// Admin Alert System
function showAdminAlert(message, type = "info") {
  // Create alert element
  const alert = document.createElement("div");
  alert.className = `admin-alert alert-${type}`;
  alert.innerHTML = `
    <i class="fas fa-info-circle me-2"></i>
    ${message}
  `;

  // Add styles
  alert.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    background: rgba(26, 26, 58, 0.95);
    border: 1px solid var(--cosmic-gold);
    border-radius: 10px;
    color: white;
    z-index: 1000;
    transform: translateX(400px);
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  `;

  document.body.appendChild(alert);

  // Slide in
  setTimeout(() => {
    alert.style.transform = "translateX(0)";
  }, 100);

  // Auto remove
  setTimeout(() => {
    alert.style.transform = "translateX(400px)";
    setTimeout(() => {
      document.body.removeChild(alert);
    }, 300);
  }, 3000);
}

// Admin Data Updates (Simulated)
function startAdminUpdates() {
  // Simulate real-time updates every 30 seconds
  setInterval(() => {
    updateAdminMetrics();
  }, 30000);
}

function updateAdminMetrics() {
  // Simulate metric updates
  const metrics = document.querySelectorAll(".metric-value");

  metrics.forEach((metric) => {
    const currentValue = parseInt(metric.textContent.replace(/,/g, ""));
    const change = Math.floor(Math.random() * 10) - 5; // Random change -5 to +5
    const newValue = Math.max(0, currentValue + change);

    // Format number with commas
    metric.textContent = newValue.toLocaleString();

    // Add update animation
    metric.style.transform = "scale(1.1)";
    metric.style.color =
      change > 0 ? "#00ff88" : change < 0 ? "#ff4444" : "#ffd700";

    setTimeout(() => {
      metric.style.transform = "scale(1)";
      metric.style.color = "#ffd700";
    }, 500);
  });
}

// Progress Bar Animations
function animateAdminProgressBars() {
  const progressBars = document.querySelectorAll(".progress-fill");

  progressBars.forEach((bar, index) => {
    const targetWidth = bar.style.width;
    bar.style.width = "0%";

    setTimeout(() => {
      bar.style.width = targetWidth;
    }, 500 + index * 200);
  });
}

// Admin Search Functionality (for future use)
function initAdminSearch() {
  const searchInput = document.getElementById("admin-search");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const query = this.value.toLowerCase();
      // Implement search functionality
      console.log("Searching for:", query);
    });
  }
}

// Export functions for global use
window.manageUsers = manageUsers;
window.addUser = addUser;
window.managePlants = managePlants;
window.addPlant = addPlant;
window.manageOrders = manageOrders;
window.processOrders = processOrders;
