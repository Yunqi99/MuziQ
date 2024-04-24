<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'FAQ ID is not provided'));
    exit;
}

$faqid = $_GET['id'];

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

if ($mysqli->connect_error) {
    echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}

$query = "SELECT * FROM FAQ WHERE FAQID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $faqid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $faqData = array(
        'question' => $row['Question'],
        'answer' => $row['Answer']
    );
} else {
    $faqData = array('error' => 'FAQ not found');
}

echo json_encode($faqData);
exit;