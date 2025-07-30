<?php
class TrainerController {
    public function dashboard() {
        // Sample data for trainer dashboard
        $dashboardData = [
            'upcomingLessons' => [
                ['id' => 1, 'student' => 'Michael Brown', 'course' => 'Basic Driving', 'date' => 'Today', 'time' => '10:00 AM'],
                ['id' => 2, 'student' => 'Sarah Johnson', 'course' => 'Parking Skills', 'date' => 'Tomorrow', 'time' => '2:00 PM']
            ],
            'studentsCount' => 12,
            'completedLessons' => 56
        ];
        
        require_once __DIR__ . '/../Views/trainer/dashboard.php';
    }
}