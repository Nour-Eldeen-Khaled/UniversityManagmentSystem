<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a professor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header('Location: ../auth/login.php');
    exit();
}

// Get professor's courses
$stmt = $pdo->prepare("
    SELECT id, course_code, title, description 
    FROM courses 
    WHERE professor_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard - Course Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include_once '../includes/header.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1><i class="fas fa-chalkboard-teacher"></i> My Courses</h1>
        </div>

        <div class="courses-section">
            <div class="course-grid">
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <span class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></span>
                        </div>
                        <div class="course-content">
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <button class="btn btn-primary view-students-btn" 
                                    data-course-id="<?php echo $course['id']; ?>">
                                <i class="fas fa-users"></i> View Students
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Students Modal -->
        <div id="studentsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Course Students</h2>
                <div id="studentsList"></div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // View Students Modal
        $('.view-students-btn').click(function() {
            const courseId = $(this).data('course-id');
            const modal = $('#studentsModal');
            const studentsList = $('#studentsList');

            // Show loading
            studentsList.html('<div class="loading">Loading...</div>');
            modal.fadeIn(300);

            // Fetch students for this course
            $.ajax({
                url: '../api/professor/get-course-students.php',
                method: 'GET',
                data: { course_id: courseId },
                success: function(response) {
                    if (response.success) {
                        let studentsHtml = '<table class="students-table">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>Name</th>' +
                            '<th>Email</th>' +
                            '<th>Actions</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>';
                        
                        response.students.forEach(function(student) {
                            studentsHtml += `
                                <tr>
                                    <td>${student.first_name} ${student.last_name}</td>
                                    <td>${student.email}</td>
                                    <td>
                                        <button class="btn btn-danger remove-student-btn" 
                                                data-enrollment-id="${student.enrollment_id}">
                                            <i class="fas fa-user-times"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        studentsHtml += '</tbody></table>';
                        studentsList.html(studentsHtml);
                    } else {
                        studentsList.html('<p>No students found or an error occurred.</p>');
                    }
                },
                error: function() {
                    studentsList.html('<p>Error loading students.</p>');
                }
            });
        });

        // Remove Student
        $(document).on('click', '.remove-student-btn', function() {
            const enrollmentId = $(this).data('enrollment-id');
            const row = $(this).closest('tr');

            if (confirm('Are you sure you want to remove this student from the course?')) {
                $.ajax({
                    url: '../api/professor/remove-student.php',
                    method: 'POST',
                    data: { enrollment_id: enrollmentId },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(300, function() { 
                                $(this).remove(); 
                                // Refresh students list if empty
                                if ($('.students-table tbody tr').length === 0) {
                                    $('#studentsList').html('<p>No students in this course.</p>');
                                }
                            });
                        } else {
                            alert(response.message || 'Failed to remove student');
                        }
                    },
                    error: function() {
                        alert('An error occurred while removing the student');
                    }
                });
            }
        });

        // Close modal
        $('.close, .modal').click(function(e) {
            if (e.target === this) {
                $('#studentsModal').fadeOut(300);
            }
        });
    });
    </script>

    <?php include_once '../includes/footer.php'; ?>
</body>
</html>
