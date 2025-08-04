<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../api/api.php';

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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>