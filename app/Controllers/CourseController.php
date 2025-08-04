<?php
require_once __DIR__ . '/../Models/Course.php';

class CourseController {
    private $model;

    public function __construct() {
        $this->model = new Course();
    }

    public function getCourses() {
        return $this->model->getAllCourses();
    }

    public function handleRequest($post) {
        $response = ['success' => false, 'message' => 'Invalid action'];

        try {
            switch ($post['action']) {
                case 'add_course':
                    $this->validateCourseData($post);
                    $this->model->addCourse($post);
                    $response = ['success' => true, 'message' => 'Course added successfully'];
                    break;

                case 'edit_course':
                    $this->validateCourseData($post, true);
                    $this->model->updateCourse($post);
                    $response = ['success' => true, 'message' => 'Course updated successfully'];
                    break;

                case 'delete_course':
                    $this->model->deleteCourse((int)$post['course_id']);
                    $response = ['success' => true, 'message' => 'Course deleted successfully'];
                    break;
            }
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }

        return $response;
    }

    private function validateCourseData(&$data, $isEdit = false) {
        if ($isEdit && empty($data['course_id'])) {
            throw new Exception('Course ID is required for update.');
        }

        $data['course_name'] = trim($data['course_name']);
        $data['course_type'] = trim($data['course_type']);
        $data['description'] = trim($data['description'] ?? '');

        if (empty($data['course_name']) || empty($data['course_type']) ||
            (int)$data['duration_days'] <= 0 || (float)$data['course_fee'] <= 0) {
            throw new Exception('All fields except description are required and must be valid.');
        }
    }
}
