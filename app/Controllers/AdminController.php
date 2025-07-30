<?php
class AdminController {
    public function dashboard() {
        // Sample data for dashboard
        $dashboardData = [
            'studentCount' => 124,
            'trainerCount' => 8,
            'courseCount' => 15,
            'recentBookings' => [
                ['id' => 1, 'student' => 'John Doe', 'course' => 'Basic Driving', 'date' => '2023-07-15', 'time' => '10:00 AM', 'status' => 'Confirmed'],
                ['id' => 2, 'student' => 'Jane Smith', 'course' => 'Advanced Driving', 'date' => '2023-07-16', 'time' => '2:00 PM', 'status' => 'Pending']
            ]
        ];
        
        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }
}