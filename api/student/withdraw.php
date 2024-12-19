<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_id = $_POST['enrollment_id'] ?? null;
    $student_id = $_SESSION['user_id'];

    if (!$enrollment_id) {
        echo json_encode(['success' => false, 'message' => 'Enrollment ID is required']);
        exit();
    }

    try {
        // Verify enrollment belongs to student
        $stmt = $pdo->prepare("
            SELECT id FROM enrollments 
            WHERE id = ? AND student_id = ? AND status = 'active'
        ");
        $stmt->execute([$enrollment_id, $student_id]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Invalid enrollment']);
            exit();
        }

        // Update enrollment status to dropped
        $stmt = $pdo->prepare("
            UPDATE enrollments 
            SET status = 'dropped' 
            WHERE id = ?
        ");
        $stmt->execute([$enrollment_id]);

        echo json_encode(['success' => true, 'message' => 'Successfully withdrawn from course']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
