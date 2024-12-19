<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    try {
        // Check if user is not the current admin
        if ($user_id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Cannot remove the current admin user";
            header("Location: dashboard.php");
            exit();
        }
        
        // Remove associated enrollments first
        $stmt = $pdo->prepare("DELETE FROM enrollments WHERE student_id = ?");
        $stmt->execute([$user_id]);
        
        // Remove user from courses if they are a professor
        $stmt = $pdo->prepare("UPDATE courses SET professor_id = NULL WHERE professor_id = ?");
        $stmt->execute([$user_id]);
        
        // Remove the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        
        $_SESSION['success'] = "User removed successfully";
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to remove user: " . $e->getMessage();
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
