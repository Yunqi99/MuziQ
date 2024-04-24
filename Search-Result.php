

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
        
    $myUserID = $_SESSION['UserID'];

    echo '<div class="content" id="content">';
    echo '<div class="search-layout">
    <h1>Search Result</h1>
    <div class="filter-options">
        <h3>Filter By : </h3>
        <label><input type="radio" id="filterTrack" name="filterOpt" checked> Track</label>
        <label><input type="radio" id="filterIndividual" name="filterOpt"> User</label>
        <label><input type="radio" id="filterPlaylist" name="filterOpt"> Playlist</label>
    </div>';

    if (isset($_GET["searchQuery"]) && !empty($_GET["searchQuery"])) {
        // Sanitize the search query
        $search = mysqli_real_escape_string($mysqli, $_GET["searchQuery"]);

        $query = "SELECT * FROM track WHERE LOWER(TrackName) 
        LIKE LOWER('%$search%') AND ValidationStatus = 'Approved' ORDER BY RAND() DESC";

            $result = mysqli_query($mysqli, $query);
        
            if ($result) {
                echo '<div class="search-container" id="filtercol">
                <div class="search-row">
                    <div class="search-content">';
                if (mysqli_num_rows($result) > 0) {
        
                        while ($row = mysqli_fetch_array($result)) {
                            echo ' <div class="column"> 
                            <div class="track-container">
                            <div class="track-img">
                                <img src="Data/TrackImage/'.$row['TrackImg'].'">
                            </div>
                            <div class="track-info">
                                <button onclick="loadMusic('. $row['TrackID'] .')"><h3>'.$row['TrackName'].'</h3></button>
                            </div>
                            </div>
                            </div>';
                        }
                        
                    echo '</div>
                    </div>
                    </div>';
                } else {
                    echo '<div class="empty-container">
                    <div class="empty-img">
                        <img src="Sources/Img/Empty.png"/>
                    </div>
                    <div class="empty-text" id="filter-text">
                        <h5>Not found. Please try to search again.</h5>
                    </div>
                </div>';
                }
            } 
        }

        echo '</div>';
        echo '</div>';
    }
?>

    <script>
    $(document).ready(function() {
        // Attach change event listener to radio buttons
        $('input[type=radio][name=filterOpt]').on('change', function() {
            // Get the value of the selected filter option
            var filterOption = $(this).attr('id').replace('filter', '').toLowerCase();
            // Call the filterResults function with the selected filter option
            filterResults(filterOption);
            
        });
    });

    function filterResults(option) {
        
        // Make an AJAX request to filter.php with the selected filter option
        $.ajax({
            type: 'GET',
            url: 'Filter.php',
            data: { option: option, searchQuery: '<?php echo isset($_GET["searchQuery"]) ? $_GET["searchQuery"] : "" ?>'},
            dataType: 'html',
            success: function(response) {
                // Update the searchResults div with the filtered search results
                $('#filtercol').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    </script>