<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $credits = $_POST['credits'];
    $professor_id = $_POST['professor_id'];
    
    try {
        // Check if course code already exists
        $stmt = $pdo->prepare("SELECT id FROM courses WHERE course_code = ?");
        $stmt->execute([$course_code]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Course code already exists";
            header("Location: dashboard.php");
            exit();
        }
        
        // Insert new course
        $stmt = $pdo->prepare("
            INSERT INTO courses (course_code, title, description, credits, professor_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$course_code, $title, $description, $credits, $professor_id]);
        
        $_SESSION['success'] = "Course added successfully";
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add course: " . $e->getMessage();
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
