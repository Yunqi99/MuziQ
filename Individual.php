<?php
require_once 'vendor/autoload.php'; 

$mysqli = new mysqli("localhost", "root", "", "muziq-test");

// Check for database connection errors
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Database connection error: ' . $mysqli->connect_error]);
    exit;
}

// Check if the ID is provided in the request
if (isset($_POST['id'])) {
    // Sanitize the individual ID
    $individualId = $mysqli->real_escape_string($_POST['id']);

    // Fetch individual data from the user table
    $query = "SELECT * FROM user WHERE UserID = $individualId";
    $result = $mysqli->query($query);

    // Check if the query was successful and data is found
    if ($result && $result->num_rows > 0) {
        // Fetch individual data
        $individualData = $result->fetch_assoc();

        // Construct the individual's information as an array
        $individual = [
            'id' => $individualData['UserID'],
            'name' => $individualData['Username'],
            'image' => $individualData['UserImage'],
        ];

        // Fetch tracks associated with the individual
        $query = "SELECT track.*, user.*
                  FROM track JOIN user ON track.UserID = user.UserID
                  WHERE user.UserID = '$individualId' AND track.ValidationStatus = 'Approved'";
        $tracksResult = $mysqli->query($query);

        // Check if tracks were found
        if ($tracksResult) {
            ob_start(); // Start output buffering

            echo '<div class="individual-layout">
                <div class="individual-container1">
                    <div class="individual-img">
                        <img src="Data/User/'. $individualData['UserImage'].'">
                    </div>
                    <div class="individual-info">
                        <div class="individual-info-col">
                            <h1>'.$individualData['Username'].'</h1>
                            <br>
                            <p>'.$individualData['UserBio'].'</p>
                        </div>
                    </div>
                </div>
            <br><br>
            <div class="individual-container2">
            <h1>Tracks</h1>';
                    
            // Loop through tracks and display them
            while ($row = $tracksResult->fetch_assoc()) {
                // Calculate track duration
                $filePath = 'Data/TrackFile/' . $row['TrackFile'];

                $trackid = $row['TrackID'];
                $data_track = htmlspecialchars(json_encode([
                    "trackid" => $trackid,
                    "individualid" => $row['UserID'],
                    "name" => $row['TrackName'],
                    "individual" => $row['Username'],
                    "path" => $row['TrackFile'],
                    "img" => $row['TrackImg']
                ]));  

?>
                <div class="column-container" data-track="<?php echo $data_track;?>">
                    <div class="track-img">
                        <img src="Data/TrackImage/<?php echo $row['TrackImg']; ?>">
                        <div class="track-overlay"></div>
                        <i class="fas fa-play play-btn"></i>
                    </div>
                    <?php echo '<audio data-trackid="'.$trackid.'" src="Data/TrackFile/'.$row['TrackFile'].'"></audio>';?>
                    <div class="track-info">
                        <span class="button-col">
                            <?php echo '<button onclick="loadMusic('. $row['TrackID'] .')"><p>'.$row['TrackName'].'</p></button>';?>
                        </span>
                        <p class="name"><?php echo  $row['Username']; ?></p>
                    </div>
                </div>
<?php
            }
?>
            </div>
        </div>
<?php
            // Get the output buffer contents and clean the buffer
            $htmlContent = ob_get_clean();

            // Return individual data and HTML content as JSON
            echo json_encode(['individual' => $individual, 'html' => $htmlContent]);
        } else {
            echo json_encode(['error' => 'Failed to fetch tracks']);
        }
    } else {
        echo json_encode(['error' => 'individual not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid individual ID']);
}
?>
