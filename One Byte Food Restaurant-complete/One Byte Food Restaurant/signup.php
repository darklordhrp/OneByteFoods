<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Sign Up</title>
</head>
<body>
    <header>
        <div class="container">
            <a href="Mainpage.php" class="logo-link">
                <h1>One Byte Foods</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="booking.php" >Bookings</a></li>
                    <li><a href="contactUS.php" >Contact Us</a></li>
                    <li><a href="login.php" class="active">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="login">
        <h1>Sign Up</h1>
        <?php
        // Database configuration
        $servername = "localhost";
        $username = "root"; // Default username for XAMPP
        $password = ""; // Default password for XAMPP
        $database = "one_byte_foods"; // Your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Prepare and bind parameters
            $username = $_POST["username"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            // Validate password and confirm password match
            if ($password !== $confirm_password) {
                echo "Passwords do not match.";
                exit();
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement to insert user data into the database
            $sql = "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                echo "User registered successfully.";
            } else {
                echo "Error: " . $conn->error;
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }
        ?>
        <form id="signup-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p><b>Name :</b></p><input type="text" placeholder="Username" name="username" id="signup-username" required>
            <p><b>Email :</b></p><input type="email" placeholder="Email" name="email" id="signup-email" required>
            <p><b>Phone Number :</b></p><input type="tel" placeholder="Phone Number" name="phone" id="signup-phone" required>
            <p><b>Password :</b></p><input type="password" placeholder="Password" name="password" id="signup-password" required>
            <p><b>Confirm Password :</b></p><input type="password" placeholder="reType" name="confirm_password" id="signup-confirmpassword" required>
            <button type="submit"><b>Sign Up</b></button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
