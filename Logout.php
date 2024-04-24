<?php
session_start();

// Check if there was an error connecting to the database
$mysqli = new mysqli("localhost", "root", "", "muziq-test");
if ($mysqli->connect_errno) {
    $output = "Failed to connect to MySQL: ( " . $mysqli->connect_errno . " ) " . $mysqli->connect_error;
}

// Check if the user or admin is logged in and unset the respective session variables
if (isset($_SESSION['UserID'])) {
    unset($_SESSION['UserID']);
} elseif (isset($_SESSION['AdminID'])) {
    unset($_SESSION['AdminID']);
}

// Redirect the user to the login page after logout
header("refresh:3;url=index.html");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="logout-box">
        <img src="Sources/Img/logout.png">
        <h1>Logged out successfully!</h1><br>
        <h3>You will be redirected to the login page in 3 seconds...</h3>
    </div>
</body>
</html>
