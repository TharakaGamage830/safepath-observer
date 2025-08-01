<?php
// Get current page to highlight active menu item
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Define menu items based on user role
$menu_items = [];
$user = [ 'name' => 'John Doe','role' => 'admin',];
if ($user['role'] === 'admin') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'admin-dashboard.php',
            'active' => ($current_page === 'admin-dashboard')
        ],
        [
            'id' => 'students',
            'label' => 'Students',
            'icon' => 'bi-people',
            'url' => 'students-details-form.php',
            'active' => ($current_page === 'students-details-form')
        ],
        [
            'id' => 'instructor',
            'label' => 'Instructors',
            'icon' => 'bi-person-badge',
            'url' => 'instructor-detail-form.php',
            'active' => ($current_page === 'instructor-detail-form')
        ],
        [
            'id' => 'courses',
            'label' => 'Courses',
            'icon' => 'bi-book',
            'url' => 'course-detail-form.php',
            'active' => ($current_page === 'course-detail-form')
        ]
    ];
} elseif ($user['role'] === 'admin') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'admin-dashboard.php',
            'active' => ($current_page === 'admin-dashboard')
        ]
    ];
}elseif ($user['role'] === 'instructor') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'instructor-dashboard.php',
            'active' => ($current_page === 'instructor-dashboard')
        ]
    ];
} elseif ($user['role'] === 'student') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'student-dashboard.php',
            'active' => ($current_page === 'student-dashboard')
        ]
    ];
}
?>

<div class="sidebar d-flex flex-column">
    <!-- Sidebar Menu -->
    <div class="flex-grow-1">
        <nav class="mt-3">
            <?php foreach ($menu_items as $item): ?>
                <a href="<?php echo $item['url']; ?>" 
                   class="sidebar-item <?php echo $item['active'] ? 'active' : ''; ?>">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                    <?php echo $item['label']; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
    
    <!-- Sidebar Footer -->
    <div class="border-top mt-auto">
        <a href="#" class="sidebar-item">
            <i class="bi bi-gear"></i>
            Setting
        </a>
        <a href="../../../login/index.php" class="sidebar-item text-danger">
            <i class="bi bi-box-arrow-left"></i>
            Logout
        </a>
    </div>
</div>