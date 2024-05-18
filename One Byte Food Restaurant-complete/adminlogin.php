<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
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
        <h1>Admin Login</h1>
        <form id="login-form" action="admin_login.php" method="post">
            <p><b>Email :</b></p> <input type="text" placeholder="Email" id="Email" name="email" required>
            <p><b>Password :</b></p><input type="password" placeholder="Password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
