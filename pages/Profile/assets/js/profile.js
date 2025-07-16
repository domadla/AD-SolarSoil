// Profile Page JavaScript
document.addEventListener("DOMContentLoaded", function () {
  // Initialize profile functionality
  initProfileButtons();
  initAnimations();
});

function initProfileButtons() {
  const editBtn = document.querySelector(".btn-primary");
  if (editBtn) {
    editBtn.addEventListener("click", function () {
      const userId = editBtn.getAttribute("data-id");
      document.getElementById("user_id").value = userId;
    });
  }
}

var deleteBtn = document.getElementById("confirmDeleteAccountBtn");
if (deleteBtn) {
  deleteBtn.addEventListener("click", function () {
    fetch("../../handlers/delete-account.handler.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "same-origin",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.href = "../../index.php?message=AccountDeleted";
        } else {
          alert(data.message || "Failed to delete account.");
        }
      })
      .catch(() => alert("Failed to delete account."));
  });
}

// Change Password button
const passwordBtn = document.querySelector(".btn-outline-primary");
if (passwordBtn) {
  passwordBtn.addEventListener("click", function () {
    showNotification("Change Password feature coming soon!", "info");
  });
}

// Logout button
const logoutBtn = document.querySelector(".btn-outline-danger");
if (logoutBtn) {
  logoutBtn.addEventListener("click", function () {
    if (confirm("Are you sure you want to logout?")) {
      // Clear session and redirect to login
      window.location.href = "../../index.php";
    }
  });
}

function initAnimations() {
  // Add fade-in animation to profile card
  const profileCard = document.querySelector(".profile-card");
  if (profileCard) {
    profileCard.style.opacity = "0";
    profileCard.style.transform = "translateY(30px)";

    setTimeout(() => {
      profileCard.style.transition = "all 0.6s ease";
      profileCard.style.opacity = "1";
      profileCard.style.transform = "translateY(0)";
    }, 200);
  }

  // Animate stat cards
  const statCards = document.querySelectorAll(".stat-card");
  statCards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";

    setTimeout(() => {
      card.style.transition = "all 0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, 400 + index * 100);
  });
}

function showNotification(message, type = "info") {
  const notification = document.createElement("div");
  notification.className = "notification";
  notification.innerHTML = `
    <div class="notification-content">
      <i class="fas fa-info-circle"></i>
      <span>${message}</span>
    </div>
  `;

  // Style the notification
  notification.style.cssText = `
    position: fixed;
    top: 100px;
    right: 20px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    z-index: 9999;
    animation: slideInRight 0.3s ease;
    max-width: 300px;
  `;

  document.body.appendChild(notification);

  // Auto-remove after 3 seconds
  setTimeout(() => {
    notification.style.animation = "slideOutRight 0.3s ease";
    setTimeout(() => {
      if (notification.parentNode) {
        document.body.removeChild(notification);
      }
    }, 300);
  }, 3000);
}

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
  
  .notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
  }
`;
document.head.appendChild(style);
