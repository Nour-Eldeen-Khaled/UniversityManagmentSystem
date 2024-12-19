<?php
// First check if we have an active session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch user details if logged in
$user_info = null;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/database.php';
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_info = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/UniversitySystem/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>

.nav-content {
  display: flex; /* Use Flexbox for layout */
  justify-content: space-between; /* Center content horizontally */
  align-items: center; /* Align items vertically */
  background-color: #f8f9fa; /* Optional: background color for the navbar */
  margin-top:16px;
}

.nav-brand a {
  font-size: 1.5rem; /* Adjust font size for brand */
  font-weight: bolder;
  text-decoration: none;
  color: #2c3e50; /* Optional: text color */
  font-size: 40px;
}
.nav-brand a:hover {
        text-decoration: none; /* Remove default underline */
 }

.nav-links {
  display: flex; /* Lay out links in a row */
  align-items: center; /* Vertically align links */
  gap: 15px; /* Spacing between links */
}

.nav-links a {
  text-decoration: none; /* Remove underline */
  color: #007bff; /* Optional: link color */
  font-size: 1rem;
}

.user-info-container {
  display: flex;
  align-items: center;
  position: relative; /* Position for dropdown handling */
}

.user-info-icon {
  font-size: 1.5rem;
  cursor: pointer;
}

.user-info-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 10px;
  display: none; /* Hidden by default */
}

.user-info-container:hover .user-info-dropdown {
  display: block; /* Show on hover */
}

.user-info-details p {
  margin: 5px 0;
  font-size: 0.9rem;
}

</style>

</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="nav-content">
                    <div class="nav-brand">
                        <a href="/UniversitySystem/">University System</a>
                    </div>
                    <div class="nav-links">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="/UniversitySystem/admin/dashboard.php">Dashboard</a>
                            <?php elseif ($_SESSION['role'] === 'professor'): ?>
                                <a href="/UniversitySystem/professor/dashboard.php">Dashboard</a>
                            <?php elseif ($_SESSION['role'] === 'student'): ?>
                                <a href="/UniversitySystem/student/dashboard.php">Dashboard</a>
                            <?php endif; ?>
                            
                            <!-- User Info Icon -->
                            <div class="user-info-container">
                                <div class="user-info-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="user-info-dropdown">
                                    <?php if ($user_info): ?>
                                        <div class="user-info-details">
                                            <p class="user-name"><?php echo htmlspecialchars($user_info['first_name'] . ' ' . $user_info['last_name']); ?></p>
                                            <p class="user-email"><?php echo htmlspecialchars($user_info['email']); ?></p>
                                            <p class="user-role"><?php echo htmlspecialchars(ucfirst($user_info['role'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <a href="/UniversitySystem/auth/logout.php">Logout</a>
                        <?php else: ?>
                            <a href="/UniversitySystem/auth/login.php">Login</a>
                            <a href="/UniversitySystem/auth/register.php">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <script src="/UniversitySystem/js/user-info.js"></script>
</body>
</html>
