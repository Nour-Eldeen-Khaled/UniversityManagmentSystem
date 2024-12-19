<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit();
}

// Get student's enrolled courses
$stmt = $pdo->prepare("
    SELECT c.*, e.status, e.id as enrollment_id
    FROM courses c
    JOIN enrollments e ON c.id = e.course_id
    WHERE e.student_id = ? AND e.status = 'active'
");
$stmt->execute([$_SESSION['user_id']]);
$enrolled_courses = $stmt->fetchAll();

// Get available courses (not enrolled)
$stmt = $pdo->prepare("
    SELECT c.*
    FROM courses c
    WHERE c.id NOT IN (
        SELECT course_id 
        FROM enrollments 
        WHERE student_id = ? AND status = 'active'
    )
");
$stmt->execute([$_SESSION['user_id']]);
$available_courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - University System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include_once '../includes/header.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1><i class="fas fa-graduation-cap"></i> My Dashboard</h1>
            <button id="toggleAvailableCourses" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Course
            </button>
        </div>

        <!-- Available Courses Section (Hidden by default) -->
        <div id="availableCoursesSection" class="courses-section available-courses" style="display: none;">
            <h2><i class="fas fa-book"></i> Available Courses</h2>
            <div class="course-grid">
                <?php foreach ($available_courses as $course): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <span class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></span>
                        </div>
                        <div class="course-content">
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <div class="course-credits">
                                <i class="fas fa-star"></i> Credits: <?php echo $course['credits']; ?>
                            </div>
                        </div>
                        <div class="course-actions">
                            <button class="btn btn-success enroll-btn" data-course-id="<?php echo $course['id']; ?>">
                                <i class="fas fa-plus-circle"></i> Enroll
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Enrolled Courses Section -->
        <div class="courses-section enrolled-courses">
            <h2><i class="fas fa-books"></i> My Enrolled Courses</h2>
            <div class="course-grid">
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <span class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></span>
                        </div>
                        <div class="course-content">
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <div class="course-credits">
                                <i class="fas fa-star"></i> Credits: <?php echo $course['credits']; ?>
                            </div>
                        </div>
                        <div class="course-actions">
                            <button class="btn btn-danger withdraw-btn" data-enrollment-id="<?php echo $course['enrollment_id']; ?>">
                                <i class="fas fa-minus-circle"></i> Withdraw
                            </button>
                            <button class="btn btn-info view-details-btn" data-course-id="<?php echo $course['id']; ?>">
                                <i class="fas fa-info-circle"></i> Details
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Course Details Modal -->
    <div id="courseModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="courseDetails"></div>
        </div>
    </div>

    <script src="../js/dashboard.js"></script>
    <?php include_once '../includes/footer.php'; ?>
</body>
</html>
