<?php
session_start();

// For testing purposes, set a default user
// In production, this should come from your authentication system
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'name' => 'Admin User',
        'role' => 'admin' // Change to 'student' or 'trainer' for testing
    ];
}

$user = $_SESSION['user'];

// Redirect to appropriate dashboard based on role
switch ($user['role']) {
    case 'admin':
        header('Location: ../app/Views/admin/admin_dashboard.php');
        break;
    case 'trainer':
        header('Location: ../app/Views/trainer/trainer_dashboard.php');
        break;
    case 'student':
        header('Location: ../app/Views/student/student_dashboard.php');
        break;
    default:
        // If no valid role, redirect to login
        header('Location: ../login/login.php');
        break;
}
exit();
?>