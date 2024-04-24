
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
            <h1>LIST OF USER PLAYLISTS</h1>
        </div>
        <br>
        <div class="track-table-container">
            <table class="track-table">
                <thead>
                    <tr class="title-row">
                        <th class="number">#</th>
                        <th class="track-img"></th>
                        <th class="title">Playlist Name</th>
                        <th>Playlist ID</th>
                        <th>User ID</th>
                        <th>Playlist Description</th>
                        <th>Genre</th>
                        <th class="total">Total Tracks</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
                $query1 = "SELECT playlist.*, user.*, COUNT(playlist_track.TrackID) AS track_count, genre.* FROM playlist
                LEFT JOIN playlist_track ON playlist.PlaylistID = playlist_track.PlaylistID
                LEFT JOIN genre ON playlist.GenreID = genre.GenreID
                LEFT JOIN user ON playlist.UserID = user.UserID
                WHERE playlist.UserID != 0
                GROUP BY playlist.PlaylistID ORDER BY playlist.UserID";
                    $res = $mysqli->query($query1);
                    if ($res) {
                        $trackNumber = 1;
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo '<tr>
                                    <td class="number">'.$trackNumber.'</td>
                                    <td class="track-img">
                                        <img src="Data/Playlist/'.$row['PlaylistImg'].'">
                                    </td>
                                    <td class="title">
                                        <a href="Playlist-Track.php?id=' . $row['PlaylistID'] . '">' . $row['PlaylistName'] . '</a>
                                    </td>
                                    <td class="playlist-id">' . $row['PlaylistGeneratedID'] . '</td>
                                    <td class="id">' . $row['UserGeneratedID'] . '</td>
                                    <td class="desc">' . $row['PlaylistDesc'] . '</td>
                                    <td>' . $row['GenreName'] . '</td>
                                    <td class="total">' . $row['track_count'] . '</td>
                                    <td>
                                        <button id="track" class="btn-track-info"  onclick="edit('.$row['PlaylistID'].')"><i class="fas fa-pen"></i></button>
                                        <a href="Playlist-Delete-Admin.php?id='. $row['PlaylistID'] .'" onclick="return confirm(\'Are you sure to delete?\');" class="btn-track-info"><i class="fas fa-trash"></i></a>
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

    echo' <!-- Pop up window to edit playlist -->
    <div class="addPlaylist" id="editPlaylist">
        <form id="playlistForm" class="playlistForm" action="Playlist-Update-Admin.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <div class="playlist-cont">
                <div class="playlist-detail1">
                    <div class="image-upload-wrap">
                        <button class="file-upload-btn2" type="button" onclick="$(\'.file-upload-input2\').trigger(\'click\')" >Add Image</button>
                        <input class="file-upload-input2"  name="fileToUpload" type="file" onchange="readURL2(this);" accept="image/*"/>
                        <img id="preview-image2" alt="Preview">
                    </div>
                </div>
                            
                <div class="playlist-detail2">
                    <p><input type="text" id="name" name="playlistname" class="playlist-input" placeholder="Playlist Name" required></p>
                        <p> 
                        <select id="genre-dropwdown" name="genreinput" required>
                        <option id="genreid" disabled selected>Select Mood and Genre</option>'; 
                                $genrequery = "SELECT * FROM genre";
                                    if ($res = $mysqli->query($genrequery)) {
                                        if ($res->num_rows > 0) {
                                            while ($row = $res->fetch_assoc()) { 
                                                echo '<option value="'. $row['GenreID'] .'">'. $row['GenreName'] .'</option>';
                                            }
                                        } else {
                                            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.location.href = "User-Dashboard.php";</script>';
                                        }
                                    } else {
                                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.location.href = "User-Dashboard.php";</script>';
                                }
                                
                            echo '</select>
                        </p>
                    <p><textarea name="playlistdesc" id="playlistdesc" class="descplaylist-input" rows="4" cols="50" maxlength="100" placeholder="Describe your playlist. Within 100 words." required></textarea></p>
                    <br>
                    <input type="hidden" id="playlistid" name="playlistid" value="'.$row['PlaylistID'].'">
                    <input type="submit" class="button-newPlaylist" name="updatePlaylist" value="Update">
                </div>
            </div>
                                       
        </form>
    </div>'; 
}
?>
    <script src="./script.js"></script>
    <script>
        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("editPlaylist").style.display = "none";
        }

        // Preview Playlist Image
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
    </script>
</body>
</html>