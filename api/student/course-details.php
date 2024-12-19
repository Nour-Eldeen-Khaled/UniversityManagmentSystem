<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
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
        // Get course details including professor name
        $stmt = $pdo->prepare("
            SELECT c.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as professor_name
            FROM courses c
            LEFT JOIN users u ON c.professor_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            echo json_encode(['success' => true, 'course' => $course]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Course not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
