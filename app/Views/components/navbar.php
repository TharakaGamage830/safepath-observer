<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    // Redirect unauthorized users to login page
    header('Location: ../../../login/logout.php');
    exit();
}

$user = $_SESSION['user'];
?>

<style>
  .navbar-custom {
    height: 60px;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1040;
    background-color: #fff !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
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
  
  .dropdown-toggle::after {
    margin-left: 8px;
  }
  
  .dropdown-menu {
    min-width: 200px;
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .dropdown-item {
    padding: 8px 16px;
  }
  
  .dropdown-item:hover {
    background-color: #f8f9fa;
  }
  
  .dropdown-item.text-danger:hover {
    background-color: #f8d7da;
    color: #842029;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom navbar-custom">
  <div class="container-fluid">
    <!-- Mobile Sidebar Toggle -->
    <button class="btn btn-outline-secondary d-md-none me-2" type="button" onclick="toggleSidebar()">
      <i class="bi bi-list"></i>
    </button>
    
    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="../../../public/index.php">
      <img src="../../../public/images/safe-path-observer-logo.png" alt="SafePath Observer Logo" width="40" height="40" class="me-2" />
      <span class="text-primary fw-bold">SafePath</span>
      <span class="text-warning fw-bold">Observer</span>
    </a>
    
    <!-- Search Bar -->
    <div class="d-none d-lg-flex flex-grow-1 justify-content-center">
      <div class="input-group" style="max-width: 500px;">
        <span class="input-group-text bg-light border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text" class="form-control border-start-0" placeholder="Search" style="background-color: #f8f9fa;" />
      </div>
    </div>

    <!-- User Menu -->
    <div class="navbar-nav">
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none" 
           href="#" 
           id="userDropdown" 
           role="button" 
           data-bs-toggle="dropdown" 
           aria-expanded="false"
           style="cursor: pointer;">
          <div class="user-avatar">
            <i class="bi bi-person text-white"></i>
          </div>
          <span class="d-none d-md-inline text-dark"><?php echo htmlspecialchars($user['name']); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
          <li><h6 class="dropdown-header">Signed in as</h6></li>
          <li><span class="dropdown-item-text fw-bold"><?php echo htmlspecialchars($user['name']); ?></span></li>
          <li><span class="dropdown-item-text small text-muted"><?php echo htmlspecialchars($user['email']); ?></span></li>
          <li><span class="dropdown-item-text small text-muted">Role: <?php echo ucfirst(htmlspecialchars($user['role'])); ?></span></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item text-danger" href="../../../login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
  
  <!-- Mobile Search (Below navbar on mobile) -->
  <div class="d-lg-none w-100 px-3 pb-3">
    <div class="input-group">
      <span class="input-group-text bg-light border-end-0">
        <i class="bi bi-search text-muted"></i>
      </span>
      <input type="text" class="form-control border-start-0" placeholder="Search" style="background-color: #f8f9fa;" />
    </div>
  </div>
</nav>

<script>
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('overlay');
    if (sidebar) sidebar.classList.toggle('show');
    if (overlay) overlay.classList.toggle('show');
  }
  
  // Ensure Bootstrap dropdown works
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    console.log('Navbar dropdowns initialized');
  });
</script>