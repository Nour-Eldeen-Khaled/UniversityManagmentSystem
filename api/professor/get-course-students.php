<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

// Check if user is logged in and is a professor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $course_id = $_GET['course_id'] ?? null;

    if (!$course_id) {
        echo json_encode(['success' => false, 'message' => 'Course ID is required']);
        exit();
    }

    try {
        // Verify the course belongs to this professor
        $stmt = $pdo->prepare("
            SELECT id FROM courses 
            WHERE id = ? AND professor_id = ?
        ");
        $stmt->execute([$course_id, $_SESSION['user_id']]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'You are not authorized to view students for this course']);
            exit();
        }

        // Get students enrolled in the course
        $stmt = $pdo->prepare("
            SELECT 
                u.id, 
                u.first_name, 
                u.last_name, 
                u.email, 
                e.id as enrollment_id
            FROM users u
            JOIN enrollments e ON u.id = e.student_id
            WHERE e.course_id = ? AND e.status = 'active'
        ");
        $stmt->execute([$course_id]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true, 
            'students' => $students
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
