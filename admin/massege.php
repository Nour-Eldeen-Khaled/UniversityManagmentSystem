<?php
// Include the PDO database connection
include '../config/database.php';

// Query to fetch messages, user details, and subject with created_at timestamp
$sql = "SELECT 
            contact_us.id, 
            contact_us.message, 
            contact_us.subject,
            contact_us.user_id, 
            contact_us.created_at,
            users.first_name,
            users.last_name, 
            users.role 
        FROM 
            contact_us 
        INNER JOIN 
            users 
        ON 
            contact_us.user_id = users.id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #4CAF50;
            color: white;
        }

        table th, table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table td:hover {
            background-color: #e6e6e6;
        }

        .no-data {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>
    <div class="container">
        <h1>User Messages</h1>

        <?php if (!empty($messages)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Message ID</th>
                        <th>Message</th>
                        <th>Subject</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No messages found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
