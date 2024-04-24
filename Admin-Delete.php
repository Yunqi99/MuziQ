<?php
session_start();

if ($loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){

   // Establish a database connection using mysqli
   $mysqli = new mysqli("localhost", "root", "", "muziq-test");
   if ($mysqli->connect_errno) {
        echo '<script>alert("Failed to connect ! Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
   
    if(isset($_GET['id'])) {

        $AdminID = $_GET['id'];

        $q1 = "DELETE FROM password_reset_admin WHERE AdminID='" . $AdminID . "';";

        // Prepare the delete statement
        if ($mysqli->query($q1)) {
            $query = "DELETE FROM `admin` WHERE AdminID = ?";
            $stmt = $mysqli->prepare($query);
            // Bind the parameters
            $stmt->bind_param("i", $AdminID);
    
            // Execute the statement
            if ($stmt->execute()) {
                echo '<script>alert("Successfully deleted!"); window.history.back();</script>';
            } else {
                echo '<script>alert("Error! Please try to delete again."); window.history.back();</script>'; 
            }
        }
        else {
            echo '<script>alert("Error! Please try to delete again."); window.history.back();</script>'; 
        }
        // Close the statement
        $stmt->close();
    } else {
        echo '<script>alert("Something went wrong! Please try again."); window.history.back();</script>'; 
    } 
}

?>