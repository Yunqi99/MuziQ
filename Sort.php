<?php

$mysqli = new mysqli("localhost", "root", "", "muziq-test");
// Check if there's an error in the database connection
if ($mysqli->connect_errno) {
    echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
}

// Include Composer autoloader
require_once 'vendor/autoload.php';
            
// Retrieve the sorting order from the GET parameters
$order = isset($_GET['order']) ? $_GET['order'] : 'Default';
$playlistId = isset($_GET['id']) ? $_GET['id'] : '';


if (!empty($playlistId)) {
    $query = "SELECT track.*, user.*
            FROM playlist_track
            INNER JOIN track ON playlist_track.TrackID = track.TrackID
            INNER JOIN user ON track.UserID = user.UserID
            WHERE playlist_track.PlaylistID = '" . $playlistId . "'
            ORDER BY ";

// Modify your existing query to include the sorting logic
$query = "SELECT track.*, user.*
            FROM playlist_track
            INNER JOIN track ON playlist_track.TrackID = track.TrackID
            INNER JOIN user ON track.UserID = user.UserID
            WHERE playlist_track.PlaylistID = '" . $_GET['id'] . "'
            ORDER BY ";

switch ($order) {
    case 'AtoZ':
        $query .= "track.TrackName ASC";
        break;
    case 'ZtoA':
        $query .= "track.TrackName DESC";
        break;
    case 'Latest':
        $query .= "track.UploadDate DESC";
        break;
    default:
        $query .= "playlist_track.P_TrackID ASC"; // Adjust this to your default sorting column
        break;
}

$playlist = []; // Initialize an empty array for the playlist
$index = 0;

// Execute the query and fetch the sorted data
if ($res = $mysqli->query($query)) {
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {

            ob_start();
            
            $trackid = $row['TrackID'];
            // Create an associative array with track data
            $data_track = [
                "TrackID" => $trackid,
                "TrackName" => $row['TrackName'],
                "Username" => $row['Username'],
                "TrackImg" => $row['TrackImg'],
                "TrackFile" => $row['TrackFile']
            ];  

            $playlist[] = $data_track; 

            echo '<div class="track-container">
                <div class="track-img" onclick="getPlaylistQueue(playlistList); loadTrack('.$index.')">
                    <img src="Data/TrackImage/' . $row['TrackImg'] . '">
                </div>
                <div class="track-info">
                <span class="button-col">
                    <button onclick="loadMusic(' . $row['TrackID'] . ')"><p>' . $row['TrackName'] . '</p></button>
                </span>
                <span class="name">
                    <button onclick="loadIndividual(' . $row['UserID'] . ')"><p>' . $row['Username'] . '</p></button>
                </span>
                </div>
            </div>';

            $index++;
        }

         // Output JavaScript queue list
        echo '<script>';
        echo 'var playlistList = ' . json_encode($playlist) . ';'; // Encode the playlist array as JSON
        echo '</script>';

        // End output buffering and get the content
        $htmlContent = ob_get_clean();

        // Output HTML content
        echo $htmlContent;    

    } else {
        echo '<script>alert("No tracks found in the playlist."); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
}
} else {
    echo '<script>alert("Playlist ID is missing."); window.history.back();</script>';
}

?>
