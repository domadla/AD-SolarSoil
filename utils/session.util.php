<?php
/**
 * Session utility functions
 */

/**
 * Start session safely - only if not already started and headers not sent
 */
function startSessionSafely()
{
    if (session_status() == PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
}

/**
 * Check if user is logged in
 */
function isUserLoggedIn()
{
    startSessionSafely();
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

/**
 * Get logged in user's first name
 */
function getUserFirstName()
{
    startSessionSafely();
    return $_SESSION['user_first_name'] ?? null;
}

/**
 * Get logged in user's last name
 */
function getUserLastName()
{
    startSessionSafely();
    return $_SESSION['user_last_name'] ?? null;
}

/**
 * Get logged in user's name (for backward compatibility)
 */
function getUserName()
{
    startSessionSafely();
    return $_SESSION['user_first_name'] ?? null;
}

/**
 * Get logged in user's email
 */
function getUserEmail()
{
    startSessionSafely();
    return $_SESSION['user_email'] ?? null;
}

/**
 * Get logged in user's full name
 */
function getUserFullName()
{
    startSessionSafely();
    $firstName = $_SESSION['user_first_name'] ?? '';
    $lastName = $_SESSION['user_last_name'] ?? '';
    return trim($firstName . ' ' . $lastName);
}
?>