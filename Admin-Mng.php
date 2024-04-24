<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MuziQ</title>
    <link rel="icon" href="Sources/Img/MuziQ.png" type="image/png">
    <link rel="stylesheet" href="style-admin.css">
    <!-- This line imports the jQuery library from Google's servers for use in the web page -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- This line links to the Material Icons library from Google Fonts for use in the web page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>
<body>

<?php
session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID']);

if (!$loggedin) { // Check if the user is not logged in
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
} else {
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

    echo '<!-- Top Navigation Bar -->
    <div class="wrapper">
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
                    $adminPosition = $row['AdminPosition'];
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

                    echo '<div class="admin-layout">
                        <div class="admin-cont">
                            <div class="bar-row">
                                <h1>LIST OF ADMIN ACCOUNTS</h1>
                                <div class="bar-cont">';
                    if ($adminPosition === 'Manager') {
                        echo '<button class="btn-create-new" onclick="createNewAdmin() "><i class="fas fa-plus"></i></button>';
                    }
                    echo '          
                                </div>
                            </div>
                            <br>';
                } else {
                    echo '<script>alert("No records found.");</script>';
                }
            } else {
                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");</script>';
            }

            echo '<div class="track-table-container">
                <table class="track-table">
                    <thead>
                        <tr class="title-row">
                            <th class="number">#</th>
                            <th class="track-img"></th>
                            <th class="name">Admin Name</th>
                            <th class="id">Admin ID</th>
                            <th>Admin Position</th>
                            <th>Email Address</th>
                            <th class="action"></th>
                        </tr>
                    </thead>
                    <tbody>';
                        $query1 = "SELECT * FROM `admin`";
                        $res = $mysqli->query($query1);
                        if ($res) {
                            $trackNumber = 1;
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td class="number">'.$trackNumber.'</td>
                                        <td class="track-img">
                                            <img src="Data/Admin/'.$row['AdminImg'].'">
                                        </td>
                                        <td class="name">'.$row['AdminName'].'</td>
                                        <td class="id">' . $row['AdminGeneratedID'] . '</td>
                                        <td>'.$row['AdminPosition'].'</td>
                                        <td class="email">' . $row['AdminEmail'] . '</td>
                                        <td class="action">';
                                        // Check the admin position in the database based on AdminID
                                        $query2 = "SELECT AdminPosition FROM `admin` WHERE AdminID = $AdminID";
                                        $positionResult = $mysqli->query($query2);
                                        if ($positionResult && $positionRow = $positionResult->fetch_assoc()) {
                                            $adminPosition = $positionRow['AdminPosition'];
                                            if ($adminPosition === 'Manager') {
                                                echo '<button onclick="editAdmin('. $row['AdminID'] .')" class="btn-track-info"><i class="fas fa-pen"></i></button>    
                                                    <a href="Admin-Delete.php?id='. $row['AdminID'] .'" onclick="return confirm(\'Are you sure to delete?\');" class="btn-track-info"><i class="fas fa-trash"></i></a>';
                                            } else {
                                                // If not a manager, disable the buttons
                                                echo '<button class="btn-track-info" disabled><i class="fas fa-pen"></i></button>    
                                                    <button class="btn-track-info" disabled><i class="fas fa-trash"></i></button>';
                                            }
                                        } else {
                                            echo '<button class="btn-track-info" disabled><i class="fas fa-pen"></i></button>    
                                                <button class="btn-track-info" disabled><i class="fas fa-trash"></i></button>';
                                        }

                                        echo '</td>
                                            </tr>';
                                        $trackNumber++;
                                    }
                                }
                            }
                    echo '</tbody>
                </table>
            </div>
        </div>
    </div>';


    echo '<!-- Dark overlay -->
    <div class="overlay" id="overlay"></div>
                            
    <!-- Pop up window to create new admin -->
    <div class="newAdmin" id="newAdmin">
            <form id="adminForm" class="adminForm" action="Admin-Create.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
                <div class="acc-cont">
                    <div class="acc-detail1">
                        <div class="image-upload-wrap">
                            <button class="file-upload-btn" type="button" onclick="$(\'.file-upload-input\').trigger(\'click\')">Add Image</button>
                            <input class="file-upload-input" id="fileToUpload" name="fileToUpload" type="file" onchange="readURL(this);" accept="image/*"/>
                            <img id="preview-image" src="#" alt="Preview" style="display: none;">
                        </div>
                    </div>
                                
                    <div class="acc-detail2">
                        <p><input type="text" name="name" class="acc-input" placeholder="Admin Name" required></p>
                        <p><input type="email" name="email" class="acc-input" placeholder="Admin Email" required></p>
                        <p><input type="password" id="enter-ps" name="password" class="acc-input" placeholder="Admin Password" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8}$"
                        title="Must contain at least 8 characters, including one each of: a number, an uppercase letter, a lowercase letter, and a special character." onkeyup="validate()"></p>
                        <span id="pwd-requirement"></span>
                        <p class="checkbox">
                            <input type="checkbox" name="ismanager" value="Manager">Is Manager?
                        </p>
                        <br>
                        <input type="submit" class="button-acc" name="createAdmin" value="Create">
                    </div>
                </div>                      
            </form>
        </div>';

        echo ' <!-- Pop up window to edit admin -->
        <div class="editAdmin" id="editAdmin">
            <form id="adminForm" class="adminForm" action="Admin-Update.php" method="POST" enctype="multipart/form-data">
                <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
                <div class="acc-cont">
                    <div class="acc-detail1">
                        <div class="image-upload-wrap">
                            <button class="file-upload-btn2" type="button" onclick="$(\'.file-upload-input2\').trigger(\'click\')">Add Image</button>
                            <input class="file-upload-input2"  name="fileToUpload2" type="file" id="file" onchange="readURL2(this);" accept="image/*" />
                            <img id="preview-image2" alt="Preview">
                        </div>
                    </div>
                                    
                    <div class="acc-detail2">
                        <p><input type="text" name="name" id="name" class="acc-input" placeholder="Admin Name" required></p>
                        <p><input type="email" name="email" id="email" class="acc-input" placeholder="Admin Email" required></p>
                        <p><input type="password" name="password" id="password" class="acc-input" placeholder="Admin Password" disabled></p>
                        <p class="checkbox">
                            <input type="checkbox" name="ismanager" id="position">Is Manager?
                        </p>
                        <br>
                        <input type="hidden" name="adminid" id="adminid">
                        <input type="submit" class="button-acc" name="updateAdmin" value="Update">
                    </div>
                </div>                      
            </form>
        </div>';
}
?> 

    <script src="./script.js"></script>
    <script>
        function createNewAdmin() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("newAdmin").style.display = "block";
        }
        
        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("newAdmin").style.display = "none";
            document.getElementById("editAdmin").style.display = "none";
        }

        function editAdmin(id) {
            // Show overlay and edit FAQ popup
            document.getElementById("overlay").style.display = "block";
            document.getElementById("editAdmin").style.display = "block";

            $.ajax({
            type: 'GET',
            url: 'Admin-Edit.php',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                var imagePath = "Data/Admin/" + response.img;
                $("#preview-image2").attr("src", imagePath);
                $("#name").val(response.name);
                if (response.position === 'Manager') {
                    $("#position").prop("checked", true);
                } else {
                    $("#position").prop("checked", false);
                }
                $("#email").val(response.email);
                $("#password").val(response.password);
                $("#adminid").val(id);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
        }

    // Preview Image
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                // Display the uploaded image preview
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-image').style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        
    // Preview Image
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
            // Display the uploaded image preview
            document.getElementById('preview-image2').src = e.target.result;
            document.getElementById('preview-image2').style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
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
                message = "<i class='fa fa-check'></i> Password is strong.";
            } else {
                // Password does not meet all requirements
                message = "Include lowercase, uppercase, number, special character";
            }

        } else {
            message = "At least 8 characters.";
        }
        document.getElementById("pwd-requirement").innerHTML = message;
    }

    document.getElementById("enter-ps").addEventListener("input", validate);

// Function for validating the sign up form
function validateForm() {
    var password = document.getElementById("enter-ps").value;
    var fileInput = document.getElementById('fileToUpload');

    // Check if an image is selected
     if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        alert('Please select an image to upload.');
        return false;
    }

    // Check if the password meets the criteria
    var lowercase = /[a-z]/;
    var uppercase = /[A-Z]/;
    var numerals = /[0-9]/;
    var specialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;

    if (password.length >= 8 && lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)) {
        return true;
    } else {
        alert("Please fill in all required fields correctly before submitting.");
        return false;
    }
}

    </script>
</body>
</html>