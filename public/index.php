<?php
session_start();

// For testing purposes, set a default user
// In production, this should come from your authentication system
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
    // User not logged in, redirect to login page
    header('Location: ../login/index.php');
    exit();
}

$user = $_SESSION['user'];

// Redirect to appropriate dashboard based on role
switch ($user['role']) {
    case 'admin':
        header('Location: ../app/Views/admin/admin-dashboard.php');
        break;
    case 'instructor':
        header('Location: ../app/Views/instructor/instructor-dashboard.php');
        break;
    case 'student':
        header('Location: ../app/Views/student/student-dashboard.php');
        break;
    default:
        // If no valid role, redirect to login
        header('Location: ../login/index.php');
        break;
}
exit();
?>