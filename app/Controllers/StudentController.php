<?php
class StudentController {
    public function dashboard() {
        // Sample data for student dashboard
        $dashboardData = [
            'currentCourse' => 'Basic Driving',
            'nextLesson' => ['date' => '2023-07-15', 'time' => '10:00 AM', 'instructor' => 'Mr. Smith'],
            'progress' => 35,
            'hoursCompleted' => 8
        ];
        
        require_once __DIR__ . '/../Views/student/dashboard.php';
    }
}