// Main JavaScript for SolarSoil

document.addEventListener("DOMContentLoaded", function () {
  // Initialize auth tabs
  initAuthTabs();

  //form validation
  initFormValidation();

  //animations
  initAnimations();

  // Always update cart badge on every page load
  if (
    window.CartUtils &&
    typeof window.CartUtils.updateCartBadge === "function"
  ) {
    window.CartUtils.updateCartBadge();
  }
});

// Login <-> Signup
function initAuthTabs() {
  const tabs = document.querySelectorAll(".auth-tab");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      const targetTab = this.getAttribute("data-tab");

      tabs.forEach((t) => t.classList.remove("active"));
      this.classList.add("active");

      contents.forEach((content) => {
        content.classList.add("d-none");
        content.classList.remove("fade-in");
      });

      const targetContent = document.getElementById(targetTab);
      if (targetContent) {
        setTimeout(() => {
          targetContent.classList.remove("d-none");
          targetContent.classList.add("fade-in");
        }, 150);
      }
    });
  });
}

// Form Validation
function initFormValidation() {
  const forms = document.querySelectorAll(".auth-form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const formType = this.getAttribute("data-form");

      // Always validate signup forms for password matching
      if (formType === "signup") {
        const isValid = validateSignupForm(this);
        if (!isValid) {
          e.preventDefault();
          return;
        }
      }

      // For demo forms (login/signup), allow normal submission to PHP if validation passes
      const actionInput = this.querySelector('input[name="action"]');
      if (
        actionInput &&
        (actionInput.value === "login" || actionInput.value === "signup")
      ) {
        return; // Let the form submit normally to PHP
      }

      // For other forms, prevent default and validate
      e.preventDefault();
      const isValid = validateForm(this, formType);

      if (isValid) {
        showMessage("success", getSuccessMessage(formType));
        this.reset();
      }
    });
  });

  // Add real-time password matching validation
  const confirmPasswordInputs = document.querySelectorAll(
    'input[name="confirm_password"]'
  );
  confirmPasswordInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const form = this.closest("form");
      const passwordInput = form.querySelector('input[name="password"]');

      // Clear previous error first
      clearFieldError(this);

      if (passwordInput && this.value) {
        if (this.value !== passwordInput.value) {
          showFieldError(this, "Passwords do not match");
        }
      }
    });
  });

  // Also add validation when password field changes
  const passwordInputs = document.querySelectorAll('input[name="password"]');
  passwordInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const form = this.closest("form");
      const confirmPasswordInput = form.querySelector(
        'input[name="confirm_password"]'
      );
      const passwordRequirement = form.querySelector(".password-requirement");

      // Show password requirement text when user starts typing
      if (passwordRequirement) {
        if (this.value.length > 0 && this.value.length < 6) {
          passwordRequirement.style.display = "block";
        } else {
          passwordRequirement.style.display = "none";
        }
      }

      if (confirmPasswordInput && confirmPasswordInput.value) {
        // Clear previous error first
        clearFieldError(confirmPasswordInput);

        if (this.value !== confirmPasswordInput.value) {
          showFieldError(confirmPasswordInput, "Passwords do not match");
        }
      }
    });
  });
}

// Form Validation Logic
function validateForm(form, formType) {
  const inputs = form.querySelectorAll("input[required]");
  let isValid = true;

  // Clear previous error messages
  clearErrorMessages(form);

  inputs.forEach((input) => {
    const value = input.value.trim();

    if (!value) {
      showFieldError(input, "This field is required");
      isValid = false;
      return;
    }

    // Password validation for signup
    if (formType === "signup" && input.type === "password") {
      if (value.length < 6) {
        showFieldError(input, "Password must be at least 6 characters long");
        isValid = false;
        return;
      }
    }

    // Confirm password validation
    if (input.name === "confirm_password") {
      const password = form.querySelector('input[name="password"]').value;
      if (value !== password) {
        showFieldError(input, "Passwords do not match");
        isValid = false;
        return;
      }
    }
  });

  return isValid;
}

// Specific validation for signup forms
function validateSignupForm(form) {
  let isValid = true;

  // Clear previous errors
  clearErrorMessages(form);

  // Get form inputs
  const password = form.querySelector('input[name="password"]');
  const confirmPassword = form.querySelector('input[name="confirm_password"]');
  const firstName = form.querySelector('input[name="first_name"]');
  const lastName = form.querySelector('input[name="last_name"]');
  const username = form.querySelector('input[name="username"]');

  // Check required fields
  if (!firstName.value.trim()) {
    showFieldError(firstName, "First name is required");
    isValid = false;
  }

  if (!lastName.value.trim()) {
    showFieldError(lastName, "Last name is required");
    isValid = false;
  }

  if (!username.value.trim()) {
    showFieldError(username, "Username is required");
    isValid = false;
  }

  if (!password.value) {
    showFieldError(password, "Password is required");
    isValid = false;
  } else if (password.value.length < 6) {
    showFieldError(password, "Password must be at least 6 characters long");
    isValid = false;
  }

  if (!confirmPassword.value) {
    showFieldError(confirmPassword, "Please confirm your password");
    isValid = false;
  } else if (password.value !== confirmPassword.value) {
    showFieldError(confirmPassword, "Passwords do not match");
    isValid = false;
  }

  return isValid;
}

function clearFieldError(input) {
  input.classList.remove("is-invalid");
  const errorElement = input.parentNode.querySelector(".field-error");
  if (errorElement) {
    errorElement.remove();
  }
}

// Helper Functions
function showFieldError(input, message) {
  // Clear any existing error for this input first
  clearFieldError(input);

  const errorElement = document.createElement("div");
  errorElement.className = "text-danger mt-1 field-error";
  errorElement.textContent = message;

  input.classList.add("is-invalid");
  input.parentNode.appendChild(errorElement);
}

function clearErrorMessages(form) {
  const errorElements = form.querySelectorAll(".field-error");
  const invalidInputs = form.querySelectorAll(".is-invalid");

  errorElements.forEach((el) => el.remove());
  invalidInputs.forEach((input) => input.classList.remove("is-invalid"));
}

function showMessage(type, message) {
  const alertContainer = document.getElementById("alert-container");
  if (!alertContainer) return;

  const alertClass = type === "success" ? "alert-success" : "alert-danger";
  const icon =
    type === "success" ? "fas fa-check-circle" : "fas fa-exclamation-triangle";

  alertContainer.innerHTML = `
        <div class="alert ${alertClass} fade-in">
            <i class="${icon} me-2"></i>${message}
        </div>
    `;

  // Auto-hide after 5 seconds
  setTimeout(() => {
    const alert = alertContainer.querySelector(".alert");
    if (alert) {
      alert.classList.add("fade-out");
      setTimeout(() => {
        alertContainer.innerHTML = "";
      }, 300);
    }
  }, 5000);
}

function getSuccessMessage(formType) {
  switch (formType) {
    case "login":
      return "Login successful! Welcome back to SolarSoil.";
    case "signup":
      return "Account created successfully! Welcome to SolarSoil.";
    default:
      return "Form submitted successfully!";
  }
}

// Animations
function initAnimations() {
  // Add fade-in class to elements when they come into view
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in");
      }
    });
  }, observerOptions);

  // Observe elements with animation class
  document.querySelectorAll(".animate-on-scroll").forEach((el) => {
    observer.observe(el);
  });
}

// Add CSS classes for animations
const style = document.createElement("style");
style.textContent = `
    .fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    .fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);

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

// CartUtils - shared cart logic for all pages
window.CartUtils = {
  getCart: function () {
    return JSON.parse(localStorage.getItem("solarsoil_cart")) || [];
  },
  setCart: function (cart) {
    localStorage.setItem("solarsoil_cart", JSON.stringify(cart));
  },
  addToCart: function (plantData) {
    let cart = this.getCart();
    const existingItem = cart.find((item) => item.id === plantData.id);
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      cart.push(plantData);
    }
    this.setCart(cart);
    this.updateCartBadge();
  },
  removeFromCart: function (id) {
    let cart = this.getCart();
    cart = cart.filter((item) => item.id !== parseInt(id));
    this.setCart(cart);
    this.updateCartBadge();
  },
  incrementQuantity: function (id) {
    let cart = this.getCart();
    const item = cart.find((i) => i.id === parseInt(id));
    if (item) item.quantity += 1;
    this.setCart(cart);
    this.updateCartBadge();
  },
  decrementQuantity: function (id) {
    let cart = this.getCart();
    const item = cart.find((i) => i.id === parseInt(id));
    if (item) {
      item.quantity -= 1;
      if (item.quantity < 1) {
        cart = cart.filter((i) => i.id !== parseInt(id));
      }
    }
    this.setCart(cart);
    this.updateCartBadge();
  },
  setQuantity: function (id, value) {
    let cart = this.getCart();
    const item = cart.find((i) => i.id === parseInt(id));
    if (item) item.quantity = value;
    this.setCart(cart);
    this.updateCartBadge();
  },
  updateCartBadge: function () {
    const cart = this.getCart();
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    const badge = document.getElementById("cart-badge");
    if (badge) badge.textContent = cartCount;
    // Also update old badge if present
    const cartCountElement = document.getElementById("cart-count");
    if (cartCountElement) cartCountElement.textContent = cartCount;
  },
};
