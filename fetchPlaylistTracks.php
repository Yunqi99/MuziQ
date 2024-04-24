<?php
header('Content-Type: application/json');

session_start(); // Start the session if not already started

$mysqli = new mysqli("localhost", "root", "", "muziq-test");
                
// Check if there's an error in the database connection
if ($mysqli->connect_errno) {
    echo json_encode(array("error" => "Database error: Please contact Muziq platform to solve the issue."));
    exit();
}


 // Check if the playlistId parameter is set
if (isset($_GET["playlistId"])) {
    // Prepare the SQL query to fetch track information based on the received playlistId
    $query = "SELECT track.TrackID, track.TrackName, user.Username, track.TrackImg, track.TrackFile 
    FROM track
    INNER JOIN user ON track.UserID = user.UserID
    INNER JOIN playlist_track ON playlist_track.TrackID = track.TrackID 
    WHERE playlist_track.PlaylistID = ?";  
    
    // Prepare and bind parameters
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $_GET["playlistId"]); // Bind single parameter instead of array

    // Execute the query
    if ($stmt->execute()) {
        // Fetch the results
        $result = $stmt->get_result();
        
        // Fetch the track information into an associative array
        $tracks = array();
        while ($row = $result->fetch_assoc()) {
            $tracks[] = $row;
        }

        // Return the track information as JSON
        echo json_encode($tracks);
    } else {
        // Return an error message if the query execution fails
        echo json_encode(array("error" => "Failed to fetch track information."));
    }

    // Close the statement
    $stmt->close();
} else {
    // Return an error message if playlistId parameter is not set
    echo json_encode(array("error" => "Invalid request. Please provide a playlist ID."));
}

?>
