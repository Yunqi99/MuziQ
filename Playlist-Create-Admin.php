<?php
session_start(); 

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {
    // Admin is logged in
    if (isset($_POST['createPlaylist'])) {
        // Process playlist creation for admin
        $target_dir = "Data/Playlist/"; // Set target directory
        $target_filename = basename($_FILES["fileToUpload"]["name"]); // Set target filename
        $target_file = $target_dir . $target_filename; // Concatenate
        $uploadOk = TRUE; 
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $playlistName = $_POST['playlistname']; 
        $playlistDesc = $_POST['playlistdesc'];
        $genreinput = $_POST['genreinput'];

        // Check the format of file uploaded
        if ($imageFileType != "jpg" && $imageFileType != "tiff" && $imageFileType != "png") {
            echo '<script>alert("Error: only jpg, tiff, png, gif files are allowed."); window.history.back();</script>';
            $uploadOk = FALSE;
        }
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // Establish a database connection using mysqli
                $mysqli = new mysqli("localhost", "root", "", "muziq-test");
                
                // Check if there's an error in the database connection
                if ($mysqli->connect_errno) {
                    echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                }
        
                $AdminID = $_SESSION['AdminID'];

             // Create the SQL query to select the maximum GeneratedID
             $queryID = "SELECT MAX(SUBSTRING(PlaylistGeneratedID, 3)) AS MaxGenID FROM playlist";

             // Execute the query to get the maximum GeneratedID
             if ($result = $mysqli->query($queryID)) {
                 // Fetch the result row
                 $row = $result->fetch_assoc();

                 // Get the maximum GeneratedID from the result
                 $maxGenID = $row['MaxGenID'];     
 
                // Check if the maximum GeneratedID is null (meaning no existing IDs)
                if ($maxGenID === null) {
                    // If no existing IDs, start the sequence from 1
                    $newID = "PL00001"; // Format the new ID
                } else {
                    $maxGenID = (int)$row['MaxGenID'];
                    // Increment the maximum GeneratedID
                    $newID = "PL" . sprintf("%05d", $maxGenID + 1);
                }
   
                // Create the SQL query to insert data
                $q1 = "INSERT INTO playlist (PlaylistGeneratedID, PlaylistName, PlaylistDesc, PlaylistImg, AdminID, GenreID) VALUES (?, ?, ?, ?, ?, ?)";

                if ($stmt = $mysqli->prepare($q1)) {
                    // Bind parameters to the statement
                    $stmt->bind_param("ssssii", $newID, $playlistName, $playlistDesc, $target_filename, $AdminID, $genreinput);

                        // Execute query and output a success or error message
                        if ($stmt->execute()) {
                            echo '<script>alert("Successfully created ! "); window.history.back();</script>';
                        } else {
                            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
                        }

                        // Close the statement
                        $stmt->close();
                    }
                } else {
                    echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
                }

            } else {
                echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
            }
        }
    } else {
        // Handle case where 'createPlaylist' is not set
        echo '<script>alert("Something went wrong! Please try again."); window.history.back();</script>';
    }
} else {
    // Neither user nor admin is logged in
    echo '<script>alert("Kindly login to proceed!"); window.location.href = "index.html";</script>';
}
?>