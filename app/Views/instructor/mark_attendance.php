<?php
session_start();
require_once '../../../config/constants.php';

header('Content-Type: application/json');

// Check if instructor is logged in
if (!isset($_SESSION['instructor_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $instructor_id = $_POST['instructor_id'];
    $date = $_POST['date'];
    $attendance_data = $_POST['attendance'] ?? [];
    
    if (empty($attendance_data)) {
        echo json_encode(['success' => false, 'message' => 'No attendance data provided']);
        exit();
    }
    
    $pdo->beginTransaction();
    
    // First, delete any existing attendance for this date and instructor's students
    $delete_stmt = $pdo->prepare("
        DELETE a FROM attendance a 
        INNER JOIN student_course_assignments sca ON a.student_id = sca.student_id 
        WHERE sca.instructor_id = ? AND DATE(a.attendance_date) = ?
    ");
    $delete_stmt->execute([$instructor_id, $date]);
    
    // Insert new attendance records
    $insert_stmt = $pdo->prepare("
        INSERT INTO attendance (student_id, instructor_id, course_id, attendance_date, status) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    // Get course_id for each student
    foreach ($attendance_data as $student_id => $status) {
        if (in_array($status, ['present', 'absent'])) {
            // Get the course_id for this student
            $course_stmt = $pdo->prepare("
                SELECT course_id FROM student_course_assignments 
                WHERE student_id = ? AND instructor_id = ? AND status = 'active'
            ");
            $course_stmt->execute([$student_id, $instructor_id]);
            $course_id = $course_stmt->fetchColumn();
            
            if ($course_id) {
                $insert_stmt->execute([$student_id, $instructor_id, $course_id, $date, $status]);
            }
        }
    }
    
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Attendance marked successfully']);
    
} catch (PDOException $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

?>