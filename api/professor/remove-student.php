<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

// Check if user is logged in and is a professor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_id = $_POST['enrollment_id'] ?? null;

    if (!$enrollment_id) {
        echo json_encode(['success' => false, 'message' => 'Enrollment ID is required']);
        exit();
    }

    try {
        // Verify the enrollment belongs to a course taught by this professor
        $stmt = $pdo->prepare("
            SELECT e.id 
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            WHERE e.id = ? AND c.professor_id = ?
        ");
        $stmt->execute([$enrollment_id, $_SESSION['user_id']]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'You are not authorized to remove this student']);
            exit();
        }

        // Remove the student from the course by updating enrollment status
        $stmt = $pdo->prepare("
            UPDATE enrollments 
            SET status = 'dropped' 
            WHERE id = ?
        ");
        $stmt->execute([$enrollment_id]);

        echo json_encode([
            'success' => true, 
            'message' => 'Student successfully removed from the course'
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
