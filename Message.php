<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MuziQ</title>
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
}
else {

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
    </div>';

    echo '<div class="admin-layout">
        <div class="admin-cont">
            <div class="bar-row">
                <h1>MESSAGES RECEIVED</h1>
            </div>
            <br>
            <div class="track-table-container">
                <table class="feedback-table">
                    <thead>
                        <tr class="title-row">
                            <th class="number">#</th>
                            <th>Feedback</th>
                            <th class="feedback">Feedback Reply</th>
                            <th>User ID</th>
                            <th class="action"></th>
                        </tr>
                    </thead>
                    <tbody>';

                    $query1 = "SELECT feedback.*, user.* FROM  feedback INNER JOIN user ON feedback.UserID = user.UserID";
                    $res = $mysqli->query($query1);
                    if ($res) {
                        $trackNumber = 1;
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo '<tr>
                                    <td class="number"><b>'.$trackNumber.'</b></td>
                                    <td><b>'.$row['FeedbackTitle'].'</b><br><br>'.$row['FeedbackMsg'].'</td>
                                    <td class="feedback-cont"><b>' . $row['ReplyTitle'] . '</b><br><br>'. $row['ReplyAnswer'] .'</td>
                                    <td class="id"><b>' . $row['UserGeneratedID'] . '</b></td>
                                    <td class="action">';
                                    
                                    // Check if reply title and answer exist
                                    if (!empty($row['ReplyTitle']) && !empty($row['ReplyAnswer'])) {
                                        echo '<button  class="btn-track-info" disabled><i class="fas fa-reply"></i></button>';
                                    } else {
                                        echo '<button  class="btn-track-info" onclick="reply('.$row['FeedbackID'].')"><i class="fas fa-reply"></i></button>';
                                    }
                                    
                                    echo '<a href="Msg-Delete.php?id='. $row['FeedbackID'] .'" onclick="return confirm(\'Are you sure to delete?\');" class="btn-track-info"><i class="fas fa-trash"></i></a>
                                    </td>
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
    <div class="overlay" id="overlay"></div>';
                            
    echo '<!-- Pop up window to upload new track -->
    <div class="contact-con" id="contact-con">
        <form id="contactForm" class="contactForm" action="Msg-Reply.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <h2>Reply User Feedback</h2>
            <img src="Sources/Img/Share.png" alt="Image of sharing">
            <p><input name="title" class="cont-input" placeholder="Feedback Title" required></p>
            <p><textarea name="feedback" class="feedback-input" rows="4" cols="50" placeholder="Kindly write your reply here." required></textarea></p>
            <br>
            <input type="hidden" name="feedbackid" id="feedbackid" value="">
            <input type="submit" class="button-feedback" name="sendFeedback" value="Send">                                        
        </form>
    </div>'; 
}
?>

    <script src="./script.js"></script>
    <script>

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("contact-con").style.display = "none";
        }

        function reply(id) {
            // Show overlay and edit FAQ popup
            document.getElementById("overlay").style.display = "block";
            document.getElementById("contact-con").style.display = "block";
            
            document.getElementById("feedbackid").value = id;

        }
    </script>
</body>
</html>