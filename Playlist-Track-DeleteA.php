<?php 

session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {

    // Check if there was an error connecting to the database
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

       // Get track ID from the URL
       $trackID = $_GET['trackid'];
        $playlistID = $_GET['playlistid'];

       // Prepare the delete statement
       $query1 = "DELETE FROM playlist_track WHERE TrackID = ? && PlaylistID = ?";

        // Execute the delete query
        if ($stmt = $mysqli->prepare($query1)) {
            $stmt->bind_param("ii", $trackID, $playlistID);
            if ($stmt->execute()) {
                echo '<script>alert("Successfully removed track from playlist !"); window.history.back();</script>';
            } else {
                echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
            }

        } else {
            echo "Error: " . $mysqli->error;
        }

        // Close the statement
        $stmt->close();
}  else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}

?>
