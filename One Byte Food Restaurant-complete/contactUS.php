<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Booking</title>
    <link rel="stylesheet" href="contact.css">
    <script>
        // Function to hide messages after 3 seconds
        function hideMessage() {
            var messages = document.getElementsByClassName("message");
            setTimeout(function() {
                for (var i = 0; i < messages.length; i++) {
                    messages[i].style.display = "none";
                }
            }, 3000);
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <a href="Mainpage.html" class="logo-link">
                <h1>One Byte Foods</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="booking.php">Bookings</a></li>
                    <li><a href="contactUS.php" class="active">Contact Us</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="contact-form-container">
        <div class="contact-info">
            <div class="contact-item">
                <p>Naxal, Kathmandu, Nepal</p>
            </div>
            <div class="contact-item">
                <p>+977-9805567429</p>
            </div>
            <div class="contact-item">
                <p>OneByteFoods@gmail.com</p>
            </div>
        </div>
        <h2>Send Message</h2>
        <form class="contact-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" id="name" name="name" placeholder="Your Name" required>
            <input type="email" id="email" name="email" placeholder="Your Email" required>
            <textarea id="message" name="message" rows="4" placeholder="Your Message" required></textarea>
            <button type="submit" onclick="hideMessage()">Send Message</button>
        </form>
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $dbname = "one_byte_foods";

        // Create connection
        $conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create database if not exists
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($conn->query($sql) === FALSE) {
            echo "Error creating database: " . $conn->error;
        }

        // Select the database
        $conn->select_db($dbname);

        // Create feedback table if not exists
        $sql = "CREATE TABLE IF NOT EXISTS feedback (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === FALSE) {
            echo "Error creating table: " . $conn->error;
        }

        // Check if user exists in signup table
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $message = $_POST["message"];

            $check_user_query = "SELECT * FROM signup WHERE name='$name' AND email='$email'";
            $check_user_result = $conn->query($check_user_query);

            if ($check_user_result->num_rows > 0) {
                // User exists, proceed to insert into feedback table
                $insert_feedback_query = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$message')";
                if ($conn->query($insert_feedback_query) === TRUE) {
                    echo '<div class="message">Message sent successfully</div>';
                } else {
                    echo '<div class="message">Error: ' . $conn->error . '</div>';
                }
                // Redirect to prevent form resubmission
                header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
                exit();
            } else {
                // User doesn't exist in signup table
                echo '<div class="message">You need to sign up first before sending a message.</div>';
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
