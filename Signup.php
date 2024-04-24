<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MuziQ</title>
    <link rel="icon" href="Sources/Img/MuziQ.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <!-- This line imports the jQuery library from Google's servers for use in the web page -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- This line links to the Material Icons library from Google Fonts for use in the web page -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>
<body>
<?php

    $signupOk = true;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $avatar = $_POST['default-avatar'];
    $bio = $_POST['userbio'];

    $bio = isset($_POST['userbio']) ? $_POST['userbio'] : 'Music speaks louder than words. Bio coming soon!';

    if ($signupOk) {
        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        
        // Check for connection errors
        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }

        // Check if the email is already registered
        $checkEmailQuery = "SELECT COUNT(*) as count FROM user WHERE UserEmail = ?";
        $stmt = $mysqli->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {
            echo '<script>alert("This email is already registered. Please use a different email."); window.location.href = "index.html";</script>';
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
            $stmt->bind_param("ssssss", $name, $newID, $avatar, $bio, $email, $password);
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->affected_rows > 0) {
                echo '<script>alert("Account created successfully! "); window.location.href = "index.html";</script>';
            } else {
                echo '<script>alert("Error! Something went wrong."); window.location.href = "index.html";</script>';
            }

            // Close the statement
            $stmt->close();
            }
        }
        // Close the database connection
        $mysqli->close();
    }
?>

</body>
</html>