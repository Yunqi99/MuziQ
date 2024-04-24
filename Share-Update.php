<?php
session_start();

// Check if the user is logged in
$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

if ($loggedin) {
    // Check if the TrackID is set in the request
    if(isset($_GET['TrackID'])) {
        // Get the TrackID from the request
        $trackid = $_GET['TrackID'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            exit; // Stop script execution if connection fails
        }

        // Prepare the UPDATE query to increment the ShareCount for the track
        $q = "UPDATE track SET ShareCount = ShareCount + 1 WHERE TrackID = ?";

        // Prepare and execute the statement
        if ($stmt = $mysqli->prepare($q)) {
            // Bind the TrackID parameter
            $stmt->bind_param("i", $trackid);
            // Execute the query
            if ($stmt->execute()) {
                echo "Success";
            } else {
                echo "Error executing query";
            }
            $stmt->close();
        } else {
            echo "Error preparing statement";
        }
        $mysqli->close(); // Close the database connection
    } else {
        echo "TrackID is not set!";
    }
} else {
    echo "User is not logged in!";
}
?>
