<?php
require_once '../bootstrap.php';
require_once UTILS_PATH . 'session.util.php';

session_start();

if (!isUserLoggedIn()) {
    header('Location: /index.php');
    exit();
}

require_once COMPONENTS_PATH . 'templates/header.component.php';
?>

<head>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
        }

        .profile-title {
            color: var(--color-primary);
            margin-bottom: 2rem;
            text-align: center;
        }

        .profile-info {
            margin-bottom: 1rem;
        }

        .profile-label {
            font-weight: 600;
            color: var(--color-text-light);
            margin-bottom: 0.5rem;
        }

        .profile-value {
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: var(--color-text-light);
        }
    </style>
</head>

<div class="container">
    <div class="profile-container">
        <h1 class="profile-title">User Profile</h1>

        <div class="profile-info">
            <div class="profile-label">First Name:</div>
            <div class="profile-value"><?php echo htmlspecialchars(getUserFirstName()); ?></div>
        </div>

        <div class="profile-info">
            <div class="profile-label">Last Name:</div>
            <div class="profile-value"><?php echo htmlspecialchars(getUserLastName()); ?></div>
        </div>

        <div class="profile-info">
            <div class="profile-label">Email:</div>
            <div class="profile-value"><?php echo htmlspecialchars(getUserEmail()); ?></div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/pages/home/index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>

<?php require_once COMPONENTS_PATH . 'templates/footer.component.php'; ?>