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
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing CSS styles here */
    </style> <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            background-color: #A4B5CB;
        }
        .btn-close1{
            background-color: #f06363ff;
            width: 150px;
            height: 50px;
            border-radius: 20px;
            border-color:#f06363ff ;
            color: white;
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
        .search-container {
            padding: 30px;
            height: 180px;
          
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 15px 15px 0 0;
            color: white;
        }
        .search-box {
            border-color: #000000;
            width: 300px;
            position: relative;
            flex-grow: 1;
            margin-right: 20px;
        }
        .search-box input {
            padding-right: 40px;
            border-radius: 20px;
            border-color: #000000;
        }
        .search-box i {
            position: absolute;
            right: 15px;
            top: 10px;
            color: #6c757d;
        }
        .search-hint {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            margin-top: 5px;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }
        .card-header {
            background-color: #E3E3EA;
            text-align: center;
            color: black;
            border-bottom: none;
            padding: 20px;
        }
        .card-body {
            background-color: white;
            padding: 0;
        }
        
        /* Creative Table Styling */
        .creative-table {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }
        
        .creative-table thead th {
            background-color: #002F6C;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px 20px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .creative-table thead th:first-child {
            border-radius: 12px 0 0 12px;
        }
        
        .creative-table thead th:last-child {
            border-radius: 0 12px 12px 0;
        }
        
        .creative-table tbody tr {
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .creative-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        
        .creative-table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border: none;
            border-top: 1px solid rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        }
        
        .creative-table tbody td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: 12px 0 0 12px;
        }
        
        .creative-table tbody td:last-child {
            border-right: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: 0 12px 12px 0;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-not_started {
            background-color: #f0f0f0;
            color: #6c757d;
        }
        
        .status-progress {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            transition: all 0.2s ease;
            border: none;
        }
        
        .view-btn {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .edit-btn {
            background-color: #fff8e1;
            color: #ff8f00;
        }
        
        .delete-btn {
            background-color: #ffebee;
            color: #d32f2f;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Empty State */
        .empty-state {
            background-color: white;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #e0e0e0;
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: #9e9e9e;
            font-size: 1.1rem;
        }
        
        .add-btn {
            margin-bottom: 25px;
            white-space: nowrap;
            color: #ffffff;
            background-color: #002F6C;
            border: 1px solid #002F6C;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .add-btn:hover {
            background-color: #A4B5CB;
            color: #000000;
        }
        
        /* Form input styling */
        .form-control, .form-select, textarea.form-control {
            border-radius: 10px !important;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: #002F6C;
            box-shadow: 0 0 0 0.25rem rgba(0, 47, 108, 0.15);
        }
        
        /* Form button styling */
        .form-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .modal-header{
            background-color: #002F6C;
        }
        .submit-btn {
            background-color: #002F6C;
            border-radius: 10px;
            padding: 10px 25px;
            color: white;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            background-color: #004494;
            transform: translateY(-2px);
        }
        
        .cancel-btn {
            background-color: #CDCA2B;
            border-radius: 10px;
            padding: 10px 25px;
            color: black;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .cancel-btn:hover {
            background-color: #e5e223;
            transform: translateY(-2px);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .creative-table thead {
                display: none;
            }
            
            .creative-table tbody tr {
                display: block;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .creative-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 15px;
                border-radius: 0 !important;
            }
            
            .creative-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #002F6C;
                margin-right: 15px;
                flex: 0 0 120px;
            }
            
            .creative-table tbody td:first-child {
                border-radius: 12px 12px 0 0 !important;
            }
            
            .creative-table tbody td:last-child {
                border-radius: 0 0 12px 12px !important;
            }
            
            .action-container {
                justify-content: flex-end;
            }
            
            .search-container {
                flex-direction: column;
                height: auto;
                padding: 20px;
            }
            
            .search-box {
                width: 100%;
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .add-btn {
                width: 100%;
            }
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
        
        <?php if (isset($_GET['update_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_GET['update_success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['add_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_GET['add_success']) ?>
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
                    <div class="search-hint">Search by student ID, name, or status</div>
                </form>
                
                <!-- Add Student Button -->
                <button class="btn add-btn" onclick="showForm()">
                    <i class="fas fa-plus me-2"></i>Add Student
                </button>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="creative-table">
                        <thead>
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
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-user-graduate"></i>
                                            <p>No students found</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php while ($student = $students->fetch_assoc()): 
                                    // Normalize status value for display and CSS
                                    $status = strtolower($student['enrollment_status']);
                                    $status = preg_replace('/[^a-z]/', '_', $status);
                                    
                                    // Standardize status values
                                    if ($status === 'active') {
                                        $status = 'progress';
                                    } elseif ($status === 'pending') {
                                        $status = 'not_started';
                                    } elseif ($status === 'completed') {
                                        $status = 'completed';
                                    }
                                    
                                    $display_status = ucwords(str_replace('_', ' ', $status));
                                ?>
                                <tr>
                                    <td data-label="Student ID"><?= htmlspecialchars($student['student_id']) ?></td>
                                    <td data-label="Name"><?= htmlspecialchars($student['name']) ?></td>
                                    <td data-label="Course"><?= htmlspecialchars($student['course_name']) ?></td>
                                    <td data-label="Phone"><?= htmlspecialchars($student['phone']) ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-<?= htmlspecialchars($status) ?>">
                                            <?= htmlspecialchars($display_status) ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="d-flex action-container">
                                            <button class="action-btn view-btn" onclick="viewStudent('<?= $student['student_id'] ?>')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn edit-btn" onclick="editStudent('<?= $student['student_id'] ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                <input type="hidden" name="action" value="delete_student">
                                                <input type="hidden" name="id" value="<?= $student['student_id'] ?>">
                                                <button type="submit" class="action-btn delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
                    <input type="hidden" name="user_id" id="userId">
                    <input type="hidden" name="status" id="status" value="pending">
                    
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
                            <label for="phone" class="form-label required">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" id="phone" required>
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
                            <label for="nationalIdNumber" class="form-label required">National ID</label>
                            <input type="text" class="form-control" name="national_id_number" id="nationalIdNumber" required>
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
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Password fields for new students -->
                    <div class="row mb-3" id="passwordSection">
                        <div class="col-md-6">
                            <label for="password" class="form-label required">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                            <div class="invalid-feedback">Please enter a password</div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmPassword" class="form-label required">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                            <div class="invalid-feedback">Passwords must match</div>
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
                    <div class="modal-header text-white">
                        <h5 class="modal-title">Student Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="studentDetails">
                        <!-- Student details will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-close1" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize modals
        const viewStudentModal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
        
        // View student details
        function viewStudent(id) {
            fetch('<?= $_SERVER['PHP_SELF'] ?>?get_student=' + id)
                .then(response => response.json())
                .then(student => {
                    // Normalize status for display
                    const status = student.enrollment_status.toLowerCase().replace(/[^a-z]/g, '_');
                    let display_status;
                    
                    if (status === 'active') {
                        display_status = 'Active';
                    } else if (status === 'pending') {
                        display_status = 'Pending';
                    } else if (status === 'completed') {
                        display_status = 'Completed';
                    } else {
                        display_status = student.enrollment_status.replace(/_/g, ' ');
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
                                <p><strong>Phone:</strong> ${student.phone}</p>
                                <p><strong>Email:</strong> ${student.email}</p>
                                <p><strong>National ID:</strong> ${student.national_id_number}</p>
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
                                <p><strong>Status:</strong> <span class="status-badge status-${status}">${display_status}</span></p>
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
                    document.getElementById('studentId').value = student.student_id;
                    document.getElementById('userId').value = student.user_id;
                    document.getElementById('name').value = student.name;
                    document.getElementById('birthDate').value = student.birth_date;
                    document.getElementById('address').value = student.address;
                    document.getElementById('phone').value = student.phone;
                    document.getElementById('email').value = student.email;
                    document.getElementById('gender').value = student.gender;
                    document.getElementById('nationalIdNumber').value = student.national_id_number;
                    document.getElementById('startDate').value = student.start_date;
                    document.getElementById('course').value = student.course_id;
                    
                    // Set status correctly
                    const status = student.enrollment_status.toLowerCase();
                    document.getElementById('statusDisplay').value = status;
                    document.getElementById('status').value = status;
                    
                    // Hide password fields for editing
                    document.getElementById('passwordSection').style.display = 'none';
                    
                    // Remove required attribute from password fields
                    document.getElementById('password').removeAttribute('required');
                    document.getElementById('confirmPassword').removeAttribute('required');
                    
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
            const isUpdate = document.getElementById('formAction').value === 'update_student';
            
            // Validate all required fields except password if updating
            requiredFields.forEach(field => {
                if (field.style.display !== 'none' && 
                   (!isUpdate || (isUpdate && field.id !== 'password' && field.id !== 'confirmPassword'))) {
                    field.classList.remove('is-invalid');
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
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
            
            // Password validation only for new students
            if (!isUpdate) {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirmPassword');
                
                if (password.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                    isValid = false;
                }
                
                if (password.value.length < 8) {
                    password.classList.add('is-invalid');
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
            document.getElementById('statusDisplay').value = 'pending';
            document.getElementById('status').value = 'pending';
            document.getElementById('passwordSection').style.display = 'flex';
            
            // Add required attribute back to password fields
            document.getElementById('password').setAttribute('required', '');
            document.getElementById('confirmPassword').setAttribute('required', '');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
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

$content = ob_get_clean();
include '../components/layout.php';

?>
<?php
// Close database connection
$conn->close();
?>