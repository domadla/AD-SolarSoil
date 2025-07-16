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
if (isset($_GET['error'])) {
    $message_type = 'danger';
    $error_code = $_GET['error'];
    switch ($error_code) {
        case 'InvalidPassword':
            $message = 'Invalid password. Please try again.';
            break;
        case 'UsernameAlreadyTaken':
            $message = 'That username is already taken. Please choose another.';
            break;
        case 'DatabaseError':
            $message = 'An error occurred while processing your request. Please try again later.';
            break;
        case 'PasswordComplexityFailed':
            $message = 'Password must be at least 6 characters long and include one uppercase letter (A-Z), one lowercase letter (a-z), one number (0-9)), and one special character (!@#$%^&*).';
            break;
       case 'AllFieldsRequired':
            $message = 'Please correct the errors on the form and try again.';
            break;
        case 'PasswordsDoNotMatch':
            $message = 'New password does not match.';
            break;
    }
}
if (isset($_GET['success'])) {
    $message_type = 'success';
    $success_code = $_GET['success'];
    switch ($success_code) {
        case 'UserUpdatedSuccessfully':
            $message = 'Profile has been successfully updated.';
            break;
        case 'PasswordUpdatedSuccessfully':
            $message = 'Password has been successfully updated.';
            break;
        case 'AccountDeletedSuccessfully':
            $message = 'Account has been successfully deleted';
            break;
    }
}

// Capture page content for layout
ob_start();
?>
<!-- Profile Content -->
<div class="profile-container">
    <div class="container py-5">
        <div class="row">
            <!-- Profile Info Card -->
              <!-- Alert Container -->
                    <div id="alert-container">
                        <?php if (isset($message) && $message): ?>
                            <div class="alert alert-<?php echo $message_type === 'info' ? 'primary' : $message_type; ?>">
                                <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>
                    </div>
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
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal" data-id="<?php echo htmlspecialchars($user_data['user_id']); ?>">
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