<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email already exists";
            header("Location: dashboard.php");
            exit();
        }
        
        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, role)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$first_name, $last_name, $email, $password, $role]);
        
        $_SESSION['success'] = "User added successfully";
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add user: " . $e->getMessage();
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
