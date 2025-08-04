<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user_id not set
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login/logout.php');
    exit();
}

// // Connect to DB 
require_once '../../../config/constants.php';

try {
    // Fetch user info from database
    $stmt = $pdo->prepare("SELECT user_id, name, email, role, profile_picture FROM users WHERE user_id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // If user not found, logout and redirect
        session_destroy();
        header('Location: /login/index.php');
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
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
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?>

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

    // Initialize Bootstrap components
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize all Bootstrap dropdowns
      var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
      var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
      });
      
      console.log('Layout loaded, Bootstrap initialized');
    });
  </script>
</body>
</html>