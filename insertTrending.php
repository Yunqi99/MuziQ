<?php
session_start();

$response = array(); // Initialize a response array

    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
                    
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        $response['error'] = "Database error: Please contact Muziq platform to solve the issue.";
    }

    if(isset($_POST['trackId'])) {
        // Sanitize the input to prevent SQL injection
        $trackId = $_POST['trackId'];

        // Fetch the current track count
        $stmt_fetch = $mysqli->prepare("SELECT TrackCount FROM track WHERE TrackID = ?");
        $stmt_fetch->bind_param("i", $trackId);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($trackcount);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Increment the track count
        $trackcount++;

        // Update the track count in the track table
        $stmt_update = $mysqli->prepare("UPDATE track SET TrackCount = ? WHERE TrackID = ?");
        $stmt_update->bind_param("ii", $trackcount, $trackId);
        
        if($stmt_update->execute()) {
            // Close the statement
            $stmt_update->close();
            
            $response['success'] = "Track count updated successfully.";
        } else {
            // Error updating track count
            $response['error'] = "Error: " . $stmt_update->error;
        }
    } else {
        // Handle case where track ID is not provided
        $response['error'] = "Error: Track ID not provided.";
    }

// Return the JSON response
echo json_encode($response);
?>
