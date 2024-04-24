<?php
session_start();

require_once 'vendor/autoload.php';

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

if (!$loggedin) { // Check if the user is not logged in
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
} else {
    // Connect to the database
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");

    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        $response['error'] = "Database error: Please contact Muziq platform to solve the issue.";
        $response['redirect'] = "MuziQ.php";
    } else {
        if (isset($_POST['id'])) {
            $playlistId = $mysqli->real_escape_string($_POST['id']); // Escape the playlist ID
            // Query to retrieve playlist information
            $query = "SELECT * FROM playlist WHERE PlaylistID = '$playlistId'";
            $result = $mysqli->query($query);

            // Check if the query executed successfully
            if ($result && $result->num_rows > 0) {
                // Fetch playlist details
                $row = $result->fetch_assoc();

                // Start output buffering
                ob_start();

                // Output HTML content

                echo '
                    <div class="content" id="content">
                        <div class="playlist-layout" id="playlist-layout">
                            <div class="playlist-container1">
                                <div class="playlist-img playlist" style="position:relative;">
                                    <img src="Data/Playlist/' . $row['PlaylistImg'] . '" alt="Playlist Image">
                                    <div class="track-overlay"></div>
                                    <i class="fas fa-play play-btn"></i>
                                </div>
                                <div class="playlist-info">
                                    <div class="playlist-info-row">
                                        <div class="playlist-info-col">
                                            <h2>' . $row['PlaylistName'] . '</h2><br>
                                            <p>' . $row['PlaylistDesc'] . '</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="playlist-feature">
                                <div class="playlist-feature-col">
                                    <div class="playlist-dropdown">
                                        <button onclick="playlistDropdown()" class="btn-sort">Sort by<i class="fa fa-caret-down"></i></button>
                                        <div class="sort-group" id="sort-content">
                                            <button onclick="sortTracks(\'AtoZ\')">A-Z</button>
                                            <button onclick="sortTracks(\'ZtoA\')">Z-A</button>
                                            <button onclick="sortTracks(\'Latest\')">Latest</button>
                                            <button onclick="sortTracks(\'Default\')">Default</button>
                                        </div>
                                    </div>
                                    <div class="playlist-feature-col2">
                                        <div class="playlist-searchbox">
                                            <input type="text" id="sPlaylistInput" placeholder="Search here"/>
                                            <i class="fa fa-search" id="search-playlist" data-playlist="' . $row['PlaylistID'] . '"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="playlist-container2" id="playlist-container2">';

                            $index = 0;
                            $playlist = []; // Initialize an empty array for the playlist

                            $query = "SELECT track.*, user.* FROM playlist_track
                                    INNER JOIN track ON playlist_track.TrackID = track.TrackID
                                    INNER JOIN user ON track.UserID = user.UserID
                                    WHERE playlist_track.PlaylistID = '$playlistId';";
                            $res = $mysqli->query($query);
                            if ($res && $res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    $filePath = 'Data/TrackFile/' . $row['TrackFile'];
                                    if (file_exists($filePath)) {
                            
                                        $trackid = $row['TrackID'];
                                        // Create an associative array with track data
                                        $data_track = [
                                            "TrackID" => $trackid,
                                            "TrackName" => $row['TrackName'],
                                            "Username" => $row['Username'],
                                            "TrackImg" => $row['TrackImg'],
                                            "TrackFile" => $row['TrackFile']
                                        ];  
                            
                                        $playlist[] = $data_track; // Add the track data to the playlist array

                                        // Output HTML for the track container
                                        echo '
                                            <div class="track-container">
                                                <div class="track-img" onclick="loadTrack('.$index.')">
                                                    <img src="Data/TrackImage/' . $row['TrackImg'] . ' ">
                                                </div>
                                                <audio src="Data/TrackFile/'.$row['TrackFile'].'"></audio>
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

                                    } else {
                                        echo '<script>alert("File not found: ' . $filePath . '");</script>';
                                    }
                            
                                }
                            } else {
                                echo '
                                    <div class="empty-container">
                                        <div class="empty-img">
                                            <img src="Sources/Img/Empty.png"/>
                                        </div>
                                        <div class="empty-text">
                                            <h5>Looks like your playlist is enjoying some quiet time.</h5>
                                        </div>
                                    </div>';
                            }
                            
                            
                            // Output JavaScript queue list
                            echo '<script>';
                            echo 'loadTrackScript();';
                            echo 'var playlistList = ' . json_encode($playlist) . ';'; // Encode the playlist array as JSON
                            echo 'getPlaylistQueue(playlistList);'; // Call a function to set up playlist events
                            echo '</script>';

                    echo '<div class="overlay" id="overlay"></div>';

                    echo '<div class="playlist-result" id="playlist-result">
                        <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
                        <div class="column-scroll">
                            <div class="playlist-cont" id="result-cont">

                            </div>
                        </div>
                    </div>';

                // End output buffering and get the content
                $htmlContent = ob_get_clean();

                // Output HTML content
                echo $htmlContent;

            } else {
                echo '<div class="error">Playlist not found</div>';
            }
        } else {
            echo '<div class="error">Playlist ID not provided</div>';
        }
    }
}
?>
