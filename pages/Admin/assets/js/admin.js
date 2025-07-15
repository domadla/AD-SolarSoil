// Helper to show the admin modal with custom content
function showAdminModal(title, body) {
  document.getElementById("adminInfoModalLabel").textContent = title;
  document.getElementById("adminInfoModalBody").innerHTML = body;
  var modal = new bootstrap.Modal(document.getElementById("adminInfoModal"));
  modal.show();
}

document.addEventListener("DOMContentLoaded", function () {
  var adminModal = document.getElementById("adminInfoModal");
  if (adminModal) {
    adminModal.addEventListener("hidden.bs.modal", function () {
      document.querySelectorAll(".modal-backdrop").forEach((el) => el.remove());
      document.body.classList.remove("modal-open");
      document.body.style = "";
    });
  }
});

function manageUsers() {
  fetch("../../components/admin/user/view-user-modal.component.php")
    .then((response) => response.text())
    .then((html) => showAdminModal("User List", html));
}
function addUser() {
  fetch("../../components/admin/user/add-user-modal.component.php")
    .then((response) => response.text())
    .then((html) => showAdminModal("Add User", html));
}
function managePlants() {
  fetch("../../components/admin/plant/view-plant-modal.component.php")
    .then((response) => response.text())
    .then((html) => showAdminModal("Plant Inventory", html));
}
function addPlant() {
  fetch("../../components/admin/plant/add-plant-modal.component.php")
    .then((response) => response.text())
    .then((html) => showAdminModal("Add Plant", html));
}
function editPlant(id) {
  fetch("../../components/admin/plant/edit-plant-modal.component.php?id=" + id)
    .then((response) => response.text())
    .then((html) => showAdminModal("Edit Plant", html));
}
function manageOrders() {
  fetch("../../components/admin/order/view-order-modal.component.php")
    .then((response) => response.text())
    .then((html) => showAdminModal("Order List", html));
}
function processOrders() {
  fetch("../../components/admin/order/process-order-modal.component.php")
    .then((response) => response.text())
    .then((html) => showAdminModal("Process Orders", html));
}
