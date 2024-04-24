<?php

session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {
    // Establish a database connection using mysqli
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    $AdminID = $_SESSION['AdminID'];
    $playlistID = $_GET['id'];

    if ($AdminID) {

        // Construct SQL queries to delete playlists and tracks
        $q3 = "DELETE FROM playlist_track WHERE playlistID='$playlistID';";
        $q4 = "DELETE FROM playlist WHERE playlistID='$playlistID';";

        // Execute the deletion queries
        if ($mysqli->query($q3) && $mysqli->query($q4)) {
            echo '<script>alert("Successfully deleted the playlist!"); window.location.href = "Feature-List.php";</script>';
        } else {
            echo '<script>alert("Something went wrong! Please try again."); window.location.href = "Feature-List.php";</script>';
        }
    }

    $mysqli->close();  // Close the database connection
}
?>
