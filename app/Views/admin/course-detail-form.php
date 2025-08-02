<?php
session_start();
require_once '../../../config/constants.php';

// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $response = ['success' => false, 'message' => ''];
        
        switch ($_POST['action']) {
            case 'add_course':
                try {
                    $course_name = trim($_POST['course_name']);
                    $course_type = trim($_POST['course_type']);
                    $duration_days = (int)$_POST['duration_days'];
                    $course_fee = (float)$_POST['course_fee'];
                    $description = trim($_POST['description']) ?: null;
                    
                    // Validation
                    if (empty($course_name) || empty($course_type) || $duration_days <= 0 || $course_fee <= 0) {
                        throw new Exception('All fields except description are required and must be valid.');
                    }
                    
                    $stmt = $pdo->prepare("INSERT INTO courses (course_name, course_type, duration_days, course_fee, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([$course_name, $course_type, $duration_days, $course_fee, $description]);
                    
                    $response = ['success' => true, 'message' => 'Course added successfully!'];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
                break;
                
            case 'edit_course':
                try {
                    $course_id = (int)$_POST['course_id'];
                    $course_name = trim($_POST['course_name']);
                    $course_type = trim($_POST['course_type']);
                    $duration_days = (int)$_POST['duration_days'];
                    $course_fee = (float)$_POST['course_fee'];
                    $description = trim($_POST['description']) ?: null;
                    
                    // Validation
                    if (empty($course_name) || empty($course_type) || $duration_days <= 0 || $course_fee <= 0) {
                        throw new Exception('All fields except description are required and must be valid.');
                    }
                    
                    $stmt = $pdo->prepare("UPDATE courses SET course_name = ?, course_type = ?, duration_days = ?, course_fee = ?, description = ?, updated_at = NOW() WHERE course_id = ?");
                    $stmt->execute([$course_name, $course_type, $duration_days, $course_fee, $description, $course_id]);
                    
                    $response = ['success' => true, 'message' => 'Course updated successfully!'];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
                break;
                
            case 'delete_course':
                try {
                    $course_id = (int)$_POST['course_id'];
                    
                    $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
                    $stmt->execute([$course_id]);
                    
                    $response = ['success' => true, 'message' => 'Course deleted successfully!'];
                } catch (Exception $e) {
                    $response = ['success' => false, 'message' => $e->getMessage()];
                }
                break;
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

// Get all courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY course_id ASC");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to generate SPO ID
function generateSPOId($course_id) {
    return 'SPO' . str_pad($course_id, 4, '0', STR_PAD_LEFT);
}

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - SafePathObserver</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .page-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .course-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
        }
        
        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }
        
        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
            transition: transform 0.2s;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        
        .course-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #007bff;
        }
        
        .course-fee {
            color: #28a745;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 2rem;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-graduation-cap me-3"></i>Course Management</h1>
            <p class="mb-0">Manage all SafePathObserver training courses</p>
        </div>
    </div>

    <div class="container">
        <!-- Courses Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-list me-2"></i>All Courses</h4>
                <div class="action-buttons">
                    <button class="btn btn-success-custom" onclick="downloadPDF()">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                        <i class="fas fa-plus me-2"></i>Add Course
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="coursesTable">
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>Course Type</th>
                            <th>Duration (Days)</th>
                            <th>Course Fee (LKR)</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                No courses found. Add your first course!
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="course-id"><?php echo generateSPOId($course['course_id']); ?></td>
                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($course['course_type']); ?></span></td>
                            <td><?php echo $course['duration_days']; ?> days</td>
                            <td class="course-fee">LKR <?php echo number_format($course['course_fee'], 2); ?></td>
                            <td>
                                <?php if ($course['description']): ?>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                          title="<?php echo htmlspecialchars($course['description']); ?>">
                                        <?php echo htmlspecialchars($course['description']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">No description</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-edit btn-action" 
                                        onclick="editCourse(<?php echo htmlspecialchars(json_encode($course)); ?>)"
                                        title="Edit Course">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete btn-action" 
                                        onclick="deleteCourse(<?php echo $course['course_id']; ?>, '<?php echo htmlspecialchars($course['course_name']); ?>')"
                                        title="Delete Course">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCourseForm">
                        <input type="hidden" name="action" value="add_course">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="course_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course_type" class="form-label">Course Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="course_type" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duration_days" class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duration_days" min="1" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course_fee" class="form-label">Course Fee (LKR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="course_fee" step="0.01" min="0.01" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Optional course description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitCourseForm('add')">Add Course</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm">
                        <input type="hidden" name="action" value="edit_course">
                        <input type="hidden" name="course_id" id="edit_course_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="course_name" id="edit_course_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_course_type" class="form-label">Course Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="course_type" id="edit_course_type" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_duration_days" class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duration_days" id="edit_duration_days" min="1" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_course_fee" class="form-label">Course Fee (LKR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="course_fee" id="edit_course_fee" step="0.01" min="0.01" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" placeholder="Optional course description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitCourseForm('edit')">Update Course</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Submit course form
        function submitCourseForm(type) {
            const form = document.getElementById(type === 'add' ? 'addCourseForm' : 'editCourseForm');
            const formData = new FormData(form);
            
            // Clear previous validation states
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the request.');
            });
        }

        // Edit course
        function editCourse(course) {
            document.getElementById('edit_course_id').value = course.course_id;
            document.getElementById('edit_course_name').value = course.course_name;
            document.getElementById('edit_course_type').value = course.course_type;
            document.getElementById('edit_duration_days').value = course.duration_days;
            document.getElementById('edit_course_fee').value = course.course_fee;
            document.getElementById('edit_description').value = course.description || '';
            
            new bootstrap.Modal(document.getElementById('editCourseModal')).show();
        }

        // Delete course
        function deleteCourse(courseId, courseName) {
            if (confirm(`Are you sure you want to delete "${courseName}"? This action cannot be undone.`)) {
                const formData = new FormData();
                formData.append('action', 'delete_course');
                formData.append('course_id', courseId);
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course.');
                });
            }
        }

        // Download PDF
        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Add title
            doc.setFontSize(20);
            doc.text('SafePathObserver - Course Details', 20, 20);
            
            // Add date
            doc.setFontSize(12);
            doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 20, 35);
            
            // Get table data
            const table = document.getElementById('coursesTable');
            const rows = [];
            
            // Add headers
            const headers = ['Course ID', 'Course Name', 'Type', 'Duration', 'Fee (LKR)', 'Description'];
            
            // Add data rows
            const tbody = table.querySelector('tbody');
            tbody.querySelectorAll('tr').forEach(row => {
                if (row.cells.length > 1) { // Skip empty state row
                    const rowData = [
                        row.cells[0].textContent.trim(),
                        row.cells[1].textContent.trim(),
                        row.cells[2].textContent.trim(),
                        row.cells[3].textContent.trim(),
                        row.cells[4].textContent.trim(),
                        row.cells[5].textContent.trim()
                    ];
                    rows.push(rowData);
                }
            });
            
            // Generate table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 45,
                theme: 'grid',
                headStyles: {
                    fillColor: [0, 123, 255],
                    textColor: 255
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                }
            });
            
            // Save PDF
            doc.save('SafePathObserver-Courses.pdf');
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                });
            });
        });
    </script>
</body>
</html>

<?php
$content = ob_get_clean(); // Get all buffered HTML
include '../components/layout.php'; // Insert layout and pass $content to it
?>