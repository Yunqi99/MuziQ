<?php
if (isset($_GET["playlistID"]) &&isset($_GET["searchQuery"]) && !empty($_GET["searchQuery"])) {

    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo json_encode(array("error" => "Database error: Please contact Muziq platform to solve the issue."));
        exit; // Stop further execution
    }
        
    // Sanitize the search query
    $search = mysqli_real_escape_string($mysqli, $_GET["searchQuery"]);

    $query = "SELECT playlist_track.*, track.* FROM playlist_track 
          INNER JOIN track ON playlist_track.TrackID = track.TrackID 
          WHERE playlist_track.PlaylistID = {$_GET['playlistID']} AND LOWER(track.TrackName) LIKE LOWER('%$search%')";


    $result = mysqli_query($mysqli, $query);
    
    if ($result) {
        $html_content = '<div class="sPlaylist-content">';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $html_content .= ' <div class="column">
                                        <div class="strack-container">
                                            <div class="track-img">
                                                <img src="Data/TrackImage/'.$row['TrackImg'].'">
                                            </div>
                                            <div class="strack-info">
                                            <button onclick="loadMusic('. $row['TrackID'] .')"><h3>'.$row['TrackName'].'</h3></button>
                                            </div>
                                        </div>
                                    </div>';
            }
            $html_content .= '</div>';
        } else {
            $html_content .= '<div class="empty-container">
                                <div class="empty-img">
                                    <img src="Sources/Img/Empty.png"/>
                                </div>
                                <div class="empty-text" id="filter-text">
                                    <h5>Not found. Please try to search again.</h5>
                                </div>
                            </div>';
        }
        echo $html_content;
    } else {
        echo json_encode(array("error" => "Query error: Please contact Muziq platform to solve the issue."));
    }
} else {
    echo json_encode(array("error" => "No search query provided."));
}
?>
