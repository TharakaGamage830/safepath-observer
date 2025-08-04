<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../api/api.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #E3E3EA;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background-color: #A4B5CB;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
        }
        .status-not_started {
            color: #6c757d;
        }
        .status-progress {
            color: #ffc107;
            font-weight: bold;
        }
        .status-completed {
            color: #198754;
            font-weight: bold;
        }
        .status-in_progress, .status-inprogress, .status-in-progress {
            color: #ffc107;
            font-weight: bold;
        }
        .card {
            border: none;
        }
        .search-container {
            padding: 30px;
            height: 180px;
            background: #E3E3EA;
            border-bottom: 1px solid #E3E3EA;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-box {
            width: 300px;
            position: relative;
            flex-grow: 1;
            margin-right: 20px;
        }
        .search-box input {
            padding-right: 40px;
            border-radius: 20px;
        }
        .search-box i {
            position: absolute;
            right: 15px;
            top: 10px;
            color: #6c757d;
        }
        .search-hint {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .card-header {
            background-color: #E3E3EA;
            text-align: center;
            color: black;
            border-bottom: none;
        }
        .card-body {
            background-color: #D9D9D9;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            table-layout: fixed;
        }
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 15%;
        }
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 20%;
        }
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 20%;
        }
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 15%;
        }
        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 15%;
        }
        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 15%;
        }
        
        .btn-outline-primary {
            border-color: #ABBCF0;
            background-color: #ABBCF0;
            color: black;
        }
        .btn-outline-primary:hover {
            background-color: #CDCA2B;
            color: black;
        }
        .alert {
            margin-top: 20px;
        }
        #studentFormSection {
            display: none;
        }
        .action-container {
            position: relative;
            width: 100%;
        }
        .action-buttons {
            position: absolute;
            right: 0;
            top: 0;
            display: none;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            padding: 5px;
            z-index: 10;
            gap: 5px;
        }
        .add-btn {
            white-space: nowrap;
            color:#ffffff;
            background-color: #002F6C;
            border: 1px solid #002F6C;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .add-btn:hover {
            background-color: #4e6fa1ff;
            color: #000000;
        }
        
        /* Form input styling */
        .form-control, .form-select, textarea.form-control {
            border-radius: 15px !important; /* Added border radius */
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        
        /* Form button styling */
        .form-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .submit-btn {
            background-color: #002F6C;
            border-radius: 15px;
            padding: 10px 25px;
            color: white;
            border: none;
            font-weight: bold;
        }
        .submit-btn:hover {
            background-color: #004494;
        }
        .cancel-btn {
            background-color: #CDCA2B;
            border-radius: 15px;
            padding: 10px 25px;
            color: black;
            border: none;
            font-weight: bold;
        }
        .cancel-btn:hover {
            background-color: #e5e223;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <?php if (isset($_GET['delete_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_GET['delete_success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Student Table -->
        <div class="card" id="studentTableSection">
            <div class="card-header">
                <h2 class="mb-0">Students Details</h2>
            </div>
            
            <!-- Search and Add Student Container -->
            <div class="search-container">
                <!-- Search Box -->
                <form method="get" class="search-box">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by ID, Name or Status..." 
                               value="<?= htmlspecialchars($searchTerm) ?>">
                        <i class="fas fa-search"></i>
                    </div>
                </form>
                
                <!-- Add Student Button -->
                <button class="btn btn-light add-btn" onclick="showForm()">
                    <i class="fas fa-plus me-2"></i>Add Student
                </button>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($students->num_rows === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No students found</td>
                                </tr>
                            <?php else: ?>
                                <?php while ($student = $students->fetch_assoc()): 
                                    // Normalize status value for display and CSS
                                    $status = strtolower($student['status']);
                                    $status = preg_replace('/[^a-z]/', '_', $status);
                                    
                                    // Standardize status values
                                    if (strpos($status, 'progress') !== false) {
                                        $status = 'progress';
                                    } elseif (strpos($status, 'not') !== false) {
                                        $status = 'not_started';
                                    } elseif (strpos($status, 'complete') !== false) {
                                        $status = 'completed';
                                    }
                                    
                                    $display_status = ucwords(str_replace('_', ' ', $status));
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['student_id']) ?></td>
                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                    <td><?= htmlspecialchars($student['course_name']) ?></td>
                                    <td><?= htmlspecialchars($student['phone_number']) ?></td>
                                    <td class="status-<?= htmlspecialchars($status) ?>">
                                        <?= htmlspecialchars($display_status) ?>
                                    </td>
                                    <td>
                                        <div class="action-container">
                                            <button class="btn btn-sm btn-outline-primary" onclick="toggleActions(<?= $student['id'] ?>)">
                                                Action
                                            </button>
                                            
                                            <div class="action-buttons" id="actions-<?= $student['id'] ?>">
                                                <button class="btn btn-sm btn-outline-info" onclick="viewStudent(<?= $student['id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <button class="btn btn-sm btn-outline-warning" onclick="editStudent(<?= $student['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                    <input type="hidden" name="action" value="delete_student">
                                                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Student Form -->
        <div class="container" id="studentFormSection">
            <div class="form-container">
                <h2 class="text-center mb-4" id="formTitle">Student Registration Form</h2>
                <form method="post" id="studentForm" onsubmit="return validateForm()">
                    <input type="hidden" name="action" id="formAction" value="add_student">
                    <input type="hidden" name="id" id="studentId">
                    <input type="hidden" name="status" id="status" value="not_started">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label required">Full Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                            <div class="invalid-feedback">Please enter the student's name</div>
                        </div>
                        <div class="col-md-6">
                            <label for="birthDate" class="form-label required">Birth Date</label>
                            <input type="date" class="form-control" name="birth_date" id="birthDate" required>
                            <div class="invalid-feedback">Please select a valid birth date</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label required">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="2" required></textarea>
                        <div class="invalid-feedback">Please enter the student's address</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phoneNumber" class="form-label required">Phone Number</label>
                            <input type="tel" class="form-control" name="phone_number" id="phoneNumber" required>
                            <div class="invalid-feedback">Please enter a valid phone number</div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                            <div class="invalid-feedback">Please enter a valid email address</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="gender" class="form-label required">Gender</label>
                            <select class="form-select" name="gender" id="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="invalid-feedback">Please select a gender</div>
                        </div>
                        <div class="col-md-4">
                            <label for="nationalId" class="form-label required">National ID</label>
                            <input type="text" class="form-control" name="national_id" id="nationalId" required>
                            <div class="invalid-feedback">Please enter the national ID</div>
                        </div>
                        <div class="col-md-4">
                            <label for="startDate" class="form-label required">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="startDate" required>
                            <div class="invalid-feedback">Please select a valid start date</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="course" class="form-label required">Course</label>
                            <select class="form-select" name="course_id" id="course" required>
                                <option value="">Select Course</option>
                                <?php 
                                $courses->data_seek(0);
                                while ($course = $courses->fetch_assoc()): ?>
                                <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">Please select a course</div>
                        </div>
                        <div class="col-md-4">
                            <label for="statusDisplay" class="form-label required">Status</label>
                            <select class="form-select" id="statusDisplay" onchange="document.getElementById('status').value = this.value" required>
                                <option value="not_started">Not Started</option>
                                <option value="progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Form footer with buttons -->
                    <div class="form-footer">
                        <button type="button" class="cancel-btn" onclick="showTable()">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="submit-btn" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Student Modal -->
        <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Student Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="studentDetails">
                        <!-- Student details will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize modals
        const viewStudentModal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
        
        // Toggle action buttons visibility
        function toggleActions(studentId) {
            // Hide all other action buttons first
            document.querySelectorAll('.action-buttons').forEach(el => {
                if (el.id !== `actions-${studentId}`) {
                    el.style.display = 'none';
                }
            });
            
            // Toggle the clicked action buttons
            const actionButtons = document.getElementById(`actions-${studentId}`);
            actionButtons.style.display = actionButtons.style.display === 'none' ? 'flex' : 'none';
        }
        
        // View student details
        function viewStudent(id) {
            fetch('<?= $_SERVER['PHP_SELF'] ?>?get_student=' + id)
                .then(response => response.json())
                .then(student => {
                    // Normalize status for display
                    const status = student.status.toLowerCase().replace(/[^a-z]/g, '_');
                    let display_status;
                    
                    if (status.includes('progress')) {
                        display_status = 'In Progress';
                    } else if (status.includes('not')) {
                        display_status = 'Not Started';
                    } else if (status.includes('complete')) {
                        display_status = 'Completed';
                    } else {
                        display_status = student.status.replace(/_/g, ' ');
                    }
                    
                    document.getElementById('studentDetails').innerHTML = `
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Student ID:</strong> ${student.student_id}</p>
                                <p><strong>Name:</strong> ${student.name}</p>
                                <p><strong>Birth Date:</strong> ${student.birth_date}</p>
                                <p><strong>Gender:</strong> ${student.gender.charAt(0).toUpperCase() + student.gender.slice(1)}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phone:</strong> ${student.phone_number}</p>
                                <p><strong>Email:</strong> ${student.email}</p>
                                <p><strong>National ID:</strong> ${student.national_id}</p>
                                <p><strong>Start Date:</strong> ${student.start_date}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p><strong>Address:</strong></p>
                            <p>${student.address}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Course:</strong> ${student.course_name}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span class="status-${status}">${display_status}</span></p>
                            </div>
                        </div>
                    `;
                    viewStudentModal.show();
                })
                .catch(error => {
                    console.error('Error fetching student:', error);
                    alert('Error loading student details');
                });
        }
        
        // Edit student
        function editStudent(id) {
            fetch('<?= $_SERVER['PHP_SELF'] ?>?get_student=' + id)
                .then(response => response.json())
                .then(student => {
                    document.getElementById('formTitle').textContent = 'Edit Student';
                    document.getElementById('formAction').value = 'update_student';
                    document.getElementById('studentId').value = student.id;
                    document.getElementById('name').value = student.name;
                    document.getElementById('birthDate').value = student.birth_date;
                    document.getElementById('address').value = student.address;
                    document.getElementById('phoneNumber').value = student.phone_number;
                    document.getElementById('email').value = student.email;
                    document.getElementById('gender').value = student.gender;
                    document.getElementById('nationalId').value = student.national_id;
                    document.getElementById('startDate').value = student.start_date;
                    document.getElementById('course').value = student.course_id;
                    document.getElementById('statusDisplay').value = student.status;
                    document.getElementById('status').value = student.status;
                    
                    document.getElementById('studentTableSection').style.display = 'none';
                    document.getElementById('studentFormSection').style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Error fetching student:', error);
                    alert('Error loading student data for editing');
                });
        }
        
        // Form validation
        function validateForm() {
            let isValid = true;
            const form = document.getElementById('studentForm');
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                field.classList.remove('is-invalid');
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
            });
            
            // Email validation
            const email = document.getElementById('email');
            if (email && email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    email.classList.add('is-invalid');
                    isValid = false;
                }
            }
            
            return isValid;
        }

        // Show/hide form and table
        function showForm() {
            document.getElementById('studentTableSection').style.display = 'none';
            document.getElementById('studentFormSection').style.display = 'block';
            document.getElementById('formTitle').textContent = 'Student Registration Form';
            document.getElementById('formAction').value = 'add_student';
            document.getElementById('studentForm').reset();
            document.getElementById('statusDisplay').value = 'not_started';
            document.getElementById('status').value = 'not_started';
        }
        
        function showTable() {
            document.getElementById('studentTableSection').style.display = 'block';
            document.getElementById('studentFormSection').style.display = 'none';
            window.location.href = window.location.pathname;
        }

        // Initialize - hide form by default
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('studentFormSection').style.display = 'none';
            
            // Real-time search functionality
            const searchInput = document.querySelector('input[name="search"]');
            
            searchInput.addEventListener('input', function() {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
            
            searchInput.form.addEventListener('submit', function(e) {
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>