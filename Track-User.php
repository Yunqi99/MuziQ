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
                <h1>LIST OF USER TRACKS</h1>
            </div>
            <br>
            <div class="track-table-container" id="Acc">
                <table class="track-table">
                    <thead>
                        <tr class="title-row">
                            <th class="number">#</th>
                            <th class="track-img"></th>
                            <th class="title">Title</th>
                            <th>Track ID</th>
                            <th>User ID</th>
                            <th>Track File</th>
                            <th>Upload Date</th>
                            <th>Release Date</th>
                            <th>Genre</th>
                            <th>Validation Status</th>
                            <th class="action"></th>
                        </tr>
                    </thead>
                    <tbody>';
                        $query1 = "SELECT track.*, genre.*, user.* FROM track
                        JOIN genre ON track.GenreID = genre.GenreID JOIN user ON track.UserID = user.UserID
                        WHERE track.UserID != 0 AND track.UserID !=10 ORDER BY track.UserID";
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
                                        <td class="title">' . $row['TrackName'] . '</td>
                                        <td>' . $row['TrackGeneratedID'] . '</td>
                                        <td>' . $row['UserGeneratedID'] . '</td>
                                        <td>' . $row['TrackFile'] . '</td>
                                        <td class="date">' . $row['UploadDate'] . '</td>
                                        <td class="date">' . $row['ReleaseDate'] . '</td>
                                        <td>' . $row['GenreName'] . '</td>
                                        <td class="status" onclick="showReason(\''.$row['Reason'].'\')">' . $row['ValidationStatus'] . '</td>
                                        <td class="action">
                                            <button id="track" class="btn-track-info" onclick="edit('.$row['TrackID'].')"><i class="fas fa-pen"></i></button>
                                            <a href="Track-Delete-Admin.php?id='. $row['TrackID'] .'" onclick="return confirm(\'Are you sure to delete?\');" class="btn-track-info"><i class="fas fa-trash"></i></a>
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

    echo'<div class="reason" id="showreason">
        <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
        <div class="reason-info">
            <p class="reason-content" id="reason-content"></p>
        </div>
    </div>';
            
        echo '<!-- Pop up window to edit track -->
        <div class="addTrack" id="editTrack">
                <form id="trackForm" class="trackForm" action="Track-Update.php" method="POST" enctype="multipart/form-data">
                    <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
                    <div class="track-cont">
                        <div class="track-detail1">
                            <div class="image-upload-wrap">
                                <button class="file-upload-btn" type="button" onclick="$(\'.file-upload-input\').trigger(\'click\')">Add Image</button>
                                <input class="file-upload-input" name="fileToUpload" type="file" onchange="readURL(this);" accept="image/*" />
                                <img id="preview-image" alt="Preview">
                            </div>
                        </div>
                                    
                        <div class="track-detail2">
                            <p><input type="text" id="name" name="trackname" class="newTrack-input" placeholder="Track Name" required></p>
                            <p class="editFileInput">Track File: <span id="fileinput"></span></p>
                            <p class="editFileInput">New Track File:<input type="file" id="editFile" name="fileToUploadTrack" class="newTrack-file" accept="audio/*" ></p>
                            <p> 
                                <select id="genre-dropdown" name="genreinput2" required>
                                <option id="genreid" disabled selected>Select Mood and Genre</option>'; 
                                    $genrequery = "SELECT * FROM genre";
                                        if ($res = $mysqli->query($genrequery)) {
                                            if ($res->num_rows > 0) {
                                                while ($row = $res->fetch_assoc()) { 
                                                    echo '<option value="'. $row['GenreID'] .'">'. $row['GenreName'] .'</option>';
                                                }
                                            } else {
                                                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                                            }
                                        } else {
                                            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                                    }  
                                echo '</select>
                            </p>
                            <p class="release-date">Release Date :<input type="date" id="releaseinput" class="newTrack-input" name="releasedate" required></p>
                            <br>
                            <input type="hidden" id="trackid" name="trackid" value="'.$row['TrackID'].'">
                            <input type="submit" class="button-newTrack" name="updateTrack" value="Update">
                        </div>
                    </div>                      
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

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("editTrack").style.display = "none";
            document.getElementById("showreason").style.display = "none";
        }

        function showReason(reason) {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("showreason").style.display = "block";
            document.getElementById("reason-content").innerText = reason;
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
        

    function edit(id) {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("editTrack").style.display = "block";

    $.ajax({
        type: 'GET',
        url: 'Track-Edit.php',
        data: { id: id },
        dataType: 'json', 
        success: function(response) {
            console.log(response);
            $("#name").val(response.name);
            var imagePath = "Data/TrackImage/" + response.img;
            $("#preview-image").attr("src", imagePath);
            $("#fileinput").text(response.file);
            $("#genre-dropdown").val(response.genreid); 
            $("#genreid").text(response.genre);
            $("#releaseinput").val(response.release);
            $("#trackid").val(id);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

    </script>
</body>
</html>