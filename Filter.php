<?php
if (isset($_GET["searchQuery"]) && !empty($_GET["searchQuery"])) {
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

    $search = mysqli_real_escape_string($mysqli, $_GET["searchQuery"]);
    $option = isset($_GET["option"]) ? $_GET["option"] : "";

    $query = "";

    switch ($option) {
        case 'individual':
            $query = "SELECT * FROM user WHERE LOWER(Username) LIKE LOWER('%$search%') ORDER BY UserID DESC";

            $result = mysqli_query($mysqli, $query);

            if ($result) {
                echo '<div class="search-container" id="filtercol">
                <div class="search-row">
                    <div class="search-content">';
                if (mysqli_num_rows($result) > 0) {
                       
                        while ($row = mysqli_fetch_array($result)) {
                            echo ' <div class="column"> 
                            <div class="track-container">
                            <div class="track-img">
                                <img src="Data/User/'.$row['UserImage'].'">
                            </div>
                            <div class="track-info">
                                <button onclick="loadIndividual('. $row['UserID'] .')"><h3>'.$row['Username'].'</h3></button>
                            </div>
                            </div>
                            </div>';
                        }
                        echo '</div>
                            </div>
                            </div>';
                    } else {
                        echo '<div class="empty-container">
                        <div class="empty-img">
                            <img src="Sources/Img/Empty.png"/>
                        </div>
                        <div class="empty-text" id="filter-text">
                            <h5>Not found. Please try to search again.</h5>
                        </div>
                    </div>';
                    }
                }
            break;

        case 'playlist':
            $query = "SELECT * FROM playlist WHERE LOWER(PlaylistName) LIKE LOWER('%$search%') ORDER BY RAND() DESC";
            
            $result = mysqli_query($mysqli, $query);

            if ($result) {
                echo '<div class="search-container" id="filtercol">
                <div class="search-row">
                    <div class="search-content">';
                if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_array($result)) {
                            echo ' <div class="column"> 
                            <div class="track-container">
                            <div class="track-img">
                                <img src="Data/Playlist/'.$row['PlaylistImg'].'">
                            </div>
                            <div class="track-info">
                                <button onclick="loadPlaylist('. $row['PlaylistID'] .')"><h3>'.$row['PlaylistName'].'</h3></button>
                            </div>
                            </div>
                            </div>';
                        }
                        echo '</div>
                            </div>
                            </div>';
                    } else {
                        echo '<div class="empty-container">
                        <div class="empty-img">
                            <img src="Sources/Img/Empty.png"/>
                        </div>
                        <div class="empty-text" id="filter-text">
                            <h5>Not found. Please try to search again.</h5>
                        </div>
                    </div>';
                    }
                }
            break;

        default:
            $query = "SELECT * FROM track WHERE LOWER(TrackName) LIKE LOWER('%$search%') ORDER BY RAND() DESC";
            
            $result = mysqli_query($mysqli, $query);

            if ($result) {
                echo '<div class="search-container" id="filtercol">
                <div class="search-row">
                    <div class="search-content">';
                if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_array($result)) {
                            echo ' <div class="column"> 
                            <div class="track-container">
                            <div class="track-img">
                                <img src="Data/TrackImage/'.$row['TrackImg'].'">
                            </div>
                            <div class="track-info">
                                <button onclick="loadMusic('. $row['TrackID'] .')"><h3>'.$row['TrackName'].'</h3></button>
                            </div>
                            </div>
                            </div>';
                        }
                        echo '</div>
                            </div>
                            </div>';
                    } else {
                        echo '<div class="empty-container">
                        <div class="empty-img">
                            <img src="Sources/Img/Empty.png"/>
                        </div>
                        <div class="empty-text" id="filter-text">
                            <h5>Not found. Please try to search again.</h5>
                        </div>
                    </div>';
                    }
                }
            break;
    }
}
?>
