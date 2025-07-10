// Home Page specific JavaScript

document.addEventListener("DOMContentLoaded", function () {
  // Initialize dashboard animations
  initDashboardAnimations();

  // Initialize action buttons
  initActionButtons();

  // Start real-time updates simulation
  startRealTimeUpdates();

  // Initialize progress bar animations
  animateProgressBars();
});

// Dashboard Animations
function initDashboardAnimations() {
  // Animate cards on load
  const cards = document.querySelectorAll(".dashboard-card");
  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";

    setTimeout(() => {
      card.style.transition = "all 0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 150);
  });

  // Animate welcome card
  const welcomeCard = document.querySelector(".welcome-card");
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

// Action Button Handlers
function initActionButtons() {
  const actionBtns = document.querySelectorAll(".action-btn");

  actionBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const action = this.querySelector("span").textContent;

      // Add click animation
      this.style.transform = "scale(0.95)";
      setTimeout(() => {
        this.style.transform = "";
      }, 150);

      // Simulate action
      showNotification(`${action} initiated!`, "success");
    });
  });
}

// Real-time Updates Simulation
function startRealTimeUpdates() {
  // Update metrics periodically
  setInterval(updateMetrics, 5000);

  // Add new activities periodically
  setInterval(addNewActivity, 15000);
}

function updateMetrics() {
  const metricValues = document.querySelectorAll(".metric-value");

  metricValues.forEach((metric) => {
    const currentValue = parseInt(metric.textContent.replace(/[^0-9]/g, ""));
    let newValue;

    if (metric.textContent.includes("K")) {
      newValue = currentValue + Math.floor(Math.random() * 10) + "K";
    } else if (metric.textContent.includes("M")) {
      newValue = currentValue + Math.floor(Math.random() * 0.1 * 10) / 10 + "M";
    } else {
      newValue = currentValue + Math.floor(Math.random() * 3);
    }

    // Animate value change
    metric.style.transform = "scale(1.1)";
    metric.style.color = "var(--cosmic-gold)";

    setTimeout(() => {
      metric.textContent = newValue;
      setTimeout(() => {
        metric.style.transform = "scale(1)";
        metric.style.color = "var(--primary-green)";
      }, 200);
    }, 300);
  });
}

function addNewActivity() {
  const activities = [
    {
      icon: "fa-seedling",
      title: "Crop Optimization Complete",
      description:
        "AI algorithms improved yield efficiency by 12% on Europa Station",
      time: "Just now",
    },
    {
      icon: "fa-satellite",
      title: "New Planet Discovered",
      description:
        "TRAPPIST-1f shows excellent conditions for hydroponic farming",
      time: "Just now",
    },
    {
      icon: "fa-robot",
      title: "Harvesting Drones Deployed",
      description:
        "Automated harvesting initiated on Titan Agricultural Complex",
      time: "Just now",
    },
  ];

  const randomActivity =
    activities[Math.floor(Math.random() * activities.length)];
  const activityList = document.querySelector(".activity-list");

  if (activityList) {
    const newActivity = createActivityElement(randomActivity);

    // Add with animation
    newActivity.style.opacity = "0";
    newActivity.style.transform = "translateX(-30px)";
    activityList.insertBefore(newActivity, activityList.firstChild);

    setTimeout(() => {
      newActivity.style.transition = "all 0.5s ease";
      newActivity.style.opacity = "1";
      newActivity.style.transform = "translateX(0)";
    }, 100);

    // Remove old activities if too many
    const activities = activityList.querySelectorAll(".activity-item");
    if (activities.length > 5) {
      const oldActivity = activities[activities.length - 1];
      oldActivity.style.transition = "all 0.3s ease";
      oldActivity.style.opacity = "0";
      oldActivity.style.transform = "translateX(30px)";

      setTimeout(() => {
        if (oldActivity.parentNode) {
          oldActivity.parentNode.removeChild(oldActivity);
        }
      }, 300);
    }
  }
}

function createActivityElement(activity) {
  const activityItem = document.createElement("div");
  activityItem.className = "activity-item";

  activityItem.innerHTML = `
        <div class="activity-icon">
            <i class="fas ${activity.icon}"></i>
        </div>
        <div class="activity-content">
            <h4>${activity.title}</h4>
            <p>${activity.description}</p>
            <span class="activity-time">${activity.time}</span>
        </div>
    `;

  return activityItem;
}

// Progress Bar Animations
function animateProgressBars() {
  const progressBars = document.querySelectorAll(".progress-fill");

  progressBars.forEach((bar) => {
    const targetWidth = bar.style.width;
    bar.style.width = "0%";

    setTimeout(() => {
      bar.style.transition = "width 2s ease-out";
      bar.style.width = targetWidth;
    }, 500);
  });
}

// Notification System
function showNotification(message, type = "info") {
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
        <i class="fas fa-rocket me-2"></i>
        ${message}
    `;

  // Add notification styles
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(26, 26, 58, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid var(--primary-green);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        color: white;
        z-index: 10000;
        transform: translateX(300px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(0, 255, 136, 0.3);
    `;

  document.body.appendChild(notification);

  // Animate in
  setTimeout(() => {
    notification.style.transform = "translateX(0)";
  }, 100);

  // Auto remove
  setTimeout(() => {
    notification.style.transform = "translateX(300px)";
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }, 3000);
}

// Keyboard shortcuts
document.addEventListener("keydown", function (e) {
  // Ctrl/Cmd + D for dashboard refresh
  if ((e.ctrlKey || e.metaKey) && e.key === "d") {
    e.preventDefault();
    location.reload();
  }

  // Escape to show help
  if (e.key === "Escape") {
    showNotification("Press Ctrl+D to refresh dashboard", "info");
  }
});
