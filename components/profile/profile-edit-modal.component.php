<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light" style="background-color: #212529 !important; color: #f8f9fa;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label" style="color:#fff;"><i
                                class="fas fa-user me-2"></i>First Name</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="editFirstName"
                            name="first_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label" style="color:#fff;"><i
                                class="fas fa-user me-2"></i>Last Name</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="editLastName"
                            name="last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editUsername" class="form-label" style="color:#fff;"><i
                                class="fas fa-at me-2"></i>Username</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="editUsername"
                            name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label" style="color:#fff;"><i
                                class="fas fa-map-marker-alt me-2"></i>Address</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="editAddress"
                            name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editJoinDate" class="form-label" style="color:#fff;"><i
                                class="fas fa-calendar me-2"></i>Member
                            Since</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="editJoinDate"
                            value="<?php echo date('F j, Y', strtotime($user_data['join_date'])); ?>" disabled>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>