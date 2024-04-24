<?php
session_start();

if(isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) {

   // Establish a database connection using mysqli
   $mysqli = new mysqli("localhost", "root", "", "muziq-test");
   if ($mysqli->connect_errno) {
        echo '<script>alert("Failed to connect ! Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
   
   $UserID = $_SESSION['UserID'];
   
   $q1 = "SELECT * FROM user WHERE UserID = $UserID";
   $result = $mysqli->query($q1);
   
   if (!$result) {
    echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
   } else {
       $row = $result->fetch_assoc();
       $userID = $row['UserID'];
   
       // Construct SQL queries to delete playlists, tracks, and the user
       $q2 = "DELETE FROM playlist WHERE UserID='" . $userID . "';";
       $q3 = "DELETE FROM track WHERE UserID='" . $userID . "';";
       $q4 = "DELETE FROM feedback WHERE UserID='" . $userID . "';";
       $q5 = "DELETE FROM history WHERE UserID='" . $userID . "';";
       $q6 = "DELETE FROM playlist_track WHERE UserID='" . $userID . "';";
       $q7 = "DELETE FROM password_reset_user WHERE UserID='" . $userID . "';";
       $q8 = "DELETE FROM user WHERE UserID='" . $userID . "';";
   
       // Execute the deletion queries
       if ($mysqli->query($q2) && $mysqli->query($q3) && $mysqli->query($q4) && $mysqli->query($q5) && $mysqli->query($q6) && $mysqli->query($q7)) {
         if ($mysqli->query($q8)){
            unset($_SESSION['UserID']);
            echo '<script>alert("Successfully deactivate your account !"); window.location.href = "index.html";</script>';
         }
       } else {
            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
       }
   }
   
   $mysqli->close();  // Close the database connection
}

?>