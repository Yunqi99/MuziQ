<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Admin ID is not provided'));
    exit;
}

$adminid = $_GET['id'];

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_error) {
    echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}

$query = "SELECT * FROM `admin` WHERE AdminID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $adminid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $accData = array(
        'img' =>  $row['AdminImg'],
        'name' => $row['AdminName'],
        'position' => $row['AdminPosition'],
        'email' => $row['AdminEmail'],
        'password' => $row['AdminPassword']
    );
} else {
    $accData = array('error' => 'Account not found');
}

echo json_encode($accData);
exit;