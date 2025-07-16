<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'SolarSoil'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">

    <!-- Additional meta tags -->
    <meta name="description"
        content="<?php echo isset($page_description) ? $page_description : 'SolarSoil - Sustainable Agriculture Solutions'; ?>">
    <meta name="keywords" content="solar, soil, agriculture, sustainable, farming">
    <meta name="author" content="SolarSoil Team">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../../index.php">
                <i class="fas fa-leaf me-2"></i>SolarSoil
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($body_class) && $body_class === 'admin-page'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../Logout/index.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../Home/index.php"><i class="fas fa-home me-1"></i>Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Shop/index.php"><i class="fas fa-store me-1"></i>Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Order/index.php"><i class="fas fa-box me-1"></i>Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../About/index.php"><i class="fas fa-info-circle me-1"></i>About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Cart/index.php">
                                <i class="fas fa-shopping-cart me-1"></i>Cart
                                <span id="cart-badge" class="badge bg-success ms-1">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Profile/index.php"><i class="fas fa-user me-1"></i>Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Logout/index.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>