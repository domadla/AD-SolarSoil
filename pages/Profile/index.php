<?php
// Include handlers
require_once '../../bootstrap.php';
require_once HANDLERS_PATH . '/profile.handler.php';
require_once UTILS_PATH . '/auth.util.php';

Auth::init();
if (!Auth::check()){
    header('Location: ../../index.php?error=LoginRequired');
    exit;
}

// Set page variables
$page_title = 'SolarSoil - User Profile';
$page_description = 'Manage your SolarSoil account and profile settings.';
$body_class = 'profile-page';

// Get user data from profile handler
$user_data = ProfileHandler::getCurrentUser();

// If user data is not found, redirect to login
if (!$user_data) {
    header('Location: ../../index.php?error=UserDataNotFound');
    exit;
}

// Capture page content for layout
ob_start();
?>
<!-- TODO: Add checker for confirming new password -->
<!-- Profile Content -->
<div class="profile-container">
    <div class="container py-5">
        <div class="row">
            <!-- Profile Info Card -->
            <div class="col-lg-8 mx-auto">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user-astronaut fa-3x"></i>
                        </div>
                        <div class="profile-info">
                            <h2 class="profile-name">
                                <?php echo htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']); ?>
                            </h2>
                            <p class="profile-usertype">
                                <i
                                    class="fas fa-user-tag me-2"></i><?php echo htmlspecialchars($user_data['usertype']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="profile-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <label class="profile-label">
                                        <i class="fas fa-user me-2"></i>First Name
                                    </label>
                                    <div class="profile-value">
                                        <?php echo htmlspecialchars($user_data['first_name']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <label class="profile-label">
                                        <i class="fas fa-user me-2"></i>Last Name
                                    </label>
                                    <div class="profile-value">
                                        <?php echo htmlspecialchars($user_data['last_name']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <label class="profile-label">
                                        <i class="fas fa-at me-2"></i>Username
                                    </label>
                                    <div class="profile-value">
                                        <?php echo htmlspecialchars($user_data['username']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <label class="profile-label">
                                        <i class="fas fa-map-marker-alt me-2"></i>Address
                                    </label>
                                    <div class="profile-value">
                                        <?php echo htmlspecialchars($user_data['address'] ?? 'Not provided'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="profile-field">
                                    <label class="profile-label">
                                        <i class="fas fa-calendar me-2"></i>Member Since
                                    </label>
                                    <div class="profile-value">
                                        <?php 
                                        if (!empty($user_data['join_date'])) {
                                            echo date('F j, Y', strtotime($user_data['join_date'])); 
                                        } else {
                                            echo 'Unknown';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card-footer">
                        <div class="d-flex gap-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                <i class="fas fa-key me-2"></i>Change Password
                            </button>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteAccountModal">
                                <i class="fas fa-user-times me-2"></i>Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

include_once '../../components/profile/delete-account-modal.component.php';
include_once '../../components/profile/profile-edit-modal.component.php';
include_once '../../components/profile/change-password-modal.component.php';
// Use the shared page layout
include '../../layouts/page-layout.php';
?>