<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Mock user data - replace with your actual authentication system
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'name' => 'Admin User',
        'role' => 'admin' // Change to 'student' or 'instructor' for testing
    ];
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafePath Observer</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            transition: all 0.3s;
        }
        
        .sidebar-collapsed {
            margin-left: -250px;
        }
        
        .main-content {
            transition: all 0.3s;
            margin-left: 0;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        .navbar-brand .text-primary {
            color: #0d6efd !important;
        }
        
        .navbar-brand .text-warning {
            color: #ffc107 !important;
        }
        
        .sidebar-item {
            padding: 12px 20px;
            color: #6c757d;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .sidebar-item:hover {
            background-color: #f8f9fa;
            color: #495057;
            text-decoration: none;
        }
        
        .sidebar-item.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                left: -250px;
                width: 250px;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }
            
            .overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <?php include 'sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    <?php
                    // Content will be included here based on the page
                    if (isset($content)) {
                        echo $content;
                    } else {
                        echo '<h1>Welcome to SafePath Observer</h1>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('overlay');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }
        
        // Close sidebar when clicking overlay
        document.getElementById('overlay').addEventListener('click', function() {
            toggleSidebar();
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.sidebar').classList.remove('show');
                document.getElementById('overlay').classList.remove('show');
            }
        });
    </script>
</body>
</html>