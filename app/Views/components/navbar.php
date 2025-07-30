<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-outline-secondary d-md-none me-2" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Brand -->
        <!-- <a class="navbar-brand" href="index.php"> -->
        <img src="../../../public/images/safe-path-observer-logo.png" alt="SafePath" width="200" height="200">
            <i class="bi bi-shield-check text-primary me-2"></i>
            <span class="text-primary">SafePath</span>
            <span class="text-warning">Observer</span>
        </a>
        
        <!-- Search Bar -->
        <div class="d-none d-lg-flex flex-grow-1 justify-content-center">
            <div class="input-group" style="max-width: 500px;">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Search" style="background-color: #f8f9fa;">
            </div>
        </div>
        
        <!-- User Menu -->
        <div class="navbar-nav">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bi bi-person text-white"></i>
                    </div>
                    <span class="d-none d-md-inline"><?php echo htmlspecialchars($user['name']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Signed in as</h6></li>
                    <li><span class="dropdown-item-text"><?php echo htmlspecialchars($user['name']); ?></span></li>
                    <li><span class="dropdown-item-text small text-muted">Role: <?php echo ucfirst($user['role']); ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
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
            <input type="text" class="form-control border-start-0" placeholder="Search" style="background-color: #f8f9fa;">
        </div>
    </div>
</nav>