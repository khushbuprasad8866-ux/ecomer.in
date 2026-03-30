<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'other');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$create_db_query = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $create_db_query)) {
    // Select the database
    mysqli_select_db($conn, DB_NAME);
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Site Configuration
define('SITE_NAME', 'POPA');
define('SITE_URL', 'http://localhost/other/popa-website/');
define('ADMIN_EMAIL', 'admin@popa.com');
define('CURRENCY', '₹');

// Session Configuration
session_start();

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time Zone
date_default_timezone_set('Asia/Kolkata');
?>
