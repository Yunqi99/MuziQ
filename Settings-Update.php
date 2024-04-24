<?php
session_start();

if ($loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) {

        $email = $_POST['email']; 
        $password = $_POST['pwd'];
        $userid = $_POST['userid'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo 'Error in connecting to the database';
        }
        
        $q = "UPDATE user SET UserEmail=?, UserPassword=? WHERE UserID=?";

        // Execute query and output a success or error message
         if ($stmt = $mysqli->prepare($q)) {
            // Bind parameters to the statement
            $stmt->bind_param("ssi", $email, $password,  $userid);
            // Execute query and output a success or error message
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error';
            }
            $stmt->close(); 
        }

} 
?>

