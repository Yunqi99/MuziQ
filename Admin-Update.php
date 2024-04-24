<?php
session_start();

if(isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){
    if (isset($_POST['updateAdmin'])) {
        $name = $_POST['name']; 
        $email = $_POST['email'];
        $ismanager = isset($_POST['ismanager']) ? 'Manager' : 'Staff';
        $AdminID = $_POST['adminid'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            exit(); // Terminate script if unable to connect to the database
        }

        // Check if an image file has been selected for upload
        if (!empty($_FILES["fileToUpload2"]["name"])) {
            // Set target directory
            $target_dir = "Data/Admin/";
            $target_filename = basename($_FILES["fileToUpload2"]["name"]); 
            $target_file = $target_dir . $target_filename; 
            $uploadOk = true;

            // Check the format of image file uploaded
            $allowed_extensions = array("jpg", "jpeg", "tiff", "png", "gif");
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!in_array($imageFileType, $allowed_extensions)) {
                    echo '<script>alert("Error: only jpg, jpeg, tiff, png, gif files are allowed."); window.history.back();</script>';
                exit(); // Terminate script if file type is not allowed
            }

            // Upload the new file to the server
            if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
                // Update the database with the new data using a prepared statement
                $q = "UPDATE `admin` SET AdminName=?, AdminPosition=?, AdminImg=?, AdminEmail=? WHERE AdminID=?";

                if ($stmt = $mysqli->prepare($q)) {
                    // Bind parameters to the statement
                    $stmt->bind_param("ssssi", $name, $ismanager, $target_filename, $email, $AdminID);

                    // Execute query and output a success or error message
                    if ($stmt->execute()) {
                        echo '<script>alert("Successfully updated !"); window.history.back();</script>';
                    } else {
                        echo '<script>alert("Error updating admin account: ' . $stmt->error . '");window.history.back();</script>';
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.history.back();</script>';
                }
            } else {
                echo '<script>alert("Error in uploading the image. Please try again."); window.history.back();</script>';
            }
        } else {
            // If no image file is selected for upload, update the database with other info using a prepared statement
            $q = "UPDATE `admin` SET AdminName=?, AdminPosition=?, AdminEmail=? WHERE AdminID=?";

            if ($stmt = $mysqli->prepare($q)) {
                // Bind parameters to the statement
                $stmt->bind_param("sssi", $name, $ismanager, $email, $AdminID);

                // Execute query and output a success or error message
                if ($stmt->execute()) {
                    echo '<script>alert("Successfully updated !"); window.history.back();</script>';
                } else {
                    echo '<script>alert("Error updating admin account: ' . $stmt->error . '"); window.history.back();</script>';
                }

                // Close the statement
                $stmt->close();
            } else {
                echo '<script>alert("Error preparing statement: ' . $mysqli->error . '"); window.history.back();</script>';
            }
        }
    } else {
        echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}
?>

