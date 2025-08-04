<?php
session_start();

// Check if user is student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: ../../../public/login.php');
    exit();
}

$user = $_SESSION['user'];
$content = '<h1>Hello, This is Student Dashboard</h1>';

include '../components/layout.php';
?>