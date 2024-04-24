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

if (!$loggedin) { 
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
                }
            }
    
    echo '<div class="admin-layout">';
        if ($res = $mysqli->query($adminquery)) {
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) { 
                    echo '<div class="admin-container fade">
                        <img class="admin-background" src="./Sources/Img/border.png"/>
                        <button class="btn-edit" onclick="editDashboard()">Edit</button>
                        <div class="admin-container-row">
                            <div class="admin-col">
                                <div class="admin-img">';
                                    if (!empty($row['AdminImg'])) {
                                        echo '<img src="Data/Admin/'.$row['AdminImg'].'" alt="Preview avatar">';
                                    } else {
                                            echo '<img src="Sources/Img/default.jpg" alt="Default avatar">';
                                    }
                                    echo '</div>
                                        <div class="name-container">
                                            <h1>'.$row['AdminName'].'</h1>
                                        </div>
                                        <div class="position">
                                            <p>'.$row['AdminPosition'].'</p>
                                        </div>

                                    </div>
                                </div>
                            </div>';
                    }    
                } else {
                    echo '<div class="empty-container">
                            <div class="empty-img">
                                <img src="Sources/Img/Empty.png"/>
                            </div>
                            <div class="empty-text">
                                <h5>No data found ! </h5>
                            </div>
                        </div>';
                    }
            } else {
                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
            }
        

    echo '<!-- Dark overlay -->
    <div class="overlay" id="overlay"></div>';
    

    echo '<!-- Pop up window to edit admin dashboard -->';
    echo '<div class="editAD" id="editAD">
        <form id="editADForm" action="Admin-Dash-Update.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <div class="editAD-cont">
                <button class="avatar-upload-btn" type="button" onclick="$(\'.avatar-upload-input\').trigger(\'click\')"><i class="fa fa-pen"></i></button>';
                    if ($res = $mysqli->query($adminquery)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                                echo '<input type="file" class="avatar-upload-input"  name="avatarUpload" accept="image/*" value="'.$row['AdminImg'].'" onchange="readURL(this);" />';
                                echo '<div class="avatar-upload-wrap">';
                                    echo '<img id="preview-image" src="Data/Admin/'.$row['AdminImg'].'" alt="Preview avatar"/>';
                                echo '</div>
                                <br>
                                <div class="edit-content">
                                    <div class="editAD-input">
                                        <p><i class="fas fa-user" id="icon"></i><input type="text" value="'.$row['AdminName'].'" name="name" required></p>
                                    </div>
                                    <br> 
                                    <input type="hidden" name="adminid" value="' .$row['AdminID'] . '">
                                </div>
                                <div class="btn-editAD">
                                    <input type="submit" class="button-editAD" name="updateAD" value="Update">        
                                </div>';
                            }
                        } else {
                            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
                        }
                    } else {
                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                    }
    
            echo '</div>                          
        </form>
    </div>';

   
}
?>
    <script src="./script.js"></script>
    <script>
       function editDashboard() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("editAD").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("editAD").style.display = "none";
        }

        // Preview Playlist Image
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

        // Preview Avatar Image
        function showPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("preview-image3");
            preview.src = src;
            preview.style.display = "block";
        }
        }

    </script>

</body>

</html>