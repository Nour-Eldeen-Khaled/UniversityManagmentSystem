<?php 
session_start();
include '../config/database.php'; // Include your database configuration

// Check if user_id is set in the session
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement
        $sql = "SELECT first_name, last_name, email, role FROM users WHERE id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user_Fname = $result['first_name'];
            $user_Lname = $result['last_name'];
            $user_email = $result['email'];
            $user_role = $result['role']; 
        } else {
            echo "User not found";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "User not logged in";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <?php include '../includes/header.php'; ?>
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

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive grid */
            gap: 20px; /* Space between items */
        }

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

        .feature-card form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feature-card input, 
        .feature-card textarea, 
        .feature-card button {
            font-size: 1rem;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
        }

        .feature-card textarea {
            resize: none;
            height: 100px;
        }

        .feature-card button {
            background-color: #00bcd4;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .feature-card button:hover {
            background-color: #0197a3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .features h2 {
                font-size: 2rem;
            }
        }
    </style>
    
</head>
<body>
    <section class="features">
        <h2>Contact Us</h2>
        <div class="feature-grid">
            <div class="feature-card">
            <form action="submit_contact.php" method="POST">
        <!-- Pre-filled fields for known user information -->
        <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($user_role . ':' . ' ' . $user_Fname . ' ' . $user_Lname); ?>" readonly>
        <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
        
        <!-- Fields for subject and message input -->
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        
        <!-- Submit button -->
        <button type="submit">Submit</button>
    </form>
            </div>
        </div>
    </section>
</body>
</html>



