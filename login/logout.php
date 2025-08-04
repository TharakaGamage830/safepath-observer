<?php
session_start();

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Optionally clear "Remember Me" cookies
if (isset($_COOKIE['remember_email'])) {
    setcookie('remember_email', '', time() - 3600, "/");
}

// Redirect to login page
header("Location: index.php");
exit();
