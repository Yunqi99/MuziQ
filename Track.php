
<?php
session_start();

require_once 'vendor/autoload.php'; 

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

// Check for database connection errors
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Database connection error: ' . $mysqli->connect_error]);
    exit;
}

// Check if the ID is provided in the request
if (isset($_POST['id'])) {
    $trackId = $_POST['id'];
    $UserID = $_SESSION['UserID'];

    // Fetch track data from the track table
    $trackquery = "SELECT track.*, user.* FROM track INNER JOIN user ON track.UserID = user.UserID WHERE track.TrackID = $trackId";
    $result = $mysqli->query($trackquery);

    // Check if the query was successful and track data is found
    if ($result && $result->num_rows > 0) {
        // Fetch track data
        $trackData = $result->fetch_assoc();

        // Construct the track's information as an array
        $track = htmlspecialchars(json_encode([
            'trackid' => $trackData['TrackID'],
            'individualid' => $trackData['UserID'],
            'name' => $trackData['TrackName'],
            'individual' => $trackData['Username'],
            'path' => $trackData['TrackFile'],
            'img' => $trackData['TrackImg'],
        ]));  

         $tracksResult = $mysqli->query($trackquery);
 
         // Check if tracks were found
         if ($tracksResult && $tracksResult->num_rows > 0) {
            // Start output buffering
            ob_start();
 
            // Output HTML content
            echo '<div class="content" id="content">';
            echo '<div class="track-layout" id="track-layout">';
            echo '<div class="track-pg-container">
                <div class="column-container" id="column-container" data-track="'. $track .'">
                    <audio data-trackid="'.$trackData['TrackID'].'" src="Data/TrackFile/'.$trackData['TrackFile'].'"></audio>    
                    <div class="track-img">
                        <img src="Data/TrackImage/'.$trackData['TrackImg'].'">
                        <div class="track-overlay"></div>
                        <i class="fas fa-play play-btn"></i>
                    </div>
                </div>
                        <div class="track-info">
                            <div class="track-info-row">
                                <div class="track-info-col1">
                                    <h1>'.$trackData['TrackName'].'</h1>
                                    <p>'.$trackData['Username'].'</p>
                                    <p>Released on '.$trackData['ReleaseDate'].'</p>
                                </div>
                                <div class="track-info-col2">
                                    <button onclick="share(); showPopup();"><i class="fa fa-share"></i></button>
                                    <br>
                                    <!-- Dark overlay -->
                                    <div class="overlay" id="overlay"></div>
                                    <div class="qr-container" id="qr-container">
                                    <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
                                        <h1>Share Track</h1>
                                        <div id="qr-img"></div>
                                        <br>
                                        <button id="btn-copy" onclick="copy('.$trackData['TrackID'].')">
                                            <i class="fa fa-copy"></i>
                                            <h4>Copy</h4>
                                        </button>
                                        <button id="btn-download">
                                            <i class="fas fa-arrow-circle-down"></i>
                                            <h4>Download</h4>
                                        </button>
                                    </div>
                                    
                                    <button onclick="addTrack()"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<script>alert("Something went wrong. Please try again.");</script>';
            }
        
        echo '<div class="playlistavailable" id="playlist-available">';

        $playlistquery = "SELECT * FROM playlist WHERE UserID = $UserID";
        if ($res = $mysqli->query($playlistquery)) {
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) { 
                    echo '<button class="btn-AIP" data-playlist-id="' . $row['PlaylistID'] . '" data-track-id="' . $trackId . '">' . $row['PlaylistName'] . '</button>';
                }

            } else {
                echo 'No playlist available.';
            }
            echo '</div>';
                echo '</div>';
                echo '</div>';
        }
            // Get the output buffer contents and clean the buffer
            $htmlContent = ob_get_clean();
    
            // Return track data and HTML content as JSON
            echo json_encode(['track' => $track, 'html' => $htmlContent]);

        } else {
            echo json_encode(['error' => 'Track ID not provided']);
        }
        
?>

