<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_error('Access denied. Admin privileges required.');
    redirect('../login.php');
}

// Get dashboard statistics
$total_products_query = "SELECT COUNT(*) as count FROM products";
$total_products = mysqli_fetch_assoc(mysqli_query($conn, $total_products_query))['count'];

$total_orders_query = "SELECT COUNT(*) as count FROM orders";
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, $total_orders_query))['count'];

$total_users_query = "SELECT COUNT(*) as count FROM users WHERE role = 'customer'";
$total_users = mysqli_fetch_assoc(mysqli_query($conn, $total_users_query))['count'];

$total_revenue_query = "SELECT SUM(final_amount) as total FROM orders WHERE payment_status = 'paid'";
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, $total_revenue_query))['total'] ?? 0;

// Recent orders
$recent_orders_query = "SELECT o.*, u.name as customer_name FROM orders o 
                        LEFT JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC LIMIT 5";
$recent_orders = mysqli_query($conn, $recent_orders_query);

// Low stock products
$low_stock_query = "SELECT * FROM products WHERE stock_quantity < 10 AND status = 'active' ORDER BY stock_quantity ASC LIMIT 5";
$low_stock_products = mysqli_query($conn, $low_stock_query);

// Recent users
$recent_users_query = "SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC LIMIT 5";
$recent_users = mysqli_query($conn, $recent_users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            text-align: center;
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-secondary);
        }
        
        .admin-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .admin-table th {
            background: var(--light-color);
            font-weight: 600;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-processing { background: #e0e7ff; color: #3730a3; }
        .status-shipped { background: #f3e8ff; color: #6b21a8; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">POPA ADMIN</div>
            <nav>
                <ul class="admin-menu">
                    <li><a href="index.php" class="active">📊 Dashboard</a></li>
                    <li><a href="products.php">📦 Products</a></li>
                    <li><a href="categories.php">🏷️ Categories</a></li>
                    <li><a href="orders.php">📋 Orders</a></li>
                    <li><a href="users.php">👥 Users</a></li>
                    <li><a href="payments.php">💳 Payments</a></li>
                    <li><a href="settings.php">⚙️ Settings</a></li>
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
                    <h1 style="font-size: 2rem; color: var(--text-primary);">Admin Dashboard</h1>
                    <p style="color: var(--text-secondary);">Welcome back, <?php echo $_SESSION['user_name']; ?>!</p>
                </div>
                <div style="text-align: right;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Last login: <?php echo date('M j, Y, g:i A'); ?></p>
                </div>
            </div>

            <!-- Messages -->
            <?php $success = get_success(); if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php $error = get_error(); if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number"><?php echo $total_products; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📋</div>
                    <div class="stat-number"><?php echo $total_orders; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Customers</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number"><?php echo format_price($total_revenue); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Recent Orders -->
                <div class="admin-section">
                    <h3 style="margin-bottom: 1.5rem;">Recent Orders</h3>
                    <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                                    <tr>
                                        <td><?php echo $order['order_number']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name'] ?: 'Guest'); ?></td>
                                        <td><?php echo format_price($order['final_amount']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div style="text-align: center; margin-top: 1rem;">
                            <a href="orders.php" class="btn btn-primary">View All Orders</a>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">No orders yet</p>
                    <?php endif; ?>
                </div>

                <!-- Low Stock Products -->
                <div class="admin-section">
                    <h3 style="margin-bottom: 1.5rem;">Low Stock Alert</h3>
                    <?php if (mysqli_num_rows($low_stock_products) > 0): ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($product = mysqli_fetch_assoc($low_stock_products)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>
                                            <span style="color: var(--danger-color); font-weight: bold;">
                                                <?php echo $product['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" 
                                               class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.9rem;">
                                                Restock
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="color: var(--accent-color); text-align: center; padding: 2rem;">✓ All products well stocked</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="admin-section">
                <h3 style="margin-bottom: 1.5rem;">New Customers</h3>
                <?php if (mysqli_num_rows($recent_users) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($recent_users)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone'] ?: 'N/A'); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="users.php" class="btn btn-primary">View All Customers</a>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">No customers yet</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
