<?php
session_start();

date_default_timezone_set('Asia/Kuala_Lumpur');

// Database connection
$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_errno) {
    error_log("Database connection error: " . $mysqli->connect_error);
    http_response_code(500); // Internal Server Error
    exit(json_encode(array("error" => "Database error: Please contact Muziq platform to solve the issue.")));
}

// Validate and sanitize the input
$trackid = filter_input(INPUT_GET, 'trackid', FILTER_VALIDATE_INT);
if ($trackid === null || $trackid === false) {
    http_response_code(400); // Bad Request
    exit(json_encode(array("error" => "Invalid track ID.")));
}

$userid = $_SESSION['UserID'];

$date = date("Y-m-d H:i:s");

// Check if history entry already exists for the given user and track
$q2 = "SELECT COUNT(*) AS count FROM history WHERE UserID = ? AND TrackID = ?";
$stmt = $mysqli->prepare($q2);
if (!$stmt) {
    error_log("Error preparing statement: " . $mysqli->error);
    http_response_code(500); // Internal Server Error
    exit(json_encode(array("error" => "Something went wrong. Please try again.")));
}

$stmt->bind_param("ii", $userid, $trackid);
if (!$stmt->execute()) {
    error_log("Error executing statement: " . $stmt->error);
    http_response_code(500); // Internal Server Error
    exit(json_encode(array("error" => "Something went wrong. Please try again.")));
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $row['count'];

// Check total number of history records for the user
$q3 = "SELECT COUNT(*) AS total FROM history WHERE UserID = ?";
$stmt = $mysqli->prepare($q3);
if (!$stmt) {
    error_log("Error preparing statement: " . $mysqli->error);
    http_response_code(500); // Internal Server Error
    exit(json_encode(array("error" => "Something went wrong. Please try again.")));
}

$stmt->bind_param("i", $userid);
if (!$stmt->execute()) {
    error_log("Error executing statement: " . $stmt->error);
    http_response_code(500); // Internal Server Error
    exit(json_encode(array("error" => "Something went wrong. Please try again.")));
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalRecords = $row['total'];
$stmt->close();

// Total records cannot exceed 15
if ($totalRecords >= 15) {
    // Delete oldest record
    $deleteOldestQuery = "DELETE FROM history WHERE UserID = ? ORDER BY DateListened ASC LIMIT 1";
    $stmtDelete = $mysqli->prepare($deleteOldestQuery);
    if (!$stmtDelete) {
        error_log("Error preparing statement: " . $mysqli->error);
        http_response_code(500); // Internal Server Error
        exit(json_encode(array("error" => "Something went wrong. Please try again.")));
    }

    $stmtDelete->bind_param("i", $userid);
    if (!$stmtDelete->execute()) {
        error_log("Error executing statement: " . $stmtDelete->error);
        http_response_code(500); // Internal Server Error
        exit(json_encode(array("error" => "Something went wrong. Please try again.")));
    }
    $stmtDelete->close();

    if ($count > 0) {
        // Update existing record
        $updateQuery = "UPDATE history SET DateListened = ? WHERE UserID = ? AND TrackID = ?";
        $stmt = $mysqli->prepare($updateQuery);
        if (!$stmt) {
            error_log("Error preparing statement: " . $mysqli->error);
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Something went wrong. Please try again.")));
        }
    
        $stmt->bind_param("sii", $date, $userid, $trackid);
        if (!$stmt->execute()) {
            error_log("Error executing statement: " . $stmt->error);
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Something went wrong. Please try again.")));
        }
    
    } else {
        // Insert new record
        $insertQuery2 = "INSERT INTO history (DateListened, UserID, TrackID) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($insertQuery2);
        if (!$stmt) {
            error_log("Error preparing statement: " . $mysqli->error);
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Something went wrong. Please try again.")));
        }
    
        $stmt->bind_param("sii", $date, $userid, $trackid);
        if (!$stmt->execute()) {
            error_log("Error executing statement: " . $stmt->error);
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Something went wrong. Please try again.")));
        }
    
        $stmt->close();
    }
    $stmtInsert->close();
}


// Now insert or update the history entry
if ($count > 0) {
    // Update existing record
    $updateQuery = "UPDATE history SET DateListened = ? WHERE UserID = ? AND TrackID = ?";
    $stmt = $mysqli->prepare($updateQuery);
    if (!$stmt) {
        error_log("Error preparing statement: " . $mysqli->error);
        http_response_code(500); // Internal Server Error
        exit(json_encode(array("error" => "Something went wrong. Please try again.")));
    }

    $stmt->bind_param("sii", $date, $userid, $trackid);
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        http_response_code(500); // Internal Server Error
        exit(json_encode(array("error" => "Something went wrong. Please try again.")));
    }

} else {
    // Insert new record
    $insertQuery2 = "INSERT INTO history (DateListened, UserID, TrackID) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($insertQuery2);
    if (!$stmt) {
        error_log("Error preparing statement: " . $mysqli->error);
        http_response_code(500); // Internal Server Error
        exit(json_encode(array("error" => "Something went wrong. Please try again.")));
    }

    $stmt->bind_param("sii", $date, $userid, $trackid);
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        http_response_code(500); // Internal Server Error
        exit(json_encode(array("error" => "Something went wrong. Please try again.")));
    }

    $stmt->close();
}

$mysqli->close();

// If everything is successful
exit(json_encode(array("success" => "History inserted successfully.")));
?>
