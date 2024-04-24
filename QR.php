<?php

include 'phpqrcode/qrlib.php';

$URL = isset($_GET['url']) ? $_GET['url'] : null;

ob_start(); // Start output buffering
QRcode::png($URL, null, 'H', 5, 5); // Generate QR code without specifying a file path [null]
$imageData = ob_get_clean(); // Get the generated image data from the output buffer
$imageBase64 = base64_encode($imageData); // Convert the image data to base64

// Display the QR code image in image
echo '<img src="data:image/png;base64,' . $imageBase64 . '" alt="QR code of the track">';
?>
