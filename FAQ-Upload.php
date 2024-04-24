<?php
session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {
    if (isset($_POST['createFAQ'])) {

        $question = $_POST['question'];
        $answer = $_POST['answer'];

        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
                    
        // Check if there's an error in the database connection
        if ($mysqli->connect_errno) {
            echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
        }

        $q1 = "INSERT INTO faq (Question, Answer) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($q1)) {
            $stmt->bind_param("ss", $question, $answer);
            // Execute query and output a success or error message
            if ($stmt->execute()) {
                echo '<script>alert("Successfully added ! "); window.history.back();</script>';
            } else {
                echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
            }
                
            // Close the statement
            $stmt->close();
        } else {
            echo '<script>alert("Error ! Please try again."); window.history.back();</script>';
            }
        }
    else{
        echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}


?>