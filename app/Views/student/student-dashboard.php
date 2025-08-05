<?php
session_start();

// Temporary: Skip database check for now
// TODO: Enable database connection after XAMPP setup
/*
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login/logout.php');
    exit();
}
*/

// Get current date
$current_date = date('F j, Y');
$current_day = date('l');

// Mock data for demonstration - replace with actual database queries
$student_name = "John Doe"; // This should come from database
$profile_picture = "../../../public/images/default-avatar.png";
$subjects = [
    ['name' => 'Road Rules & Regulations', 'instructor' => 'Mr. Smith', 'schedule' => 'Mon, Wed, Fri - 9:00 AM'],
    ['name' => 'Practical Driving', 'instructor' => 'Ms. Johnson', 'schedule' => 'Tue, Thu - 2:00 PM'],
    ['name' => 'Vehicle Maintenance', 'instructor' => 'Mr. Brown', 'schedule' => 'Sat - 10:00 AM']
];

$attendance_percentage = 85; // This should be calculated from database

$content = '
<div class="student-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header mb-4">
        <div class="row">
            <div class="col-md-8">
                <h2 class="dashboard-title">Student Dashboard</h2>
                <p class="text-muted mb-0">' . $current_day . ', ' . $current_date . '</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="profile-section">
                    <img src="' . $profile_picture . '" alt="Profile" class="profile-image">
                    <span class="ms-2">' . $student_name . '</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="welcome-card mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="text-primary mb-2">Welcome back, ' . $student_name . '!</h4>
                <p class="text-muted mb-0">Ready to continue your driving journey? Check your progress and upcoming lessons below.</p>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row mb-4">
        <!-- Attendance Card -->
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-calendar-check text-success mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="text-success">' . $attendance_percentage . '%</h3>
                    <h5 class="card-title">Attendance Rate</h5>
                    <p class="text-muted">Your overall attendance</p>
                </div>
            </div>
        </div>

        <!-- Subjects Card -->
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-book text-primary mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="text-primary">' . count($subjects) . '</h3>
                    <h5 class="card-title">Active Subjects</h5>
                    <p class="text-muted">Currently enrolled</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects List -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-book me-2"></i>My Subjects</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Instructor</th>
                            <th>Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

foreach ($subjects as $subject) {
    $content .= '
                        <tr>
                            <td>
                                <strong>' . $subject['name'] . '</strong>
                            </td>
                            <td>' . $subject['instructor'] . '</td>
                            <td>' . $subject['schedule'] . '</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm">View Details</button>
                            </td>
                        </tr>';
}

$content .= '
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Learning Progress</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="progress-item">
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 80%"></div>
                                </div>
                                <small class="text-muted">Theory Lessons: 80%</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="progress-item">
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                                </div>
                                <small class="text-muted">Practical Lessons: 60%</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="progress-item">
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: 90%"></div>
                                </div>
                                <small class="text-muted">Attendance: 90%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.student-dashboard {
    padding: 20px 0;
}

.dashboard-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 5px;
}

.profile-section {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.profile-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.welcome-card .card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.welcome-card h4 {
    color: white !important;
}

.welcome-card p {
    color: rgba(255, 255, 255, 0.9) !important;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.progress-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-top: none;
}

.btn-outline-primary {
    font-size: 0.875rem;
    padding: 4px 12px;
}

@media (max-width: 768px) {
    .profile-section {
        justify-content: flex-start;
        margin-top: 15px;
    }
    
    .dashboard-header .col-md-4 {
        text-align: left !important;
    }
}
</style>
';

include '../components/layout_simple.php';
?>