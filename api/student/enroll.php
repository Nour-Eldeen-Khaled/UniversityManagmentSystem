<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;
    $student_id = $_SESSION['user_id'];

    if (!$course_id) {
        echo json_encode(['success' => false, 'message' => 'Course ID is required']);
        exit();
    }

    try {
        // Check if already enrolled
        $stmt = $pdo->prepare("
            SELECT id FROM enrollments 
            WHERE student_id = ? AND course_id = ? AND status = 'active'
        ");
        $stmt->execute([$student_id, $course_id]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Already enrolled in this course']);
            exit();
        }

        // Enroll in the course
        $stmt = $pdo->prepare("
            INSERT INTO enrollments (student_id, course_id, status)
            VALUES (?, ?, 'active')
        ");
        $stmt->execute([$student_id, $course_id]);

        echo json_encode(['success' => true, 'message' => 'Successfully enrolled in course']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
