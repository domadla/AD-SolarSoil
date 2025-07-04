<?php
ob_start();

require_once '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_clean();
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        session_start();

        $emailParts = explode('@', $email);
        $firstName = ucfirst($emailParts[0]);
        $lastName = 'User';

        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_first_name'] = $firstName;
        $_SESSION['user_last_name'] = $lastName;
        $_SESSION['user_email'] = $email;

        header('Location: /pages/home/index.php');
        exit();
    } else {
        header('Location: /index.php?error=invalid_credentials');
        exit();
    }
} else {
    header('Location: /index.php');
    exit();
}
?>