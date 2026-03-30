<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    redirect('index.php');
}

// Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    
    // Validate
    if (empty($name) || empty($email) || empty($password)) {
        set_error('Please fill in all required fields');
    } elseif (!is_valid_email($email)) {
        set_error('Please enter a valid email address');
    } elseif ($password !== $confirm_password) {
        set_error('Passwords do not match');
    } elseif (strlen($password) < 6) {
        set_error('Password must be at least 6 characters long');
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            set_error('Email address already exists');
        } else {
            // Insert new user
            $hashed_password = hash_password($password);
            $insert_query = "INSERT INTO users (name, email, password, phone, address) 
                           VALUES ('$name', '$email', '$hashed_password', '$phone', '$address')";
            
            if (mysqli_query($conn, $insert_query)) {
                set_success('Registration successful! Please login to continue.');
                redirect('login.php');
            } else {
                set_error('Registration failed. Please try again.');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;">
    <div class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                <!-- Logo -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h1 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">POPA</h1>
                    <p style="color: var(--text-secondary);">Create your account and start shopping!</p>
                </div>

                <!-- Messages -->
                <?php $success = get_success(); if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php $error = get_error(); if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form method="POST" data-no-validate>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   placeholder="Enter your name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="3" 
                                  placeholder="Enter your address"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label" for="password">Password *</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" class="form-control" 
                                       placeholder="Enter password" required>
                                <button type="button" onclick="togglePassword('password')" 
                                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem;">
                                    👁️
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="confirm_password">Confirm Password *</label>
                            <div style="position: relative;">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                       placeholder="Confirm password" required>
                                <button type="button" onclick="togglePassword('confirm_password')" 
                                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem;">
                                    👁️
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div id="password-strength" style="margin-bottom: 1rem; display: none;">
                        <div style="height: 4px; background: var(--border-color); border-radius: 2px; overflow: hidden;">
                            <div id="strength-bar" style="height: 100%; width: 0%; transition: all 0.3s ease;"></div>
                        </div>
                        <p id="strength-text" style="font-size: 0.8rem; margin-top: 0.25rem;"></p>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="terms" required style="width: auto;">
                            <span style="font-size: 0.9rem;">
                                I agree to the <a href="terms.php" style="color: var(--primary-color);">Terms of Service</a> 
                                and <a href="privacy.php" style="color: var(--primary-color);">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        Create Account
                    </button>

                    <div style="text-align: center; padding: 1rem 0; border-top: 1px solid var(--border-color);">
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Already have an account?</p>
                        <a href="login.php" class="btn btn-secondary" style="width: 100%;">
                            Login
                        </a>
                    </div>
                </form>

                <!-- Social Registration (Optional) -->
                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">Or register with</p>
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
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            if (password.length > 0) {
                strengthDiv.style.display = 'block';
                
                let strength = 0;
                if (password.length >= 6) strength++;
                if (password.length >= 10) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;
                
                const strengthLevels = [
                    { width: '20%', color: '#ef4444', text: 'Very Weak' },
                    { width: '40%', color: '#f59e0b', text: 'Weak' },
                    { width: '60%', color: '#eab308', text: 'Fair' },
                    { width: '80%', color: '#84cc16', text: 'Good' },
                    { width: '100%', color: '#10b981', text: 'Strong' }
                ];
                
                const level = strengthLevels[Math.min(strength, 4)];
                strengthBar.style.width = level.width;
                strengthBar.style.backgroundColor = level.color;
                strengthText.textContent = level.text;
                strengthText.style.color = level.color;
            } else {
                strengthDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>
