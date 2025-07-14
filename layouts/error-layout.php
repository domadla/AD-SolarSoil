<?php
if (!isset($error_code))
    $error_code = 'Error';
if (!isset($error_title))
    $error_title = 'An Error Occurred';
if (!isset($error_message))
    $error_message = 'Something went wrong.';
if (!isset($error_button_url))
    $error_button_url = '/layouts/login-layout.php';
if (!isset($error_button_text))
    $error_button_text = 'Back to Home';
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($error_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/error.css">
</head>

<body>
    <div class="stars"></div>
    <div class="stars2"></div>
    <div class="stars3"></div>
    <div class="container text-center py-5" style="position:relative; z-index:1;">
        <h1 class="display-1 text-danger"><?php echo htmlspecialchars($error_code); ?></h1>
        <h2 class="mb-4"><?php echo htmlspecialchars($error_title); ?></h2>
        <p class="lead mb-4"><?php echo htmlspecialchars($error_message); ?></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>