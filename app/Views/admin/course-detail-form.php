<?php
session_start();
require_once '../../Controllers/CourseController.php';

$controller = new CourseController();
$courses = $controller->getCourses();

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
    <title>Course Details - SafePathObserver</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .page-header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 2rem 0; margin-bottom: 2rem; }
        .table-container, .course-card { background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem; }
        .btn-action { width: 35px; height: 35px; border-radius: 50%; padding: 0; display: inline-flex; align-items: center; justify-content: center; margin: 0 2px; }
        .btn-edit { background-color: #ffc107; color: #000; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-action:hover { transform: scale(1.1); transition: transform 0.2s; }
        .modal-header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
        .form-control:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .course-id { font-family: monospace; color: #007bff; font-weight: bold; }
        .course-fee { color: #28a745; font-weight: 600; }
        .btn-primary-custom, .btn-success-custom {
            border: none; padding: 12px 30px; border-radius: 25px; color: white; font-weight: 600;
        }
        .btn-primary-custom { background: linear-gradient(135deg, #007bff, #0056b3); }
        .btn-success-custom { background: linear-gradient(135deg, #28a745, #1e7e34); }
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
            <div class="d-flex gap-3">
                <button class="btn btn-success-custom" onclick="downloadPDF()"><i class="fas fa-download me-2"></i>Download PDF</button>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCourseModal"><i class="fas fa-plus me-2"></i>Add Course</button>
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
                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2"></i><br>No courses found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="course-id"><?= generateSPOId($course['course_id']) ?></td>
                            <td><?= htmlspecialchars($course['course_name']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($course['course_type']) ?></span></td>
                            <td><?= $course['duration_days'] ?> days</td>
                            <td class="course-fee">LKR <?= number_format($course['course_fee'], 2) ?></td>
                            <td>
                                <?= $course['description']
                                    ? '<span class="d-inline-block text-truncate" style="max-width:200px;" title="'.htmlspecialchars($course['description']).'">'.htmlspecialchars($course['description']).'</span>'
                                    : '<span class="text-muted">No description</span>' ?>
                            </td>
                            <td>
                                <button class="btn btn-edit btn-action" onclick='editCourse(<?= json_encode($course) ?>)' title="Edit Course"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-delete btn-action" onclick='deleteCourse(<?= $course["course_id"] ?>, "<?= addslashes($course["course_name"]) ?>")' title="Delete Course"><i class="fas fa-trash"></i></button>
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
            <div class="modal-header"><h5 class="modal-title">Add New Course</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="addCourseForm">
                    <input type="hidden" name="action" value="add_course">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Name *</label>
                            <input type="text" class="form-control" name="course_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Type *</label>
                            <input type="text" class="form-control" name="course_type" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration (Days) *</label>
                            <input type="number" class="form-control" name="duration_days" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Fee (LKR) *</label>
                            <input type="number" class="form-control" name="course_fee" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Optional description"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="submitCourseForm('add')">Add Course</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Course</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="editCourseForm">
                    <input type="hidden" name="action" value="edit_course">
                    <input type="hidden" name="course_id" id="edit_course_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Name *</label>
                            <input type="text" class="form-control" name="course_name" id="edit_course_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Type *</label>
                            <input type="text" class="form-control" name="course_type" id="edit_course_type" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration (Days) *</label>
                            <input type="number" class="form-control" name="duration_days" id="edit_duration_days" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Fee (LKR) *</label>
                            <input type="number" class="form-control" name="course_fee" id="edit_course_fee" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="submitCourseForm('edit')">Update Course</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    const apiURL = '/SafePathObserver/app/API/course_api.php';

    function submitCourseForm(type) {
        const form = document.getElementById(type === 'add' ? 'addCourseForm' : 'editCourseForm');
        const formData = new FormData(form);

        fetch(apiURL, {
            method: 'POST',
            body: formData
        }).then(res => res.json())
          .then(data => {
              if (data.success) {
                  alert(data.message);
                  location.reload();
              } else {
                  alert('Error: ' + data.message);
              }
          }).catch(err => {
              console.error(err);
              alert('Something went wrong.');
          });
    }

    function editCourse(course) {
        document.getElementById('edit_course_id').value = course.course_id;
        document.getElementById('edit_course_name').value = course.course_name;
        document.getElementById('edit_course_type').value = course.course_type;
        document.getElementById('edit_duration_days').value = course.duration_days;
        document.getElementById('edit_course_fee').value = course.course_fee;
        document.getElementById('edit_description').value = course.description || '';
        new bootstrap.Modal(document.getElementById('editCourseModal')).show();
    }

    function deleteCourse(courseId, courseName) {
        if (confirm(`Are you sure you want to delete "${courseName}"?`)) {
            const formData = new FormData();
            formData.append('action', 'delete_course');
            formData.append('course_id', courseId);

            fetch(apiURL, {
                method: 'POST',
                body: formData
            }).then(res => res.json())
              .then(data => {
                  if (data.success) {
                      alert(data.message);
                      location.reload();
                  } else {
                      alert('Error: ' + data.message);
                  }
              }).catch(err => {
                  console.error(err);
                  alert('Something went wrong.');
              });
        }
    }

    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFontSize(20).text('SafePathObserver - Course Details', 20, 20);
        doc.setFontSize(12).text(`Generated on: ${new Date().toLocaleDateString()}`, 20, 35);

        const table = document.getElementById('coursesTable');
        const rows = [];

        table.querySelectorAll('tbody tr').forEach(row => {
            if (row.cells.length > 1) {
                const rowData = Array.from(row.cells).slice(0, 6).map(cell => cell.textContent.trim());
                rows.push(rowData);
            }
        });

        doc.autoTable({
            head: [['Course ID', 'Course Name', 'Type', 'Duration', 'Fee (LKR)', 'Description']],
            body: rows,
            startY: 45,
            theme: 'grid',
            headStyles: { fillColor: [0, 123, 255], textColor: 255 },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });

        doc.save('SafePathObserver-Courses.pdf');
    }
</script>
</body>
</html>

<?php
$content = ob_get_clean();
include '../components/layout.php';
