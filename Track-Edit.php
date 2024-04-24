<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Track ID is not provided'));
    exit;
}

$id = $_GET['id'];

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_error) {
    echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}

$query = "SELECT track.*, genre.* FROM track JOIN genre ON track.GenreID = genre.GenreID WHERE track.TrackID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data = array(
        'name' => $row['TrackName'],
        'img' =>  $row['TrackImg'],
        'file' =>  $row['TrackFile'],
        'genreid' => $row['GenreID'],
        'genre' => $row['GenreName'],
        'release' => $row['ReleaseDate']
    );
} else {
    $data = array('error' => 'Track not found');
}

echo json_encode($data);
exit;
