<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Store password as-is
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    // Basic validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email already exists";
            } else {
                // Insert new user WITHOUT hashing
                $stmt = $pdo->prepare("
                    INSERT INTO users (first_name, last_name, email, password, role)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$first_name, $last_name, $email, $password, $role]);
                
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - University System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Register</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="student">Student</option>
                        <option value="professor">Professor</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            
            <p class="text-center">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
