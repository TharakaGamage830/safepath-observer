-- SafePathObserver Database Schema



-- Users table (for both students and instructors)
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('student','instructor','admin') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Courses table
CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `course_type` varchar(100) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `course_fee` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`),
  INDEX `idx_course_type` (`course_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Student Course Assignments table (linking students to instructors and courses)
CREATE TABLE `student_course_assignments` (
  `assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrollment_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('active','completed','dropped') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`assignment_id`),
  FOREIGN KEY (`student_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`instructor_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_student_course` (`student_id`, `course_id`),
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_instructor_id` (`instructor_id`),
  INDEX `idx_course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Attendance table
CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`attendance_id`),
  FOREIGN KEY (`student_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`instructor_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_student_date` (`student_id`, `attendance_date`),
  INDEX `idx_student_date` (`student_id`, `attendance_date`),
  INDEX `idx_instructor_date` (`instructor_id`, `attendance_date`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data Insertion

-- Insert sample users (password should be hashed in real application)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `profile_picture`) VALUES
('Tharaka Gamage', 'tharaka@gmail.com', '$2y$10$example_hashed_password', 'student', NULL),
('John Instructor', 'john@gmail.com', '$2y$10$example_hashed_password', 'instructor', NULL),
('Lahiru Gamage', 'lahiru@gmail.com', '$2y$10$example_hashed_password', 'student', NULL),
('Gimbani Abinsa', 'gimbani@gmail.com', '$2y$10$example_hashed_password', 'student', NULL),
('Admin', 'admin@gmail.com', '$2y$20$example_hashed_password', 'admin', NULL);

-- Insert sample courses
INSERT INTO `courses` (`course_name`, `course_type`, `duration_days`, `course_fee`, `description`) VALUES
('Basic Light Vehicle', 'Light Vehicle Training', 30, 25000.00, 'Complete light vehicle training program'),
('Heavy Vehicle Training', 'Heavy Vehicle Training', 45, 45000.00, 'Professional heavy vehicle training'),
('Motorcycle Training', 'Motorcycle Training', 20, 15000.00, 'Comprehensive motorcycle riding training');

-- Insert sample course assignments
INSERT INTO `student_course_assignments` (`student_id`, `instructor_id`, `course_id`, `enrollment_date`) VALUES
(1, 2, 1, '2025-07-01'),
(3, 2, 2, '2025-07-01'),
(4, 2, 1, '2025-07-01');

-- Insert sample attendance records
INSERT INTO `attendance` (`student_id`, `instructor_id`, `course_id`, `attendance_date`, `status`) VALUES
(1, 2, 1, '2025-07-30', 'present'),
(1, 2, 1, '2025-07-31', 'absent'),
(3, 2, 2, '2025-07-30', 'present'),
(3, 2, 2, '2025-07-31', 'present'),
(4, 2, 1, '2025-07-30', 'absent'),
(4, 2, 1, '2025-07-31', 'present');

-- Views for easier data access

-- View for student progress
CREATE VIEW `student_progress_view` AS
SELECT 
    u.user_id as student_id,
    u.name as student_name,
    u.email,
    u.profile_picture,
    c.course_name,
    c.course_type,
    c.duration_days,
    sca.instructor_id,
    sca.enrollment_date,
    COUNT(CASE WHEN a.status = 'present' THEN 1 END) as days_present,
    COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as days_absent,
    COUNT(a.attendance_id) as total_marked_days,
    CASE 
        WHEN c.duration_days > 0 THEN 
            ROUND((COUNT(CASE WHEN a.status = 'present' THEN 1 END) / c.duration_days) * 100, 2)
        ELSE 0 
    END as progress_percentage
FROM users u
INNER JOIN student_course_assignments sca ON u.user_id = sca.student_id
INNER JOIN courses c ON sca.course_id = c.course_id
LEFT JOIN attendance a ON u.user_id = a.student_id
WHERE u.role = 'student' AND sca.status = 'active'
GROUP BY u.user_id, u.name, u.email, u.profile_picture, c.course_name, c.course_type, c.duration_days, sca.instructor_id, sca.enrollment_date;

-- View for daily attendance summary
CREATE VIEW `daily_attendance_summary` AS
SELECT 
    DATE(a.attendance_date) as attendance_date,
    a.instructor_id,
    COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present_count,
    COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_count,
    COUNT(a.attendance_id) as total_marked
FROM attendance a
GROUP BY DATE(a.attendance_date), a.instructor_id
ORDER BY attendance_date DESC;

-- Additional indexes for better performance
CREATE INDEX idx_attendance_date ON attendance(attendance_date);
CREATE INDEX idx_attendance_student_date ON attendance(student_id, attendance_date);
CREATE INDEX idx_attendance_instructor_date ON attendance(instructor_id, attendance_date);
CREATE INDEX idx_sca_student_instructor ON student_course_assignments(student_id, instructor_id);
CREATE INDEX idx_sca_status ON student_course_assignments(status);
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('student','instructor','admin') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes for better performance
CREATE INDEX idx_attendance_date ON attendance(attendance_date);
CREATE INDEX idx_attendance_student_date ON attendance(student_id, attendance_date);
CREATE INDEX idx_attendance_instructor_date ON attendance(instructor_id, attendance_date);
CREATE INDEX idx_sca_student_instructor ON student_course_assignments(student_id, instructor_id);
CREATE INDEX idx_sca_status ON student_course_assignments(status);