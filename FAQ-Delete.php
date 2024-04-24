<?php
session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {

    // Check if there was an error connecting to the database
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
    
    if(isset($_GET['id'])) {
        $FAQID = $_GET['id'];
    
        $query = "DELETE FROM faq WHERE FAQID = ?";
        $stmt = $mysqli->prepare($query);
    
        $stmt->bind_param("i", $FAQID);
    
        // Execute the statement
        if ($stmt->execute()) {
            echo '<script>alert("Successfully deleted!"); window.history.back();</script>';
        } else {
            echo '<script>alert("Error! Please try to delete again."); window.history.back();</script>'; 
        }
    
        // Close the statement
        $stmt->close();
    } else {
        echo '<script>alert("Error! Track ID is not provided."); window.history.back();</script>'; 
    }
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}

?>
