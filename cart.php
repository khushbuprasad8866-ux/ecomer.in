<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if not logged in
if (!is_logged_in()) {
    set_error('Please login to view your cart');
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get cart items
$cart_query = "SELECT c.*, p.name, p.price, p.discount_price, p.image, p.stock_quantity 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = $user_id AND p.status = 'active'";
$cart_result = mysqli_query($conn, $cart_query);

// Calculate totals
$total_amount = 0;
$cart_items = [];
while ($item = mysqli_fetch_assoc($cart_result)) {
    $price = $item['discount_price'] ?: $item['price'];
    $item_total = $price * $item['quantity'];
    $item['subtotal'] = $item_total;
    $total_amount += $item_total;
    $cart_items[] = $item;
}

// Get shipping fee
$shipping_fee = (float)get_setting('shipping_fee', 50);
$final_total = $total_amount + ($total_amount > 0 ? $shipping_fee : 0);

// Process cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        
        if ($quantity > 0) {
            $update_query = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
            mysqli_query($conn, $update_query);
            set_success('Cart updated successfully');
        }
        redirect('cart.php');
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $product_id = (int)$_POST['product_id'];
        $delete_query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $delete_query);
        set_success('Item removed from cart');
        redirect('cart.php');
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'clear') {
        $clear_query = "DELETE FROM cart WHERE user_id = $user_id";
        mysqli_query($conn, $clear_query);
        set_success('Cart cleared successfully');
        redirect('cart.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">POPA</a>
                <nav>
                    <ul class="nav-menu">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <?php if (is_logged_in()): ?>
                            <li><a href="profile.php">Profile</a></li>
                            <?php if (is_admin()): ?>
                                <li><a href="admin/">Admin</a></li>
                            <?php endif; ?>
                            <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                        <li class="cart-icon">
                            <a href="cart.php" class="active">
                                🛒 Cart
                                <?php $cart_count = get_cart_count(); if ($cart_count > 0): ?>
                                    <span class="cart-count"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main style="background: white; padding: 2rem 0; min-height: 60vh;">
        <div class="container">
            <!-- Messages -->
            <?php $success = get_success(); if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php $error = get_error(); if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Page Header -->
            <div style="margin-bottom: 2rem;">
                <h1 style="font-size: 2.5rem; color: var(--text-primary); margin-bottom: 0.5rem;">Shopping Cart</h1>
                <p style="color: var(--text-secondary);">
                    <?php echo count($cart_items); ?> item(s) in your cart
                </p>
            </div>

            <?php if (!empty($cart_items)): ?>
                <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
                    <!-- Cart Items -->
                    <div>
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px;">
                            <?php foreach ($cart_items as $item): ?>
                                <div id="cart-item-<?php echo $item['product_id']; ?>" 
                                     style="display: flex; gap: 1rem; padding: 1rem; background: white; border-radius: 10px; margin-bottom: 1rem;">
                                    
                                    <!-- Product Image -->
                                    <img src="<?php echo $item['image'] ?: 'assets/images/placeholder.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                                    
                                    <!-- Product Details -->
                                    <div style="flex: 1;">
                                        <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </h3>
                                        <p style="color: var(--primary-color); font-weight: 600; margin-bottom: 0.5rem;">
                                            <?php echo format_price($item['discount_price'] ?: $item['price']); ?>
                                        </p>
                                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                                            Stock: <?php echo $item['stock_quantity']; ?>
                                        </p>
                                        
                                        <!-- Quantity Controls -->
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            <form method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <button type="button" onclick="updateQuantity(<?php echo $item['product_id']; ?>, -1)" 
                                                        style="width: 30px; height: 30px; border: 1px solid var(--border-color); background: white; border-radius: 5px; cursor: pointer;">
                                                    -
                                                </button>
                                                <input type="number" id="quantity-<?php echo $item['product_id']; ?>" 
                                                       name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                       min="1" max="<?php echo $item['stock_quantity']; ?>" 
                                                       style="width: 60px; text-align: center; border: 1px solid var(--border-color); border-radius: 5px; padding: 0.25rem;">
                                                <button type="button" onclick="updateQuantity(<?php echo $item['product_id']; ?>, 1)" 
                                                        style="width: 30px; height: 30px; border: 1px solid var(--border-color); background: white; border-radius: 5px; cursor: pointer;">
                                                    +
                                                </button>
                                                <button type="submit" style="padding: 0.25rem 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 5px; cursor: pointer;">
                                                    Update
                                                </button>
                                            </form>
                                            
                                            <form method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.9rem;">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal -->
                                    <div style="text-align: right;">
                                        <p style="font-size: 0.9rem; color: var(--text-secondary);">Subtotal</p>
                                        <p style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">
                                            <?php echo format_price($item['subtotal']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Clear Cart -->
                            <div style="text-align: center; margin-top: 2rem;">
                                <form method="POST" onsubmit="return confirm('Clear entire cart?')">
                                    <input type="hidden" name="action" value="clear">
                                    <button type="submit" class="btn btn-danger">
                                        Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div>
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; position: sticky; top: 100px;">
                            <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Order Summary</h3>
                            
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Subtotal:</span>
                                    <span><?php echo format_price($total_amount); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Shipping:</span>
                                    <span><?php echo format_price($total_amount > 0 ? $shipping_fee : 0); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Tax (18%):</span>
                                    <span><?php echo format_price($total_amount * 0.18); ?></span>
                                </div>
                            </div>
                            
                            <div style="border-top: 2px solid var(--border-color); padding-top: 1rem; margin-bottom: 1.5rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 600;">
                                    <span>Total:</span>
                                    <span class="cart-total"><?php echo format_price($final_total + ($total_amount * 0.18)); ?></span>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <a href="products.php" class="btn btn-secondary" style="width: 100%; margin-bottom: 0.5rem;">
                                    Continue Shopping
                                </a>
                                <a href="checkout.php" class="btn btn-primary" style="width: 100%;">
                                    Proceed to Checkout
                                </a>
                            </div>
                            
                            <div style="text-align: center; font-size: 0.9rem; color: var(--text-secondary);">
                                <p>Secure checkout powered by POPA</p>
                                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem;">
                                    <span>🔒</span>
                                    <span>💳</span>
                                    <span>📱</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty Cart -->
                <div style="text-align: center; padding: 4rem; background: var(--light-color); border-radius: 15px;">
                    <div style="font-size: 5rem; margin-bottom: 2rem;">🛒</div>
                    <h2 style="color: var(--text-primary); margin-bottom: 1rem;">Your cart is empty</h2>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                        Looks like you haven't added any products to your cart yet.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <a href="products.php" class="btn btn-primary">
                            Start Shopping
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            Back to Home
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About POPA</h3>
                    <p>Your premium shopping destination for quality products at amazing prices.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="products.php">All Products</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="track_order.php">Track Order</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p>📧 <?php echo get_setting('site_email', 'contact@popa.com'); ?></p>
                    <p>📞 <?php echo get_setting('site_phone', '+91 9876543210'); ?></p>
                    <p>📍 123 Shopping Street, India</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
