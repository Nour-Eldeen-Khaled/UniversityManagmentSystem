<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    
    try {
        // Remove associated enrollments first
        $stmt = $pdo->prepare("DELETE FROM enrollments WHERE course_id = ?");
        $stmt->execute([$course_id]);
        
        // Remove associated course materials
        $stmt = $pdo->prepare("DELETE FROM course_materials WHERE course_id = ?");
        $stmt->execute([$course_id]);
        
        // Remove the course
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        
        $_SESSION['success'] = "Course removed successfully";
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to remove course: " . $e->getMessage();
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
