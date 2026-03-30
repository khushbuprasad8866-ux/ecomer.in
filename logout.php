<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Destroy session
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to home with success message
set_success('You have been logged out successfully.');
redirect('index.php');
?>
