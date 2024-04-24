<?php
session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) {  
        $title = $_POST['title'];
        $feedback = $_POST['feedback'];

        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
                    
       // Check if there's an error in the database connection
        if ($mysqli->connect_errno) {
            echo 'Error in connecting to the database';
        }

        $UserID = $_SESSION['UserID'];
            
        // Create the SQL query to insert data
        $q1 = "INSERT INTO feedback (FeedbackTitle, FeedbackMsg, UserID) VALUES (?, ?, ?)";

                if ($stmt = $mysqli->prepare($q1)) {
                    // Bind parameters to the statement
                    $stmt->bind_param("ssi", $title, $feedback, $UserID);
                
                    // Execute query and output a success or error message
                    if ($stmt->execute()) {
                        echo 'success';
                    } else {
                        echo 'error';
                    }
                
                    // Close the statement
                    $stmt->close();
                } 
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}