<?php
require_once __DIR__ . '/../Model/InstructorRole.php';

class InstructorController {
    private $model;

    public function __construct() {
        $this->model = new InstructorRole();
    }

    public function getDashboardData($instructorId) {
        return [
            'students' => $this->model->getStudentsByInstructor($instructorId),
            'summary'  => $this->model->getTodaySummary($instructorId)
        ];
    }

    public function dashboard() {
        session_start();

        if (!isset($_SESSION['instructor_id'])) {
            header('Location: /login');
            exit();
        }

        $instructorId = $_SESSION['instructor_id'];

        $instructorData = $this->model->getInstructorNameById($instructorId);
        $instructorName = $instructorData['name'] ?? 'Instructor';

        $dashboardData = $this->getDashboardData($instructorId);
        $students = $dashboardData['students'];

        // Get counts for dashboard cards
        $summary = $this->model->getTodaySummaryAndTotals($instructorId);

        include APPROOT . '/View/instructor/instructor-dashboard.php';
    }

    public function getInstructorIdByUserID($userId) {
    $stmt = $this->model->getInstructorIdByUserID($userId); // Assuming this method exists in your model
    return $stmt; // Return the instructor ID
    }  


}


