
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="Homepage-admin.php"><i class="fa fa-home"></i>Home</a>
            <a href="Validation-List.php"><i class="fa fa-pencil"></i>Validation</a>
            <a href="Insights.php" class="active"><i class="fa fa-pie-chart"></i>Insights</a>
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
        <h1 id="insights-title">Metrics and Insights</h1>
        <div class="insights-table">
            <div class="insights-row1">
                <div class="insights-cont1">
                    <div class="insights-col1" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <h2>Registered Users</h2>';
                        $query1 = "SELECT COUNT(*) AS totalUser FROM user";
                        $res = $mysqli->query($query1);
                        $row = $res->fetch_assoc();
                        echo '<div class="data-col"><h3>'.$row['totalUser'].'</h3></div>
                    </div>
                    <div class="insights-col1" style="margin-bottom: 15px;">
                        <div class="info">
                            <h2>Total Tracks On Platform</h2>';
                            $query2 = "SELECT COUNT(*) AS totalTrack FROM track";
                            $res = $mysqli->query($query2);
                            $row = $res->fetch_assoc();
                            echo '<div class="data-col"><h3>'.$row['totalTrack'].'</h3></div>
                        </div>
                        <div class="info">
                            <h3 class="info-h3">Total User Tracks</h3>';
                            $query3 = "SELECT COUNT(*) AS totalUserTrack FROM track WHERE UserID <> 0";
                            $res = $mysqli->query($query3);
                            $row = $res->fetch_assoc();
                            echo '<div class="data-col-s"><h3>'.$row['totalUserTrack'].'</h3></div>
                        </div>
                        <div class="info">
                            <h3 class="info-h3">Total Platform Tracks</h3>';
                            $query4 = "SELECT COUNT(*) AS totalPlatformTrack FROM track WHERE AdminID <> 0";
                            $res = $mysqli->query($query4);
                            $row = $res->fetch_assoc();
                            echo '<div class="data-col-s"><h3>'.$row['totalPlatformTrack'].'</h3></div>
                        </div>
                    </div>
                    <div class="insights-col1">
                        <div class="info">
                            <h2>Total Playlists On Platform</h2>';
                            $query5 = "SELECT COUNT(*) AS totalPlaylist FROM playlist";
                            $res = $mysqli->query($query5);
                            $row = $res->fetch_assoc();
                            echo '<div class="data-col"><h3>'.$row['totalPlaylist'].'</h3></div>
                        </div>
                        <div class="info">
                            <h3 class="info-h3">Total User Playlists</h3>';
                            $query6 = "SELECT COUNT(*) AS totalUserPlaylist FROM playlist WHERE UserID <> 0";
                            $res = $mysqli->query($query6);
                            $row = $res->fetch_assoc();
                            echo '<div class="data-col-s"><h3>'.$row['totalUserPlaylist'].'</h3></div>
                        </div>
                        <div class="info">
                            <h3 class="info-h3">Total Platform-Generated Playlists</h3>';
                            $query7 = "SELECT COUNT(*) AS totalPlatformPlaylist FROM playlist WHERE AdminID <> 0";
                            $res = $mysqli->query($query7);
                            $row = $res->fetch_assoc();
                            echo '<div class="data-col-s"><h3>'.$row['totalPlatformPlaylist'].'</h3></div>
                        </div>
                    </div>
                </div>

                <div class="insights-cont2">
                    <h2 style="margin: auto; width: 100%;">Count of Tracks Based on Genre</h2>
                    <div class="piechart">
                        <canvas id="pieChart1"></canvas>
                    </div>
                </div>
            </div>
            <div class="insights-row2">
                <h2>Most Played Tracks</h2>
                <div class="linechart">
                    <canvas id="lineChart1"></canvas>
                </div>
            </div>
            <div class="insights-row2">
                <h2>Most Shared Tracks</h2>
                <div class="linechart">
                    <canvas id="lineChart2"></canvas>
                </div>
            </div>
        </div>
    </div>';


    $query8 = "SELECT genre.*, COUNT(track.TrackID) AS genreCount FROM genre LEFT JOIN track ON genre.GenreID = track.GenreID GROUP BY genre.GenreID";
    $result = $mysqli->query($query8);
    $genreData = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genreData[] = [
                'label' => $row['GenreName'],
                'count' => $row['genreCount']
            ];
        }
    } else {
        // Default data if no records found
        $genreData[] = [
            'label' => "Default Genre",
            'trackCount' => 0
        ];
    }

    $query9 = "SELECT * FROM track ORDER BY TrackCount DESC LIMIT 10"; // Limit to 10 tracks for the example
    $result = $mysqli->query($query9);
    $topTrackData = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topTrackData[] = [
                'label' => $row['TrackName'],
                'count' => $row['TrackCount']
            ];
        }
    } else {
        // Default data if no records found
        $topTrackData[] = [
            'label' => "No Tracks Found",
            'count' => 0
        ];
    }

    $query10 = "SELECT * FROM track ORDER BY ShareCount DESC LIMIT 10";
    $result = $mysqli->query($query10);
    $topShareData = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topShareData[] = [
                'label' => $row['TrackName'],
                'count' => $row['ShareCount']
            ];
        }
    } else {
        // Default data if no records found
        $topShareData[] = [
            'label' => "Default Track",
            'count' => 0
        ];
    }

}
?>

    <script src="./script.js"></script>
    <script>
    const genreLabels = <?php echo json_encode(array_column($genreData, 'label')); ?>;
    const genreCounts = <?php echo json_encode(array_column($genreData, 'count')); ?>;

    const ctx1 = document.getElementById('pieChart1');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: genreLabels,
            datasets: [{
                data: genreCounts,
                backgroundColor: [
                    '#e7feff',
                    '#B2FFFF',
                    '#a2a2d0',
                    '#A4DDED',
                    '#CCCCFF',
                    '#6CB4EE',
                    '#5A4FCF',
                    '#1F75FE',
                    '#008E97',
                    '#5072A7',
                    '#120A8F',
                    '#1E2952',
                    '#005f69',
                ],
                borderWidth: 0
            }]
        },
        options: {
            scales: {
                x: {
                    display: false,
                },
                y: {
                    display: false,
                },
            }
        }
    });

    // Bar Chart for Top Tracks by Play Count
    const topTrackLabels = <?php echo json_encode(array_column($topTrackData, 'label')); ?>;
    const topTrackCounts = <?php echo json_encode(array_column($topTrackData, 'count')); ?>;

    const ctx2 = document.getElementById('lineChart1');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: topTrackLabels,
            datasets: [{
                label: 'Track Count',
                data: topTrackCounts,
                backgroundColor: '#6A5ACD',
                borderWidth: 0
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Bar Chart for Top Tracks by Share Count
    const topShareLabels = <?php echo json_encode(array_column($topShareData, 'label')); ?>;
    const topShareCounts = <?php echo json_encode(array_column($topShareData, 'count')); ?>;

    const ctx3 = document.getElementById('lineChart2');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: topShareLabels,
            datasets: [{
                label: 'Share Count',
                data: topShareCounts,
                backgroundColor: '#9932cc',
                borderWidth: 0
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
});

    </script>

</body>
</html>