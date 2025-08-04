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

-- Instructors table (extends users table with instructor-specific information)
CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `national_id_number` varchar(50) DEFAULT NULL UNIQUE,
  `experience_years` int(11) DEFAULT NULL,
  `driving_license_number` varchar(50) DEFAULT NULL,
  `vehicle_type` varchar(100) DEFAULT NULL,

  `qualification_documents` text DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`instructor_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_instructor` (`user_id`),
  INDEX `idx_national_id` (`national_id_number`),
  INDEX `idx_license_number` (`driving_license_number`),
  INDEX `idx_vehicle_type` (`vehicle_type`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Students table (extends users table with student-specific information)
CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `national_id_number` varchar(50) DEFAULT NULL UNIQUE,
  `start_date` date DEFAULT NULL,
  `enrollment_status` enum('pending','active','completed','dropped','suspended') DEFAULT 'pending',
  `completion_date` date DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`instructor_id`) REFERENCES `instructors`(`instructor_id`) ON DELETE RESTRICT,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`) ON DELETE SET NULL,
  UNIQUE KEY `unique_user_student` (`user_id`),
  INDEX `idx_instructor_id` (`instructor_id`),
  INDEX `idx_course_id` (`course_id`),
  INDEX `idx_national_id` (`national_id_number`),
  INDEX `idx_enrollment_status` (`enrollment_status`),
  INDEX `idx_start_date` (`start_date`)
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

-- View to get complete student information
CREATE VIEW `student_details_view` AS
SELECT 
    s.student_id,
    u.user_id,
    u.name as student_name,
    u.email,
    u.phone,
    u.address,
    u.profile_picture,
    s.birth_date,
    s.gender,
    s.national_id_number,
    s.start_date,
    s.enrollment_status,
    s.completion_date,
    s.instructor_id,
    i.name as instructor_name,
    s.course_id,
    c.course_name,
    c.course_type,
    c.duration_days,
    c.course_fee,
    s.created_at as student_created_at,
    u.created_at as user_created_at
FROM students s
INNER JOIN users u ON s.user_id = u.user_id
LEFT JOIN instructors inst ON s.instructor_id = inst.instructor_id
LEFT JOIN users i ON inst.user_id = i.user_id
LEFT JOIN courses c ON s.course_id = c.course_id
WHERE u.role = 'student';

-- View for instructor's students
CREATE VIEW `instructor_students_view` AS
SELECT 
    s.student_id,
    u.name as student_name,
    u.email,
    u.phone,
    s.birth_date,
    s.enrollment_status,
    s.start_date,
    c.course_name,
    c.course_type,
    i.name as instructor_name,
    s.instructor_id,
    inst.instructor_id as instructor_table_id
FROM students s
INNER JOIN users u ON s.user_id = u.user_id
INNER JOIN instructors inst ON s.instructor_id = inst.instructor_id
INNER JOIN users i ON inst.user_id = i.user_id
LEFT JOIN courses c ON s.course_id = c.course_id
WHERE u.role = 'student' 
AND s.instructor_id IS NOT NULL;

-- View to get complete instructor information
CREATE VIEW `instructor_details_view` AS
SELECT 
    i.instructor_id,
    u.user_id,
    u.name,
    u.email,
    u.phone,
    u.address,
    u.profile_picture,
    i.birth_date,
    i.gender,
    i.national_id_number,
    i.experience_years,
    i.driving_license_number,
    i.vehicle_type,
    i.qualification_documents,
    i.status,
    i.hire_date,
    i.created_at as instructor_created_at,
    u.created_at as user_created_at
FROM instructors i
INNER JOIN users u ON i.user_id = u.user_id
WHERE u.role = 'instructor';

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


------Insertion data for all tables and views


-- SafePathObserver Database - Sri Lankan Mock Data

-- Clear existing data
DELETE FROM attendance;
DELETE FROM student_course_assignments;
DELETE FROM students;
DELETE FROM instructors;
DELETE FROM users WHERE role != 'admin';
DELETE FROM courses;

-- Insert Courses (using provided data)
INSERT INTO `courses` (`course_id`, `course_name`, `course_type`, `duration_days`, `course_fee`, `description`, `created_at`, `updated_at`) VALUES
(4, 'Light Motorcycle Training', 'A1', 10, 15000.00, 'Light Motorcycle (≤ 100CC). Can drive G1 category as well.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(5, 'Motorcycle Training', 'A', 12, 18000.00, 'Motorcycle (> 100CC). Can drive A1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(6, 'Light Tricycle/Van Training', 'B1', 14, 16000.00, 'Tricycle or van with tare weight ≤ 500kg. Can drive G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(7, 'Motor Car Training', 'B', 15, 20000.00, 'Motor Car (tare ≤ 2500kg). Can drive B1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(8, 'Dual-purpose Vehicle Training', 'C1', 16, 22000.00, 'Tare ≤ 2500kg. Can drive B1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(9, 'Ambulance/Hearse Training', 'C2', 16, 22000.00, 'Tare ≤ 2500kg. Can drive B1 and G1. Hospitality', '2025-08-01 09:10:41', '2025-08-01 09:39:53'),
(10, 'Light Lorry Training', 'C3', 18, 24000.00, 'Lorry with tare ≤ 2500kg. Can drive B1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(11, 'Heavy Car Training', 'CE', 20, 30000.00, 'Car with tare > 2500kg. Can drive CE1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(12, 'Heavy Dual-purpose Vehicle Training', 'CE2', 21, 32000.00, 'Can drive CE1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(13, 'Passenger Vehicle Training (≤ 32)', 'D', 18, 28000.00, 'Can drive D1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(14, 'Mini Bus Training (≤ 16)', 'D2', 17, 26000.00, 'Can drive D1 and G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(15, 'Special Purpose Vehicle Training', 'G', 15, 25000.00, 'Can drive G1.', '2025-08-01 09:10:41', '2025-08-01 09:10:41'),
(16, 'Land Vehicles Training', 'G1', 14, 22000.00, 'Land-based (e.g., tractors, rollers). Standalone category.', '2025-08-01 09:10:41', '2025-08-01 09:10:41');

-- Insert Admin User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `address`, `created_at`) VALUES
('System Administrator', 'admin@safepathobserver.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '+94711234567', 'No. 123, Galle Road, Colombo 03, Sri Lanka', '2025-01-01 00:00:00');

-- Insert Instructor Users
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `address`, `profile_picture`, `created_at`) VALUES
('Mahinda Rajapaksha', 'mahinda.rajapaksha@safepath.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', '+94712345678', 'No. 45, Kandy Road, Kurunegala, North Western Province, Sri Lanka', 'uploads/instructors/mahinda_profile.jpg', '2025-07-01 08:00:00'),
('Kamala Wijesinghe', 'kamala.wijesinghe@safepath.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', '+94723456789', 'No. 78, Matara Road, Galle, Southern Province, Sri Lanka', 'uploads/instructors/kamala_profile.jpg', '2025-07-01 09:00:00'),
('Pradeep Fernando', 'pradeep.fernando@safepath.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', '+94734567890', 'No. 156, Negombo Road, Katunayake, Western Province, Sri Lanka', 'uploads/instructors/pradeep_profile.jpg', '2025-07-02 07:30:00'),
('Nilanthi Perera', 'nilanthi.perera@safepath.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', '+94745678901', 'No. 234, Anuradhapura Road, Dambulla, Central Province, Sri Lanka', 'uploads/instructors/nilanthi_profile.jpg', '2025-07-05 10:15:00'),
('Sampath Gunawardena', 'sampath.gunawardena@safepath.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', '+94756789012', 'No. 89, Ratnapura Road, Embilipitiya, Sabaragamuwa Province, Sri Lanka', 'uploads/instructors/sampath_profile.jpg', '2025-07-08 11:30:00');

-- Insert Student Users
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `address`, `profile_picture`, `created_at`) VALUES
('Kasun Madhusanka', 'kasun.madhusanka@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94767890123', 'No. 67, Temple Road, Kandy, Central Province, Sri Lanka', 'uploads/students/kasun_profile.jpg', '2025-07-10 14:20:00'),
('Dilani Jayawardene', 'dilani.jayawardene@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94778901234', 'No. 123, Main Street, Jaffna, Northern Province, Sri Lanka', 'uploads/students/dilani_profile.jpg', '2025-07-12 16:45:00'),
('Ruwan Kumara', 'ruwan.kumara@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94789012345', 'No. 456, Colombo Road, Kalutara, Western Province, Sri Lanka', 'uploads/students/ruwan_profile.jpg', '2025-07-15 09:30:00'),
('Tharindu Wickramasinghe', 'tharindu.wickramasinghe@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94790123456', 'No. 789, Batticaloa Road, Ampara, Eastern Province, Sri Lanka', 'uploads/students/tharindu_profile.jpg', '2025-07-18 13:15:00'),
('Shani Mendis', 'shani.mendis@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94701234567', 'No. 321, Galle Road, Matara, Southern Province, Sri Lanka', 'uploads/students/shani_profile.jpg', '2025-07-20 11:00:00'),
('Chamara Silva', 'chamara.silva@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94712345678', 'No. 654, Chilaw Road, Puttalam, North Western Province, Sri Lanka', 'uploads/students/chamara_profile.jpg', '2025-07-22 15:45:00'),
('Nimesha Rathnayake', 'nimesha.rathnayake@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94723456789', 'No. 987, Badulla Road, Bandarawela, Uva Province, Sri Lanka', 'uploads/students/nimesha_profile.jpg', '2025-07-25 12:30:00'),
('Lahiru Gamage', 'lahiru.gamage@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94734567890', 'No. 159, Polonnaruwa Road, Habarana, North Central Province, Sri Lanka', 'uploads/students/lahiru_profile.jpg', '2025-07-28 10:15:00'),
('Malika Fernando', 'malika.fernando@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94745678901', 'No. 753, Vavuniya Road, Mannar, Northern Province, Sri Lanka', 'uploads/students/malika_profile.jpg', '2025-07-30 14:50:00'),
('Ishara Perera', 'ishara.perera@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94756789012', 'No. 852, Trincomalee Road, Batticaloa, Eastern Province, Sri Lanka', 'uploads/students/ishara_profile.jpg', '2025-08-01 16:20:00'),
('Sahan Dissanayake', 'sahan.dissanayake@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94767890123', 'No. 456, Kurunegala Road, Kegalle, Sabaragamuwa Province, Sri Lanka', 'uploads/students/sahan_profile.jpg', '2025-08-02 08:45:00'),
('Gayani Wickremasinghe', 'gayani.wickremasinghe@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+94778901234', 'No. 741, Horana Road, Panadura, Western Province, Sri Lanka', 'uploads/students/gayani_profile.jpg', '2025-08-03 13:25:00');

-- Insert instructor details using the actual user_ids from the query above
-- Replace these user_ids with the actual ones from your SELECT query
INSERT INTO `instructors` (`user_id`, `birth_date`, `gender`, `national_id_number`, `experience_years`, `driving_license_number`, `vehicle_type`, `status`, `hire_date`) VALUES
-- Mahinda Rajapaksha (should be user_id 2 if admin is 1)
((SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk'), '1978-03-15', 'male', '782751234V', 12, 'B1234567', 'Heavy Vehicle', 'active', '2020-01-15'),
-- Kamala Wijesinghe 
((SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk'), '1985-07-22', 'female', '856432109V', 8, 'B2345678', 'Light Vehicle', 'active', '2022-03-01'),
-- Pradeep Fernando
((SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk'), '1982-11-08', 'male', '821120987V', 10, 'B3456789', 'Motorcycle', 'active', '2021-06-10'),
-- Nilanthi Perera
((SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk'), '1980-09-14', 'female', '804567891V', 15, 'B4567890', 'Heavy Vehicle', 'active', '2019-08-20'),
-- Sampath Gunawardena
((SELECT user_id FROM users WHERE email = 'sampath.gunawardena@safepath.lk'), '1987-05-30', 'male', '873456789V', 7, 'B5678901', 'Light Vehicle', 'active', '2023-02-15');


-- Insert Student Details (using dynamic ID resolution)
INSERT INTO `students` (`user_id`, `instructor_id`, `course_id`, `birth_date`, `gender`, `national_id_number`, `start_date`, `enrollment_status`) VALUES
-- Kasun Madhusanka -> Mahinda Rajapaksha -> Motor Car Training
((SELECT user_id FROM users WHERE email = 'kasun.madhusanka@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
 '1995-12-10', 'male', '952341567V', '2025-07-15', 'active'),

-- Dilani Jayawardene -> Kamala Wijesinghe -> Motorcycle Training
((SELECT user_id FROM users WHERE email = 'dilani.jayawardene@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Motorcycle Training'), 
 '1998-04-25', 'female', '986785432V', '2025-07-16', 'active'),

-- Ruwan Kumara -> Mahinda Rajapaksha -> Motor Car Training
((SELECT user_id FROM users WHERE email = 'ruwan.kumara@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
 '1996-08-18', 'male', '963456789V', '2025-07-17', 'active'),

-- Tharindu Wickramasinghe -> Pradeep Fernando -> Light Motorcycle Training
((SELECT user_id FROM users WHERE email = 'tharindu.wickramasinghe@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
 '1999-01-05', 'male', '991234567V', '2025-07-18', 'active'),

-- Shani Mendis -> Kamala Wijesinghe -> Dual-purpose Vehicle Training
((SELECT user_id FROM users WHERE email = 'shani.mendis@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Dual-purpose Vehicle Training'), 
 '1997-11-30', 'female', '976543210V', '2025-07-19', 'active'),

-- Chamara Silva -> Nilanthi Perera -> Heavy Car Training
((SELECT user_id FROM users WHERE email = 'chamara.silva@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Heavy Car Training'), 
 '1994-06-12', 'male', '942345678V', '2025-07-20', 'active'),

-- Nimesha Rathnayake -> Mahinda Rajapaksha -> Light Tricycle/Van Training
((SELECT user_id FROM users WHERE email = 'nimesha.rathnayake@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Tricycle/Van Training'), 
 '2000-02-28', 'female', '005678901V', '2025-07-21', 'active'),

-- Lahiru Gamage -> Sampath Gunawardena -> Passenger Vehicle Training (≤ 32)
((SELECT user_id FROM users WHERE email = 'lahiru.gamage@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'sampath.gunawardena@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Passenger Vehicle Training (≤ 32)'), 
 '1993-09-15', 'male', '931234567V', '2025-07-22', 'active'),

-- Malika Fernando -> Pradeep Fernando -> Light Motorcycle Training
((SELECT user_id FROM users WHERE email = 'malika.fernando@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
 '1996-07-03', 'female', '964567890V', '2025-07-23', 'active'),

-- Ishara Perera -> Kamala Wijesinghe -> Light Lorry Training
((SELECT user_id FROM users WHERE email = 'ishara.perera@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Lorry Training'), 
 '1998-12-20', 'male', '987654321V', '2025-07-24', 'active'),

-- Sahan Dissanayake -> Nilanthi Perera -> Mini Bus Training (≤ 16)
((SELECT user_id FROM users WHERE email = 'sahan.dissanayake@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Mini Bus Training (≤ 16)'), 
 '1995-03-08', 'male', '950987654V', '2025-07-25', 'active'),

-- Gayani Wickremasinghe -> Mahinda Rajapaksha -> Ambulance/Hearse Training
((SELECT user_id FROM users WHERE email = 'gayani.wickremasinghe@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Ambulance/Hearse Training'), 
 '1999-10-17', 'female', '992345678V', '2025-07-26', 'active');


-- Insert Student Course Assignments (using user_id instead of student_id)
INSERT INTO `student_course_assignments` (`student_id`, `instructor_id`, `course_id`, `enrollment_date`, `status`) VALUES
-- Kasun Madhusanka -> Mahinda Rajapaksha -> Motor Car Training
((SELECT user_id FROM users WHERE email = 'kasun.madhusanka@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
 '2025-07-15', 'active'),

-- Dilani Jayawardene -> Kamala Wijesinghe -> Motorcycle Training
((SELECT user_id FROM users WHERE email = 'dilani.jayawardene@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Motorcycle Training'), 
 '2025-07-16', 'active'),

-- Ruwan Kumara -> Mahinda Rajapaksha -> Motor Car Training
((SELECT user_id FROM users WHERE email = 'ruwan.kumara@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
 '2025-07-17', 'active'),

-- Tharindu Wickramasinghe -> Pradeep Fernando -> Light Motorcycle Training
((SELECT user_id FROM users WHERE email = 'tharindu.wickramasinghe@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
 '2025-07-18', 'active'),

-- Shani Mendis -> Kamala Wijesinghe -> Dual-purpose Vehicle Training
((SELECT user_id FROM users WHERE email = 'shani.mendis@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Dual-purpose Vehicle Training'), 
 '2025-07-19', 'active'),

-- Chamara Silva -> Nilanthi Perera -> Heavy Car Training
((SELECT user_id FROM users WHERE email = 'chamara.silva@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Heavy Car Training'), 
 '2025-07-20', 'active'),

-- Nimesha Rathnayake -> Mahinda Rajapaksha -> Light Tricycle/Van Training
((SELECT user_id FROM users WHERE email = 'nimesha.rathnayake@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Tricycle/Van Training'), 
 '2025-07-21', 'active'),

-- Lahiru Gamage -> Sampath Gunawardena -> Passenger Vehicle Training (≤ 32)
((SELECT user_id FROM users WHERE email = 'lahiru.gamage@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'sampath.gunawardena@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Passenger Vehicle Training (≤ 32)'), 
 '2025-07-22', 'active'),

-- Malika Fernando -> Pradeep Fernando -> Light Motorcycle Training
((SELECT user_id FROM users WHERE email = 'malika.fernando@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
 '2025-07-23', 'active'),

-- Ishara Perera -> Kamala Wijesinghe -> Light Lorry Training
((SELECT user_id FROM users WHERE email = 'ishara.perera@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Light Lorry Training'), 
 '2025-07-24', 'active'),

-- Sahan Dissanayake -> Nilanthi Perera -> Mini Bus Training (≤ 16)
((SELECT user_id FROM users WHERE email = 'sahan.dissanayake@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Mini Bus Training (≤ 16)'), 
 '2025-07-25', 'active'),

-- Gayani Wickremasinghe -> Mahinda Rajapaksha -> Ambulance/Hearse Training
((SELECT user_id FROM users WHERE email = 'gayani.wickremasinghe@gmail.com'), 
 (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
 (SELECT course_id FROM courses WHERE course_name = 'Ambulance/Hearse Training'), 
 '2025-07-26', 'active');


-- Updated Attendance Records (using user_id for student reference)
    INSERT INTO `attendance` (`student_id`, `instructor_id`, `course_id`, `attendance_date`, `status`, `notes`) VALUES
    -- Week 1: 2025-07-21 to 2025-07-27
    -- Kasun Madhusanka (Motor Car Training)
    ((SELECT user_id FROM users WHERE email = 'kasun.madhusanka@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-07-21', 'present', 'Good performance in practical driving'),

    -- Dilani Jayawardene (Motorcycle Training)
    ((SELECT user_id FROM users WHERE email = 'dilani.jayawardene@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motorcycle Training'), 
    '2025-07-21', 'present', 'Excellent motorcycle handling skills'),

    -- Ruwan Kumara (Motor Car Training)
    ((SELECT user_id FROM users WHERE email = 'ruwan.kumara@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-07-21', 'absent', 'Family emergency'),

    -- Tharindu Wickramasinghe (Light Motorcycle Training)
    ((SELECT user_id FROM users WHERE email = 'tharindu.wickramasinghe@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
    '2025-07-21', 'present', 'Quick learner, good progress'),

    -- Shani Mendis (Dual-purpose Vehicle Training)
    ((SELECT user_id FROM users WHERE email = 'shani.mendis@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Dual-purpose Vehicle Training'), 
    '2025-07-21', 'present', 'Need more practice on parking'),

    -- Chamara Silva (Heavy Car Training)
    ((SELECT user_id FROM users WHERE email = 'chamara.silva@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Heavy Car Training'), 
    '2025-07-21', 'present', 'Advanced driving techniques practiced'),

    -- Day 2: 2025-07-22
    -- Kasun Madhusanka
    ((SELECT user_id FROM users WHERE email = 'kasun.madhusanka@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-07-22', 'present', 'Improved steering control'),

    -- Dilani Jayawardene
    ((SELECT user_id FROM users WHERE email = 'dilani.jayawardene@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motorcycle Training'), 
    '2025-07-22', 'present', 'Mastered gear shifting'),

    -- Ruwan Kumara
    ((SELECT user_id FROM users WHERE email = 'ruwan.kumara@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-07-22', 'present', 'Made up for missed lesson'),

    -- Tharindu Wickramasinghe
    ((SELECT user_id FROM users WHERE email = 'tharindu.wickramasinghe@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
    '2025-07-22', 'present', 'Highway driving practice'),

    -- Shani Mendis
    ((SELECT user_id FROM users WHERE email = 'shani.mendis@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Dual-purpose Vehicle Training'), 
    '2025-07-22', 'absent', 'Medical appointment'),

    -- Chamara Silva
    ((SELECT user_id FROM users WHERE email = 'chamara.silva@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Heavy Car Training'), 
    '2025-07-22', 'present', 'Heavy vehicle maneuvering'),

    -- Day 3: 2025-07-23
    -- Kasun Madhusanka
    ((SELECT user_id FROM users WHERE email = 'kasun.madhusanka@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-07-23', 'present', 'Parallel parking practice'),

    -- Dilani Jayawardene
    ((SELECT user_id FROM users WHERE email = 'dilani.jayawardene@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motorcycle Training'), 
    '2025-07-23', 'present', 'Traffic rules theory test passed'),

    -- Ruwan Kumara
    ((SELECT user_id FROM users WHERE email = 'ruwan.kumara@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-07-23', 'present', 'Confidence building exercises'),

    -- Tharindu Wickramasinghe
    ((SELECT user_id FROM users WHERE email = 'tharindu.wickramasinghe@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
    '2025-07-23', 'present', 'Night driving practice'),

    -- Shani Mendis
    ((SELECT user_id FROM users WHERE email = 'shani.mendis@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Dual-purpose Vehicle Training'), 
    '2025-07-23', 'present', 'Clutch control improvement'),

    -- Chamara Silva
    ((SELECT user_id FROM users WHERE email = 'chamara.silva@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Heavy Car Training'), 
    '2025-07-23', 'present', 'Commercial vehicle regulations'),

    -- Week 2: 2025-07-28 to 2025-08-03
    -- Nimesha Rathnayake (Light Tricycle/Van Training)
    ((SELECT user_id FROM users WHERE email = 'nimesha.rathnayake@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Tricycle/Van Training'), 
    '2025-07-28', 'present', 'Three-wheeler basics covered'),

    -- Lahiru Gamage (Passenger Vehicle Training)
    ((SELECT user_id FROM users WHERE email = 'lahiru.gamage@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'sampath.gunawardena@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Passenger Vehicle Training (≤ 32)'), 
    '2025-07-28', 'present', 'Passenger safety protocols'),

    -- Malika Fernando (Light Motorcycle Training)
    ((SELECT user_id FROM users WHERE email = 'malika.fernando@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
    '2025-07-28', 'present', 'Beginner motorcycle lessons'),

    -- Ishara Perera (Light Lorry Training)
    ((SELECT user_id FROM users WHERE email = 'ishara.perera@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Lorry Training'), 
    '2025-07-28', 'present', 'Light lorry loading techniques'),

    -- Sahan Dissanayake (Mini Bus Training)
    ((SELECT user_id FROM users WHERE email = 'sahan.dissanayake@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Mini Bus Training (≤ 16)'), 
    '2025-07-28', 'present', 'Mini bus driving fundamentals'),

    -- Gayani Wickremasinghe (Ambulance/Hearse Training)
    ((SELECT user_id FROM users WHERE email = 'gayani.wickremasinghe@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Ambulance/Hearse Training'), 
    '2025-07-28', 'present', 'Emergency vehicle procedures'),

    -- Continue with remaining attendance records (2025-07-29 to 2025-08-03)
    -- Day 2: 2025-07-29
    -- Nimesha Rathnayake
    ((SELECT user_id FROM users WHERE email = 'nimesha.rathnayake@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Tricycle/Van Training'), 
    '2025-07-29', 'present', 'Van driving on main roads'),

    -- Lahiru Gamage
    ((SELECT user_id FROM users WHERE email = 'lahiru.gamage@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'sampath.gunawardena@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Passenger Vehicle Training (≤ 32)'), 
    '2025-07-29', 'absent', 'Personal work commitment'),

    -- Malika Fernando
    ((SELECT user_id FROM users WHERE email = 'malika.fernando@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'pradeep.fernando@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Motorcycle Training'), 
    '2025-07-29', 'present', 'Motorcycle balance exercises'),

    -- Ishara Perera
    ((SELECT user_id FROM users WHERE email = 'ishara.perera@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'kamala.wijesinghe@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Lorry Training'), 
    '2025-07-29', 'present', 'Commercial driving ethics'),

    -- Sahan Dissanayake
    ((SELECT user_id FROM users WHERE email = 'sahan.dissanayake@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'nilanthi.perera@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Mini Bus Training (≤ 16)'), 
    '2025-07-29', 'present', 'Passenger vehicle inspection'),

    -- Gayani Wickremasinghe
    ((SELECT user_id FROM users WHERE email = 'gayani.wickremasinghe@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Ambulance/Hearse Training'), 
    '2025-07-29', 'present', 'Medical emergency response'),

    -- Day 3: 2025-07-30
    -- Nimesha Rathnayake
    ((SELECT user_id FROM users WHERE email = 'nimesha.rathnayake@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Light Tricycle/Van Training'), 
    '2025-07-30', 'present', 'City traffic navigation'),

    -- Lahiru Gamage
    ((SELECT user_id FROM users WHERE email = 'lahiru.gamage@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'sampath.gunawardena@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Passenger Vehicle Training (≤ 32)'), 
    '2025-07-30', 'present', 'Route planning strategies'),

    -- Final days (2025-08-01 to 2025-08-03) - Add remaining records as needed
    -- Day 4: 2025-08-01
    -- Kasun Madhusanka
    ((SELECT user_id FROM users WHERE email = 'kasun.madhusanka@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Motor Car Training'), 
    '2025-08-01', 'present', 'Advanced parking techniques'),

    -- Add more attendance records as needed...

    -- Day 6: 2025-08-03 (today)
    -- Gayani Wickremasinghe
    ((SELECT user_id FROM users WHERE email = 'gayani.wickremasinghe@gmail.com'), 
    (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'mahinda.rajapaksha@safepath.lk')), 
    (SELECT course_id FROM courses WHERE course_name = 'Ambulance/Hearse Training'), 
    '2025-08-03', 'present', 'Emergency response certification');







--Here these are the user account creation


-- Insert Admin user
INSERT INTO users (name, email, password, role, phone, address, created_at) VALUES
('Admin User', 'admin@spo.com', '$2y$10$ar9lCf6n.jYzV8vvZrkSCO1SSgnDrtq4hr2gOv30Ql7AeUzy1ZPc2', 'admin', '+94770000001', 'Admin Address', NOW());
-- email:admin@spo.com, password:admin123


-- Insert Instructor user
INSERT INTO users (name, email, password, role, phone, address, created_at) VALUES
('Instructor John', 'instructor@spo.com', '$2y$10$sdVD6tF9yxsR3q1.WhLZieqzGKksM.sEb/680AX9Q1C4TK4OCSYvK', 'instructor', '+94770000002', 'Instructor Address', NOW());

-- Link Instructor details
INSERT INTO instructors (user_id, birth_date, gender, national_id_number, experience_years, driving_license_number, vehicle_type, status, hire_date) VALUES
(
  (SELECT user_id FROM users WHERE email = 'instructor@spo.com'),
  '1980-05-15',
  'male',
  '800123456V',
  10,
  'D1234567',
  'Car',
  'active',
  '2020-01-01'
);
-- email:instructor@spo.com, password:instructor123



-- Insert Student user
INSERT INTO users (name, email, password, role, phone, address, created_at) VALUES
('Student Clara', 'student@spo.com', '$2y$10$dcfb.vZYdyXYy.NEOKjvg.gUOl6TxzT/xYetf7TDNZvyGabtXSlIW', 'student', '+94770000003', 'Student Address', NOW());

-- Link Student details
INSERT INTO students (user_id, instructor_id, course_id, birth_date, gender, national_id_number, start_date, enrollment_status) VALUES
(
  (SELECT user_id FROM users WHERE email = 'student@spo.com'),
  (SELECT instructor_id FROM instructors WHERE user_id = (SELECT user_id FROM users WHERE email = 'instructor@spo.com')),
  1, -- Replace with actual course_id or NULL
  '2000-07-20',
  'female',
  '900123456V',
  CURDATE(),
  'active'
);

-- email:student@spo.com, password:student123

