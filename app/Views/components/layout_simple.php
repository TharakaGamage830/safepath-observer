<?php
// Temporary layout without database dependency
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mock user data for testing
$user = [
    'user_id' => 1,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'role' => 'student',
    'profile_picture' => null
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SafePath Observer</title>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
      overflow-x: hidden;
    }

    .sidebar {
      width: 250px;
      position: fixed;
      top: 60px;
      left: 0;
      height: calc(100vh - 60px);
      background-color: #fff;
      border-right: 1px solid #dee2e6;
      z-index: 1030;
      transition: left 0.3s ease;
    }

    .sidebar.show {
      left: 0;
    }

    .main-content {
      margin-left: 250px;
      margin-top: 60px;
      min-height: calc(100vh - 60px);
      padding: 20px;
      overflow-y: auto;
    }

    .navbar {
      height: 60px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1040;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1029;
      display: none;
    }

    .overlay.show {
      display: block;
    }

    @media (max-width: 768px) {
      .sidebar {
        left: -250px;
      }
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>
  <!-- Simple Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
      <button class="btn btn-outline-secondary d-md-none" type="button" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
      </button>
      <a class="navbar-brand ms-2" href="#">
        <i class="bi bi-shield-check text-primary"></i>
        <strong>SafePath Observer</strong>
      </a>
      <div class="navbar-nav ms-auto">
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <div class="user-avatar">
              <i class="bi bi-person text-white"></i>
            </div>
            <?php echo htmlspecialchars($user['name']); ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../../../login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Simple Sidebar -->
  <div class="sidebar">
    <div class="p-3">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="student-dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="bi bi-calendar-check me-2"></i>Attendance
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="bi bi-book me-2"></i>Subjects
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="bi bi-award me-2"></i>Certificates
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Scrollable Page Content -->
  <div class="main-content">
    <?php
    if (isset($content)) {
      echo $content;
    } else {
      echo '<h1>Welcome to SafePath Observer</h1>';
    }
    ?>
  </div>

  <!-- Overlay for mobile -->
  <div class="overlay" id="overlay"></div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .user-avatar {
      width: 32px;
      height: 32px;
      background-color: #6c757d;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 8px;
    }
  </style>

  <script>
    // Mobile sidebar toggle
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.getElementById('overlay');

      sidebar.classList.toggle('show');
      overlay.classList.toggle('show');
    }

    // Hide sidebar on overlay click
    document.getElementById('overlay').addEventListener('click', function () {
      toggleSidebar();
    });

    // Remove overlay on resize
    window.addEventListener('resize', function () {
      if (window.innerWidth > 768) {
        document.querySelector('.sidebar').classList.remove('show');
        document.getElementById('overlay').classList.remove('show');
      }
    });
  </script>
</body>
</html>
