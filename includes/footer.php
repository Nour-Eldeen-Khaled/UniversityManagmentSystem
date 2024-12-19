<html>
    <head>
    <style>
            /* Footer Container */
.footer {
  background-color: #1d1d1d; /* Dark background */
  color: #f1f1f1; /* Light text color */
  padding: 40px 20px;
  font-family: 'Arial', sans-serif;
  margin-top: 50px;
}

.footer .container {
  max-width: 1200px;
}

/* Footer Content */
.footer-content {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap; /* Allow content to wrap on smaller screens */
  gap: 20px; /* Spacing between sections */
}

.footer-section {
  flex: 1; /* Ensure equal spacing between sections */
  min-width: 250px; /* Ensure sections don't collapse */
}

.footer-section h3 {
  font-size: 1.5rem;
  margin-bottom: 15px;
  color: #00bcd4; /* Accent color */
  text-transform: uppercase; /* Stylish uppercase text */
  border-bottom: 2px solid #00bcd4; /* Underline effect */
  display: inline-block; /* Fit underline to text size */
  padding-bottom: 5px;
}

.footer-section p,
.footer-section ul,
.footer-section a {
  font-size: 1rem;
  line-height: 1.6; /* Improve readability */
}

.footer-section ul {
  list-style: none; /* Remove bullet points */
  padding: 0; /* Remove default padding */
}

.footer-section ul li {
  margin-bottom: 10px;
}

.footer-section .social-links {
  display: flex;
  gap: 10px;
}

.footer-section .social-icon {
  text-decoration: none;
  background-color: #00bcd4; /* Background color for icons */
  color: #1d1d1d; /* Contrast text */
  padding: 10px 15px;
  border-radius: 5px;
  font-size: 1rem;
  transition: all 0.3s ease; /* Smooth hover transition */
}

.footer-section .social-icon:hover {
  background-color: #0288d1; /* Slightly darker on hover */
  color: #fff; /* White text on hover */
}

/* Footer Bottom */
.footer-bottom {
  margin-top: 30px;
  text-align: center;
  font-size: 0.9rem;
  color: #bfbfbf; /* Subtle gray text */
  border-top: 1px solid #333; /* Thin top border */
  padding-top: 20px; /* Space above the text */
}

.footer-bottom p {
  margin: 0; /* Remove extra margins */
}

.footer-bottom p span {
  color: #00bcd4; /* Highlighted color */
}

        </style>
    </head>
    <body>
    <footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>University Management System</h3>
                <p>Empowering education through technology and innovation.</p>
            </div>
            
            <?php 
$currentPath = $_SERVER['PHP_SELF'];

// Check if the current path contains 'admin'
if (strpos($currentPath, '/admin/') === false) { 
?>
<div class="footer-section">
    <h3>Contact Information</h3>
    <div class="social-link">
        <?php 
        // Set the correct contact us path
        $contactUsPath = ($currentPath === '/UniversitySystem/index.php') 
            ? 'contact_us/contact_us.php' 
            : '../contact_us/contact_us.php';
        ?>
        <button onclick="window.location.href='<?php echo $contactUsPath; ?>';" class="social-icon">Contact Us</button>
    </div>
</div>
<?php 
} 
?>

            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <!-- <a href="#" class="social-icon">Facebook</a>
                    <a href="#" class="social-icon">Twitter</a> -->
                    <a href="https://www.linkedin.com/in/nour-khaled-b04987272/" class="social-icon">LinkedIn</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Alexandria Management System. All rights reserved.</p>
        </div>
    </div>

</footer>
</body>
</html>
