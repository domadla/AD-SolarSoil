const navToggle = document.getElementById("nav-toggle");
const navMenu = document.getElementById("nav-menu");

navToggle.addEventListener("click", () => {
  const expanded = navToggle.getAttribute("aria-expanded") === "true" || false;
  navToggle.setAttribute("aria-expanded", !expanded);
  navMenu.classList.toggle("show");
});

document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", (e) => {
    const href = link.getAttribute("href");
    if (href.startsWith("#")) {
      e.preventDefault();
      const targetId = href.substring(1);
      const targetSection = document.getElementById(targetId);
      if (targetSection) {
        targetSection.focus();
        targetSection.scrollIntoView({ behavior: "smooth" });
      }
      if (navMenu.classList.contains("show")) {
        navMenu.classList.remove("show");
        navToggle.setAttribute("aria-expanded", false);
      }
      document
        .querySelectorAll(".nav-link")
        .forEach((nav) => nav.classList.remove("active"));
      link.classList.add("active");
    }
  });
});

function toggleUserDropdown() {
  const dropdown = document.querySelector(".user-dropdown");
  if (dropdown) {
    dropdown.classList.toggle("active");
  }
}

document.addEventListener("click", function (event) {
  const dropdown = document.querySelector(".user-dropdown");
  const userLink = document.querySelector(".user-link");

  if (dropdown && !dropdown.contains(event.target)) {
    dropdown.classList.remove("active");
  }
});
