<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if not logged in
if (!is_logged_in()) {
    set_error('Please login to view order confirmation');
    redirect('login.php');
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id === 0) {
    set_error('Invalid order');
    redirect('index.php');
}

// Get order details
$order_query = "SELECT o.*, u.name, u.email, u.phone FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = $order_id AND o.user_id = " . $_SESSION['user_id'];
$order_result = mysqli_query($conn, $order_query);

if (!mysqli_num_rows($order_result)) {
    set_error('Order not found');
    redirect('index.php');
}

$order = mysqli_fetch_assoc($order_result);

// Get order items
$items_query = "SELECT oi.*, p.name, p.image FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = $order_id";
$items_result = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - <?php echo SITE_NAME; ?></title>
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
            <!-- Success Message -->
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="font-size: 5rem; margin-bottom: 1rem;">✅</div>
                <h1 style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 1rem;">Order Confirmed!</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem;">Thank you for your order. We'll send you a confirmation email shortly.</p>
            </div>

            <!-- Order Details -->
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="background: var(--light-color); padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem;">Order Information</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Order Number</p>
                            <p style="font-weight: 600; font-size: 1.1rem;"><?php echo $order['order_number']; ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Order Date</p>
                            <p style="font-weight: 600;"><?php echo date('M j, Y, g:i A', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Payment Method</p>
                            <p style="font-weight: 600;"><?php echo ucfirst($order['payment_method']); ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Order Status</p>
                            <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div style="background: var(--light-color); padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem;">Customer Information</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Name</p>
                            <p style="font-weight: 600;"><?php echo htmlspecialchars($order['name']); ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Email</p>
                            <p style="font-weight: 600;"><?php echo htmlspecialchars($order['email']); ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Phone</p>
                            <p style="font-weight: 600;"><?php echo htmlspecialchars($order['phone'] ?: 'N/A'); ?></p>
                        </div>
                    </div>
                    
                    <?php if ($order['shipping_address']): ?>
                        <div style="margin-top: 1.5rem;">
                            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">Shipping Address</p>
                            <p style="font-weight: 600;"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Order Items -->
                <div style="background: var(--light-color); padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem;">Order Items</h3>
                    <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                        <div style="display: flex; gap: 1rem; padding: 1rem; background: white; border-radius: 10px; margin-bottom: 1rem;">
                            <img src="<?php echo $item['image'] ?: 'assets/images/placeholder.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            <div style="flex: 1;">
                                <h4 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p style="color: var(--text-secondary); font-size: 0.9rem;">Quantity: <?php echo $item['quantity']; ?></p>
                                <p style="color: var(--primary-color); font-weight: 600;"><?php echo format_price($item['price']); ?> each</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-weight: 600; font-size: 1.1rem;"><?php echo format_price($item['total']); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Order Summary -->
                <div style="background: var(--light-color); padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
                    <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Subtotal:</span>
                            <span><?php echo format_price($order['total_amount']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Shipping:</span>
                            <span><?php echo format_price($order['final_amount'] - $order['total_amount']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Tax:</span>
                            <span><?php echo format_price(($order['final_amount'] - $order['total_amount']) * 0.18); ?></span>
                        </div>
                        <div style="border-top: 2px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 600;">
                                <span>Total:</span>
                                <span style="color: var(--primary-color);"><?php echo format_price($order['final_amount']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem;">What's Next?</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📧</div>
                            <h4 style="margin-bottom: 0.5rem;">Confirmation Email</h4>
                            <p style="font-size: 0.9rem; opacity: 0.9;">Check your email for order details</p>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📦</div>
                            <h4 style="margin-bottom: 0.5rem;">Order Processing</h4>
                            <p style="font-size: 0.9rem; opacity: 0.9;">We'll prepare your items</p>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🚚</div>
                            <h4 style="margin-bottom: 0.5rem;">Fast Delivery</h4>
                            <p style="font-size: 0.9rem; opacity: 0.9;">Get your order soon</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    <a href="profile.php#orders" class="btn btn-secondary">View My Orders</a>
                </div>
            </div>
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
