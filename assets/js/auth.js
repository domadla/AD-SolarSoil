function switchToSignup() {
  const loginForm = document.getElementById("loginForm");
  const signupForm = document.getElementById("signupForm");

  loginForm.classList.remove("active");
  signupForm.classList.add("active");
}

function switchToLogin() {
  const loginForm = document.getElementById("loginForm");
  const signupForm = document.getElementById("signupForm");

  signupForm.classList.remove("active");
  loginForm.classList.add("active");
}

document.addEventListener("DOMContentLoaded", function () {
  const signupForm = document.querySelector("#signupForm form");
  const password = document.getElementById("signupPassword");
  const confirmPassword = document.getElementById("confirmPassword");

  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert("Passwords do not match!");
        confirmPassword.focus();
        return false;
      }
    });
  }

  if (confirmPassword) {
    confirmPassword.addEventListener("input", function () {
      if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity("Passwords do not match");
      } else {
        confirmPassword.setCustomValidity("");
      }
    });
  }

  const inputs = document.querySelectorAll("input[required]");
  inputs.forEach((input) => {
    input.addEventListener("blur", function () {
      if (this.validity.valid) {
        this.style.borderColor = "var(--color-primary)";
      } else {
        this.style.borderColor = "#ff6b6b";
      }
    });

    input.addEventListener("focus", function () {
      this.style.borderColor = "var(--color-primary)";
    });
  });

  const firstName = document.getElementById("signupFirstName");
  const lastName = document.getElementById("signupLastName");

  if (firstName) {
    firstName.addEventListener("input", function () {
      if (this.value.trim().length < 2) {
        this.setCustomValidity("First name must be at least 2 characters");
      } else {
        this.setCustomValidity("");
      }
    });
  }

  if (lastName) {
    lastName.addEventListener("input", function () {
      if (this.value.trim().length < 2) {
        this.setCustomValidity("Last name must be at least 2 characters");
      } else {
        this.setCustomValidity("");
      }
    });
  }
});
