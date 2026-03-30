<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_error('Access denied. Admin privileges required.');
    redirect('../login.php');
}

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = clean($_POST['name']);
                $category_id = (int)$_POST['category_id'];
                $description = clean($_POST['description']);
                $price = (float)$_POST['price'];
                $discount_price = (float)$_POST['discount_price'];
                $stock_quantity = (int)$_POST['stock_quantity'];
                $sku = clean($_POST['sku']);
                $featured = isset($_POST['featured']) ? 1 : 0;
                
                // Handle image upload
                $image = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = upload_image($_FILES['image'], '../uploads/');
                }
                
                $insert_query = "INSERT INTO products (name, category_id, description, price, discount_price, stock_quantity, image, sku, featured) 
                               VALUES ('$name', $category_id, '$description', $price, $discount_price, $stock_quantity, '$image', '$sku', $featured)";
                
                if (mysqli_query($conn, $insert_query)) {
                    set_success('Product added successfully');
                } else {
                    set_error('Failed to add product');
                }
                redirect('products.php');
                break;
                
            case 'delete':
                $product_id = (int)$_POST['product_id'];
                $delete_query = "DELETE FROM products WHERE id = $product_id";
                if (mysqli_query($conn, $delete_query)) {
                    set_success('Product deleted successfully');
                } else {
                    set_error('Failed to delete product');
                }
                redirect('products.php');
                break;
                
            case 'toggle_featured':
                $product_id = (int)$_POST['product_id'];
                $featured = (int)$_POST['featured'];
                $update_query = "UPDATE products SET featured = $featured WHERE id = $product_id";
                if (mysqli_query($conn, $update_query)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
                exit;
                break;
        }
    }
}

// Get products with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          ORDER BY p.created_at DESC";
$pagination = paginate($query, $limit, $page);
$products_result = $pagination['data'];

// Get categories for dropdown
$categories_query = "SELECT * FROM categories WHERE status = 'active' ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - <?php echo SITE_NAME; ?></title>
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
        
        .product-image-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .form-grid .form-group.full-width {
            grid-column: 1 / -1;
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
                    <li><a href="products.php" class="active">📦 Products</a></li>
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
                    <h1 style="font-size: 2rem; color: var(--text-primary);">Products Management</h1>
                    <p style="color: var(--text-secondary);">Manage your product inventory</p>
                </div>
                <button onclick="openAddProductModal()" class="btn btn-primary">
                    + Add New Product
                </button>
            </div>

            <!-- Messages -->
            <?php $success = get_success(); if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php $error = get_error(); if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Products Table -->
            <div class="admin-section">
                <?php if (mysqli_num_rows($products_result) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $product['image'] ?: '../assets/images/placeholder.jpg'; ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                             class="product-image-thumb">
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                            <br>
                                            <small style="color: var(--text-secondary);">SKU: <?php echo htmlspecialchars($product['sku']); ?></small>
                                            <?php if ($product['featured']): ?>
                                                <span style="background: var(--accent-color); color: white; padding: 0.1rem 0.5rem; border-radius: 10px; font-size: 0.7rem; margin-left: 0.5rem;">
                                                    ⭐ Featured
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?: 'Uncategorized'); ?></td>
                                    <td>
                                        <?php echo format_price($product['price']); ?>
                                        <?php if ($product['discount_price'] && $product['discount_price'] < $product['price']): ?>
                                            <br>
                                            <small style="color: var(--accent-color); font-weight: bold;">
                                                <?php echo format_price($product['discount_price']); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($product['stock_quantity'] > 10): ?>
                                            <span style="color: var(--accent-color); font-weight: bold;"><?php echo $product['stock_quantity']; ?></span>
                                        <?php elseif ($product['stock_quantity'] > 0): ?>
                                            <span style="color: var(--warning-color); font-weight: bold;"><?php echo $product['stock_quantity']; ?></span>
                                        <?php else: ?>
                                            <span style="color: var(--danger-color); font-weight: bold;">Out of stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span style="color: <?php echo $product['status'] === 'active' ? 'var(--accent-color)' : 'var(--danger-color)'; ?>; font-weight: bold;">
                                            <?php echo ucfirst($product['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="toggleFeatured(<?php echo $product['id']; ?>, <?php echo $product['featured']; ?>)" 
                                                    class="btn btn-sm btn-secondary" title="Toggle Featured">
                                                <?php echo $product['featured'] ? '⭐' : '☆'; ?>
                                            </button>
                                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <form method="POST" onsubmit="return confirm('Delete this product?')" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem;">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="?page=<?php echo $pagination['current_page'] - 1; ?>" class="btn btn-secondary">« Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                    <span class="btn btn-primary" style="opacity: 0.8;"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>" class="btn btn-secondary"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="?page=<?php echo $pagination['current_page'] + 1; ?>" class="btn btn-secondary">Next »</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 3rem;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                        <h3 style="color: var(--text-primary); margin-bottom: 1rem;">No products found</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 2rem;">Start by adding your first product</p>
                        <button onclick="openAddProductModal()" class="btn btn-primary">Add New Product</button>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <button class="close-modal" onclick="closeAddProductModal()">×</button>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" class="form-control" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Regular Price *</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Discount Price</label>
                        <input type="number" name="discount_price" class="form-control" step="0.01" min="0">
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="form-group full-width">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="featured" style="width: auto;">
                            <span>Mark as Featured Product</span>
                        </label>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Add Product</button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddProductModal()" style="flex: 1;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
    <script>
        function openAddProductModal() {
            document.getElementById('addProductModal').style.display = 'block';
        }
        
        function closeAddProductModal() {
            document.getElementById('addProductModal').style.display = 'none';
        }
        
        function toggleFeatured(productId, currentStatus) {
            const newStatus = currentStatus ? 0 : 1;
            
            fetch('products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=toggle_featured&product_id=${productId}&featured=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update product status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addProductModal');
            if (event.target === modal) {
                closeAddProductModal();
            }
        }
    </script>
</body>
</html>
