<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if not logged in
if (!is_logged_in()) {
    set_error('Please login to continue');
    redirect('login.php');
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id === 0) {
    set_error('Invalid order');
    redirect('index.php');
}

// Get order details
$order_query = "SELECT * FROM orders WHERE id = $order_id AND user_id = " . $_SESSION['user_id'];
$order_result = mysqli_query($conn, $order_query);

if (!mysqli_num_rows($order_result)) {
    set_error('Order not found');
    redirect('index.php');
}

$order = mysqli_fetch_assoc($order_result);

// Process payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = clean($_POST['transaction_id']);
    $payment_method = clean($_POST['payment_method']);
    
    if (empty($transaction_id)) {
        set_error('Please enter transaction ID');
    } else {
        // Update order status
        $update_order_query = "UPDATE orders SET status = 'confirmed', payment_status = 'paid' WHERE id = $order_id";
        
        if (mysqli_query($conn, $update_order_query)) {
            // Add payment record
            $insert_payment_query = "INSERT INTO payments (order_id, transaction_id, payment_method, amount, status) 
                                   VALUES ($order_id, '$transaction_id', '$payment_method', {$order['final_amount']}, 'success')";
            mysqli_query($conn, $insert_payment_query);
            
            set_success('Payment successful! Your order is confirmed.');
            redirect('order_confirmation.php?id=' . $order_id);
        } else {
            set_error('Payment verification failed');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPI Payment - <?php echo SITE_NAME; ?></title>
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

            <div style="max-width: 600px; margin: 0 auto;">
                <!-- Payment Header -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h1 style="font-size: 2rem; color: var(--text-primary); margin-bottom: 0.5rem;">UPI Payment</h1>
                    <p style="color: var(--text-secondary);">Complete your payment using UPI</p>
                </div>

                <!-- Order Summary -->
                <div style="background: var(--light-color); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Order Summary</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Order Number:</span>
                        <strong><?php echo $order['order_number']; ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Total Amount:</span>
                        <strong style="color: var(--primary-color); font-size: 1.2rem;"><?php echo format_price($order['final_amount']); ?></strong>
                    </div>
                </div>

                <!-- UPI Payment Form -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; text-align: center;">Scan & Pay</h3>
                    
                    <!-- QR Code -->
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="width: 200px; height: 200px; background: white; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 6rem;">
                            📱
                        </div>
                        <p style="margin-top: 1rem; font-size: 0.9rem;">Scan this QR code with your UPI app</p>
                    </div>
                    
                    <!-- UPI ID -->
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <p style="margin-bottom: 0.5rem;">Or pay directly to:</p>
                        <div style="background: rgba(255, 255, 255, 0.2); padding: 1rem; border-radius: 10px; font-family: monospace; font-size: 1.2rem;">
                            <?php echo get_setting('upi_id', 'popa@ybl'); ?>
                        </div>
                        <button onclick="copyUPIId()" class="btn btn-secondary" style="margin-top: 1rem; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3);">
                            📋 Copy UPI ID
                        </button>
                    </div>
                    
                    <!-- Payment Steps -->
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 10px;">
                        <h4 style="margin-bottom: 1rem;">How to Pay:</h4>
                        <ol style="margin: 0; padding-left: 1.5rem;">
                            <li style="margin-bottom: 0.5rem;">Scan the QR code or copy the UPI ID</li>
                            <li style="margin-bottom: 0.5rem;">Enter the amount: <?php echo format_price($order['final_amount']); ?></li>
                            <li style="margin-bottom: 0.5rem;">Complete the payment in your UPI app</li>
                            <li>Enter the transaction ID below to confirm</li>
                        </ol>
                    </div>
                </div>

                <!-- Payment Confirmation Form -->
                <div style="background: var(--light-color); padding: 2rem; border-radius: 15px;">
                    <h3 style="margin-bottom: 1.5rem;">Confirm Payment</h3>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Transaction ID *</label>
                            <input type="text" name="transaction_id" class="form-control" 
                                   placeholder="Enter your UPI transaction ID" required>
                            <small style="color: var(--text-secondary);">
                                You can find this in your UPI app payment history
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select UPI App</option>
                                <option value="google_pay">Google Pay</option>
                                <option value="phonepe">PhonePe</option>
                                <option value="paytm">Paytm</option>
                                <option value="bhim">BHIM</option>
                                <option value="other">Other UPI App</option>
                            </select>
                        </div>
                        
                        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                            <a href="order_confirmation.php?id=<?php echo $order_id; ?>" class="btn btn-secondary" style="flex: 1;">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" style="flex: 1;">
                                Confirm Payment
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Support -->
                <div style="text-align: center; margin-top: 2rem; padding: 1rem; background: var(--light-color); border-radius: 10px;">
                    <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">Need help with payment?</p>
                    <p style="color: var(--primary-color); font-weight: 600;">
                        📞 <?php echo get_setting('site_phone', '+91 9876543210'); ?>
                    </p>
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
    <script>
        function copyUPIId() {
            const upiId = '<?php echo get_setting('upi_id', 'popa@ybl'); ?>';
            navigator.clipboard.writeText(upiId).then(function() {
                showMessage('UPI ID copied to clipboard!', 'success');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = upiId;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showMessage('UPI ID copied to clipboard!', 'success');
            });
        }
        
        // Auto-refresh order status every 30 seconds
        setInterval(function() {
            // Check if payment is already processed
            fetch('check_payment_status.php?order_id=<?php echo $order_id; ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.paid) {
                        showMessage('Payment received! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = 'order_confirmation.php?id=<?php echo $order_id; ?>';
                        }, 2000);
                    }
                })
                .catch(error => console.log('Checking payment status...'));
        }, 30000);
    </script>
</body>
</html>
