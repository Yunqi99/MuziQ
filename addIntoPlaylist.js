
    function addIntoPlaylist(playlistId, trackId) {
        $.ajax({
            type: 'GET', 
            url: 'AddIntoPlaylist.php',
            data: {
                playlistId: playlistId,
                trackId: trackId
            },
            success: function(response) {
        // Check if the response contains the string "exists"
        if (response.indexOf("exists") !== -1) {
            alert("This track is already in the playlist.");
        // Check if the response contains the string "success"
        } else if (response.indexOf("success") !== -1) {
            alert("Successfully added to playlist.");
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
