<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "one_byte_foods";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted email and password
    $email = $_POST["Email"];
    $password = $_POST["password"];

    // Prepare SQL statement to fetch user data from the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user with the provided email exists
    if ($result->num_rows == 1) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user["password"])) {
            // Password is correct, redirect to booking.php

            // Store user information in session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: booking.php");
            exit();
        } else {
            // Password is incorrect, display error message
            $error_message = "Invalid details. Please try again.";
        }
    } else {
        // User does not exist, display error message
        $error_message = "Invalid details. Please try again.";
    }

    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="Mainpage.php" class="logo-link">
                <h1>One Byte Foods</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="booking.php">Bookings</a></li>
                    <li><a href="contactUS.php">Contact Us</a></li>
                    <li><a href="login.php" class="active">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="login">
        <h2>Login</h2>
        <?php if(isset($error_message)) { ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
        <form id="login-form" action="" method="post"> <!-- Updated form action -->
            <p><b>Email :</b></p> <input type="text" placeholder="Email" id="Email" name="Email" required>
            <p><b>Password :</b></p><input type="password" placeholder="Password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p><b>Don't have an account?</b> <a href="signup.php"><b>Sign Up</b></a></p>
        <p><b>Login as:</b> <a href="adminlogin.php"><b>Admin</b></a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>
