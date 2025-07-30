<?php
session_start();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validate credentials (replace with your actual authentication logic)
    if ($email === 'admin@drivingschool.com' && $password === 'admin123') {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_email'] = $email;
        
        // Handle "Remember Me" functionality
        if ($remember) {
            setcookie('remember_email', $email, time() + (86400 * 30), "/"); // 30 days
        }
        
        $success_message = "Login successful! Welcome back.";
    } else {
        $error_message = "Invalid email or password. Please try again.";
    }
}

// Get remembered email if exists
$remembered_email = $_COOKIE['remember_email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Driving School Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="background">
    <div class="login-box">
      <h2>Sign in Your Account</h2>
      
      <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
      <?php endif; ?>
      
      <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="form-group">
          <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($remembered_email); ?>" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <label class="remember">
          <input type="checkbox" name="remember"> Remember my preference
        </label>
        <button type="submit">Sign In</button>
      </form>
    </div>
  </div>
</body>
</html>
