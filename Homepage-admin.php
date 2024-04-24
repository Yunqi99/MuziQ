
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
        
    $AdminID = $_SESSION['AdminID'];    

    echo '<!-- Top Navigation Bar -->
    <div class="wrapper">
        <nav class="tabs">
            <div class="selector"></div>
            <a href="Homepage-admin.php" class="active"><i class="fa fa-home"></i>Home</a>
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
        }else {
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
</div>

    <div class="admin-layout">
        <div class="home-cont-row">
            <a href="Admin-Mng.php">
                <div class="home-cont-col">
                    <i class="fa fa-group"></i>
                    <div class="col-title">
                        <h2>Admin Account Management</h2>
                    </div>
                </div>
            </a>
            <a href="User-Mng.php">
                <div class="home-cont-col">
                    <i class="fas fa-address-book"></i>
                    <div class="col-title">
                        <h2>User Account Management</h2>
                    </div>
                </div>
            </a>
            <a href="Validation-List.php">
                <div class="home-cont-col">
                    <i class="fas fa-pen"></i>
                    <div class="col-title">
                        <h2>Validation</h2>
                    </div>
                </div>
            </a>
            <a href="Track-Opt.php">
            <div class="home-cont-col">
                <i class="fas fa-music"></i>
                <div class="col-title">
                    <h2>Track Management</h2>
                </div>
            </div>
        </a>

        <div class="home-cont-row">
            <a href="Playlist-Opt.php">
                <div class="home-cont-col">
                    <i class="fas fa-folder"></i>
                    <div class="col-title">
                        <h2>Playlist Management</h2>
                    </div>
                </div>
            </a>
            <a href="FAQ-Mng.php">
                <div class="home-cont-col">
                    <i class="fas fa-comments"></i>
                    <div class="col-title">
                        <h2>FAQ Management</h2>
                    </div>
                </div>
            </a>
            <a href="Message.php">
                <div class="home-cont-col">
                <i class="fa fa-envelope"></i>
                    <div class="col-title">
                        <h2>Messages Received</h2>
                    </div>
                </div>
            </a>
        </div>
    </div>';
}
?>

    <script src="./script.js"></script>
</body>
</html>