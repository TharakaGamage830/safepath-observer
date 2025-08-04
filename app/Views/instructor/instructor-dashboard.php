<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Instructor Dashboard - SafePathObserver</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .dashboard-header {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white; padding: 2rem 0; margin-bottom: 2rem;
    }
    .stat-card {
      background: white; border-radius: 15px; padding: 1.5rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border-left: 4px solid; margin-bottom: 1rem;
    }
    .stat-card.present { border-left-color: #28a745; }
    .stat-card.absent { border-left-color: #dc3545; }
    .stat-card.total { border-left-color: #007bff; }

    .student-card {
      background: white; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      transition: transform 0.2s ease; margin-bottom: 1.5rem; overflow: hidden;
    }
    .student-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.12); }

    .profile-img {
      width: 80px; height: 80px; object-fit: cover;
      border: 3px solid #f0f0f0;
    }

    .progress-bar-custom {
      height: 10px; background-color: #e9ecef; border-radius: 5px; overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #3b82f6, #1d4ed8);
      border-radius: 5px; transition: width 0.3s ease;
    }

    .status-badge {
      padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
    }
    .status-present { background-color: #d1fae5; color: #065f46; }
    .status-absent { background-color: #fee2e2; color: #991b1b; }
  </style>
</head>
<body>

<!-- Header -->
<div class="dashboard-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1><i class="fas fa-chalkboard-teacher me-3"></i>Instructor Dashboard</h1>
        <h2>Welcome, <?= htmlspecialchars($instructorName ?? 'Instructor') ?></h2>
        <small class="opacity-75">Here are the students you are currently training.</small>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <!-- Summary -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="stat-card present">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-success mb-1"><?= $today_summary['present_count'] ?? 0 ?></h3>
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
            <h3 class="text-danger mb-1"><?= $today_summary['absent_count'] ?? 0 ?></h3>
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
            <h3 class="text-primary mb-1"><?= $today_summary['total_marked'] ?? 0 ?></h3>
            <p class="mb-0 text-muted">Total Students</p>
          </div>
          <i class="fas fa-users fa-2x text-primary"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Students -->
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
        $progress = round($student['progress_percentage']);
        $today_status = strtolower($student['today_status']) === 'present' ? 'Present' : 'Absent';
        $status_class = strtolower($student['today_status']) === 'present' ? 'status-present' : 'status-absent';
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="student-card">
          <div class="card-body p-4 text-center">
            <img src="<?= !empty($student['profile_picture']) 
              ? '../../../public/images/profiles/' . htmlspecialchars($student['profile_picture']) 
              : '../../../public/images/default-avatar.png' ?>" 
              alt="Student" class="profile-img rounded-circle mb-2">
            <h5 class="mb-1"><?= htmlspecialchars($student['student_name']) ?></h5>
            <p class="text-muted small mb-3"><?= htmlspecialchars($student['email']) ?></p>

            <div class="mb-3">
              <div class="d-flex justify-content-between mb-1">
                <span class="small text-muted">Progress</span>
                <span class="small fw-bold"><?= $progress ?>%</span>
              </div>
              <div class="progress-bar-custom">
                <div class="progress-fill" style="width: <?= $progress ?>%"></div>
              </div>
            </div>

            <div class="mb-2">
              <div class="small text-muted mb-1">Today's Status</div>
              <span class="status-badge <?= $status_class ?>"><?= $today_status ?></span>
            </div>

            <div>
              <small class="text-muted d-block">Course: <?= htmlspecialchars($student['course_name']) ?></small>
              <small class="text-muted">Attended: <?= $student['days_present'] ?>/<?= $student['duration_days'] ?> days</small>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();
include '../components/layout.php';
?>
