<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../api/api.php';



ob_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f8fafc;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            color: var(--dark-color);
            padding: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .dashboard-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .dashboard-title {
            color: var(--dark-color);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-subtitle {
            color: #64748b;
            font-size: 1.1rem;
        }

        .table-custom {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table-custom thead {
            background: var(--primary-color);
            color: white;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);

        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);

        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-completed {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .badge-suspended {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .course-progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e2e8f0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--primary-color);

        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-pie me-3" style="color: var(--primary-color);"></i>
                Dashboard Overview
            </h1>
            <p class="dashboard-subtitle">Driving School Management System Statistics</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Students</div>
                            <h2 class="stat-number"><?= $totalStudents ?></h2>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>Last 7 days: <?= $recentStudents->num_rows ?> new
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Add more stat cards as needed -->
        </div>

        <!-- Recent Student Registrations -->
        <div class="chart-container">
            <h5 class="mb-4">
                <i class="fas fa-users me-2" style="color: var(--primary-color);"></i>
                Recent Student Registrations (Last 7 Days)
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recentStudents->num_rows === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center">No recent registrations</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($student = $recentStudents->fetch_assoc()): 
                                $statusClass = $student['status'] === 'Active' ? 'badge-active' : 'badge-pending';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($student['student_id']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td><?= htmlspecialchars($student['course_name']) ?></td>
                                <td><?= htmlspecialchars($student['phone_number']) ?></td>
                                <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($student['status']) ?></span></td>
                                <td><?= date('M d, Y', strtotime($student['created_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Active Students</div>
                            <?php
                            $activeStudentsQuery = "SELECT COUNT(*) as total FROM students WHERE enrollment_status = 'active'";
                            $activeStudents = $conn->query($activeStudentsQuery)->fetch_assoc()['total'];
                            ?>
                            <h2 class="stat-number"><?= $activeStudents ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Completed Courses</div>
                            <?php
                            $completedQuery = "SELECT COUNT(*) as total FROM students WHERE enrollment_status = 'completed'";
                            $completed = $conn->query($completedQuery)->fetch_assoc()['total'];
                            ?>
                            <h2 class="stat-number"><?= $completed ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Available Courses</div>
                            <?php
                            $coursesQuery = "SELECT COUNT(*) as total FROM courses";
                            $coursesCount = $conn->query($coursesQuery)->fetch_assoc()['total'];
                            ?>
                            <h2 class="stat-number"><?= $coursesCount ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Distribution -->
        <div class="row">
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="mb-4">
                        <i class="fas fa-chart-bar me-2" style="color: var(--primary-color);"></i>
                        Course Distribution
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Course</th>
                                    <th>Students</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalStudents = max(1, $totalStudents); // Prevent division by zero
                                while ($course = $courseStats->fetch_assoc()): 
                                    $percentage = round(($course['student_count'] / $totalStudents) * 100, 1);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($course['course_name']) ?></td>
                                    <td><?= $course['student_count'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="course-progress me-2" style="width: 100px;">
                                                <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                            </div>
                                            <span><?= $percentage ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Student Registrations -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="mb-4">
                        <i class="fas fa-users me-2" style="color: var(--primary-color);"></i>
                        Recent Student Registrations (Last 7 Days)
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recentStudents->num_rows === 0): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No recent registrations</td>
                                    </tr>
                                <?php else: ?>
                                    <?php 
                                    $recentStudents->data_seek(0); // Reset pointer
                                    while ($student = $recentStudents->fetch_assoc()): 
                                        $statusClass = 'badge-' . strtolower($student['status']);
                                        $statusDisplay = ucfirst($student['status']);
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                                        <td><?= htmlspecialchars($student['name']) ?></td>
                                        <td><?= htmlspecialchars($student['course_name']) ?></td>
                                        <td><span class="status-badge <?= $statusClass ?>"><?= $statusDisplay ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php

$content = ob_get_clean();
include '../components/layout.php';

?>

<?php
$conn->close();
?>