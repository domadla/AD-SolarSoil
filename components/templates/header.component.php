<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="/assets/img/Logo.png">
    <title>SolarSoil - Interstellar Plant Store</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header class="header" role="banner">
        <div class="container nav-container">
            <a href="/pages/home/index.php" class="logo" id="home">
                SolarSoil
            </a>
            <nav class="nav" id="nav-menu" aria-label="Primary Navigation">
                <ul class="nav-list">
                    <li><a href="/pages/home/index.php" class="nav-link">Home</a></li>
                    <li><a href="/pages/shop/index.php" class="nav-link">Shop</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                </ul>
            </nav>
            <?php
            require_once UTILS_PATH . 'session.util.php';

            if (isUserLoggedIn()): ?>
                <div class="user-dropdown">
                    <a href="#" class="user-link" onclick="toggleUserDropdown()">
                        <span class="user-icon">&#128100;</span>
                        <?php echo htmlspecialchars(getUserFirstName()); ?>
                        <span class="dropdown-arrow">&#9662;</span>
                    </a>
                    <div class="user-dropdown-menu" id="userDropdown">
                        <a href="/auth/profile.php" class="dropdown-item">Profile</a>
                        <a href="/auth/logout.php" class="dropdown-item">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/index.php" class="user-link">
                    <span class="user-icon">&#128100;</span>
                    Login
                </a>
            <?php endif; ?>


            </nav>
            <button id="nav-toggle" class="nav-toggle" aria-controls="nav-menu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="hamburger"></span>
            </button>
        </div>
    </header>
    <main></main>