<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="login">
        <h1>Admin Sign Up</h1>
        <form id="signup-form" action="signup_process.php" method="post">
            <p><b>Name :</b></p><input type="text" placeholder="Username" id="signup-username" name="username" required>
            <p><b>Email :</b></p><input type="email" placeholder="Email" id="signup-email" name="email" required>
            <p><b>Phone Number :</b></p><input type="tel" placeholder="Phone Number" id="signup-phone" name="phone" required>
            <p><b>Password :</b></p><input type="password" placeholder="Password" id="signup-password" name="password" required>
            <p><b>Confirm Password :</b></p><input type="password" placeholder="reType" id="signup-confirmpassword" name="confirm_password" required>
            <button type="submit"><b>Sign Up</b></button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
