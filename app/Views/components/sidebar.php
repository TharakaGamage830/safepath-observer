<?php
// Get current page to highlight active menu item
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Define menu items based on user role
$menu_items = [];

if ($user['role'] === 'admin') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'admin_dashboard.php',
            'active' => ($current_page === 'admin_dashboard' || $current_page === 'index')
        ],
        [
            'id' => 'students',
            'label' => 'Students',
            'icon' => 'bi-people',
            'url' => 'admin_student_management.php',
            'active' => ($current_page === 'admin_student_management')
        ],
        [
            'id' => 'instructors',
            'label' => 'Instructors',
            'icon' => 'bi-person-badge',
            'url' => 'admin_instructor_management.php',
            'active' => ($current_page === 'admin_instructor_management')
        ],
        [
            'id' => 'courses',
            'label' => 'Courses',
            'icon' => 'bi-book',
            'url' => 'admin_courses_management.php',
            'active' => ($current_page === 'admin_courses_management')
        ]
    ];
} elseif ($user['role'] === 'instructor') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'instructor_dashboard.php',
            'active' => ($current_page === 'instructor_dashboard' || $current_page === 'index')
        ]
    ];
} elseif ($user['role'] === 'student') {
    $menu_items = [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'student_dashboard.php',
            'active' => ($current_page === 'student_dashboard' || $current_page === 'index')
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
        <a href="logout.php" class="sidebar-item text-danger">
            <i class="bi bi-box-arrow-left"></i>
            Logout
        </a>
    </div>
</div>