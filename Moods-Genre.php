<?php
session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

$response = array(); // Initialize response array

if (!$loggedin) { // Check if the user is not logged in
    $response['error'] = "Kindly login to proceed!";
    $response['redirect'] = "index.html";
} else {
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        $response['error'] = "Database error: Please contact Muziq platform to solve the issue.";
        $response['redirect'] = "MuziQ.php";
    } else {
        $UserID = $_SESSION['UserID'];

        $GenreID = $_POST['id'];

        $html = '<div class="content" id="content">';
        $html .= '<div class="MG-layout" id="MG-layout">';

        $genrequery = "SELECT * FROM genre WHERE GenreID = $GenreID";
        if ($res = $mysqli->query($genrequery)) {
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) { 
                    $html .= '<h1>'.$row['GenreName'].' Feature Playlist</h1>';
                }
            }
        }
        $html .= '<div class="MG-container">
        <div class="MG-container-row">';

        $q1 = "SELECT * FROM playlist WHERE AdminID <> 0 AND GenreID = $GenreID ORDER BY RAND() LIMIT 12";
        if ($res = $mysqli->query($q1)) {
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) { 
                    $html .= '<div class="column-container" id="column-container">
                        <div class="column">
                            <div class="column-track">
                                <div class="column-img">
                                    <img src="Data/Playlist/'.$row['PlaylistImg'].'"/>
                                </div>
                                <div class="info">
                                    <button onclick="loadPlaylist('.$row['PlaylistID'].')"><h5>'.$row['PlaylistName'].'</h5></button>
                                    <p>By MuziQ</p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
        }
        $html .= '
        </div>
        </div>';

    $response['html'] = $html; // Add HTML content to response
}

echo json_encode($response); // Encode response array as JSON

}
?>
