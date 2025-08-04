<?php
require_once __DIR__ . '/../../config/constants.php';


$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredStudents = [];

// Handle get student request
if (isset($_GET['get_student'])) {
    $id = (int)$_GET['get_student'];
    $stmt = $conn->prepare("SELECT s.*, c.name as course_name FROM students s JOIN courses c ON s.course_id = c.id WHERE s.id = ?");
    $stmt->bind_param("i", $id);
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
    $student_id = 'STU' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, birth_date, address, phone_number, email, gender, national_id, start_date, course_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssis", 
        $student_id,
        $_POST['name'],
        $_POST['birth_date'],
        $_POST['address'],
        $_POST['phone_number'],
        $_POST['email'],
        $_POST['gender'],
        $_POST['national_id'],
        $_POST['start_date'],
        $_POST['course_id'],
        $_POST['status']
    );
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

function updateStudent($conn) {
    $stmt = $conn->prepare("UPDATE students SET name=?, birth_date=?, address=?, phone_number=?, email=?, gender=?, national_id=?, start_date=?, course_id=?, status=? WHERE id=?");
    $stmt->bind_param("ssssssssisi", 
        $_POST['name'],
        $_POST['birth_date'],
        $_POST['address'],
        $_POST['phone_number'],
        $_POST['email'],
        $_POST['gender'],
        $_POST['national_id'],
        $_POST['start_date'],
        $_POST['course_id'],
        $_POST['status'],
        $_POST['id']
    );
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

function deleteStudent($conn) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']."?delete_success=Student deleted successfully");
    exit();
}

// Get data for display
$courses = $conn->query("SELECT * FROM courses WHERE is_active = 1");

// Handle search functionality
if (!empty($searchTerm)) {
    $stmt = $conn->prepare("SELECT s.*, c.name as course_name FROM students s JOIN courses c ON s.course_id = c.id 
                           WHERE s.student_id LIKE ? OR s.name LIKE ? OR s.status LIKE ?");
    $searchParam = "%$searchTerm%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $conn->query("SELECT s.*, c.name as course_name FROM students s JOIN courses c ON s.course_id = c.id");
}
?>
