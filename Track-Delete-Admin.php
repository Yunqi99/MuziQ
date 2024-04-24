<?php 

session_start();

if (isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID'])) {

    // Check if there was an error connecting to the database
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

    // Check if track ID is set in the URL
    if(isset($_GET['id'])) {
        // Get track ID from the URL
        $trackID = $_GET['id'];
    
        // Begin transaction
        $mysqli->begin_transaction();
    
        try {
            // Prepare the delete statement for history table
            $checkTracksQuery = "SELECT COUNT(*) AS trackCount FROM playlist_track WHERE TrackID = ?";
            $stmtCheckTracks = $mysqli->prepare($checkTracksQuery);
            $stmtCheckTracks->bind_param("i", $trackID);
            $stmtCheckTracks->execute();
            $resultCheckTracks = $stmtCheckTracks->get_result();
            $rowCheckTracks = $resultCheckTracks->fetch_assoc();
            $trackCount = $rowCheckTracks['trackCount'];
            $stmtCheckTracks->close();
    
            if ($trackCount > 0) {
                // Delete tracks associated with the track
                $deleteTracksQuery = "DELETE FROM playlist_track WHERE TrackID = ?";
                $stmtDeleteTracks = $mysqli->prepare($deleteTracksQuery);
                $stmtDeleteTracks->bind_param("i", $trackID);
                $deleteTracksSuccess = $stmtDeleteTracks->execute();
                $stmtDeleteTracks->close();
                if (!$deleteTracksSuccess) {
                    echo 'error';
                    exit; // Exit script if track deletion fails
                }
            }
    
            // Prepare the delete statement for history table
            $checkTracksQuery2 = "SELECT COUNT(*) AS trackCount2 FROM history WHERE TrackID = ?";
            $stmtCheckTracks2 = $mysqli->prepare($checkTracksQuery2); // corrected variable name here
            $stmtCheckTracks2->bind_param("i", $trackID);
            $stmtCheckTracks2->execute();
            $resultCheckTracks2 = $stmtCheckTracks2->get_result();
            $rowCheckTracks2 = $resultCheckTracks2->fetch_assoc();
            $trackCount2 = $rowCheckTracks2['trackCount2'];
            $stmtCheckTracks2->close();
    
            if ($trackCount2 > 0) {
                // Delete tracks associated with the track
                $deleteTracksQuery2 = "DELETE FROM history WHERE TrackID = ?"; // corrected table name here
                $stmtDeleteTracks2 = $mysqli->prepare($deleteTracksQuery2);
                $stmtDeleteTracks2->bind_param("i", $trackID);
                $deleteTracksSuccess2 = $stmtDeleteTracks2->execute();
                $stmtDeleteTracks2->close();
                if (!$deleteTracksSuccess2) {
                    echo 'error';
                    exit; // Exit script if track deletion fails
                }
            }

              // Check if there are tracks associated with the playlist
            $checkTracksQuery3 = "SELECT COUNT(*) AS trackCount3 FROM playlist WHERE TrackID = ?";
            $stmtCheckTracks3 = $mysqli->prepare($checkTracksQuery);
            $stmtCheckTracks3->bind_param("i", $playlistID);
            $stmtCheckTracks3->execute();
            $resultCheckTracks3 = $stmtCheckTracks3->get_result();
            $rowCheckTracks3 = $resultCheckTracks3->fetch_assoc();
            $trackCount3 = $rowCheckTracks3['trackCount'];
            $stmtCheckTracks3->close();

            if ($trackCount3 > 0) {
                // Delete tracks associated with the playlist
                $deleteTracksQuery3 = "DELETE FROM track WHERE TrackID = ?";
                $stmtDeleteTracks3 = $mysqli->prepare($deleteTracksQuery);
                $stmtDeleteTracks3->bind_param("i", $playlistID);
                $deleteTracksSuccess3 = $stmtDeleteTracks3->execute();
                $stmtDeleteTracks3->close();
                if (!$deleteTracksSuccess3) {
                    echo 'error';
                    exit; // Exit script if track deletion fails
                }
            }


            // Prepare the delete statement for track table
            $query = "DELETE FROM track WHERE TrackID = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $trackID);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            // Commit the transaction if at least one deletion was successful
            if ($affectedRows > 0) {
                $mysqli->commit();
                echo '<script>alert("Successfully deleted track!"); window.history.back();</script>';
            } else {
                // Rollback the transaction if no deletion was successful
                $mysqli->rollback();
                echo '<script>alert("No related entries found for deletion."); window.history.back();</script>';
            }

        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            $mysqli->rollback();
            echo '<script>alert("Error: ' . $e->getMessage() . '"); window.history.back();</script>';
        }
    } else {
        echo '<script>alert("Error! Track ID is not provided."); window.history.back();</script>'; 
    } 
} else {
    echo '<script>alert("Kindly login to proceed!"); window.location.href = "Index.html";</script>';
}

?>
