<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch all users
$users_stmt = $pdo->query("
    SELECT id, first_name, last_name, email, role 
    FROM users 
    WHERE role IN ('student', 'professor')
");
$users = $users_stmt->fetchAll();

// Fetch all courses
$courses_stmt = $pdo->query("
    SELECT c.id, c.course_code, c.title, c.credits, 
           u.first_name AS professor_first_name, 
           u.last_name AS professor_last_name 
    FROM courses c
    LEFT JOIN users u ON c.professor_id = u.id
");
$courses = $courses_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - University System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<style>/* Messages Button Styles */
.messages-button {
    display: inline-block;
    background-color: var(--primary-color);
    color: white;
    font-size: 1rem;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-transform: uppercase;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.messages-button:hover {
    background-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
}

.messages-button:active {
    transform: translateY(0);
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
}</style>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container admin-dashboard">
        <h1>Admin Dashboard</h1>
        <button class="messages-button" onclick="window.location.href='massege.php';">Messages</button>


        <!-- Users Section -->
        <section class="admin-section">
            <h2>Users Management</h2>
            
            <!-- Add User Form -->
            <div class="add-user-form">
                <h3>Add New User</h3>
                <form action="add_user.php" method="POST">
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
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role" required>
                            <option value="student">Student</option>
                            <option value="professor">Professor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
            
            <!-- Users Table -->
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="remove_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        
        <!-- Courses Section -->
        <section class="admin-section">
            <h2>Courses Management</h2>
            
            <!-- Add Course Form -->
            <div class="add-course-form">
                <h3>Add New Course</h3>
                <form action="add_course.php" method="POST">
                    <div class="form-group">
                        <label for="course_code">Course Code:</label>
                        <input type="text" id="course_code" name="course_code" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Course Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="credits">Credits:</label>
                        <input type="number" id="credits" name="credits" required>
                    </div>
                    <div class="form-group">
                        <label for="professor_id">Assign Professor:</label>
                        <select id="professor_id" name="professor_id" required>
                            <?php 
                            $professors_stmt = $pdo->query("SELECT id, first_name, last_name FROM users WHERE role = 'professor'");
                            $professors = $professors_stmt->fetchAll();
                            foreach ($professors as $professor): ?>
                                <option value="<?php echo $professor['id']; ?>">
                                    <?php echo htmlspecialchars($professor['first_name'] . ' ' . $professor['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>
            
            <!-- Courses Table -->
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course Code</th>
                        <th>Title</th>
                        <th>Credits</th>
                        <th>Professor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['id']); ?></td>
                        <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td><?php echo htmlspecialchars($course['credits']); ?></td>
                        <td><?php echo htmlspecialchars($course['professor_first_name'] . ' ' . $course['professor_last_name']); ?></td>
                        <td>
                            <a href="remove_course.php?id=<?php echo $course['id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <?php // include '../includes/footer.php'; ?>
    
    <script src="../js/admin.js"></script>
</body>
</html>
