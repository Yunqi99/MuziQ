<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MuziQ</title>
    <link rel="icon" href="Sources/Img/MuziQ.png" type="image/png">
    <link rel="stylesheet" href="style-admin.css">
    <!-- This line imports the jQuery library from Google's servers for use in the web page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- This line links to the Material Icons library from Google Fonts for use in the web page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

</head>

<body>
<?php
session_start();

if ($loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) { // Check if the user is not logged in
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.location.href = "User-Dashboard.php";</script>';
    }

    // Top navigation bar
    echo '<div class="wrapper">
            <nav class="tabs">
            <div class="selector"></div>
            <a href="Homepage-admin.php"><i class="fa fa-home"></i>Home</a>
            <a href="Validation-List.php"><i class="fa fa-pencil"></i>Validation</a>
            <a href="Insights.php"><i class="fa fa-pie-chart"></i>Insights</a>
        </nav>
        <div class="logo">
            <a href="Homepage-admin.php">MuziQ</a>
        </div> 
        <div class="user-menu">
            <div class="user-icon" onclick="menuToggle();">';
            $AdminID = $_SESSION['AdminID'];
            $adminquery = "SELECT * FROM `admin` WHERE AdminID = $AdminID";
            $res = $mysqli->query($adminquery);
            if ($res) {
                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    echo '<img src="Data/Admin/'.$row['AdminImg'].'" />';
                } else{
                    echo '<script>alert("No records found.");</script>';
                }
            } else {
                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");</script>';
            }
            echo '</div>
            <div class="user-dropdown"> 
                <ul>
                <li><a href="Admin-Dashboard.php"><i class="fas fa-user-circle"></i>My Profile</a></li><hr>
                <li><a href="Settings-Admin.php"><i class="fa fa-gear"></i>Settings</a></li><hr>
                <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>';


    echo '<div class="settings-layout">
    <br><br><br><br>
        <h1>Settings</h1>
        <div class="settings-admin-container">
            <div class="settings-admin-col1">';
 
            $res = $mysqli->query($adminquery);
            if ($res) {
                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $maskedPassword = str_repeat('●', strlen($row['AdminPassword']));
                    echo '<h2>Manage Account</h2>
                    <br>
                    <h3>Email</h3>
                    <p>'.$row['AdminEmail'].'</p>
                    <br>
                    <h3>Password</h3>
                    <p>'.$maskedPassword.'</p>
                    <br>';
                } else{
                    echo '<script>alert("No records found.");</script>';
                }
            } else {
                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");</script>';
            }

            echo '<button onclick="editAccount()">Manage</button>
            </div>
        </div>
    </div>';

    echo '<div class="overlay" id="overlay"></div>';

     echo '<!-- Pop up window to edit account -->
     <div class="editAcc" id="editAcc">
        <form action="Settings-Update-Admin.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <div class="editAcc-cont">';
                    if ($res = $mysqli->query($adminquery)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                                echo '
                                <div class="edit-Acc">
                                    <div class="input-col">
                                        <p><i class="fas fa-envelope" id="icon"></i><input type="email" placeholder="Email" name="email-adress" value="'.$row['AdminEmail'].'"  disabled></p>
                                    </div>
                                    <div class="input-col">
                                        <p><i class="fas fa-key" id="icon"></i>
                                        <input type="password" placeholder="Password" id="enter-ps" onkeyup="validate();" name="pwd" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8}$"
                                         title="Must contain at least 8 characters, including one each of: a number, an uppercase letter, a lowercase letter, and a special character." />
                                        <span id="pwd-requirement"></span>
                                        </p>          
                                    </div>
                                    <div class="input-col">
                                        <p><i class="fas fa-shield-alt" id="icon"></i><input type="password" placeholder="Confirm Password"
                                            name="repeatPwd" id="repeat-ps" required>
                                        <span id="confirmation"></span>
                                        </p>
                                    </div>
                                    <br>
                                    <input type="hidden" name="email" value="'.$row['AdminEmail'].'">
                                    <input type="hidden" name="adminid" value="'.$row['AdminID'].'">
                                    <div class="btn-editAcc">
                                        <input type="submit" class="button-editAcc" name="updateAcc" value="Update">        
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<script>alert("Something went wrong ! Please try again."); window.location.href = "Settings.php";</script>';
                        }
                    } else {
                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.location.href = "Settings.php";</script>';
                    } 
            echo '</div>                          
        </form>
    </div>'; 
}
else {
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
}
?> 

<script src="./script.js"></script>
<script>
    function editAccount() {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("editAcc").style.display = "block";
    }

    function hidePopup() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("editAcc").style.display = "none";
    }

     // Function for password checking
     function validate() {
            var password = document.getElementById("enter-ps").value;
            var message = "";

            if (password.length >= 8) {
                // Check for lowercase, uppercase, numbers, and special characters
                var lowercase = /[a-z]/;
                var uppercase = /[A-Z]/;
                var numerals = /[0-9]/;
                var specialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;

                if (lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)) {
                    // Password meets all requirements
                    message = "<i class='fa fa-check'></i>";
                } else {
                    // Password does not meet all requirements
                    message = "At least 1 lowercase, 1 uppercase, 1 number, 1 special character";
                }

            } else {
                message = "At least 8 characters.";
            }
            document.getElementById("pwd-requirement").innerHTML = message;
        }

        document.getElementById("enter-ps").addEventListener("input", validate);

        // Function for validating password matching
        var confirmationPS = function () {
            var enterPs = document.getElementById('enter-ps').value;
            var repeatPs = document.getElementById('repeat-ps').value;
            if (enterPs.trim() !== "") {
                if (enterPs == repeatPs){
                    document.getElementById('confirmation').style.color = 'green';
                    document.getElementById('confirmation').innerHTML = "<i class='fa fa-check'></i>";
                } else {
                document.getElementById('confirmation').style.color = 'red';
                document.getElementById('confirmation').innerHTML = 'Password not matched.';
                }
            }
        }
        window.onload = function () {
            document.getElementById('repeat-ps').addEventListener('keyup', confirmationPS);
        };

        // Function for validating the form
        function validateForm() {
            var password = document.getElementById("enter-ps").value;
            var confirmPassword = document.getElementById("repeat-ps").value;

            if (password.length >= 8 && password == confirmPassword && lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)){
                return true;
            }
            else {
                alert("Please fill in all required fields correctly before submitting.");
                return false;
            }
        }
</script>

</body>

</html>