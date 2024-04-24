<?php
session_start(); 

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {
    if (isset($_POST['createAdmin'])) {

        // Process playlist creation for admin
        $target_dir = "Data/Admin/"; // Set target directory
        $target_filename = basename($_FILES["fileToUpload"]["name"]); // Set target filename
        $target_file = $target_dir . $target_filename; // Concatenate
        $uploadOk = TRUE; 
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $name = $_POST['name']; 
        $email = $_POST['email'];
        $password = $_POST['password'];
        $ismanager = isset($_POST['ismanager']) ? 'Manager' : 'Staff';

        // Check the format of file uploaded
        if ($imageFileType != "jpg" && $imageFileType != "tiff" && $imageFileType != "png") {
            echo '<script>alert("Error: only jpg, tiff, png, gif files are allowed."); window.history.back();</script>';
            $uploadOk = FALSE;
        }
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                
                $mysqli = new mysqli("localhost", "root", "", "muziq-test"); // Establish a database connection using mysqli
                if ($mysqli->connect_errno) {
                    echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                }

                $q1 = "SELECT MAX(SUBSTRING(AdminGeneratedID, 3)) AS MaxGenID FROM `admin`";

                if ($result = $mysqli->query($q1)) {
                    $row = $result->fetch_assoc();
                   
                    $maxGenID = $row['MaxGenID'];  // Get the maximum GeneratedID from the result
                    
                    if ($maxGenID === null) { // Check if the maximum GeneratedID is null (meaning no existing IDs)
                        $newID = "AD00001"; // If no existing IDs, start the sequence from 1
                    } else {
                        $maxGenID = (int)$row['MaxGenID'];
                        $newID = "AD" . sprintf("%05d", $maxGenID + 1); // Increment the maximum GeneratedID
                    }
                    
                    $q2 = "INSERT INTO `admin` (AdminName, AdminGeneratedID, AdminPosition, AdminImg, AdminEmail, AdminPassword) VALUES (?, ?, ?, ?, ?, ?)";

                    if ($stmt = $mysqli->prepare($q2)) {
                        $stmt->bind_param("ssssss", $name, $newID, $ismanager, $target_filename, $email, $password);
                        
                        if ($stmt->execute()) { // Execute query and output a success or error message
                            echo '<script>alert("Successfully created ! "); window.history.back();</script>';
                        } else {
                            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
                        }
                        $stmt->close();
                    } else {
                        echo '<script>alert("Something went wrong! Please try again."); window.history.back();</script>';
                    }
                } else {
                    echo '<script>alert("Something went wrong! Please try again."); window.history.back();</script>';
                }

            } else {
                echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
            }
        }
    } else {
        echo '<script>alert("Something went wrong! Please try again."); window.history.back();</script>';  // Handle case where 'createPlaylist' is not set
    }
} else {
    echo '<script>alert("Kindly login to proceed!"); window.location.href = "index.html";</script>';
}
?>
