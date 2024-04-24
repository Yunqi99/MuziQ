<?php
session_start();

if ($loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){

   // Establish a database connection using mysqli
   $mysqli = new mysqli("localhost", "root", "", "muziq-test");
   if ($mysqli->connect_errno) {
        echo '<script>alert("Failed to connect ! Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
   
    if(isset($_GET['id'])) {

        $FeedbackID = $_GET['id'];

        // Prepare the delete statement
        $query = "DELETE FROM feedback WHERE FeedbackID = ?";
        $stmt = $mysqli->prepare($query);
    
        // Bind the parameters
        $stmt->bind_param("i", $FeedbackID);
    
        // Execute the statement
        if ($stmt->execute()) {
            echo '<script>alert("Successfully deleted!"); window.location.href = "Message.php";</script>';
        } else {
            echo '<script>alert("Error! Please try to delete again."); window.location.href = "Message.php";</script>'; 
        }
    
        // Close the statement
        $stmt->close();
    } else {
        echo '<script>alert("Something went wrong! Please try again."); window.location.href = "Message.php";</script>'; 
    } 
}

?>