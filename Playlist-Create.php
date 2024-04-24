<?php
session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) {

    // Set target directory
    $target_dir = "Data/Playlist/";

    // Set target filename
    $target_filename = basename($_FILES["fileToUpload"]["name"]);

    // Concatenate target directory and filename
    $target_file = $target_dir . $target_filename;

    // Check if file format is allowed
    $allowed_formats = array("jpg", "tiff", "png", "gif");
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $allowed_formats)) {
        echo 'error';
        exit; // Exit script if format is not allowed
    }

    // Move uploaded file to target directory
    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo 'error';
        exit; // Exit script if file move fails
    }

    // Database connection
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");

    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo 'Error in connecting to database';
        exit; // Exit script if database connection fails
    }

    // Get UserID from session
    $UserID = $_SESSION['UserID'];

    // Get the maximum GeneratedID from the playlist table
    $queryID = "SELECT MAX(SUBSTRING(PlaylistGeneratedID, 3)) AS MaxGenID FROM playlist";
    $result = $mysqli->query($queryID);

    if (!$result) {
        echo 'error';
        exit; // Exit script if query fails
    }

    // Fetch the result row
    $row = $result->fetch_assoc();

    // Get the maximum GeneratedID
    $maxGenID = ($row['MaxGenID'] === null) ? 0 : (int)$row['MaxGenID'];

    // Increment the maximum GeneratedID
    $newID = "PL" . sprintf("%05d", $maxGenID + 1);

    // Prepare and execute the insert query
    $q1 = "INSERT INTO playlist (PlaylistGeneratedID, PlaylistName, PlaylistDesc, PlaylistImg, UserID, GenreID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($q1);

    if (!$stmt) {
        echo 'error';
        exit; // Exit script if statement preparation fails
    }

    // Bind parameters to the statement
    $stmt->bind_param("ssssii", $newID, $_POST['playlistname'], $_POST['playlistdesc'], $target_filename, $UserID, $_POST['genreinput']);

    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close the statement and database connection
    $stmt->close();
    $mysqli->close();

} else {
    echo 'error';
}
?>
