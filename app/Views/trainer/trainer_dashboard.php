<?php
// session_start();

// // Check if user is trainer
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'instructor') {
//     header('Location: login.php');
//     exit();
// }

// $user = $_SESSION['user'];
$content = '<h1>Hello, This is Trainer Dashboard</h1>';

include 'layout.php';
?>