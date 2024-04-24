<?php
session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) {
    // Establish a database connection using mysqli
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        echo 'Error in connecting to database';
        exit; // Exit script if database connection fails
    }

    $UserID = $_SESSION['UserID'];
    $playlistID = isset($_GET['id']) ? $_GET['id'] : null; // Fetch playlist ID from $_GET['id']

    if ($UserID && $playlistID !== null) {
        // Check if there are tracks associated with the playlist
        $checkTracksQuery = "SELECT COUNT(*) AS trackCount FROM playlist_track WHERE playlistID = ?";
        $stmtCheckTracks = $mysqli->prepare($checkTracksQuery);
        $stmtCheckTracks->bind_param("i", $playlistID);
        $stmtCheckTracks->execute();
        $resultCheckTracks = $stmtCheckTracks->get_result();
        $rowCheckTracks = $resultCheckTracks->fetch_assoc();
        $trackCount = $rowCheckTracks['trackCount'];
        $stmtCheckTracks->close();

        if ($trackCount > 0) {
            // Delete tracks associated with the playlist
            $deleteTracksQuery = "DELETE FROM playlist_track WHERE playlistID = ?";
            $stmtDeleteTracks = $mysqli->prepare($deleteTracksQuery);
            $stmtDeleteTracks->bind_param("i", $playlistID);
            $deleteTracksSuccess = $stmtDeleteTracks->execute();
            $stmtDeleteTracks->close();
            if (!$deleteTracksSuccess) {
                echo 'error';
                exit; // Exit script if track deletion fails
            }
        }

        // Delete the playlist
        $deletePlaylistQuery = "DELETE FROM playlist WHERE playlistID = ?";
        $stmtDeletePlaylist = $mysqli->prepare($deletePlaylistQuery);
        $stmtDeletePlaylist->bind_param("i", $playlistID);
        $deletePlaylistSuccess = $stmtDeletePlaylist->execute();
        $stmtDeletePlaylist->close();

        // Check if playlist deletion was successful
        if ($deletePlaylistSuccess) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
        echo '<script>alert("Invalid user or playlist ID."); window.location.href="User-dashboard.php";</script>';
    }

    $mysqli->close();  // Close the database connection
} else {
    echo 'error';
}
?>
