<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f8fafc;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            color: var(--dark-color);
            padding: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .icon-students {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .icon-instructors {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .icon-courses {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .icon-revenue {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .dashboard-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            text-align: center;
        }

        .dashboard-title {
            color: var(--dark-color);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-subtitle {
            color: #64748b;
            font-size: 1.1rem;
        }

        .table-custom {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table-custom thead {
            background: var(--primary-color);
            color: white;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .refresh-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
            border-radius: 50px;
            padding: 1rem 1.5rem;
            background: var(--primary-color);
            border: none;
            color: white;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
            transition: all 0.3s ease;
        }

        .refresh-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(37, 99, 235, 0.4);
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-pie me-3" style="color: var(--primary-color);"></i>
                Dashboard Overview
            </h1>
            <p class="dashboard-subtitle">Driving School Management System Statistics</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Students</div>
                            <h2 class="stat-number" id="total-students">1230</h2>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>+12% from last month
                            </small>
                        </div>
                        <div class="stat-icon icon-students">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Instructors</div>
                            <h2 class="stat-number" id="total-instructors">30</h2>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>+5% from last month
                            </small>
                        </div>
                        <div class="stat-icon icon-instructors">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Active Courses</div>
                            <h2 class="stat-number" id="total-courses">45</h2>
                            <small class="text-warning">
                                <i class="fas fa-minus me-1"></i>No change
                            </small>
                        </div>
                        <div class="stat-icon icon-courses">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Monthly Revenue</div>
                            <h2 class="stat-number" id="total-revenue">$24.5K</h2>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>+18% from last month
                            </small>
                        </div>
                        <div class="stat-icon icon-revenue">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics Row -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-container">
                    <div class="text-center">
                        <div class="stat-icon icon-courses mx-auto mb-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h6 class="text-muted mb-2">Course Completion Rate</h6>
                        <h3 class="text-success mb-0" id="completion-rate">87%</h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>+3% this month
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-container">
                    <div class="text-center">
                        <div class="stat-icon icon-revenue mx-auto mb-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h6 class="text-muted mb-2">Average Study Time</h6>
                        <h3 class="text-primary mb-0" id="study-time">5.2hrs</h3>
                        <small class="text-muted">per student/day</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-container">
                    <div class="text-center">
                        <div class="stat-icon icon-students mx-auto mb-3">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h6 class="text-muted mb-2">Certificates Issued</h6>
                        <h3 class="text-warning mb-0" id="certificates">324</h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>+28 this week
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="chart-container mt-4">
            <h5 class="mb-4">
                <i class="fas fa-users me-2" style="color: var(--primary-color);"></i>
                Recent Student Registrations
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="students-table">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Floating Refresh Button -->
        <button class="btn refresh-btn" onclick="refreshData()" title="Refresh Data">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>

        <!-- Loading Spinner -->
        <div class="loading-spinner" id="loading-spinner">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading dashboard data...</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


    <script>
        // Sample data - In real application, this would come from PHP/MySQL
        const dashboardData = {
            students: [
                { id: 'STU001', name: 'John Smith', email: 'john@email.com', course: 'Web Development', date: '2025-07-28', status: 'Active' },
                { id: 'STU002', name: 'Sarah Johnson', email: 'sarah@email.com', course: 'Data Science', date: '2025-07-27', status: 'Active' },
                { id: 'STU003', name: 'Mike Chen', email: 'mike@email.com', course: 'Mobile Development', date: '2025-07-26', status: 'Pending' },
                { id: 'STU004', name: 'Emily Davis', email: 'emily@email.com', course: 'UI/UX Design', date: '2025-07-25', status: 'Active' },
                { id: 'STU005', name: 'David Wilson', email: 'david@email.com', course: 'Cybersecurity', date: '2025-07-24', status: 'Active' },
                { id: 'STU006', name: 'Lisa Brown', email: 'lisa@email.com', course: 'Machine Learning', date: '2025-07-23', status: 'Active' },
                { id: 'STU007', name: 'Alex Garcia', email: 'alex@email.com', course: 'Cloud Computing', date: '2025-07-22', status: 'Pending' }
            ],
            enrollmentData: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                data: [120, 190, 300, 500, 200, 300, 450]
            },
            categoryData: {
                labels: ['Web Development', 'Data Science', 'Mobile Dev', 'UI/UX', 'Cybersecurity'],
                data: [25, 20, 15, 20, 20]
            }
        };

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            populateStudentsTable();
            animateCounters();
        });



        // Populate students table
        function populateStudentsTable() {
            const tbody = document.getElementById('students-table');
            tbody.innerHTML = '';

            dashboardData.students.forEach(student => {
                const statusClass = student.status === 'Active' ? 'success' : 'warning';
                const row = `
                    <tr>
                        <td><strong>${student.id}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                </div>
                                ${student.name}
                            </div>
                        </td>
                        <td>${student.email}</td>
                        <td><span class="badge bg-light text-dark">${student.course}</span></td>
                        <td>${student.date}</td>
                        <td><span class="badge bg-${statusClass}">${student.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="viewStudent('${student.id}')" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="editStudent('${student.id}')" title="Edit Student">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Animate counters
        function animateCounters() {
            const counters = [
                { element: 'total-students', target: 1230 },
                { element: 'total-instructors', target: 30 },
                { element: 'total-courses', target: 45 }
            ];

            counters.forEach(counter => {
                animateCounter(counter.element, counter.target);
            });
        }

        function animateCounter(elementId, target) {
            const element = document.getElementById(elementId);
            element.textContent = '0';
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 20);
        }

        // Action functions
        function refreshData() {
            const spinner = document.getElementById('loading-spinner');
            const content = document.querySelector('.container-fluid');
            
            // Show spinner
            spinner.style.display = 'block';
            content.style.opacity = '0.5';
            
            // Simulate API call
            setTimeout(() => {
                spinner.style.display = 'none';
                content.style.opacity = '1';
                
                // Re-animate counters
                animateCounters();
                
                // Show success message
                showToast('Dashboard data refreshed successfully!', 'success');
            }, 2000);
        }

        function viewStudent(studentId) {
            const student = dashboardData.students.find(s => s.id === studentId);
            if (student) {
                showToast(`Viewing details for ${student.name}`, 'info');
            }
        }

        function editStudent(studentId) {
            const student = dashboardData.students.find(s => s.id === studentId);
            if (student) {
                showToast(`Edit mode for ${student.name}`, 'warning');
            }
        }

        // Utility function for toast notifications
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-info-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            // Add to container
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.appendChild(toast);
            
            // Show toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove after hiding
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        // Auto-refresh every 5 minutes
        setInterval(() => {
            refreshData();
        }, 300000);
    </script>
</body>
</html>