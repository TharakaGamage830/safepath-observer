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
    
    // Filter out empty values (not marked students)
    $attendance_data = array_filter($attendance_data, function($status) {
        return !empty($status) && in_array($status, ['present', 'absent']);
    });
    
    if (empty($attendance_data)) {
        echo json_encode(['success' => false, 'message' => 'No attendance data provided']);
        exit();
    }
    
    $pdo->beginTransaction();
    
    // First, delete any existing attendance for this date and these students
    $student_ids = array_keys($attendance_data);
    $placeholders = str_repeat('?,', count($student_ids) - 1) . '?';
    
    $delete_stmt = $pdo->prepare("
        DELETE FROM attendance 
        WHERE student_id IN ($placeholders) AND DATE(attendance_date) = ?
    ");
    $delete_params = array_merge($student_ids, [$date]);
    $delete_stmt->execute($delete_params);
    
    // Get course_id for each student
    $course_stmt = $pdo->prepare("
        SELECT sca.student_id, sca.course_id 
        FROM student_course_assignments sca 
        WHERE sca.student_id IN ($placeholders) AND sca.instructor_id = ?
    ");
    $course_params = array_merge($student_ids, [$instructor_id]);
    $course_stmt->execute($course_params);
    $student_courses = $course_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Insert new attendance records
    $insert_stmt = $pdo->prepare("
        INSERT INTO attendance (student_id, instructor_id, course_id, attendance_date, status) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($attendance_data as $student_id => $status) {
        $course_id = $student_courses[$student_id] ?? null;
        if ($course_id) {
            $insert_stmt->execute([$student_id, $instructor_id, $course_id, $date, $status]);
        }
    }
    
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Attendance submitted successfully']);
    
} catch (PDOException $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>