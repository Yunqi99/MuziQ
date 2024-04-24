<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "muziq-test"); // Check if there was an error connecting to the database
if ($mysqli->connect_errno) {
    $output = "Failed to connect to MySQL: ( " . $mysqli->connect_errno . " ) " . $mysqli->connect_error;
}

$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// Sanitize user input
$email = $mysqli->real_escape_string($email);
$password = $mysqli->real_escape_string($password);
$role = $mysqli->real_escape_string($role);

// Store email and role in session
$_SESSION['email'] = $email;
$_SESSION['role'] = $role;

$q = '';

if ($role == 'user') {
    $q = 'SELECT * FROM user WHERE UserEmail = ?';
} elseif ($role == 'admin') {
    $q = 'SELECT * FROM `admin` WHERE AdminEmail = ?';
}

if (!empty($q)) {
    $stmt = $mysqli->prepare($q);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            if ($role == 'user' && $password == $row['UserPassword']) {
                $_SESSION['UserID'] = $row['UserID']; // Passwords match, login successful for user
                setcookie("webmusicplayer", $email, time() + (60 * 60), '/');
                echo '<script>alert("Successfully logged in as user! "); window.location.href = "MuziQ.php";</script> ';
            } elseif ($role == 'admin' && $password == $row['AdminPassword']) {
                $_SESSION['AdminID'] = $row['AdminID']; // Passwords match, login successful for admin
                setcookie("webmusicplayer", $email, time() + (60 * 60), '/');
                echo '<script>alert("Successfully logged in as admin! "); window.location.href = "Homepage-admin.php";</script> ';
            } else {
                // Invalid password
                echo '<script>alert("Wrong password! Kindly input the correct password."); window.location.href = "index.html";</script>';
            }
        }
    } else {
        echo '<script>alert("Account not found! Kindly input the email correctly."); window.location.href = "index.html";</script>'; // User not found
    }
} else {
    echo '<script>alert("Role not specified! "); window.location.href = "index.html";</script>'; 
}
?>
