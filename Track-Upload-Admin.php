<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');
if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {
    if (isset($_POST['createTrack'])) {

        $trackname = $_POST['trackname'];
        $genreinput = $_POST['genreinput'];
        $releasedate = $_POST['releasedate'];

        $target_dir1 = "Data/TrackImage/"; //Set target directory
        $target_filename1 = basename($_FILES["fileToUpload2"]["name"]); //Set target filename
        $target_file1 = $target_dir1 . $target_filename1; 
        $uploadOk = TRUE; 
        $imageFileType = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION)); 

        // Check the format of image file uploaded
        if ($imageFileType != "jpg" && $imageFileType != "tiff" && $imageFileType != "png" && $imageFileType != "gif") {
            echo '<script>alert("Error: only jpg, tiff, png, gif files are allowed."); window.history.back();</script>';
            $uploadOk = FALSE;
        }

        $target_dir2 = "Data/TrackFile/"; //Set target directory
        $target_filename2 = basename($_FILES["fileToUploadTrack"]["name"]); //Set target filename
        $target_file2 = $target_dir2 . $target_filename2; 
        $uploadOk = TRUE; 
        $audioFileType = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

        // Check audio file format
        if ($audioFileType != "mp3" && $imageFileType != "wav" && $imageFileType != "aac") {
            echo '<script>alert("Error: only mp3, wav, aac files are allowed."); window.history.back();</script>';
            $uploadOk = FALSE;
        }

        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file1) && move_uploaded_file($_FILES["fileToUploadTrack"]["tmp_name"], $target_file2)) {
                $mysqli = new mysqli("localhost", "root", "", "muziq-test");
                    
                // Check if there's an error in the database connection
                if ($mysqli->connect_errno) {
                    echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                }

                $AdminID = $_SESSION['AdminID'];

                $uploaddate = date("Y-m-d H:i:s");

                $status = "Approved";
                $userId = 10;

                // Create the SQL query to select the maximum GeneratedID
                $q3 = "SELECT MAX(SUBSTRING(TrackGeneratedID, 3)) AS MaxGenID FROM track";

                // Execute the query to get the maximum GeneratedID
                if ($result = $mysqli->query($q3)) {
                    // Fetch the result row
                    $row = $result->fetch_assoc();

                    // Get the maximum GeneratedID from the result
                    $maxGenID = $row['MaxGenID'];

                    // Check if the maximum GeneratedID is null (meaning no existing IDs)
                    if ($maxGenID === null) {
                        // If no existing IDs, start the sequence from 1
                        $newID = "TR00001"; // Format the new ID
                    } else {
                        $maxGenID = (int)$row['MaxGenID'];
                        // Increment the maximum GeneratedID
                        $newID = "TR" . sprintf("%05d", $maxGenID + 1);
                    }
            
                    // Create the SQL query to insert data
                    $q4 = "INSERT INTO track (TrackGeneratedID, TrackName, TrackFile, TrackImg, UploadDate, ReleaseDate, ValidationStatus, AdminID, UserID, GenreID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    if ($stmt = $mysqli->prepare($q4)) {
                        // Bind parameters to the statement
                        $stmt->bind_param("sssssssiii", $newID, $trackname, $target_filename2, $target_filename1, $uploaddate, $releasedate, $status, $AdminID, $userId, $genreinput);
                    
                        // Execute query and output a success or error message
                        if ($stmt->execute()) {
                            echo '<script>alert("Successfully uploaded ! "); window.history.back();</script>';
                        } else {
                            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
                        }
                    
                        // Close the statement
                        $stmt->close();
                    }
                }
            } else {
                echo '<script>alert("Error ! Please try to upload again."); window.history.back();</script>';
            }
        }
        else{
            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
        }
    }
    else{
        echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}
?>