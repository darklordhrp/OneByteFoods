<?php
session_start();
header("Location: login.php"); // Redirect to login page before destroying session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
exit();
?>
