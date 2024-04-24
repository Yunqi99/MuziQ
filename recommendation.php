<?php
header('Content-Type: application/json');

session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

$mysqli = new mysqli("localhost", "root", "", "muziq-test");
                
// Check if there's an error in the database connection
if ($mysqli->connect_errno) {
    echo json_encode(array("error" => "Database error: Please contact Muziq platform to solve the issue."));
    exit();
}
$UserID = $_SESSION['UserID'];
$q1 = "SELECT * FROM user where UserID = ?";
$stmt = $mysqli->prepare($q1);
$stmt->bind_param("i", $UserID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$UserID = $row['UserID'];

$mostFrequentGenreIds = getMostFrequentGenreIds($mysqli, $UserID);

// Fetch recommended tracks based on the most frequent genre IDs
if (!empty($mostFrequentGenreIds)) {
    $randomGenreId = $mostFrequentGenreIds[array_rand($mostFrequentGenreIds)]; // Randomly select one genre ID
    $playlistWithGenre = getPlaylistByGenreId($mysqli, $randomGenreId);
    if ($playlistWithGenre !== null) {
        $recommendedTracks = getAllTracksInPlaylist($mysqli, $playlistWithGenre['PlaylistID']);
        echo json_encode($recommendedTracks);
    } else {
        echo json_encode(array("error" => "No playlist found with the most frequent genre."));
    }
} else {
    $randomPlaylist = getRandomPlaylist($mysqli);

    $recommendedTracks = getAllTracksInPlaylist($mysqli, $randomPlaylist['PlaylistID']);
    echo json_encode($recommendedTracks);
}

function getMostFrequentGenreIds($mysqli, $UserID) {
    if (!$mysqli) {
        error_log("MySQLi connection is null or invalid");
        return getRandomGenreId($mysqli);
    }

    $query = "SELECT GenreID, COUNT(*) AS count FROM track JOIN history ON track.TrackID = history.TrackID WHERE history.UserID = ? GROUP BY track.GenreID ORDER BY count DESC";
    error_log("SQL query: " . $query); // Log the SQL query
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $UserID);
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return getRandomGenreId($mysqli);
    }
    $result = $stmt->get_result();

    // Fetch all rows with the highest count
    $mostFrequentGenreIds = array();
    $highestCount = 0;
    while ($row = $result->fetch_assoc()) {
        $genreID = $row['GenreID'];
        $count = $row['count'];
        if ($count >= $highestCount) {
            // If count is greater than or equal to the highest count so far, add to the array
            $highestCount = $count;
            $mostFrequentGenreIds[] = $genreID;
        } else {
            // If count is less than the highest count, break the loop as we found all highest counts
            break;
        }
    }

    if (!empty($mostFrequentGenreIds)) {
        error_log("Most frequent genre IDs: " . json_encode($mostFrequentGenreIds)); // Log the result
        return $mostFrequentGenreIds;
    } else {
        // If no most frequent genre IDs found, return a random genre ID
        return null;
    }
}

function getRandomGenreId($mysqli) {
    // Query to get a random genre ID
    $query = "SELECT GenreID FROM track ORDER BY RAND() LIMIT 1";
    $result = $mysqli->query($query);
    if (!$result) {
        error_log("Error executing query: " . $mysqli->error);
        return null; // Return null if error executing the query
    }
    $row = $result->fetch_assoc();
    return $row['GenreID'];
}


function getPlaylistByGenreId($mysqli, $genreId) {
    // Query to get a playlist with the specified genre ID
    $query = "SELECT * FROM playlist WHERE GenreID = ? ORDER BY RAND() LIMIT 1";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        error_log("Error preparing statement: " . $mysqli->error);
        return getRandomPlaylist($mysqli); // Return a random playlist if statement preparation fails
    }
    $stmt->bind_param("i", $genreId);
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return getRandomPlaylist($mysqli); // Return a random playlist if statement execution fails
    }
    // Get the result
    $result = $stmt->get_result();

    // Fetch the playlist
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        // If no playlist is found with the given genre ID, return a random playlist
        return getRandomPlaylist($mysqli);
    }
}

function getRandomPlaylist($mysqli) {
    // Query to get a random playlist
    $query = "SELECT * FROM playlist ORDER BY RAND() LIMIT 1";
    $result = $mysqli->query($query);
    if (!$result) {
        error_log("Error executing query: " . $mysqli->error);
        return null; // Return null if error executing the query
    }
    $row = $result->fetch_assoc();
    return $row;
}


// Function to get all tracks in a playlist
function getAllTracksInPlaylist($mysqli, $playlistId) {
    $query = "SELECT * FROM playlist_track WHERE playlist_track.PlaylistID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $playlistId);
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return array(); // Return an empty array in case of an error
    }
    // Get the result
    $result = $stmt->get_result();

    // Fetch all tracks in the playlist
    $tracks = array();
    while ($row = $result->fetch_assoc()) {
        $tracks[] = $row;
    }

    return $tracks;
}
?>
