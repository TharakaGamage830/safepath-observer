<?php
require_once __DIR__ . '/../../config/constants.php';

class Course {
    private $pdo;
    
    public function __construct() {
        // Initialize PDO connection from constants.php
        global $pdo;
        $this->pdo = $pdo;
        
        // Alternative approach if global doesn't work:
        // try {
        //     $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Database connection failed: " . $e->getMessage());
        // }
    }
    
    public function getAllCourses() {
        $stmt = $this->pdo->query("SELECT * FROM courses ORDER BY course_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addCourse($data) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (course_name, course_type, duration_days, course_fee, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([
            $data['course_name'],
            $data['course_type'],
            $data['duration_days'],
            $data['course_fee'],
            $data['description']
        ]);
    }
    
    public function updateCourse($data) {
        $stmt = $this->pdo->prepare("UPDATE courses SET course_name = ?, course_type = ?, duration_days = ?, course_fee = ?, description = ?, updated_at = NOW() WHERE course_id = ?");
        return $stmt->execute([
            $data['course_name'],
            $data['course_type'],
            $data['duration_days'],
            $data['course_fee'],
            $data['description'],
            $data['course_id']
        ]);
    }
    
    public function deleteCourse($course_id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE course_id = ?");
        return $stmt->execute([$course_id]);
    }
    
    public function getCourseById($course_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->execute([$course_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}