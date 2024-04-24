<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "muziq-test"); // Establish a database connection using mysqli
if ($mysqli->connect_errno) {
    echo 'Database error: Please contact Muziq platform to solve the issue.';
}

if (isset($_GET['playlistId']) && isset($_GET['trackId'])) {
    $PlaylistID = $_GET['playlistId'];
    $TrackID = $_GET['trackId'];

    // Check if the track is already in the playlist
    $qCheck = "SELECT * FROM playlist_track WHERE PlaylistID = ? AND TrackID = ?";
    if ($stmtCheck = $mysqli->prepare($qCheck)) {
        $stmtCheck->bind_param("ii", $PlaylistID, $TrackID);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            echo 'exists'; // Track is already in the playlist
            exit; // Stop further execution
        }
        $stmtCheck->close();
    } else {
        echo 'error: Something went wrong during the check: ' . $mysqli->error;
        exit; // Stop further execution
    }

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date = date("Y-m-d H:i:s");

    $q2 = "INSERT INTO playlist_track (P_DateAdded, PlaylistID, TrackID) VALUES (?, ?, ?)";
    if ($stmt = $mysqli->prepare($q2)) {
        $stmt->bind_param("sii", $date, $PlaylistID, $TrackID);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error: Something went wrong! Please try again: ' . $stmt->error;
        }
        $stmt->close(); // Close the statement
    } else {
        echo 'error: Something went wrong! Please try again: ' . $mysqli->error;
    }

} else {
    echo "error: Invalid request";
}

$mysqli->close();

?>
