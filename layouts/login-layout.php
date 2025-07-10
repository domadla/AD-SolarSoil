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
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Meta tags -->
    <meta name="description"
        content="<?php echo isset($page_description) ? $page_description : 'SolarSoil - Sustainable Agriculture Solutions'; ?>">
    <meta name="keywords" content="solar, soil, agriculture, sustainable, farming, space, interstellar">
    <meta name="author" content="SolarSoil Team">
</head>

<body class="<?php echo isset($body_class) ? $body_class : ''; ?>">

    <!-- Animated Star Field Background -->
    <div class="stars"></div>
    <div class="stars2"></div>
    <div class="stars3"></div>
    <div class="stars4"></div>
    <div class="stars5"></div>

    <!-- Main Content -->
    <?php echo $content; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>

    <!-- Additional Scripts -->
    <?php if (isset($additional_scripts)): ?>
        <?php echo $additional_scripts; ?>
    <?php endif; ?>

</body>

</html>
