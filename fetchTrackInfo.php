<?php
header('Content-Type: application/json');

session_start(); // Start the session if not already started

$mysqli = new mysqli("localhost", "root", "", "muziq-test");
                
// Check if there's an error in the database connection
if ($mysqli->connect_errno) {
    echo json_encode(array("error" => "Database error: Please contact Muziq platform to solve the issue."));
    exit();
}


// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if the trackIds parameter is set
    if (isset($_GET["trackIds"]) && is_array($_GET["trackIds"])) {
        // Prepare a placeholder string for the trackIds in the SQL query
        $placeholders = rtrim(str_repeat('?,', count($_GET["trackIds"])), ',');

        // Prepare the SQL query to fetch track information based on the received TrackIDs
        $query = "SELECT track.TrackID, track.TrackName, user.Username, track.TrackImg, track.TrackFile FROM track INNER JOIN user ON track.UserID = user.UserID WHERE TrackID IN ($placeholders)";
        
        // Prepare and bind parameters
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param(str_repeat('i', count($_GET["trackIds"])), ...$_GET["trackIds"]);

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
    } else {
        // Return an error message if trackIds parameter is not set or not an array
        echo json_encode(array("error" => "Invalid request. Please provide an array of track IDs."));
    }
} else {
    // Return an error message if the request method is not GET
    echo json_encode(array("error" => "Invalid request method."));
}
?>
