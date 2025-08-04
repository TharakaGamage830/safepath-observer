<?php
session_start();
require_once '../Controller/InstructorController.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../login/index.php');
    exit();
}

$user = $_SESSION['user'];
$instructorId = $user['id'];
$instructorName = $user['name'];

$controller = new InstructorController();
$data = $controller->getDashboardData($instructorId);

$students = $data['students'];
$today_summary = $data['summary'];

include '../View/instructor/instructor-dashboard.php';
