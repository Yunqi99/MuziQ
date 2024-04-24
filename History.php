
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
        
    $UserID = $_SESSION[ 'UserID' ];

    echo '<div class="content" id="content">';
    echo '<div class="history-layout" id="history-layout">
        <div class="history-container">
            <h1>History</h1>
            <div class="history-content">';
            $userquery = "SELECT UserID FROM user WHERE UserID = $UserID";
            $res = $mysqli->query($userquery);
            
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $userid = $row['UserID'];
            
                $query = "SELECT track.*, history.*, user.*
                FROM track
                INNER JOIN history ON track.TrackID = history.TrackID
                INNER JOIN user ON history.UserID = user.UserID
                WHERE history.UserID = $userid ORDER BY DateListened DESC";
            
                $res = $mysqli->query($query);
            
                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $listenedDateTime = date('Y-m-d H:i', strtotime($row['DateListened']));
                        // Split the date and time
                        list($date, $time) = explode(' ', $listenedDateTime);

                        $trackid = $row['TrackID'];

                        echo '<div class="track-container">
                            <audio data-trackid="'.$trackid.'" src="Data/TrackFile/'.$row['TrackFile'].'"></audio>
                            <div class="track-img">
                                <img src="Data/TrackImage/'.$row['TrackImg'].'">
                            </div>
                            <div class="track-info">
                                <button onclick="loadMusic('. $row['TrackID'] .')"><h3>'.$row['TrackName'].'</h3></button><br>
                                <p class="history-date">Listened on '.$date.' at '.$time.'</p>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="empty-container">
                        <div class="empty-img">
                            <img src="Sources/Img/Empty.png"/>
                        </div>
                      <div class="empty-text">
                         <h5>Looks like your history is enjoying some quiet time.</h5>
                      </div>
                    </div>';
                }
            } else {
                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");</script>';
            }
        echo '</div></div>';
        echo '</div></div>';

}
?>
