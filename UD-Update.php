<?php
session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

if ($loggedin) {

        $name = $_POST['name']; 
        $bio = $_POST['userbio'];
        $userid = $_POST['userid'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo 'Error in connecting to the database';
        }

        // Check if a file has been selected for upload
        if (!empty($_FILES["avatarUpload"]["name"])) {
            //Set target directory
            $target_dir = "Data/User/";
            $target_filename = basename($_FILES["avatarUpload"]["name"]); 
            $target_file = $target_dir . $target_filename; //Concatenate
            $uploadOk = TRUE;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check the format of image file uploaded
            if ($imageFileType != "jpg" && $imageFileType != "tiff" && $imageFileType != "png" && $imageFileType != "gif") {
                echo 'Error in uploading image';
                $uploadOk = FALSE;
            }

            // Upload the new file to the server
            if ($uploadOk) {
                if (move_uploaded_file($_FILES["avatarUpload"]["tmp_name"], $target_file)) {
                    // Update the database with the new data using a prepared statement
                    $q = "UPDATE user SET Username=?, UserImage=?, UserBio=? WHERE UserID=?";

                    if ($stmt = $mysqli->prepare($q)) {
                        // Bind parameters to the statement
                        $stmt->bind_param("sssi", $name, $target_filename, $bio, $userid);

                        // Execute query and output a success or error message
                        if ($stmt->execute()) {
                            echo 'success';
                        } else {
                            echo 'error';
                        }

                        // Close the statement
                        $stmt->close();
                    } else {
                        echo 'error';
                    }
                } else {
                    echo 'error';
                }
            }
        } else {
            // If no file is selected for upload, update the database with other info using a prepared statement
            $q = "UPDATE user SET Username=?, UserBio=? WHERE UserID=?";

            // Execute query and output a success or error message
            if ($stmt = $mysqli->prepare($q)) {
                // Bind parameters to the statement
                $stmt->bind_param("ssi", $name, $bio, $userid);

                // Execute query and output a success or error message
                if ($stmt->execute()) {
                    echo 'success';
                } else {
                    echo 'error';
                }

                // Close the statement
                $stmt->close();
            } 
        }
    }
else {
    echo 'error';
}
?>

