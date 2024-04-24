<?php 
session_start();
if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){
    if (isset($_POST['updateTrack'])) {
        $name = $_POST['trackname']; 
        $genreinput = $_POST['genreinput2'];
        $releasedate = $_POST['releasedate'];
        $trackid = $_POST['trackid'];

        $uploadOk = TRUE;

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            exit(); // Terminate script if unable to connect to the database
        }

        // Check if image file has been selected for upload
        if (!empty($_FILES["fileToUpload"]["name"])) {

            $target_dir1 = "Data/TrackImage/"; //Set target directory
            $target_filename1 = basename($_FILES["fileToUpload"]["name"]); //Set target filename
            $target_file1 = $target_dir1 . $target_filename1; 
            
            $uploadOk = TRUE; 
            $imageFileType = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION)); 
    
            // Check the format of image file uploaded
            if ($imageFileType != "jpg" && $imageFileType != "tiff" && $imageFileType != "png" && $imageFileType != "gif") {
                echo '<script>alert("Error in uploading the image file. Please try again."); window.history.back();</script>';
                $uploadOk = FALSE;
            }

            // Check if track file has been selected for upload
            if (!empty($_FILES["fileToUploadTrack"]["name"])) {
                $target_dir2 = "Data/TrackFile/"; //Set target directory
                $target_filename2 = basename($_FILES["fileToUploadTrack"]["name"]); //Set target filename
                $target_file2 = $target_dir2 . $target_filename2; 
                $audioFileType = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

                // Check audio file format
                if ($audioFileType != "mp3" && $audioFileType != "wav" && $audioFileType != "aac") {
                    echo '<script>alert("Error in uploading audio file. Please try again."); window.history.back();</script>';
                    $uploadOk = FALSE;
                }

                if ($uploadOk) {
                    // Upload the new file to the server
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1) && move_uploaded_file($_FILES["fileToUploadTrack"]["tmp_name"], $target_file2)) {
                        // Update the database with the new data using a prepared statement
                        $q1 = "UPDATE track SET TrackName=?, TrackFile=?, TrackImg=?, ReleaseDate=?, GenreID=? WHERE TrackID=?";

                        if ($stmt = $mysqli->prepare($q1)) {
                            // Bind parameters to the statement
                            $stmt->bind_param("ssssii", $name, $target_filename2, $target_filename1, $releasedate, $genreinput, $trackid);

                            // Execute query and output a success or error message
                            if ($stmt->execute()) {
                                echo '<script>alert("Successfully updated !"); window.history.back();</script>';
                            } else {
                                echo '<script>alert("Error updating Track: ' . $stmt->error . '"); window.history.back();</script>';
                            }

                            // Close the statement
                            $stmt->close();
                        } else {
                            echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.history.back();</script>';
                        }
                    } else {
                        echo '<script>alert("Error in uploading the file. Please try again."); window.history.back();</script>';
                    }
                }
            } else {
                if ($uploadOk) {
                    // Upload the new file to the server
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1)) {
                        // Update the database with the new data using a prepared statement
                        $q2 = "UPDATE track SET TrackName=?, TrackImg=?, ReleaseDate=?, GenreID=? WHERE TrackID=?";

                        if ($stmt = $mysqli->prepare($q2)) {
                            // Bind parameters to the statement
                            $stmt->bind_param("sssii", $name, $target_filename1, $releasedate, $genreinput, $trackid);

                            // Execute query and output a success or error message
                            if ($stmt->execute()) {
                                echo '<script>alert("Successfully updated !"); window.history.back();</script>';
                            } else {
                                echo '<script>alert("Error updating Track: ' . $stmt->error . '"); window.history.back();</script>';
                            }

                            // Close the statement
                            $stmt->close();
                        } else {
                            echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.history.back();</script>';
                        }
                    }
        
                }
            }
        } else {

           // Check if track file has been selected for upload
           if (!empty($_FILES["fileToUploadTrack"]["name"])) {
                $target_dir2 = "Data/TrackFile/"; //Set target directory
                $target_filename2 = basename($_FILES["fileToUploadTrack"]["name"]); //Set target filename
                $target_file2 = $target_dir2 . $target_filename2; 
                $audioFileType = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

                // Check audio file format
                if ($audioFileType != "mp3" && $audioFileType != "wav" && $audioFileType != "aac") {
                    echo '<script>alert("Error in uploading audio file. Please try again."); window.history.back();</script>';
                    $uploadOk = FALSE;
                }

                if ($uploadOk) {
                    // Upload the new file to the server
                    if (move_uploaded_file($_FILES["fileToUploadTrack"]["tmp_name"], $target_file2)) {
                        // Update the database with the new data using a prepared statement
                        $q3 = "UPDATE track SET TrackName=?, TrackFile=?, ReleaseDate=?, GenreID=? WHERE TrackID=?";

                        if ($stmt = $mysqli->prepare($q3)) {
                            // Bind parameters to the statement
                            $stmt->bind_param("sssii", $name, $target_filename2, $releasedate, $genreinput, $trackid);

                            // Execute query and output a success or error message
                            if ($stmt->execute()) {
                                echo '<script>alert("Successfully updated !"); window.history.back();</script>';
                            } else {
                                echo '<script>alert("Error updating Track: ' . $stmt->error . '"); window.history.back();</script>';
                            }

                            // Close the statement
                            $stmt->close();
                        } else {
                            echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.history.back();</script>';
                        }
                    } else {
                        echo '<script>alert("Error in uploading the file. Please try again."); window.history.back();</script>';
                    }
                }
            } else {
                // Update the database with the new data using a prepared statement
                $q4 = "UPDATE track SET TrackName=?, ReleaseDate=?, GenreID=? WHERE TrackID=?";

                if ($stmt = $mysqli->prepare($q4)) {
                    // Bind parameters to the statement
                    $stmt->bind_param("ssii", $name, $releasedate, $genreinput, $trackid);

                    // Execute query and output a success or error message
                    if ($stmt->execute()) {
                        echo '<script>alert("Successfully updated !"); window.history.back();</script>';
                    } else {
                        echo '<script>alert("Error updating Track: ' . $stmt->error . '"); window.history.back();</script>';
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.history.back();</script>';
                }
            }
        }
    } else {
        echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
}
 
?>

