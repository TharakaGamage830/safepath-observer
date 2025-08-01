<?php
session_start();
require_once '../../../config/constants.php';

// Check if instructor is logged in
if (!isset($_SESSION['instructor_id'])) {
    header('Location: ../login/index.php');
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

// Get today's attendance summary
$today_stmt = $pdo->prepare("
    SELECT 
        COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present_count,
        COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_count
    FROM users u
    INNER JOIN student_course_assignments sca ON u.user_id = sca.student_id
    LEFT JOIN attendance a ON u.user_id = a.student_id AND DATE(a.attendance_date) = CURDATE()
    WHERE sca.instructor_id = ? AND u.role = 'student'
");
$today_stmt->execute([$instructor_id]);
$today_summary = $today_stmt->fetch(PDO::FETCH_ASSOC);

// Get students with their attendance history and course details
$students_stmt = $pdo->prepare("
    SELECT 
        u.user_id as student_id,
        u.name as student_name,
        u.email,
        u.profile_picture,
        c.course_name,
        c.course_type,
        c.duration_days,
        COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present_count,
        COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_count,
        COUNT(a.attendance_id) as total_marked_days
    FROM users u
    INNER JOIN student_course_assignments sca ON u.user_id = sca.student_id
    INNER JOIN courses c ON sca.course_id = c.course_id
    LEFT JOIN attendance a ON u.user_id = a.student_id
    WHERE sca.instructor_id = ? AND u.role = 'student'
    GROUP BY u.user_id, u.name, u.email, u.profile_picture, c.course_name, c.course_type, c.duration_days
    ORDER BY u.name
");
$students_stmt->execute([$instructor_id]);
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get attendance history for each student (last 10 records)
function getStudentAttendanceHistory($pdo, $student_id, $limit = 10) {
    $stmt = $pdo->prepare("
        SELECT attendance_date, status 
        FROM attendance 
        WHERE student_id = ? 
        ORDER BY attendance_date DESC 
        LIMIT ?
    ");
    $stmt->execute([$student_id, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - SafePathObserver</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .header-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .date-display {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        .stats-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            flex: 1;
            min-width: 180px;
        }
        .stat-card.present {
            border-left: 4px solid #22c55e;
        }
        .stat-card.absent {
            border-left: 4px solid #ef4444;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .stat-number.present {
            color: #22c55e;
        }
        .stat-number.absent {
            color: #ef4444;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .history-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1.5px solid #3b82f6;
        }
        .history-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .history-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .student-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }
        .student-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
        .student-header {
            display: flex;
            justify-content: between;
            align-items: center;
        }
        .student-name {
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        .attendance-percentage {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .expand-icon {
            transition: transform 0.2s ease;
            color: #666;
        }
        .expand-icon.expanded {
            transform: rotate(180deg);
        }
        .attendance-history {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            display: none;
        }
        .attendance-history.show {
            display: block;
        }
        .attendance-record {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .attendance-record:last-child {
            border-bottom: none;
        }
        .attendance-date {
            color: #666;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-present {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .status-absent {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .mark-attendance-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .mark-attendance-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .attendance-form-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: none;
        }
        .attendance-form-section.show {
            display: block;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .form-section-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .student-attendance-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
        }
        .student-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .student-details h6 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .course-type {
            color: #666;
            font-size: 0.8rem;
        }
        .attendance-options {
            display: flex;
            gap: 0.5rem;
        }
        .attendance-option {
            flex: 1;
            padding: 0.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            text-align: center;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .attendance-option.not-marked {
            color: #666;
            border-color: #d1d5db;
        }
        .attendance-option.present {
            color: #16a34a;
            border-color: #22c55e;
            background: #f0fdf4;
        }
        .attendance-option.absent {
            color: #dc2626;
            border-color: #ef4444;
            background: #fef2f2;
        }
        .attendance-option:hover {
            transform: translateY(-1px);
        }
        .submit-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 1.5rem;
            transition: all 0.2s ease;
        }
        .submit-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="header-title">Attendance Management</h1>
                <div class="date-display"><?php echo date('Y/m/d'); ?></div>
            </div>
            <button class="mark-attendance-btn" onclick="toggleAttendanceForm()">
                <i class="fas fa-plus"></i>
                Mark Attendance
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card present">
                <div class="stat-number present"><?php echo $today_summary['present_count']; ?></div>
                <p class="stat-label">
                    <i class="fas fa-check-circle"></i>
                    Present
                </p>
            </div>
            <div class="stat-card absent">
                <div class="stat-number absent"><?php echo $today_summary['absent_count']; ?></div>
                <p class="stat-label">
                    <i class="fas fa-times-circle"></i>
                    Absent
                </p>
            </div>
        </div>

        <!-- Attendance History Section -->
        <div class="history-section">
            <h3 class="history-title">Attendance History</h3>
            <p class="history-subtitle">Recent attendance records</p>
            
            <?php foreach ($students as $student): 
                $total_days = $student['present_count'] + $student['absent_count'];
                $attendance_percentage = $total_days > 0 ? 
                    round(($student['present_count'] / $total_days) * 100) : 0;
                $attendance_history = getStudentAttendanceHistory($pdo, $student['student_id']);
            ?>
            <div class="student-item" onclick="toggleStudentHistory(<?php echo $student['student_id']; ?>)">
                <div class="student-header">
                    <div class="flex-grow-1">
                        <h6 class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></h6>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="attendance-percentage 
                            <?php echo $attendance_percentage >= 80 ? 'text-success' : 
                                ($attendance_percentage >= 60 ? 'text-warning' : 'text-danger'); ?>">
                            <?php echo $attendance_percentage; ?>% Attendance
                        </span>
                        <i class="fas fa-chevron-down expand-icon" id="icon-<?php echo $student['student_id']; ?>"></i>
                    </div>
                </div>
                
                <div class="attendance-history" id="history-<?php echo $student['student_id']; ?>">
                    <?php if (!empty($attendance_history)): ?>
                        <?php foreach ($attendance_history as $record): ?>
                        <div class="attendance-record">
                            <div class="attendance-date">
                                <i class="fas fa-clock"></i>
                                <?php echo date('Y-m-d', strtotime($record['attendance_date'])); ?>
                            </div>
                            <span class="status-badge status-<?php echo $record['status']; ?>">
                                <?php echo ucfirst($record['status']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-info-circle"></i>
                            No attendance records found
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Mark Attendance Form Section -->
        <div class="attendance-form-section" id="attendanceFormSection">
            <h3 class="form-section-title">Students - <?php echo date('Y-m-d'); ?></h3>
            <p class="form-section-subtitle">Mark attendance for each student</p>
            
            <form id="attendanceForm" method="POST" action="submit_attendance.php">
                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                <input type="hidden" name="instructor_id" value="<?php echo $instructor_id; ?>">
                
                <?php foreach ($students as $student): ?>
                <div class="student-attendance-item">
                    <div class="student-info">
                        <img src="<?php echo !empty($student['profile_picture']) ? 
                            '../public/images/profiles/' . $student['profile_picture'] : 
                            '../public/images/default-avatar.png'; ?>" 
                            alt="Student" class="student-avatar">
                        <div class="student-details">
                            <h6><?php echo htmlspecialchars($student['student_name']); ?></h6>
                            <div class="course-type"><?php echo htmlspecialchars($student['course_type']); ?></div>
                        </div>
                    </div>
                    
                    <div class="attendance-options">
                        <div class="attendance-option not-marked" 
                             onclick="selectAttendance(<?php echo $student['student_id']; ?>, 'not-marked', this)">
                            Not Marked
                        </div>
                        <div class="attendance-option" 
                             onclick="selectAttendance(<?php echo $student['student_id']; ?>, 'present', this)">
                            Present
                        </div>
                        <div class="attendance-option" 
                             onclick="selectAttendance(<?php echo $student['student_id']; ?>, 'absent', this)">
                            Absent
                        </div>
                    </div>
                    
                    <input type="hidden" name="attendance[<?php echo $student['student_id']; ?>]" 
                           id="attendance-<?php echo $student['student_id']; ?>" value="">
                </div>
                <?php endforeach; ?>
                
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleStudentHistory(studentId) {
            const historyDiv = document.getElementById('history-' + studentId);
            const icon = document.getElementById('icon-' + studentId);
            
            if (historyDiv.classList.contains('show')) {
                historyDiv.classList.remove('show');
                icon.classList.remove('expanded');
            } else {
                historyDiv.classList.add('show');
                icon.classList.add('expanded');
            }
        }

        function toggleAttendanceForm() {
            const formSection = document.getElementById('attendanceFormSection');
            if (formSection.classList.contains('show')) {
                formSection.classList.remove('show');
            } else {
                formSection.classList.add('show');
                formSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function selectAttendance(studentId, status, element) {
            // Remove active class from all options for this student
            const options = element.parentNode.querySelectorAll('.attendance-option');
            options.forEach(option => {
                option.classList.remove('not-marked', 'present', 'absent');
            });
            
            // Add active class to selected option
            element.classList.add(status);
            
            // Update hidden input
            const hiddenInput = document.getElementById('attendance-' + studentId);
            hiddenInput.value = status === 'not-marked' ? '' : status;
        }

        // Handle form submission
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('submit_attendance.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Attendance submitted successfully!');
                    document.getElementById('attendanceFormSection').classList.remove('show');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting attendance.');
            });
        });
    </script>
</body>
</html>
<?php
$content = ob_get_clean(); // Get all buffered HTML
include '../components/layout.php'; // Insert layout and pass $content to it
?>