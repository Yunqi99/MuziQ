function showPlaylistRes() {
    var overlay = document.getElementById("overlay");
    var playlistResult = document.getElementById("playlist-result");

    if (overlay !== null && playlistResult !== null) {
        overlay.style.display = "block";
        playlistResult.style.display = "block";
    } else {
        console.error("Overlay or playlist-result element not found.");
    }
}

function hidePopup() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("playlist-result").style.display = "none";
}

// Sort dropdown button in playlist
function playlistDropdown() {
    document.getElementById("sort-content").classList.toggle("show");
}


    document.getElementById("search-playlist").addEventListener("click", function() {
        var searchQuery = document.getElementById("sPlaylistInput").value.trim(); // Trim whitespace
        var playlistId = this.getAttribute('data-playlist');
        // Check if the search query is not empty
        if (searchQuery) {
            // Proceed with the search
            searchPlaylist(searchQuery, playlistId);
        } else {
            // Display a message or handle the case where the search query is empty
            console.log("Search query is empty. Please enter a valid search query.");
        }
    });

function searchPlaylist(searchQuery, playlistID) {
    showPlaylistRes();
    $.ajax({
        method: "GET",
        url: "Playlist-Search.php",
        data: { searchQuery: searchQuery, playlistID: playlistID },
        dataType: 'html',
        success: function(response) {
            $("#result-cont").html(response); 
            showPlaylistRes();
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
}

    // Preview Playlist Image
    function showPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("preview-image");
            preview.src = src;
            preview.style.display = "block";
        }
    }


function sortTracks(order) {
    var playlistId = getPlaylistId(); 
    $.ajax({
        url: 'Sort.php',
        type: 'GET',
        // dataType: 'json',
        data: { order:order, id: playlistId},
        success: function(response) {
            $('#playlist-container2').html(response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status, error);
        }
    });
    // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
    return false;
}

function getPlaylistId() {
    // Adjust this logic based on how you obtain the playlist ID
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id') || ''; 
}
