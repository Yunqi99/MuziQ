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
            <a href="Validation-List.php" class="active"><i class="fa fa-pencil"></i>Validation</a>
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
                <h1>TRACKS VALIDATION</h1>
            </div>
            <br>
            <div class="track-table-container">
                <table class="track-table">
                    <thead>
                        <tr class="title-row">
                            <th class="number">#</th>
                            <th class="track-img"></th>
                            <th>Title</th>
                            <th>Track ID</th>
                            <th>Track File</th>
                            <th>User ID / Username</th>
                            <th>Release Date</th>
                            <th>Upload Date</th>
                            <th>Genre</th>
                            <th class="action"></th>
                        </tr>
                    </thead>
                    <tbody>';
                        $query1 = "SELECT track.*, genre.*, user.* FROM track JOIN genre ON track.GenreID = genre.GenreID JOIN user ON track.UserID = user.UserID WHERE track.ValidationStatus='Pending' ORDER BY track.UploadDate ASC";
                        $res = $mysqli->query($query1);
                        if ($res) {
                            $trackNumber = 1;
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td class="number">'.$trackNumber.'</td>
                                        <td class="track-img" onclick="toggleAudio(this)">
                                            <img src="Data/TrackImage/' . $row['TrackImg'] . '">
                                            <div class="track-overlay"></div>
                                            <i class="fas fa-play"></i>
                                            <audio class="track-audio" src="Data/TrackFile/' . $row['TrackFile'] . '"></audio>
                                        </td>
                                        <td class="title">' . $row['TrackName'] . '</a></td>
                                        <td>' . $row['TrackGeneratedID'] . '</td>
                                        <td>' . $row['TrackFile'] .'</td>
                                        <td>' . $row['UserGeneratedID'] . '<br> '.$row['Username'].' </td>
                                        <td class="date">' . $row['ReleaseDate'] . '</td>
                                        <td class="date">' . $row['UploadDate'] . '</td>
                                        <td>' . $row['GenreName'] . '</td>
                                        <td class="action">
                                            <button id="btn-validation" class="btn-track-info" onclick="approveReason(' . $row['TrackID'] . ')">Approve</button>
                                            <button id="btn-validation" class="btn-track-info" onclick="rejectReason(' . $row['TrackID'] . ')">Reject</button>
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
    <div class="overlay" id="overlay"></div>
                            
    <!-- Pop up window to approve -->
    <div class="reason" id="approvereason">
        <form id="approveForm" class="reasonForm" action="Track-Approve.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <div class="reason-cont">
                <p><textarea name="reasonAppr" class="reasoninput" rows="8" cols="80" placeholder="Kindly write reason for approval." required></textarea></p>
            </div>
            <input type="hidden" id="trackIDapprove" name="TrackID" value="">
            <input type="submit" id="approveButton" class="button-feedback" name="sendReason" value="Send">                                                              
        </form>
    </div>

    <!-- Pop up window to reject -->
    <div class="reason" id="rejectreason">
        <form id="rejectForm" class="reasonForm" action="Track-Reject.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <div class="reason-cont">
                <p><textarea name="reasonRej" class="reasoninput" rows="8" cols="80" placeholder="Kindly write reason for rejection." required></textarea></p>
            </div>
            <input type="hidden" id="trackIDreject" name="TrackID" value="">
            <input type="submit" id="rejectButton" class="button-feedback" name="sendReason" value="Send">                                                              
        </form>
    </div>';
}
?> 

    <script src="./script.js"></script>
    <script>
        function toggleAudio(element) {
            var audio = element.querySelector('.track-audio');
            var icon = element.querySelector('i');

            if (audio.paused) {
                audio.play();
                icon.classList.remove('fa-play');
                icon.classList.add('fa-pause');
            } else {
                audio.pause();
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-play');
            }
        }
        
        function approveReason(TrackID) {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("approvereason").style.display = "block";
            document.getElementById("trackIDapprove").value = TrackID;
        }

        function rejectReason(TrackID) {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("rejectreason").style.display = "block";
            document.getElementById("trackIDreject").value = TrackID;
        }

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("approvereason").style.display = "none";
            document.getElementById("rejectreason").style.display = "none";
        }

    $("#approveForm").submit(function(ev) {
        ev.preventDefault(); // Prevent default form submission

        var form = $(this);
        url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this), // Use FormData to correctly handle file uploads
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                if (response.trim() === 'success') {
                    alert("Track approved.");
                    window.location.reload();
                } else {
                    alert("Failed to approve track. Please try again later.");
                }
            },
            error: function() {
                alert("An error occurred while processing your request. Please try again later.");
            }
        });
    });

    $("#rejectForm").submit(function(ev) {
        ev.preventDefault(); // Prevent default form submission

        var form = $(this);
        url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this), // Use FormData to correctly handle file uploads
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                if (response.trim() === 'success') {
                    alert("Track rejected.");
                    window.location.reload();
                } else {
                    alert("Failed to reject track. Please try again later.");
                }
            },
            error: function() {
                alert("An error occurred while processing your request. Please try again later.");
            }
        });
    });

    </script>
</body>
</html>
