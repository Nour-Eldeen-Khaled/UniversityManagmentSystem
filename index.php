<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Management System</title>
    <link rel="stylesheet" href="/UniversitySystem/css/style.css">

    <style>
                /* Features Section */
.features {
  background-color: #f8f9fa; /* Light background */
  padding: 50px 20px;
  font-family: 'Arial', sans-serif;
  text-align: center; /* Center align section content */
}

.features h2 {
  font-size: 2.5rem;
  color: #2c3e50; /* Dark text for contrast */
  margin-bottom: 40px;
  text-transform: uppercase;
  letter-spacing: 2px;
  border-bottom: 3px solid #00bcd4; /* Accent underline */
  display: inline-block;
  padding-bottom: 10px;
}

/* Grid Layout */
.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive grid */
  gap: 20px; /* Space between cards */
}

/* Feature Card */
.feature-card {
  background-color: #FFFFFF; /* White card background */
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  padding: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover effect */
}

.feature-card:hover {
  transform: translateY(-10px); /* Slight lift on hover */
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
}

.feature-card h3 {
  font-size: 1.5rem;
  color: #00bcd4; /* Accent color for titles */
  margin-bottom: 15px;
  text-transform: uppercase;
  border-bottom: 2px solid #00bcd4; /* Underline effect */
  display: inline-block;
  padding-bottom: 5px;
}

.feature-card ul {
  list-style: none; /* Remove default list styling */
  padding: 0;
  margin: 0;
}

.feature-card ul li {
  font-size: 1rem;
  color: #555; /* Subtle text color */
  margin-bottom: 10px;
  position: relative; /* Enable custom bullet points */
  padding-left: 20px;
}

.feature-card ul li::before {
  content: "✔"; /* Checkmark icon as bullet */
  color: #00bcd4; /* Accent color for bullets */
  position: absolute;
  left: 0;
  font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .features h2 {
    font-size: 2rem;
  }

  .feature-card h3 {
    font-size: 1.2rem;
  }

  .feature-card ul li {
    font-size: 0.9rem;
  }
}

            </style>

</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <section class="hero">
            <h1>Welcome to Alexandria University Management System</h1>
            <p>Access your academic portal for students, professors, and administrators.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="cta-buttons">
                    <a href="auth/login.php" class="btn btn-primary" style="margin-right: 5px;" >Login</a>
                    <a href="auth/register.php" class="btn btn-secondary" style="background-color: #00bcd4;">Register</a>
                </div>
            <?php endif; ?>
        </section>
        
        <section class="features">
            <h2>Our Features</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>For Students</h3>
                    <ul>
                        <li>Course Registration</li>
                        <li>Access Course Materials</li>
                        <li>View Grades</li>
                        <li>Track Progress</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <h3>For Professors</h3>
                    <ul>
                        <li>Manage Courses</li>
                        <li>Upload Materials</li>
                        <li>Grade Students</li>
                        <li>Track Attendance</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <h3>For Administrators</h3>
                    <ul>
                        <li>User Management</li>
                        <li>Course Management</li>
                        <li>System Overview</li>
                        <li>Generate Reports</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'includes/footer.php'; ?>
</body>
</html>
