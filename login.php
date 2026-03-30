<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    redirect('index.php');
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    
    // Validate
    if (empty($email) || empty($password)) {
        set_error('Please fill in all fields');
    } else {
        // Check user
        $query = "SELECT * FROM users WHERE email = '$email' AND status = 'active'";
        $result = mysqli_query($conn, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            if (verify_password($password, $row['password'])) {
                // Set session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                
                set_success('Login successful! Welcome back.');
                redirect('index.php');
            } else {
                set_error('Invalid email or password');
            }
        } else {
            set_error('Invalid email or password');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                <!-- Logo -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h1 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">POPA</h1>
                    <p style="color: var(--text-secondary);">Welcome back! Please login to your account.</p>
                </div>

                <!-- Messages -->
                <?php $success = get_success(); if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php $error = get_error(); if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" data-no-validate>
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Enter your password" required>
                            <button type="button" onclick="togglePassword('password')" 
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem;">
                                👁️
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="remember" style="width: auto;">
                            <span style="font-size: 0.9rem;">Remember me</span>
                        </label>
                        <a href="forgot_password.php" style="color: var(--primary-color); text-decoration: none; font-size: 0.9rem;">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        Login
                    </button>

                    <div style="text-align: center; padding: 1rem 0; border-top: 1px solid var(--border-color);">
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Don't have an account?</p>
                        <a href="register.php" class="btn btn-secondary" style="width: 100%;">
                            Create Account
                        </a>
                    </div>
                </form>

                <!-- Social Login (Optional) -->
                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">Or continue with</p>
                    <div style="display: flex; gap: 1rem;">
                        <button style="flex: 1; padding: 0.75rem; border: 2px solid var(--border-color); background: white; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <span>📧</span> Google
                        </button>
                        <button style="flex: 1; padding: 0.75rem; border: 2px solid var(--border-color); background: white; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <span>📘</span> Facebook
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
