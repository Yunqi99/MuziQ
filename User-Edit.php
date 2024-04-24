<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'User ID is not provided'));
    exit;
}

$userid = $_GET['id'];

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_error) {
    echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}

$query = "SELECT * FROM user WHERE UserID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $accData = array(
        'name' => $row['Username'],
        'img' =>  $row['UserImage'],
        'bio' =>  $row['UserBio'],
        'email' => $row['UserEmail'],
        'password' => $row['UserPassword']
    );
} else {
    $accData = array('error' => 'Account not found');
}

echo json_encode($accData);
exit;