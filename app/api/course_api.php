<?php
session_start();
require_once __DIR__ . '/../Controllers/CourseController.php';

$controller = new CourseController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = $controller->handleRequest($_POST);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
