<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Playlist ID is not provided'));
    exit;
}

$id = $_GET['id'];

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_error) {
    echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}

$query = "SELECT playlist.*, genre.* FROM playlist JOIN genre ON playlist.GenreID = genre.GenreID WHERE playlist.PlaylistID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data = array(
        'name' => $row['PlaylistName'],
        'img' =>  $row['PlaylistImg'],
        'genreid' => $row['GenreID'],
        'genre' => $row['GenreName'],
        'desc' =>  $row['PlaylistDesc']
    );
} else {
    $data = array('error' => 'Playlist not found');
}

echo json_encode($data);
exit;
