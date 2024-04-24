<!DOCTYPE html>
<html lang="en">

<?php
session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

if (!$loggedin) { // Check if the user is not logged in
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
} else {
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
        
    $UserID = $_SESSION['UserID'];
    $userquery = "SELECT * FROM user WHERE UserID = $UserID";
    
    echo '<div class="content" id="content">';
    echo '<div class="UD-layout" id="UD-layout">
        <div class="dot-nav">
            <span class="dot" onclick="currentSection(1)">User Profile</span><br>
            <span class="dot" onclick="currentSection(2)">My Playlist</span><br>
            <span class="dot" onclick="currentSection(3)">My Track</span><br>
            <span class="dot" onclick="currentSection(4)">Track Status</span><br>
            <span class="dot" onclick="currentSection(5)">My Message</span>
        </div>';
    echo '<div class="content-UD">'; 
            if ($res = $mysqli->query($userquery)) {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) { 
                        echo '<div class="UD-container fade">
                                <img class="UD-background" src="./Sources/Img/border.png"/>
                                <button class="btn-edit" onclick="editUserDashboard()">Edit</button>
                                <div class="UD-container-row">
                                    <div class="UD-col">
                                        <div class="UD-img">';
                                            if (!empty($row['UserImage'])) {
                                                // If the user has an existing image
                                                echo '<img src="Data/User/'.$row['UserImage'].'" alt="Preview avatar">';
                                            } else {
                                                // If the user does not have an existing image, display the default image
                                                echo '<img src="Sources/Img/default.jpg" alt="Default avatar">';
                                            }
                                        echo '</div>
                                        <div class="name-container">
                                            <h1>'.$row['Username'].'</h1>
                                        </div>
                                        <div class="bio-container">
                                            <p>'.$row['UserBio'].'</p>
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
        
        echo '<div class="UD-container fade">
            <div class="UD-container-row">
                <div class="UD-content">
                    <div class="btn-container">
                        <button class="btn-createNew" onclick="createNewPlaylist()"><i class="fa fa-plus"></i></button>
                    </div>';

                $playlistquery = "SELECT * FROM playlist WHERE UserID = (SELECT UserID FROM user WHERE UserID = $UserID) ;";
                    if ($res = $mysqli->query($playlistquery)) {
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) { 
                                    echo '<div class="track-container">
                                            <div class="track-img">
                                                <img src="Data/Playlist/'.$row['PlaylistImg'].'">
                                            </div>
                                            <div class="track-info">
                                                <span onclick="loadPlaylistUser('. $row['PlaylistID'] .')"><h4>'.$row['PlaylistName'].'</h4></span>
                                            </div>
                                            <div class="delete-icon" onclick="deletePlaylist('.$row['PlaylistID'].')">
                                                <span><i class="fas fa-trash-alt"></i></span>
                                            </div>
                                        </div>';
                                }    
                            } else {
                                echo '<div class="empty-container">
                                    <div class="empty-img">
                                        <img src="Sources/Img/Empty.png"/>
                                    </div>
                                    <div class="empty-text">
                                        <h5>Looks like your playlist is enjoying some quiet time.</h5>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                        }
            echo'</div>
            </div>    
        </div>

        <div class="UD-container fade">
            <div class="UD-container-row">
                <div class="UD-content">
                    <div class="btn-container">
                        <button class="btn-createNew" onclick="createNewTrack()"><i class="fa fa-plus"></i></button>
                    </div>';

                    $trackquery = "SELECT track.*, user.* FROM track INNER JOIN user ON track.UserID = user.UserID WHERE user.UserID=$UserID AND track.ValidationStatus = 'Approved';;";
                    if ($res = $mysqli->query($trackquery)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                                echo '<div class="track-container">
                                        <div class="track-img">
                                            <img src="Data/TrackImage/' . $row['TrackImg'] . '" alt="Track Image">
                                        </div>
                                        <div class="track-info">
                                        <span onclick="loadMusic('. $row['TrackID'] .')">
                                                <h4>' . $row['TrackName'] . '</h4>
                                        </span>
                                        </div>
                                        <div class="delete-icon" onclick="deleteTrack('.$row['TrackID'].')">
                                            <span><i class="fas fa-trash-alt"></i></span>
                                        </div>
                                    </div>';
                            }    
                        } else {
                            echo '<div class="empty-container">
                                <div class="empty-img">
                                    <img src="Sources/Img/Empty.png"/>
                                </div>
                                <div class="empty-text">
                                    <h5>Your music is taking a break ! </h5>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                    }
                    
                echo '</div>
            </div>
        </div>

        
        <div class="UD-container fade" style="height: auto;">
            <div class="UD-container-row "style="height: auto;">
                <div class="UD-content" style="height: auto;">';

                    $trackquery = "SELECT track.*, user.* FROM track INNER JOIN user ON track.UserID = user.UserID WHERE user.UserID=$UserID;";
                    if ($res = $mysqli->query($trackquery)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                               
                                echo '<div class="track-container" style="height: auto;">
                                        <div class="track-img">
                                            <img src="Data/TrackImage/' . $row['TrackImg'] . '" alt="Track Image">
                                        </div>
                                        <div class="track-info" style="height: auto;">
                                            <h4 class="status-name"> ' . $row['TrackName'] . '</h4>
                                            <h4 class="status-reason" style="color: papayawhip;" onclick="showReason(\''.$row['Reason'].'\')">Status: ' . $row['ValidationStatus'] . '</h4>
                                        </div>
                                    </div>';
                            }    
                        } else {
                            echo '<div class="empty-container">
                                <div class="empty-img">
                                    <img src="Sources/Img/Empty.png"/>
                                </div>
                                <div class="empty-text">
                                    <h5>Your music is taking a break ! </h5>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                    }
                    
                echo '</div>
            </div>
        </div>';

        echo '<div class="UD-container fade">
        <div class="UD-container-row">
            <div class="UD-message">';

                $trackquery = "SELECT feedback.*, user.* FROM feedback INNER JOIN user ON feedback.UserID = user.UserID WHERE user.UserID=$UserID;";
                if ($res = $mysqli->query($trackquery)) {
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) { 
                            echo '<div class="feedback-cont">';
                                echo '<div class="feedback-title">
                                    <h3>Feedback: </h3>
                                        <h4>'.$row['FeedbackTitle'].'</h4>
                                        <p>'.$row['FeedbackMsg'].'</p>
                                    </div><br>
                                    <h3>Reply from MuziQ: </h3>
                                    <div class="reply-title">
                                        <h4>'.$row['ReplyTitle'].'</h4>
                                        <p>'.$row['ReplyAnswer'].'</p>
                                    </div>';
                            echo '</div>';
                        }    
                    } else {
                        echo '<div class="empty-container">
                            <div class="empty-img">
                                <img src="Sources/Img/Empty.png"/>
                            </div>
                            <div class="empty-text">
                                <h5>Your music is taking a break ! </h5>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                }     
                echo '</div>
            </div>
        </div>
        </div>
    </div>
    </div>';

    echo '<!-- Dark overlay -->
    <div class="overlay" id="overlay"></div>';

    echo'<div class="reason" id="showreason">
        <span id="closeButton" onclick="hidePop()"><i class="fa fa-close"></i></span>
        <div class="reason-info">
            <p class="reason-content" id="reason-content"></p>
        </div>
    </div>
                            
    <!-- Pop up window to create new playlist -->
    <div class="addPlaylist" id="newPlaylist">
        <form id="playlistForm" class="playlistForm" action="Playlist-Create.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePop()"><i class="fa fa-close"></i></span>
            <div class="playlist-cont">
                <div class="playlist-detail1">
                    <div class="image-upload-wrap">
                        <button class="file-upload-btn" type="button" onclick="$(\'.file-upload-input\').trigger(\'click\')">Add Image</button>
                        <input class="file-upload-input"  name="fileToUpload" type="file" onchange="readURL(this);" accept="image/*" />
                        <img id="preview-image" src="#" alt="Preview" style="display: none;">
                    </div>
                </div>
                            
                <div class="playlist-detail2">
                    <p><input type="text" name="playlistname" class="playlist-input" placeholder="Playlist Name" required></p>
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
                                            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                                        }
                                    } else {
                                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                                }       
                        echo '</select>
                    </p>
                    <p><textarea name="playlistdesc" class="descplaylist-input" rows="4" cols="50" maxlength="100" placeholder="Describe your playlist. Within 100 characters." required></textarea></p>
                    <br>
                    <input type="submit" class="button-newPlaylist" id="createPlaylist" name="createPlaylist" value="Create">
                </div>
            </div>                               
        </form>
    </div> 
    
    <!-- Pop up window to upload new track -->
    <div class="addTrack" id="newTrack">
        <form id="trackForm" class="trackForm" action="Track-Upload.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePop()"><i class="fa fa-close"></i></span>
            <div class="track-cont">
                <div class="track-detail1">
                    <div class="image-upload-wrap">
                        <button class="file-upload-btn2" type="button" onclick="$(\'.file-upload-input2\').trigger(\'click\')">Add Image</button>
                        <input class="file-upload-input2"  name="fileToUpload2" type="file" onchange="readURL2(this);" accept="image/*" />
                        <img id="preview-image2" src="#" alt="Preview" style="display: none;">
                    </div>
                </div>
                            
                <div class="track-detail2">
                    <p><input type="text" name="trackname" class="newTrack-input" placeholder="Track Name" required></p>
                    <p><input type="file" name="fileToUploadTrack" class="newTrack-file" accept="audio/*" required></p>
                    <p> 
                        <select id="genre-dropwdown" name="genreinput" required>
                        <option value="" disabled selected>Select Mood and Genre</option>'; 
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
                    <input type="submit" class="button-newTrack" name="createTrack" value="Upload">
                </div>
            </div>                      
        </form>
    </div>';

    echo '<!-- Pop up window to edit user dashboard -->';
    echo '<div class="editUD" id="editUD">
        <form id="editUDForm" action="UD-update.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePop()"><i class="fa fa-close"></i></span>
            <div class="editUD-cont">
                <button class="avatar-upload-btn" type="button" onclick="$(\'.avatar-upload-input\').trigger(\'click\')"><i class="fa fa-pen"></i></button>';
                    if ($res = $mysqli->query($userquery)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                                echo '<input type="file" class="avatar-upload-input"  name="avatarUpload" accept="image/*" value="'.$row['UserImage'].'" onchange="showPreview(event);" />';
                                echo '<div class="avatar-upload-wrap">';
                                    echo '<img id="preview-image3" src="Data/User/'.$row['UserImage'].'" alt="Preview avatar"/>';
                                echo '</div>
                                <br>
                                <div class="edit-content">
                                    <div class="editUD-input">
                                        <p><i class="fas fa-user" id="icon"></i><input type="text" value="'.$row['Username'].'" name="name" required></p>
                                    </div>
            
                                    <p><textarea name="userbio" class="userbio-input" rows="3" cols="50" maxlength="100" placeholder="Tell us your story! Share a bit about yourself! Within 100 words." required>'.$row['UserBio'].'</textarea></p>
                                    <br> 
                                    <input type="hidden" name="userid" value="' .$row['UserID'] . '">
                                </div>
                                <div class="btn-editUD">
                                    <input type="submit" class="button-editUD" name="updateUD" value="Update">        
                                </div>';
                            }
                        }
                    }
            echo '</div>                          
        </form>
    </div>';

}


?>

<script>


// Define currentSection function in the global scope
function currentSection(n) {
    showSection(secIndex = n);
}

// Function to show section
function showSection(n) {
    let i;
    let section = document.getElementsByClassName("UD-container");
    let dots = document.getElementsByClassName("dot");
    if (n > section.length) { secIndex = 1 } // Corrected assignment to secIndex
    if (n < 1) { secIndex = section.length }
    for (i = 0; i < section.length; i++) {
        section[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    section[secIndex - 1].style.display = "block";
    dots[secIndex - 1].className += " active";
}



$(document).ready(function() {
    // Initialize secIndex
    secIndex = 1;

    // Show initial section
    showSection(secIndex);

    // Handle user dashboard form submission
    $("#editUDForm").submit(function(ev) {
        ev.preventDefault(); // Prevent default form submission

        var form = $(this);
        url = form.attr('action');
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
                    alert("Profile updated successfully!");
                    hidePop();
                    form[0].reset(); // Clear form inputs
                    loadPage('User-Dashboard', 'User Dashboard', 'User-Dashboard.php');
                } else {
                    alert("Fail to update profile. Please try again.");
                }
            },
            error: function() {
                alert("An error occurred while processing your request. Please try again.");
            }
        });
    });

// Handle playlist form submission
$("#playlistForm").submit(function(ev) {
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
                alert("Playlist created successfully!");
                hidePop();
                form[0].reset(); // Clear form inputs
                loadPage('User-Dashboard', 'User Dashboard', 'User-Dashboard.php');
            } else {
                alert("Fail to create playlist. Please try again.");
            }
        },
        error: function() {
            alert("An error occurred while processing your request. Please try again.");
        }
    });
});

// Handle track form submission
$("#trackForm").submit(function(ev) {
    ev.preventDefault(); // Prevent default form submission

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this), // Use FormData to correctly handle file uploads
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
            if (response.trim() === 'success') {
                alert("Track created successfully!");
                hidePop();
                form[0].reset(); // Clear form inputs
                loadPage('User-Dashboard', 'User Dashboard', 'User-Dashboard.php');
            } else {
                alert("Fail to create track. Please try again.");
            }
        },
        error: function() {
            alert("An error occurred while processing your request. Please try again.");
        }
    });
});

});


// Function to delete playlist
function deletePlaylist(playlistID) {
    if (confirm('Are you sure you want to delete this playlist?')) {
        $.ajax({
            type: "GET",
            url: "Playlist-Delete.php",
            data: { id: playlistID },
            success: function(response) {
                if (response.trim() === 'success') {
                    alert("Playlist deleted successfully!");
                    loadPage('User-Dashboard', 'User Dashboard', 'User-Dashboard.php');
                } else {
                    alert("Fail to delete playlist. Please try again.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error:", textStatus, errorThrown);
                alert("An error occurred while processing your request. Please try again.");
            }
        });
    }
    return false; // Prevent default link behavior
}

// Function to delete playlist
function deleteTrack(trackID) {
    if (confirm('Are you sure you want to delete this track?')) {
        $.ajax({
            type: "GET",
            url: "Track-Delete.php",
            data: { id: trackID },
            success: function(response) {
                if (response.trim() === 'success') {
                    alert("Track deleted successfully!");
                    loadPage('User-Dashboard', 'User Dashboard', 'User-Dashboard.php');
                } else {
                    alert("Failto delete track. Please try again.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error:", textStatus, errorThrown);
                alert("An error occurred while processing your request. Please try again.");
            }
        });
    }
    return false; // Prevent default link behavior
}

function showReason(reason) {
  document.getElementById("overlay").style.display = "block";
  document.getElementById("showreason").style.display = "block";
  document.getElementById("reason-content").innerText = reason;
}

</script>
