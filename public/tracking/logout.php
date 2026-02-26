<?php
/**
 * Logout Page
 * Handles logout untuk admin dan client
 */

session_start();
require_once __DIR__ . '/helpers.php';

// Determine redirect page based on user type
$redirectPage = isset($_SESSION['admin']) ? 'admin_login.php' : 'login.php';

// Destroy all session data
session_unset();
session_destroy();

// Redirect to appropriate login page
redirectTo($redirectPage);
