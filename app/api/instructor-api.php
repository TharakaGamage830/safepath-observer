<?php
// session_start();
// require_once '../Controller/InstructorController.php';

// if (!isset($_SESSION['user'])) {
//     header('Location: ../../login/index.php');
//     exit();
// }

// $user = $_SESSION['user'];
// $instructorId = $user['user_id'];
// $instructorName = $user['name'];

// $controller = new InstructorController();
// $data = $controller->getDashboardData($instructorId);

// $students = $data['students'];
// $today_summary = $data['summary'];

// include '../View/instructor/instructor-dashboard.php';

session_start();
require_once '../Controllers/InstructorController.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../login/index.php');
    exit();
}

$user = $_SESSION['user'];
$userId = $user['user_id'];
$userName = $user['name'];

// Fetch instructor ID and instructor name
$controller = new InstructorController();
$instructorId = $controller->getInstructorIdByUserID($userId);
$_SESSION['instructor_id'] = $instructorId;

// Optionally, fetch instructor name if needed
$instructorName = $controller->getInstructorNameById($instructorId);

$data = $controller->getDashboardData($instructorId);

$students = $data['students'];
$today_summary = $data['summary'];

include '../View/instructor/instructor-dashboard.php';

