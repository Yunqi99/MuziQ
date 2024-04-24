<?php
session_start();

if(isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){
    if (isset($_POST['editFAQ'])) {
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $faqid = $_POST['faqid'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            exit(); // Terminate script if unable to connect to the database
        }

        $q = "UPDATE faq SET Question=?, Answer=? WHERE FAQID=?";

        if ($stmt = $mysqli->prepare($q)) {
            // Bind parameters to the statement
            $stmt->bind_param("ssi", $question, $answer, $faqid);

            // Execute query and output a success or error message
            if ($stmt->execute()) {
                echo '<script>alert("Successfully updated !"); window.history.back();</script>';
            } else {
                echo '<script>alert("Error updating FAQ: ' . $stmt->error . '"); window.history.back();</script>';
            }
            
            // Close the statement
            $stmt->close();
        } else {
            echo '<script>alert("Something went wrong ! Please try again."); indow.history.back(); </script>';
        }
    } else {
        echo '<script>alert("Something went wrong ! Please try again."); indow.history.back(); </script>';
    }
}
?>

