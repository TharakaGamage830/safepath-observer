<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering to prevent header issues
ob_start();

// Initialize session
session_start();

// Include database connection
require_once '../config/constants.php';

// Initialize error message
$error_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error_message = "Email and password are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT user_id, name, role, email, password FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // FIX: Use actual database data and consistent session keys
                $_SESSION['user_id'] = $user['user_id'];  // For layout.php compatibility
                $_SESSION['user'] = [
                    'id' => $user['user_id'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'email' => $user['email']
                ];

                error_log("Login successful for user: " . $user['name']);

                // Handle "Remember Me" cookie
                if ($remember) {
                    setcookie('remember_email', $email, time() + (86400 * 30), "/", "", false, true);
                } else {
                    setcookie('remember_email', '', time() - 3600, "/");
                }

                // Redirect to index.php
                header('Location: ../public/index.php');
                ob_end_flush();
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $error_message = "An error occurred. Please try again later.";
        }
    }
}

// Get remembered email from cookie
$remembered_email = filter_input(INPUT_COOKIE, 'remember_email', FILTER_SANITIZE_EMAIL) ?? '';
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
            <h2>Sign in to Your Account</h2>
            
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($remembered_email, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <label class="remember">
                    <input type="checkbox" name="remember" <?php echo $remembered_email ? 'checked' : ''; ?>> Remember my preference
                </label>
                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>