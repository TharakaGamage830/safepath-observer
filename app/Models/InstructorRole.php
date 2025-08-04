<?php
require_once __DIR__ . '/../../config/constants.php';

class InstructorRole {
    private $pdo;

    public function getStudentsByInstructor($instructorId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                spv.student_id,
                spv.student_name,
                spv.email,
                spv.profile_picture,
                spv.course_name,
                spv.duration_days,
                spv.days_present,
                spv.progress_percentage,
                (
                    SELECT status 
                    FROM attendance 
                    WHERE student_id = spv.student_id AND attendance_date = CURDATE()
                    LIMIT 1
                ) AS today_status
            FROM student_progress_view spv
            WHERE spv.instructor_id = ?
        ");
        $stmt->execute([$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTodaySummary($instructorId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                present_count,
                absent_count,
                total_marked
            FROM daily_attendance_summary
            WHERE instructor_id = ? AND attendance_date = CURDATE()
            LIMIT 1
        ");
        $stmt->execute([$instructorId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['present_count' => 0, 'absent_count' => 0, 'total_marked' => 0];
    }

    public function getInstructorNameById($instructorId){
        $stmt = $this->pdo->prepare("
            SELECT u.name 
            FROM instructors i
            JOIN users u ON i.user_id = u.user_id
            WHERE i.instructor_id = ?
        ");
        $stmt->execute([$instructorId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getTodaySummaryAndTotals($instructorId) {
        // Get total students assigned to instructor
        $stmt1 = $this->pdo->prepare("
            SELECT COUNT(*) as total_students
            FROM students
            WHERE instructor_id = ?
        ");
        $stmt1->execute([$instructorId]);
        $totalStudents = $stmt1->fetchColumn();

        // Get count of students present today for this instructor
        $stmt2 = $this->pdo->prepare("
            SELECT COUNT(DISTINCT a.student_id) as present_students
            FROM attendance a
            JOIN students s ON a.student_id = s.user_id
            WHERE s.instructor_id = ? AND a.attendance_date = CURDATE() AND a.status = 'present'
        ");
        $stmt2->execute([$instructorId]);
        $presentStudents = $stmt2->fetchColumn();

        // Absent students = total - present (optional)
        $absentStudents = $totalStudents - $presentStudents;

        return [
            'total_students' => (int)$totalStudents,
            'present_students' => (int)$presentStudents,
            'absent_students' => (int)$absentStudents,
        ];
    }


}
