<?php
session_start();
require_once '../../../config/constants.php';

// Check if instructor is logged in
if (!isset($_SESSION['instructor_id'])) {
    header('Location: ../../../login/index.php');
    exit();
}

$instructor_id = $_SESSION['instructor_id'];
$instructor_name = $_SESSION['instructor_name'] ?? 'Instructor';




// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get students assigned to this instructor with their course details
$stmt = $pdo->prepare("
    SELECT 
        u.user_id as student_id,
        u.name as student_name,
        u.email,
        u.profile_picture,
        c.course_name,
        c.duration_days,
        c.course_id,
        COUNT(CASE WHEN a.status = 'present' THEN 1 END) as total_present_days,
        MAX(CASE WHEN DATE(a.attendance_date) = CURDATE() AND a.status = 'present' THEN 1 ELSE 0 END) as today_present
    FROM users u
    INNER JOIN student_course_assignments sca ON u.user_id = sca.student_id
    INNER JOIN courses c ON sca.course_id = c.course_id
    LEFT JOIN attendance a ON u.user_id = a.student_id
    WHERE sca.instructor_id = ? AND u.role = 'student'
    GROUP BY u.user_id, u.name, u.email, u.profile_picture, c.course_name, c.duration_days, c.course_id
");

$stmt->execute([$instructor_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get today's attendance summary
$today_stmt = $pdo->prepare("
    SELECT 
        COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present_count,
        COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_count,
        COUNT(u.user_id) as total_students
    FROM users u
    INNER JOIN student_course_assignments sca ON u.user_id = sca.student_id
    LEFT JOIN attendance a ON u.user_id = a.student_id AND DATE(a.attendance_date) = CURDATE()
    WHERE sca.instructor_id = ? AND u.role = 'student'
");
$today_stmt->execute([$instructor_id]);
$today_summary = $today_stmt->fetch(PDO::FETCH_ASSOC);
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - SafePathObserver</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid;
            margin-bottom: 1rem;
        }
        
        .stat-card.present {
            border-left-color: #28a745;
        }
        
        .stat-card.absent {
            border-left-color: #dc3545;
        }
        
        .stat-card.total {
            border-left-color: #007bff;
        }
        
        .student-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        
        .profile-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 3px solid #f0f0f0;
        }
        
        .progress-bar-custom {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            border-radius: 5px;
            transition: width 0.3s ease;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-present {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-absent {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .mark-attendance-btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .mark-attendance-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
            color: white;
        }
        
        .attendance-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
        }
        
        .attendance-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .attendance-btn {
            min-width: 80px;
            margin: 0 2px;
            font-size: 0.85rem;
        }
        
        .btn-outline-success:checked + label,
        .btn-outline-success.active {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .btn-outline-danger:checked + label,
        .btn-outline-danger.active {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-chalkboard-teacher me-3"></i>Instructor Dashboard</h1>
                    <p class="mb-0">Welcome back, <?php echo htmlspecialchars($instructor_name); ?>!</p>
                    <small class="opacity-75">Here are the students you are currently training.</small>
                </div>
                <!-- <div class="col-md-4 text-end">
                    <button class="btn mark-attendance-btn" onclick="toggleAttendanceSection()">
                        <i class="fas fa-plus me-2"></i>Mark Attendance
                    </button>
                </div> -->
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Today's Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card present">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-success mb-1"><?php echo $today_summary['present_count'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Present Today</p>
                        </div>
                        <i class="fas fa-user-check fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card absent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-danger mb-1"><?php echo $today_summary['absent_count'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Absent Today</p>
                        </div>
                        <i class="fas fa-user-times fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card total">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-primary mb-1"><?php echo $today_summary['total_students'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Total Students</p>
                        </div>
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="row">
            <?php if (empty($students)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No students are currently assigned to you.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($students as $student): 
                    $progress = $student['duration_days'] > 0 ? round(($student['total_present_days'] / $student['duration_days']) * 100) : 0;
                    $today_status = $student['today_present'] ? 'Present' : 'Absent';
                    $status_class = $student['today_present'] ? 'status-present' : 'status-absent';
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="student-card">
                        <div class="card-body p-4">
                            <!-- Profile Section -->
                            <div class="text-center mb-3">
                                <img src="<?php echo !empty($student['profile_picture']) ? 
                                    '../../../public/images/profiles/' . htmlspecialchars($student['profile_picture']) : 
                                    '../../../public/images/default-avatar.png'; ?>" 
                                    alt="Student" class="profile-img rounded-circle mb-2">
                                <h5 class="mb-1"><?php echo htmlspecialchars($student['student_name']); ?></h5>
                                <p class="text-muted small mb-2"><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>

                            <!-- Progress Section -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">Progress</span>
                                    <span class="small fw-bold"><?php echo $progress; ?>%</span>
                                </div>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
                                </div>
                            </div>

                            <!-- Status Section -->
                            <div class="text-center mb-3">
                                <div class="small text-muted mb-1">Status</div>
                                <span class="status-badge <?php echo $status_class; ?>"><?php echo $today_status; ?></span>
                            </div>

                            <!-- Course Info -->
                            <div class="text-center">
                                <small class="text-muted d-block">Course: <?php echo htmlspecialchars($student['course_name']); ?></small>
                                <small class="text-muted">Attended: <?php echo $student['total_present_days']; ?>/<?php echo $student['duration_days']; ?> days</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Mark Attendance Section -->
        <div id="attendanceSection" class="attendance-section">
            <h4 class="mb-4"><i class="fas fa-calendar-check me-2"></i>Mark Attendance - <?php echo date('Y-m-d'); ?></h4>
            
            <form id="attendanceForm" method="POST" action="mark_attendance.php">
                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                <input type="hidden" name="instructor_id" value="<?php echo $instructor_id; ?>">
                
                <div class="row">
                    <?php foreach ($students as $student): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="attendance-card">
                            <img src="<?php echo !empty($student['profile_picture']) ? 
                                '../../../public/images/profiles/' . htmlspecialchars($student['profile_picture']) : 
                                '../../../public/images/default-avatar.png'; ?>" 
                                alt="Student" class="rounded-circle mb-2" style="width: 50px; height: 50px; object-fit: cover;">
                            <h6 class="mb-3"><?php echo htmlspecialchars($student['student_name']); ?></h6>
                            
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="attendance[<?php echo $student['student_id']; ?>]" 
                                       id="present_<?php echo $student['student_id']; ?>" value="present">
                                <label class="btn btn-outline-success attendance-btn" 
                                       for="present_<?php echo $student['student_id']; ?>">Present</label>
                                
                                <input type="radio" class="btn-check" name="attendance[<?php echo $student['student_id']; ?>]" 
                                       id="absent_<?php echo $student['student_id']; ?>" value="absent">
                                <label class="btn btn-outline-danger attendance-btn" 
                                       for="absent_<?php echo $student['student_id']; ?>">Absent</label>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Submit Attendance
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg ms-2" onclick="toggleAttendanceSection()">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAttendanceSection() {
            const section = document.getElementById('attendanceSection');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                section.scrollIntoView({ behavior: 'smooth' });
            } else {
                section.style.display = 'none';
            }
        }

        // Handle form submission
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Check if at least one attendance is marked
            let hasAttendance = false;
            const attendanceInputs = document.querySelectorAll('input[name^="attendance"]');
            for (let input of attendanceInputs) {
                if (input.checked) {
                    hasAttendance = true;
                    break;
                }
            }
            
            if (!hasAttendance) {
                alert('Please mark attendance for at least one student.');
                return;
            }
            
            fetch('mark_attendance.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Attendance marked successfully!');
                    document.getElementById('attendanceSection').style.display = 'none';
                    location.reload(); // Refresh to update the dashboard
                } else {
                    alert('Error: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while marking attendance.');
            });
        });
    </script>
</body>
</html>

<?php
$content = ob_get_clean(); // Get all buffered HTML
include '../components/layout.php'; // Insert layout and pass $content to it
?>