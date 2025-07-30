<?php
// session_start();

// // Check if user is admin
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// $user = $_SESSION['user'];
$content = '<h1>Hello, This is Admin Dashboard</h1>';

include 'layout.php';
?>