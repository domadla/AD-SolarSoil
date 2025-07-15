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

  // Progress bar logic
  function setProgressBar(countId, progressId) {
    var countElem = document.getElementById(countId);
    var progressElem = document.getElementById(progressId);
    if (countElem && progressElem) {
      var count = parseInt(countElem.getAttribute("data-count"), 10);
      var goal = parseInt(countElem.getAttribute("data-goal"), 10);
      var percent = 0;
      if (goal > 0) {
        percent = Math.min(100, (count / goal) * 100);
      }
      progressElem.style.width = percent + "%";
    }
  }
  setProgressBar("user-count", "user-progress");
  setProgressBar("plant-count", "plant-progress");
  setProgressBar("order-count", "order-progress");
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
}function deleteUser(id) {
  fetch("../../components/admin/user/delete-user-modal.component.php?id=" + id)
    .then((response) => response.text())
    .then((html) => showAdminModal("Delete User", html));
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
