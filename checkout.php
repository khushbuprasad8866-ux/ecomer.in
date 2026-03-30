<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if not logged in
if (!is_logged_in()) {
    set_error('Please login to checkout');
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get cart items
$cart_query = "SELECT c.*, p.name, p.price, p.discount_price, p.image, p.stock_quantity 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = $user_id AND p.status = 'active'";
$cart_result = mysqli_query($conn, $cart_query);

// Check if cart is empty
if (mysqli_num_rows($cart_result) === 0) {
    set_error('Your cart is empty');
    redirect('cart.php');
}

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

// Get shipping fee and tax
$shipping_fee = (float)get_setting('shipping_fee', 50);
$tax_rate = (float)get_setting('tax_rate', 18) / 100;
$tax_amount = $total_amount * $tax_rate;
$final_total = $total_amount + $shipping_fee + $tax_amount;

// Get user details
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user = mysqli_fetch_assoc(mysqli_query($conn, $user_query));

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = clean($_POST['shipping_address']);
    $billing_address = clean($_POST['billing_address']);
    $payment_method = clean($_POST['payment_method']);
    $notes = clean($_POST['notes']);
    
    // Validate
    if (empty($shipping_address) || empty($payment_method)) {
        set_error('Please fill in all required fields');
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Create order
            $order_number = generate_order_number();
            $insert_order_query = "INSERT INTO orders (user_id, order_number, total_amount, final_amount, shipping_address, billing_address, notes, payment_method) 
                                 VALUES ($user_id, '$order_number', $total_amount, $final_total, '$shipping_address', '$billing_address', '$notes', '$payment_method')";
            
            if (mysqli_query($conn, $insert_order_query)) {
                $order_id = mysqli_insert_id($conn);
                
                // Add order items
                foreach ($cart_items as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    $price = $item['discount_price'] ?: $item['price'];
                    $subtotal = $price * $quantity;
                    
                    $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price, total) 
                                        VALUES ($order_id, $product_id, $quantity, $price, $subtotal)";
                    mysqli_query($conn, $insert_item_query);
                    
                    // Update product stock
                    $update_stock_query = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE id = $product_id";
                    mysqli_query($conn, $update_stock_query);
                }
                
                // Clear cart
                $clear_cart_query = "DELETE FROM cart WHERE user_id = $user_id";
                mysqli_query($conn, $clear_cart_query);
                
                // Commit transaction
                mysqli_commit($conn);
                
                set_success('Order placed successfully! Order number: ' . $order_number);
                redirect('order_confirmation.php?id=' . $order_id);
            } else {
                throw new Exception('Failed to create order');
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            set_error('Order failed. Please try again.');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo SITE_NAME; ?></title>
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
                            <a href="cart.php">
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
                <h1 style="font-size: 2rem; color: var(--text-primary); margin-bottom: 0.5rem;">Checkout</h1>
                <p style="color: var(--text-secondary);">Complete your order details</p>
            </div>

            <form method="POST" id="checkout-form">
                <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
                    <!-- Checkout Form -->
                    <div>
                        <!-- User Information -->
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                            <h3 style="margin-bottom: 1.5rem;">Contact Information</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                                <div style="grid-column: 1 / -1;">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?: ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                            <h3 style="margin-bottom: 1.5rem;">Shipping Address</h3>
                            <div class="form-group">
                                <label class="form-label">Shipping Address *</label>
                                <textarea name="shipping_address" class="form-control" rows="3" required><?php echo htmlspecialchars($user['address'] ?: ''); ?></textarea>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                            <h3 style="margin-bottom: 1.5rem;">Billing Address</h3>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                    <input type="checkbox" id="same_as_shipping" checked style="width: auto;">
                                    <span>Same as shipping address</span>
                                </label>
                                <textarea name="billing_address" id="billing_address" class="form-control" rows="3" style="display: none;"><?php echo htmlspecialchars($user['address'] ?: ''); ?></textarea>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                            <h3 style="margin-bottom: 1.5rem;">Payment Method</h3>
                            <div class="form-group">
                                <?php if (get_setting('cod_enabled') == '1'): ?>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; cursor: pointer;">
                                        <input type="radio" name="payment_method" value="cod" required>
                                        <span>💵 Cash on Delivery</span>
                                    </label>
                                <?php endif; ?>
                                
                                <?php if (get_setting('upi_enabled') == '1'): ?>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; cursor: pointer;">
                                        <input type="radio" name="payment_method" value="upi" required>
                                        <span>📱 UPI Payment</span>
                                    </label>
                                <?php endif; ?>
                                
                                <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; cursor: pointer;">
                                    <input type="radio" name="payment_method" value="card" required>
                                    <span>💳 Credit/Debit Card</span>
                                </label>
                                
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="radio" name="payment_method" value="netbanking" required>
                                    <span>🏦 Net Banking</span>
                                </label>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px;">
                            <h3 style="margin-bottom: 1.5rem;">Order Notes (Optional)</h3>
                            <div class="form-group">
                                <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions for your order..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div>
                        <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; position: sticky; top: 100px;">
                            <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
                            
                            <!-- Cart Items -->
                            <div style="margin-bottom: 1.5rem;">
                                <?php foreach ($cart_items as $item): ?>
                                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                        <img src="<?php echo $item['image'] ?: 'assets/images/placeholder.jpg'; ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        <div style="flex: 1;">
                                            <p style="font-weight: 500; font-size: 0.9rem;"><?php echo htmlspecialchars($item['name']); ?></p>
                                            <p style="color: var(--text-secondary); font-size: 0.8rem;">Qty: <?php echo $item['quantity']; ?></p>
                                        </div>
                                        <p style="font-weight: 600;"><?php echo format_price($item['subtotal']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Price Breakdown -->
                            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Subtotal:</span>
                                    <span><?php echo format_price($total_amount); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Shipping:</span>
                                    <span><?php echo format_price($shipping_fee); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Tax:</span>
                                    <span><?php echo format_price($tax_amount); ?></span>
                                </div>
                            </div>
                            
                            <div style="border-top: 2px solid var(--border-color); padding-top: 1rem; margin-bottom: 1.5rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 600;">
                                    <span>Total:</span>
                                    <span><?php echo format_price($final_total); ?></span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                Place Order
                            </button>
                            
                            <div style="text-align: center; font-size: 0.9rem; color: var(--text-secondary); margin-top: 1rem;">
                                <p>🔒 Secure checkout powered by POPA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    <script>
        // Handle billing address checkbox
        document.getElementById('same_as_shipping').addEventListener('change', function() {
            const billingAddress = document.getElementById('billing_address');
            const shippingAddress = document.querySelector('textarea[name="shipping_address"]');
            
            if (this.checked) {
                billingAddress.style.display = 'none';
                billingAddress.value = shippingAddress.value;
            } else {
                billingAddress.style.display = 'block';
            }
        });
        
        // Form validation
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            
            if (!paymentMethod) {
                e.preventDefault();
                showMessage('Please select a payment method', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<span class="loading"></span> Processing...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
