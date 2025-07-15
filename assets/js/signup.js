// Signup form password validation script

document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("signup-password");
  const lengthMsg = document.getElementById("password-length-msg");
  const specialMsg = document.getElementById("password-special-msg");
  const upperMsg = document.getElementById("password-upper-msg");
  const lowerMsg = document.getElementById("password-lower-msg");
  const numberMsg = document.getElementById("password-number-msg");

  if (!passwordInput) return;

  passwordInput.addEventListener("input", function () {
    const value = passwordInput.value;
    const specialChar = /[!@#$%^&*(),.?":{}|<>]/;
    const upperChar = /[A-Z]/;
    const lowerChar = /[a-z]/;
    const numberChar = /[0-9]/;
    let valid = true;

    if (value.length === 0) {
      lengthMsg.classList.remove("show");
      specialMsg.classList.remove("show");
      upperMsg.classList.remove("show");
      lowerMsg.classList.remove("show");
      numberMsg.classList.remove("show");
      passwordInput.setCustomValidity("");
      return;
    }

    if (value.length < 6) {
      lengthMsg.classList.add("show");
      valid = false;
    } else {
      lengthMsg.classList.remove("show");
    }

    if (!specialChar.test(value)) {
      specialMsg.classList.add("show");
      valid = false;
    } else {
      specialMsg.classList.remove("show");
    }

    if (!upperChar.test(value)) {
      upperMsg.classList.add("show");
      valid = false;
    } else {
      upperMsg.classList.remove("show");
    }

    if (!lowerChar.test(value)) {
      lowerMsg.classList.add("show");
      valid = false;
    } else {
      lowerMsg.classList.remove("show");
    }

    if (!numberChar.test(value)) {
      numberMsg.classList.add("show");
      valid = false;
    } else {
      numberMsg.classList.remove("show");
    }

    if (!valid) {
      passwordInput.setCustomValidity(
        "Password must be at least 6 characters and contain at least 1 special character, 1 uppercase letter, 1 lowercase letter, and 1 number."
      );
    } else {
      passwordInput.setCustomValidity("");
    }
  });
});
