<?php
require_once __DIR__ . '/../../config/config.php';

// Get total number of students
$totalStudentsQuery = "SELECT COUNT(*) as total FROM students";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudents = $totalStudentsResult->fetch_assoc()['total'];

// Get recent student registrations (last 7 days)
$recentStudentsQuery = "SELECT s.student_id, u.name, u.email, u.phone, u.address, 
                       c.course_name, s.enrollment_status as status, s.created_at
                       FROM students s
                       JOIN users u ON s.user_id = u.user_id
                       LEFT JOIN courses c ON s.course_id = c.course_id
                       WHERE s.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                       ORDER BY s.created_at DESC LIMIT 5";
$recentStudents = $conn->query($recentStudentsQuery);

// Get course statistics
$courseStatsQuery = "SELECT c.course_name, COUNT(s.student_id) as student_count
                    FROM courses c
                    LEFT JOIN students s ON c.course_id = s.course_id
                    GROUP BY c.course_id";
$courseStats = $conn->query($courseStatsQuery);

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Handle get student request
if (isset($_GET['get_student'])) {
    $id = $_GET['get_student'];
    $stmt = $conn->prepare("SELECT s.*, u.*, c.course_name 
                          FROM students s 
                          JOIN users u ON s.user_id = u.user_id 
                          LEFT JOIN courses c ON s.course_id = c.course_id 
                          WHERE s.student_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($student);
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_student':
                addStudent($conn);
                break;
            case 'update_student':
                updateStudent($conn);
                break;
            case 'delete_student':
                deleteStudent($conn);
                break;
        }
    }
}

// CRUD Functions
function addStudent($conn) {
    // First create user
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, phone, address) 
                          VALUES (?, ?, ?, 'student', ?, ?)");
    $stmt->bind_param("sssss", 
        $_POST['name'],
        $_POST['email'],
        $hashed_password,
        $_POST['phone'],
        $_POST['address']
    );
    $stmt->execute();
    $user_id = $conn->insert_id;

    // Then create student
    $student_id = 'STU' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $stmt = $conn->prepare("INSERT INTO students 
                          (student_id, user_id, instructor_id, course_id, birth_date, gender, national_id_number, start_date, enrollment_status) 
                          VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisssss", 
        $student_id,
        $user_id,
        $_POST['course_id'],
        $_POST['birth_date'],
        $_POST['gender'],
        $_POST['national_id_number'],
        $_POST['start_date'],
        $_POST['status']
    );
    $stmt->execute();
    
    header("Location: ".$_SERVER['PHP_SELF']."?add_success=Student added successfully");
    exit();
}

function updateStudent($conn) {
    // Update user
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, address=? WHERE user_id=?");
    $stmt->bind_param("ssssi", 
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['user_id']
    );
    $stmt->execute();

    // Update student
    $stmt = $conn->prepare("UPDATE students SET 
                          course_id=?, birth_date=?, gender=?, national_id_number=?, 
                          start_date=?, enrollment_status=? 
                          WHERE student_id=?");
    $stmt->bind_param("issssss", 
        $_POST['course_id'],
        $_POST['birth_date'],
        $_POST['gender'],
        $_POST['national_id_number'],
        $_POST['start_date'],
        $_POST['status'],
        $_POST['id']
    );
    $stmt->execute();
    
    header("Location: ".$_SERVER['PHP_SELF']."?update_success=Student updated successfully");
    exit();
}

function deleteStudent($conn) {
    // First get user_id
    $stmt = $conn->prepare("SELECT user_id FROM students WHERE student_id=?");
    $stmt->bind_param("s", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    
    // Delete student
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id=?");
    $stmt->bind_param("s", $_POST['id']);
    $stmt->execute();
    
    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->bind_param("i", $student['user_id']);
    $stmt->execute();
    
    header("Location: ".$_SERVER['PHP_SELF']."?delete_success=Student deleted successfully");
    exit();
}

// Get data for display
$courses = $conn->query("SELECT course_id as id, course_name as name FROM courses");

// Handle search functionality
if (!empty($searchTerm)) {
    $stmt = $conn->prepare("SELECT s.*, u.*, c.course_name 
                          FROM students s 
                          JOIN users u ON s.user_id = u.user_id 
                          LEFT JOIN courses c ON s.course_id = c.course_id
                          WHERE s.student_id LIKE ? OR u.name LIKE ? OR s.enrollment_status LIKE ?");
    $searchParam = "%$searchTerm%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $conn->query("SELECT s.*, u.*, c.course_name 
                            FROM students s 
                            JOIN users u ON s.user_id = u.user_id 
                            LEFT JOIN courses c ON s.course_id = c.course_id");
}
?>