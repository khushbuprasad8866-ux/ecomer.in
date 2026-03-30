<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Please login to add products to cart']);
    exit;
}

// Process add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];
    
    // Validate
    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
        exit;
    }
    
    // Check if product exists and has enough stock
    $product_query = "SELECT * FROM products WHERE id = $product_id AND status = 'active'";
    $product_result = mysqli_query($conn, $product_query);
    
    if (!mysqli_num_rows($product_result)) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $product = mysqli_fetch_assoc($product_result);
    
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit;
    }
    
    // Check if item already in cart
    $cart_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $cart_result = mysqli_query($conn, $cart_query);
    
    if (mysqli_num_rows($cart_result) > 0) {
        // Update quantity
        $cart_item = mysqli_fetch_assoc($cart_result);
        $new_quantity = $cart_item['quantity'] + $quantity;
        
        if ($product['stock_quantity'] < $new_quantity) {
            echo json_encode(['success' => false, 'message' => 'Cannot add more items than available stock']);
            exit;
        }
        
        $update_query = "UPDATE cart SET quantity = $new_quantity WHERE user_id = $user_id AND product_id = $product_id";
        if (mysqli_query($conn, $update_query)) {
            $cart_count = get_cart_count();
            echo json_encode(['success' => true, 'message' => 'Cart updated successfully', 'cart_count' => $cart_count]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        }
    } else {
        // Add new item
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        if (mysqli_query($conn, $insert_query)) {
            $cart_count = get_cart_count();
            echo json_encode(['success' => true, 'message' => 'Product added to cart', 'cart_count' => $cart_count]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
