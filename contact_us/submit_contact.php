<?php
session_start();
include '../config/database.php'; // Include your database configuration

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the POST data
    $user_id = $_SESSION['user_id'];
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    try {
        // Prepare the SQL statement to insert data into the contact_us table
        $sql = "INSERT INTO contact_us (user_id, subject, message) VALUES (:user_id, :subject, :message)";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters to the statement
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Redirect to a thank you page or success message
        header("Location: thank_you.php");
        exit();

    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If accessed directly, redirect to the contact page
    header("Location: contact_us/contact_us.php");
    exit();
}
?>
