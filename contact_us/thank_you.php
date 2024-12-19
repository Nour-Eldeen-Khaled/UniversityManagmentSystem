<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Container for the message */
        .thank-you-container {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        /* Title */
        .thank-you-container h1 {
            font-size: 2.5rem;
            color: #00bcd4;
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 3px solid #00bcd4;
            display: inline-block;
            padding-bottom: 10px;
        }

        /* Message */
        .thank-you-container p {
            font-size: 1.2rem;
            color: #555;
            margin-top: 20px;
        }

        /* Button to go back to the contact page */
        .back-button {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #00bcd4;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #008c9e;
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1>Thank You!</h1>
        <p>Your message has been successfully sent to admin. We will get back to you soon.</p>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = '../index.php';
        }, 3000); // 3 seconds delay
    </script>

</body>
</html>
