<?php
// Helper Functions for POPA E-commerce

// Function to sanitize input
function clean($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

// Function to hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to display success message
function set_success($message) {
    $_SESSION['success'] = $message;
}

// Function to display error message
function set_error($message) {
    $_SESSION['error'] = $message;
}

// Function to get success message
function get_success() {
    if (isset($_SESSION['success'])) {
        $message = $_SESSION['success'];
        unset($_SESSION['success']);
        return $message;
    }
    return '';
}

// Function to get error message
function get_error() {
    if (isset($_SESSION['error'])) {
        $message = $_SESSION['error'];
        unset($_SESSION['error']);
        return $message;
    }
    return '';
}

// Function to generate unique order number
function generate_order_number() {
    return 'POP' . date('Y') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

// Function to format price
function format_price($price) {
    return CURRENCY . number_format($price, 2);
}

// Function to get cart count
function get_cart_count() {
    if (!is_logged_in()) {
        return 0;
    }
    
    global $conn;
    $user_id = $_SESSION['user_id'];
    $query = "SELECT SUM(quantity) as count FROM cart WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] ?? 0;
}

// Function to get setting value
function get_setting($key, $default = '') {
    global $conn;
    $query = "SELECT setting_value FROM settings WHERE setting_key = '$key'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['setting_value'];
    }
    return $default;
}

// Function to upload image
function upload_image($file, $upload_dir = 'uploads/') {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_name = time() . '_' . basename($file['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $target_file;
        }
    }
    return false;
}

// Function to paginate
function paginate($query, $limit = 10, $page = 1) {
    global $conn;
    $offset = ($page - 1) * $limit;
    
    // Get total records
    $count_query = str_replace('SELECT *', 'SELECT COUNT(*) as total', $query);
    $count_result = mysqli_query($conn, $count_query);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    // Get paginated results
    $paginated_query = $query . " LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $paginated_query);
    
    $total_pages = ceil($total / $limit);
    
    return [
        'data' => $result,
        'total' => $total,
        'per_page' => $limit,
        'current_page' => $page,
        'total_pages' => $total_pages
    ];
}

// Function to send email (basic implementation)
function send_email($to, $subject, $message) {
    $headers = "From: " . ADMIN_EMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Function to validate email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to generate CSRF token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function to verify CSRF token
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
