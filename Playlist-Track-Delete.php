<?php
session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) {  

    // Check if there was an error connecting to the database
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

        // Get track ID from the URL
        $trackID = $_GET['trackid'];
        $playlistID = $_GET['playlistid'];
    
        // Prepare the delete statement for playlist_track
        $query1 = "DELETE FROM playlist_track WHERE TrackID = ? && PlaylistID = ?";
        $stmt1 = $mysqli->prepare($query1);
        $stmt1->bind_param("ii", $trackID, $playlistID);
    
        // Execute the statement for playlist_track
        $playlistTrackDeleted = $stmt1->execute();
    
        // Close the statement for playlist_track
        $stmt1->close();
    
        // Check if both deletion operations were successful
        if ($playlistTrackDeleted) {
            echo 'success';
        } else {
            echo 'error';
        }

    
    // Close the database connection
    $mysqli->close();
}

?>
