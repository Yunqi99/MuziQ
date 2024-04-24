<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_errno) {
    // If there's an error in the database connection, return an error message
    echo 'Database error';
    exit;
}

if (!isset($_POST['TrackID'])) {
    // If trackId is not provided, return an error message
    echo'Track ID is missing';
    exit;
}

$trackId = $_POST['TrackID'];
$reason = $_POST['reasonAppr'];

// Prepare the SQL statement
$query = "UPDATE track SET ValidationStatus = 'Approved', Reason = ? WHERE TrackID = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    // If there's an error in preparing the statement, return an error message
    echo 'Failed to prepare statement';
    exit;
}

// Bind the parameters to the statement
$stmt->bind_param('si', $reason, $trackId);

// Execute the statement
if ($stmt->execute()) {
    // If the query is successful, return a success message
    echo 'success';
} else {
    // If there's an error in the query, return an error message
    echo 'error';
}

$stmt->close();
$mysqli->close();
?>
