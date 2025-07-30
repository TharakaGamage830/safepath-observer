<?php
// admin_courses_management.php
// session_start();

// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// $user = $_SESSION['user'];
$content = '<h1>Hello, This is Admin Courses Management</h1>';

include 'layout.php';
?>