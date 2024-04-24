<?php
session_start();

if(isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])){
    if (isset($_POST['createUser'])) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $bio = $_POST['userbio'];

        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        // Check for connection errors
        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }

        $target_dir = "Data/User/"; //Set target directory
        $target_filename = basename($_FILES["fileToUpload"]["name"]); //Set target filename
        $target_file = $target_dir . $target_filename; 
        $uploadOk = TRUE; 
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); 

        // Check the format of image file uploaded
        if ($imageFileType != "jpg" && $imageFileType != "tiff" && $imageFileType != "png" && $imageFileType != "gif") {
            echo '<script>alert("Error: only jpg, tiff, png, gif files are allowed."); window.history.back();</script>';
            $uploadOk = FALSE;
        }

        if($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // Check if the email is already registered
                $checkEmailQuery = "SELECT COUNT(*) as count FROM user WHERE UserEmail = ?";
                $stmt = $mysqli->prepare($checkEmailQuery);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();

                if ($row['count'] > 0) {
                    echo '<script>alert("This email is already registered. Please use a different email."); window,history.back();</script>';
                } else {
                    // Create the SQL query to select the maximum GeneratedID
                    $q1 = "SELECT MAX(SUBSTRING(UserGeneratedID, 3)) AS MaxGenID FROM user";

                    // Execute the query to get the maximum GeneratedID
                    if ($result = $mysqli->query($q1)) {
                        // Fetch the result row
                        $row = $result->fetch_assoc();

                        // Get the maximum GeneratedID from the result
                        $maxGenID = $row['MaxGenID'];

                        // Check if the maximum GeneratedID is null (meaning no existing IDs)
                        if ($maxGenID === null) {
                            // If no existing IDs, start the sequence from 1
                            $newID = "US00001"; // Format the new ID
                        } else {
                            $maxGenID = (int)$row['MaxGenID'];
                            // Increment the maximum GeneratedID
                            $newID = "US" . sprintf("%05d", $maxGenID + 1);
                        }


                    // Create the SQL query to insert data using prepared statements
                    $q = "INSERT INTO user (Username, UserGeneratedID, UserImage, UserBio, UserEmail, UserPassword) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($q);
                    $stmt->bind_param("ssssss", $name, $newID, $target_filename, $bio, $email, $password);
                    $stmt->execute();

                    // Check if the query was successful
                    if ($stmt->affected_rows > 0) {
                        echo '<script>alert("Account created successfully! "); window,history.back();</script>';
                    } else {
                        echo '<script>alert("Error! Something went wrong."); window,history.back();</script>';
                    }

                    // Close the statement
                    $stmt->close();
                }
                }
            } else {
                echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
            }
            // Close the database connection
            $mysqli->close();

        } else {
            echo '<script>alert("Error ! Please try to create again."); window.history.back();</script>';
        }
    } else {
        echo '<script>alert("Error ! Please try to create again."); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}



?>