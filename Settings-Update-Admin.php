<?php 
session_start();
if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {
    if (isset($_POST['updateAcc'])) {
        $email = $_POST['email']; 
        $password = $_POST['pwd'];
        $adminid = $_POST['adminid'];

        // Establish a database connection using mysqli
        $mysqli = new mysqli("localhost", "root", "", "muziq-test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        
        $q = "UPDATE `admin` SET AdminEmail=?, AdminPassword=? WHERE AdminID=?";

        // Execute query and output a success or error message
         if ($stmt = $mysqli->prepare($q)) {
            // Bind parameters to the statement
            $stmt->bind_param("ssi", $email, $password,  $adminid);
            // Execute query and output a success or error message
            if ($stmt->execute()) {
                echo '<script>alert("Successfully updated !"); window.location.href = "Settings-Admin.php";</script>';
            } else {
                echo '<script>alert("Something went wrong ! Please try again."); window.location.href = "Settings-Admin.php";</script>';
            }
            $stmt->close(); 
        }
    } else {
        echo '<script>alert("Something went wrong ! Please try again.");</script>';
    }
} else {
    echo '<script>alert("Kindly login to proceed ! "); window.location.href = "Index.html";</script>';
}
?>