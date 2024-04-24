
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

    $playlistID = $_GET['id'];
        
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
            <h1>LIST OF TRACKS IN PLAYLIST</h1>
            <div class="bar-cont">
                <button class="btn-create-new" onclick="showTracks() "><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <br>
        <div class="track-table-container">
            <table class="track-table">
                <thead>
                    <tr class="title-row">
                        <th class="number">#</th>
                        <th class="track-img"></th>
                        <th class="title">Track Name</th>
                        <th>Track ID</th>
                        <th>Playlist ID</th>
                        <th>Date Added</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
                $query1 = "SELECT playlist_track.*, track.*, playlist.* FROM playlist_track
                INNER JOIN track ON playlist_track.TrackID = track.TrackID 
                INNER JOIN playlist ON playlist_track.PlaylistID = playlist.PlaylistID 
                WHERE playlist_track.PlaylistID = $playlistID";

                    $res = $mysqli->query($query1);
                    if ($res) {
                        $trackNumber = 1;
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo '<tr>
                                    <td class="number">'.$trackNumber.'</td>
                                    <td class="track-img">
                                        <img src="Data/TrackImage/'.$row['TrackImg'].'">
                                    </td>
                                    <td class="title">' . $row['TrackName'] . '</td>
                                    <td class="id">' . $row['TrackGeneratedID'] . '</td>
                                    <td>' . $row['PlaylistGeneratedID'] . '</td>
                                    <td>' . $row['P_DateAdded'] . '</td>
                                    <td>
                                        <a href="Playlist-Track-DeleteA.php?trackid=' . $row['TrackID'] . '&playlistid=' . $row['PlaylistID'] . '" onclick="return confirm(\'Are you sure you want to remove this track?\');" class="btn-track-info"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>';
                                $trackNumber++;
                            }
                        }
                        else {
                            echo '<div class="empty-container">
                                <div class="empty-img">
                                    <img src="Sources/Img/Empty.png"/>
                                </div>
                                <div class="empty-text">
                                    <h5>It\'s empty ! </h5>
                                </div>
                            </div>';
                        }
                    }
                echo '</tbody>
            </table>
        </div>
    </div>
</div>';

    echo '<!-- Dark overlay -->
    <div class="overlay" id="overlay"></div>
                            
    <!-- Pop up window to show tracks -->
    <div class="showTracks" id="showTracks">
    <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
    <div class="track-available-cont">
        <div class="track-available">
        <table class="track-table">
        <thead>
            <tr class="title-row">
                <th class="number">#</th>
                <th class="track-img"></th>
                <th class="title">Track Name</th>
                <th>Track ID</th>
                <th></th>
            </tr>
        </thead>
        <tbody>';
            $query1 = "SELECT * from track WHERE ValidationStatus = 'Approved'";

                $res = $mysqli->query($query1);
                if ($res) {
                    $trackNumber = 1;
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            echo '<tr>
                                <td class="number">'.$trackNumber.'</td>
                                <td class="track-img">
                                    <img src="Data/TrackImage/'.$row['TrackImg'].'">
                                </td>
                                <td class="title">' . $row['TrackName'] . '</td>
                                <td class="id">' . $row['TrackGeneratedID'] . '</td>
                                <td>
                                <button class="btn-create-new" onclick="addIntoPlaylist('.$playlistID.', '.$row['TrackID'].') "><i class="fas fa-plus"></i></button>
                                </td>
                            </tr>';
                            $trackNumber++;
                        }
                    }
                    else {
                        echo '<div class="empty-container">
                            <div class="empty-img">
                                <img src="Sources/Img/Empty.png"/>
                            </div>
                            <div class="empty-text">
                                <h5>It\'s empty ! </h5>
                            </div>
                        </div>';
                    }
                }
            echo '</tbody>
        </table>
        </div>
        </div>
    </div>'; 

}
?>
    <script src="./script.js"></script>
    <script>
        function showTracks() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("showTracks").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("showTracks").style.display = "none";
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


    function edit(id) {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("editPlaylist").style.display = "block";

        $.ajax({
            type: 'GET',
            url: 'Playlist-Edit.php',
            data: { id: id },
            dataType: 'json', 
            success: function(response) {
                console.log(response);
                $("#name").val(response.name);
                var imagePath = "Data/Playlist/" + response.img;
                $("#preview-image2").attr("src", imagePath);
                $("#genreid").val(response.genreid);
                $("#genreid").text(response.genre);
                $("#playlistdesc").val(response.desc);
                $("#playlistid").val(id);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    
    function addIntoPlaylist(playlistId, trackId) {
        $.ajax({
            type: 'GET', 
            url: 'AddIntoPlaylistAdmin.php',
            data: {
                playlistId: playlistId,
                trackId: trackId
            },
            success: function(response) {
        // Check if the response contains the string "exists"
        if (response.indexOf("exists") !== -1) {
            alert("This track is already in the playlist.");
            
        } else if (response.indexOf("success") !== -1) {
            alert("Successfully added to playlist.");
            location.reload();
        } else {
            alert("Unexpected response: " + response);
        }
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert("Error adding to playlist. Please try again.");
    }
        });
    }

    </script>
</body>
</html>