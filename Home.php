
<?php

session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

if (!$loggedin) { // Check if the user is not logged in
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
} else {
    $UserID = $_SESSION['UserID'];

    
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
    
    $userquery = "SELECT * FROM user WHERE UserID = $UserID ";

    echo '<div class="content" id="content">';
        echo'<div class="home-layout" id="home-layout">';
        $res = $mysqli->query($userquery);
        if ($res) {
            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                echo '<h1>Welcome, '.$row['Username'].' !</h1>';
            }else{
                echo '<script>alert("No username found."); window.history.back();</script>';
            }
        }else {
            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
        }
        echo '<div class="layout-responsive">';
        echo '<div class="home-row">
            <div class="home-container1 hidden">
                <h2>For You</h2>
                <div class="carousel-container">
                    <div class="carousel-inner">
                        <div class="carousel-row">';

                        require_once 'vendor/autoload.php';

                        $q1 = "SELECT track.*, user.*
                        FROM track 
                        INNER JOIN user ON track.UserID = user.UserID 
                        WHERE track.ValidationStatus = 'Approved'
                        ORDER BY RAND() LIMIT 7"; 

                        if ($res = $mysqli->query($q1)) {
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) { 
                                $trackid = $row['TrackID'];
                                
                                $filePath = 'Data/TrackFile/' . $row['TrackFile'];

                                $data_track = htmlspecialchars(json_encode([
                                    "trackid" => $trackid,
                                    "individualid" => $row['UserID'],
                                    "name" => $row['TrackName'],
                                    "individual" => $row['Username'],
                                    "path" => $row['TrackFile'],
                                    "img" => $row['TrackImg']
                                ]));  

                                echo '<div class="column-container" id="column-container" data-track="'. $data_track .'">
                                <audio data-trackid="'.$trackid.'" src="Data/TrackFile/'.$row['TrackFile'].'"></audio>
                                    <div class="column">
                                        <div class="column-track">
                                            <div class="column-img">
                                                <img src="Data/TrackImage/'.$row['TrackImg'].'"/>
                                                <div class="track-overlay"></div>
                                                <i class="fas fa-play play-btn"></i>
                                            </div>
                                           
                                            <div class="info">
                                                <button onclick="loadMusic('. $row['TrackID'] .')"><h5>'.$row['TrackName'].'</h5></button><br>
                                                <button onclick="loadIndividual('. $row['UserID'] .')"><p>'.$row['Username'].'</p></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                                }
                            }else{
                              echo '<script>alert("No records found."); window.history.back();</script>';
                            }
                        } else {
                            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                        }

                            
                    echo'</div>
                    </div>
                    <div class="carousel-nav">
                        <button class="btn-prev">
                            <span class="material-icons"><i class="fas fa-angle-left"></i></span>
                        </button>
                        <button class="btn-next">
                            <span class="material-icons"><i class="fas fa-angle-right"></i></span>
                        </button>
                    </div> 
                </div>  
            </div>

            <div class="home-container2 hidden">
                <div class="genre-header">
                    <h2>Genre</h2>
                    <div class="button-genre">
                        <button class="btn-genre" onclick="loadPage(\'Explore\', \'Explore\', \'Explore.php\')">More</button>
                    </div>
                </div>    
                
                <div class="genre-row">
                    <div class="genre-column">
                        <button onclick="loadMG(1)">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/Classical.png"/>
                            </div>
                            <h5>Classical</h5>
                        </button>
                    </div>
                   
                    <div class="genre-column">
                        <button onclick="loadMG(2)">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/C-Pop.png"/>
                            </div>
                            <h5>C-Pop</h5>
                        </button>
                    </div>
                    <div class="genre-column">
                        <button onclick="loadMG(3)">
                        <div class="genre-column">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/Electronic.png"/>
                            </div>
                            <h5>Electronic</h5>
                        </button>
                    </div>
                    <div class="genre-column">
                        <button onclick="loadMG(4)">
                        <div class="genre-column">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/Hindi.png"/>
                            </div>
                            <h5>Hindi</h5>
                        </button>
                    </div>
                </div>
                <div class="genre-row">
                    <div class="genre-column">
                        <button onclick="loadMG(5)">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/Jazz.png"/>
                            </div>
                            <h5>Jazz</h5>
                        </button>
                    </div>
                    <div class="genre-column">
                        <button onclick="loadMG(7)">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/K-Pop.png"/>
                            </div>
                            <h5>K-Pop</h5>
                        </button>
                    </div>
                    <div class="genre-column">
                        <button onclick="loadMG(9)">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/Malay.png"/>
                            </div>
                            <h5>Malay</h5>
                        </button>
                    </div>
                    <div class="genre-column">
                        <button onclick="loadMG(10)">
                            <div class="column-img">
                                <div class="track-overlay"></div>  
                                <img src="Sources/Genre/Pop.png"/>
                            </div>
                        <h5>Pop</h5>
                        </button>
                    </div>
                </div>
            </div>';

        echo '<div class="home-container4 hidden">
            <h2>New Releases</h2>
            <div class="carousel-container2">
                <div class="carousel-inner">
                    <div class="carousel-row">';

                    $q3 = "SELECT track.*, user.*
                    FROM track 
                    INNER JOIN user ON track.UserID = user.UserID 
                    WHERE track.ValidationStatus = 'Approved'
                    ORDER BY track.ReleaseDate DESC, RAND() LIMIT 11"; 
                    

                    if ($res = $mysqli->query($q3)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                                $trackid = $row['TrackID'];
                                $data_track = htmlspecialchars(json_encode([
                                    "trackid" => $trackid,
                                    "individualid" => $row['UserID'],
                                    "name" => $row['TrackName'],
                                    "individual" => $row['Username'],
                                    "path" => $row['TrackFile'],
                                    "img" => $row['TrackImg']
                                ]));  

                                echo '<div class="column-container" id="column-container" data-track="'. $data_track .'">
                                <div class="column">
                                    <audio data-trackid="'.$trackid.'" src="Data/TrackFile/'.$row['TrackFile'].'"></audio>
                                    <div class="column-track">
                                        <div class="column-img">
                                            <img src="Data/TrackImage/'.$row['TrackImg'].'"/>
                                            <div class="track-overlay"></div>
                                            <i class="fas fa-play play-btn"></i>
                                        </div>
                                        <div class="info">
                                            <button onclick="loadMusic('. $row['TrackID'] .')"><h5>'.$row['TrackName'].'</h5></button><br>
                                            <button onclick="loadIndividual('. $row['UserID'] .')"><p>'.$row['Username'].'</p></button>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                            }
                        }else{
                          echo '<script>alert("No records found."); window.history.back();</script>';
                        }
                    } else {
                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                    }
                        
                   echo '</div>
                </div>
                <div class="carousel-nav">
                    <button class="btn-prev">
                        <span class="material-icons"><i class="fas fa-angle-left"></i></span>
                    </button>
                    <button class="btn-next">
                        <span class="material-icons"><i class="fas fa-angle-right"></i></span>
                    </button>
                </div> 
            </div>   
        </div> 
    </div>


        <div class="home-container3 hidden">
            <h2>Trending</h2>
            <div class="trending-list">';
                $q2 = "SELECT track.*, user.*
                FROM track 
                INNER JOIN user ON track.UserID = user.UserID 
                WHERE track.ValidationStatus = 'Approved'
                ORDER BY track.TrackCount DESC LIMIT 5";

                if ($res = $mysqli->query($q2)) {
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            $trackid = $row['TrackID'];
                                
                            $filePath = 'Data/TrackFile/' . $row['TrackFile'];

                            $data_track = htmlspecialchars(json_encode([
                                "trackid" => $trackid,
                                "individualid" => $row['UserID'],
                                "name" => $row['TrackName'],
                                "individual" => $row['Username'],
                                "path" => $row['TrackFile'],
                                "img" => $row['TrackImg']
                            ]));  
                        echo '<div class="column-container" id="trending-container" data-track="'. $data_track .'">
                            <audio data-trackid="'.$trackid.'" src="Data/TrackFile/'.$row['TrackFile'].'"></audio>
                            <div class="track-img">
                                <img src="Data/TrackImage/'.$row['TrackImg'].'" />
                                <div class="track-overlay"></div>
                                <i class="fas fa-play play-btn" data-track="'. $data_track .'"></i>
                            </div>
                            <div class="info">
                                <button onclick="loadMusic('. $row['TrackID'] .')"><h5>'.$row['TrackName'].'</h5></button><br>
                                <button onclick="loadIndividual('. $row['UserID'] .')"><p>'.$row['Username'].'</p></button>
                            </div>
                        </div>';
                        }
                    } else {
                    echo '<script>alert("No records found.");  window.history.back();</script>';
                    }
                } else {
                    echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");  window.history.back();</script>';
                }
                    
                echo '</div>
                </div>    
            </div>
        </div>
    </div>'; 
}

?>
    <script>
   // Wait for all resources to be loaded
   window.onload = function() {
        var containers = document.querySelectorAll('.home-container1, .home-container2, .home-container3, .home-container4');
        
        containers.forEach(function(container) {
            container.classList.remove('hidden');
        });
    }; 

    </script>
