<?php
session_start();

if(isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){
    if (isset($_POST['sendFeedback'])) {
        $title = $_POST['title']; 
        $feedback = $_POST['feedback'];
        $feedbackid = $_POST['feedbackid'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            exit(); // Terminate script if unable to connect to the database
        }

        $q = "UPDATE feedback SET ReplyTitle=?, ReplyAnswer=? WHERE FeedbackID=?";

        if ($stmt = $mysqli->prepare($q)) {
            // Bind parameters to the statement
            $stmt->bind_param("ssi", $title, $feedback, $feedbackid);

            // Execute query and output a success or error message
            if ($stmt->execute()) {
                echo '<script>alert("Successfully replied to user !"); window.location.href= "Message.php";</script>';
            } else {
                echo '<script>alert("Error sending to user: ' . $stmt->error . '"); window.location.href = "Message.php";</script>';
            }

            // Close the statement
            $stmt->close();
            } else {
                echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.location.href = "Message.php";</script>';
            }

    } else {
        echo '<script>alert("Something went wrong ! Please try again."); window.location.href = "Message.php";</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
}
?>

