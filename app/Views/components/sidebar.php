<?php
// Start session and DB connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../config/constants.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login/logout.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT user_id, name, email, role, profile_picture FROM users WHERE user_id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: /login/index.php');
        exit();
    }
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

$current_page = basename($_SERVER['PHP_SELF'], '.php');

$menu_items = [];

if ($user['role'] === 'admin') {
    $menu_items = [
        ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'admin-dashboard.php', 'active' => ($current_page === 'admin-dashboard')],
        ['id' => 'students', 'label' => 'Students', 'icon' => 'bi-people', 'url' => 'student_table.php', 'active' => ($current_page === 'student_table')],
        ['id' => 'instructor', 'label' => 'Instructors', 'icon' => 'bi-person-badge', 'url' => 'instructor-detail-form.php', 'active' => ($current_page === 'instructor-detail-form')],
        ['id' => 'courses', 'label' => 'Courses', 'icon' => 'bi-book', 'url' => 'course-detail-form.php', 'active' => ($current_page === 'course-detail-form')],
        ['id' => 'aboutus', 'label' => 'About-us', 'icon' => 'bi-wheel', 'url' => 'aboutus.php', 'active' => ($current_page === 'aboutus')]
    ];
} elseif ($user['role'] === 'instructor') {
    $menu_items = [
        ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'instructor-dashboard.php', 'active' => ($current_page === 'instructor-dashboard')],
        ['id' => 'attendance', 'label' => 'Attendance', 'icon' => 'bi-book', 'url' => 'attendance_management.php', 'active' => ($current_page === 'attendance_management')],
    ];
} elseif ($user['role'] === 'student') {
    $menu_items = [
        ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'student-dashboard.php', 'active' => ($current_page === 'student-dashboard')],
    ];
}
?>

<style>
.sidebar {
    width: 250px;
    height: calc(100vh - 60px); /* Fixed height calculation */
    position: fixed;
    top: 60px;
    left: 0;
    background-color: #fff;
    border-right: 1px solid #dee2e6;
    z-index: 1030;
    display: flex;
    flex-direction: column;
    overflow-y: auto; /* Allow scrolling if content is too long */
}

.sidebar-nav {
    flex-grow: 1;
    padding-top: 1rem;
    overflow-y: auto;
}

.sidebar-item {
    padding: 12px 20px;
    color: #6c757d;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    font-weight: 500;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
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
    font-size: 1.1rem;
}

.sidebar-footer {
    border-top: 1px solid #dee2e6;
    padding: 10px 0;
    margin-top: auto; /* Push to bottom */
    flex-shrink: 0; /* Don't shrink */
}

.sidebar-footer .sidebar-item {
    margin: 0;
}

.sidebar-footer .sidebar-item.text-danger:hover {
    background-color: #f8d7da;
    color: #842029;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .sidebar {
        left: -250px;
        transition: left 0.3s ease;
    }
    
    .sidebar.show {
        left: 0;
    }
}
</style>

<div class="sidebar" id="sidebar">
    <!-- Main Navigation -->
    <div class="sidebar-nav">
        <?php foreach ($menu_items as $item): ?>
            <a href="<?php echo htmlspecialchars($item['url']); ?>" class="sidebar-item <?php echo $item['active'] ? 'active' : ''; ?>">
                <i class="bi <?php echo htmlspecialchars($item['icon']); ?>"></i>
                <?php echo htmlspecialchars($item['label']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Footer Section with Settings and Logout -->
    <div class="sidebar-footer">
        <a href="#" class="sidebar-item">
            <i class="bi bi-gear"></i>
            Settings
        </a>
        <a href="../../../login/logout.php" class="sidebar-item text-danger">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>
    </div>
</div>

<script>
// Ensure sidebar shows properly on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sidebar loaded');
});
</script>