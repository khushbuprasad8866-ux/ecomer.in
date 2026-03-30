<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_error('Access denied. Admin privileges required.');
    redirect('../login.php');
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_settings'])) {
        $settings = [
            'site_name' => clean($_POST['site_name']),
            'site_email' => clean($_POST['site_email']),
            'site_phone' => clean($_POST['site_phone']),
            'upi_id' => clean($_POST['upi_id']),
            'upi_enabled' => isset($_POST['upi_enabled']) ? '1' : '0',
            'cod_enabled' => isset($_POST['cod_enabled']) ? '1' : '0',
            'shipping_fee' => (float)$_POST['shipping_fee'],
            'tax_rate' => (float)$_POST['tax_rate']
        ];
        
        foreach ($settings as $key => $value) {
            $update_query = "UPDATE settings SET setting_value = '$value' WHERE setting_key = '$key'";
            mysqli_query($conn, $update_query);
        }
        
        set_success('Settings updated successfully');
        redirect('settings.php');
    }
    
    if (isset($_POST['add_payment_method'])) {
        $method_name = clean($_POST['method_name']);
        $method_type = clean($_POST['method_type']);
        $method_details = clean($_POST['method_details']);
        
        $insert_query = "INSERT INTO settings (setting_key, setting_value, setting_type, description) 
                        VALUES ('payment_method_$method_name', '$method_details', 'json', 'Payment method: $method_name')";
        
        if (mysqli_query($conn, $insert_query)) {
            set_success('Payment method added successfully');
        } else {
            set_error('Failed to add payment method');
        }
        redirect('settings.php');
    }
}

// Get current settings
$settings_query = "SELECT * FROM settings";
$settings_result = mysqli_query($conn, $settings_query);
$settings = [];
while ($row = mysqli_fetch_assoc($settings_result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
            background: var(--light-color);
        }
        
        .admin-sidebar {
            width: 250px;
            background: var(--dark-color);
            color: white;
            padding: 2rem 0;
        }
        
        .admin-logo {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: var(--primary-color);
        }
        
        .admin-menu {
            list-style: none;
        }
        
        .admin-menu li {
            margin-bottom: 0.5rem;
        }
        
        .admin-menu a {
            display: block;
            padding: 1rem 2rem;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        
        .admin-menu a:hover,
        .admin-menu a.active {
            background: var(--primary-color);
        }
        
        .admin-content {
            flex: 1;
            padding: 2rem;
        }
        
        .admin-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }
        
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .upi-qr {
            text-align: center;
            padding: 2rem;
            background: var(--light-color);
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .qr-placeholder {
            width: 200px;
            height: 200px;
            background: white;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 4rem;
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .payment-method-card {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .payment-method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .payment-method-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
        }
        
        .payment-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
            background: var(--border-color);
            border-radius: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .toggle-switch.active {
            background: var(--accent-color);
        }
        
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }
        
        .toggle-switch.active::after {
            transform: translateX(30px);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">POPA ADMIN</div>
            <nav>
                <ul class="admin-menu">
                    <li><a href="index.php">📊 Dashboard</a></li>
                    <li><a href="products.php">📦 Products</a></li>
                    <li><a href="categories.php">🏷️ Categories</a></li>
                    <li><a href="orders.php">📋 Orders</a></li>
                    <li><a href="users.php">👥 Users</a></li>
                    <li><a href="payments.php">💳 Payments</a></li>
                    <li><a href="settings.php" class="active">⚙️ Settings</a></li>
                    <li><a href="../index.php">🏠 Website</a></li>
                    <li><a href="../logout.php">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 style="font-size: 2rem; color: var(--text-primary);">Settings</h1>
                    <p style="color: var(--text-secondary);">Manage your store settings and payment methods</p>
                </div>
            </div>

            <!-- Messages -->
            <?php $success = get_success(); if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php $error = get_error(); if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Basic Settings -->
            <div class="admin-section">
                <h3 style="margin-bottom: 1.5rem;">Basic Settings</h3>
                <form method="POST">
                    <input type="hidden" name="update_settings" value="1">
                    
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name'] ?? 'POPA'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="site_email" class="form-control" value="<?php echo htmlspecialchars($settings['site_email'] ?? 'contact@popa.com'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="site_phone" class="form-control" value="<?php echo htmlspecialchars($settings['site_phone'] ?? '+91 9876543210'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Shipping Fee (₹)</label>
                            <input type="number" name="shipping_fee" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($settings['shipping_fee'] ?? '50'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" class="form-control" step="0.01" min="0" max="100" value="<?php echo htmlspecialchars($settings['tax_rate'] ?? '18'); ?>">
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </div>
                </form>
            </div>

            <!-- UPI Settings -->
            <div class="admin-section">
                <h3 style="margin-bottom: 1.5rem;">UPI Payment Settings</h3>
                
                <div class="upi-qr">
                    <div class="qr-placeholder">📱</div>
                    <h4 style="margin-bottom: 1rem;">UPI QR Code</h4>
                    <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                        Upload your UPI QR code image for easy payments
                    </p>
                    <button class="btn btn-secondary">Upload QR Code</button>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="update_settings" value="1">
                    
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">UPI ID</label>
                            <input type="text" name="upi_id" class="form-control" placeholder="yourname@ybl" value="<?php echo htmlspecialchars($settings['upi_id'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Enable UPI Payments</label>
                            <label style="display: flex; align-items: center; gap: 1rem;">
                                <div class="toggle-switch <?php echo ($settings['upi_enabled'] ?? '0') == '1' ? 'active' : ''; ?>" 
                                     onclick="this.classList.toggle('active'); document.querySelector('input[name=\"upi_enabled\"]').value = this.classList.contains('active') ? '1' : '0';"></div>
                                <input type="hidden" name="upi_enabled" value="<?php echo $settings['upi_enabled'] ?? '0'; ?>">
                                <span><?php echo ($settings['upi_enabled'] ?? '0') == '1' ? 'Enabled' : 'Disabled'; ?></span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Enable Cash on Delivery</label>
                            <label style="display: flex; align-items: center; gap: 1rem;">
                                <div class="toggle-switch <?php echo ($settings['cod_enabled'] ?? '1') == '1' ? 'active' : ''; ?>" 
                                     onclick="this.classList.toggle('active'); document.querySelector('input[name=\"cod_enabled\"]').value = this.classList.contains('active') ? '1' : '0';"></div>
                                <input type="hidden" name="cod_enabled" value="<?php echo $settings['cod_enabled'] ?? '1'; ?>">
                                <span><?php echo ($settings['cod_enabled'] ?? '1') == '1' ? 'Enabled' : 'Disabled'; ?></span>
                            </label>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Update UPI Settings</button>
                    </div>
                </form>
            </div>

            <!-- Payment Methods -->
            <div class="admin-section">
                <h3 style="margin-bottom: 1.5rem;">Payment Methods</h3>
                
                <div class="payment-methods">
                    <div class="payment-method-card <?php echo ($settings['cod_enabled'] ?? '1') == '1' ? 'active' : ''; ?>">
                        <div class="payment-icon">💵</div>
                        <h4>Cash on Delivery</h4>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Pay when you receive</p>
                        <div style="margin-top: 1rem;">
                            <span class="status-badge <?php echo ($settings['cod_enabled'] ?? '1') == '1' ? 'status-delivered' : 'status-cancelled'; ?>">
                                <?php echo ($settings['cod_enabled'] ?? '1') == '1' ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="payment-method-card <?php echo ($settings['upi_enabled'] ?? '0') == '1' ? 'active' : ''; ?>">
                        <div class="payment-icon">📱</div>
                        <h4>UPI Payment</h4>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Instant UPI transfers</p>
                        <div style="margin-top: 1rem;">
                            <span class="status-badge <?php echo ($settings['upi_enabled'] ?? '0') == '1' ? 'status-delivered' : 'status-cancelled'; ?>">
                                <?php echo ($settings['upi_enabled'] ?? '0') == '1' ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="payment-method-card">
                        <div class="payment-icon">💳</div>
                        <h4>Credit/Debit Card</h4>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">All major cards accepted</p>
                        <div style="margin-top: 1rem;">
                            <span class="status-badge status-pending">Coming Soon</span>
                        </div>
                    </div>
                    
                    <div class="payment-method-card">
                        <div class="payment-icon">🏦</div>
                        <h4>Net Banking</h4>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">All major banks</p>
                        <div style="margin-top: 1rem;">
                            <span class="status-badge status-pending">Coming Soon</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Information -->
            <div class="admin-section">
                <h3 style="margin-bottom: 1.5rem;">Store Information</h3>
                
                <div class="settings-grid">
                    <div>
                        <h4 style="margin-bottom: 1rem;">Current UPI ID</h4>
                        <div style="background: var(--light-color); padding: 1rem; border-radius: 10px; font-family: monospace; font-size: 1.1rem;">
                            <?php echo $settings['upi_id'] ?: 'Not set'; ?>
                        </div>
                    </div>
                    
                    <div>
                        <h4 style="margin-bottom: 1rem;">Store Status</h4>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 12px; height: 12px; background: var(--accent-color); border-radius: 50%;"></div>
                            <span>Store is Open</span>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">
                            Customers can place orders
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
