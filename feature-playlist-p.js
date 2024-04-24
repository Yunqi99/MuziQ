function editPlaylist() {
    var overlay = document.getElementById("overlay");
    var editPlaylist = document.getElementById("editPlaylist");

    if (overlay !== null && editPlaylist !== null) {
        overlay.style.display = "block";
        editPlaylist.style.display = "block";
    } else {
        console.error("Overlay or editPlaylist element not found.");
    }
}

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
    document.getElementById("editPlaylist").style.display = "none";
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
            console.log(response);
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

    // function sortTracks(order) {
    //     var playlistId = getPlaylistId(); 
    //     var xhr = new XMLHttpRequest();
    
    //     xhr.onreadystatechange = function () {
    //         if (xhr.readyState == 4 && xhr.status == 200) {
    //             document.querySelector('.playlist-container2').innerHTML = xhr.responseText;
    //             var playlistList = JSON.parse(xhr.responseText); // Parse JSON response
    //                     console.log(xhr.responseText); // Move console.log here
    //             getPlaylistQueue(playlistList); // Call function with playlist list
    //         }
    //     };
    
    //     // Send both the sorting order and playlist ID to your PHP script
    //     xhr.open('GET', 'Sort.php?order=' + order + '&id=' + playlistId, true);
    //     xhr.send();
    // }
    
// Function to load playlist page using AJAX
// function sortTracks(order) {
//     var playlistId = getPlaylistId();
//     $.ajax({
//         url: 'Sort.php',
//         type: 'GET',
//         // dataType: 'html',
//         data: {order: order, id: playlistId},
//         success: function(response) {
//             $('#track-container2').html(response);
//             // Update the interface here
//             var playlistList = JSON.parse(response); // Parse JSON response
//             console.log(playlistList); // Log the playlist list for debugging
//             // getPlaylistQueue(playlistList); // Call function with playlist list
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX Error: ' + status, error);
//         }
//     });
//     return false; // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
// }

function sortTracks(order) {
    var playlistId = getPlaylistId(); 
    $.ajax({
        url: 'Sort-P.php',
        type: 'GET',
        // dataType: 'json',
        data: { order:order, id: playlistId},
        success: function(response) {
            // console.log(response);

            // Update the URL without reloading the page
            // var newUrl = 'MuziQ.php?page=Playlist&id=' + playlistId; // Construct URL with playlist ID
            // history.pushState({ page: 'Playlist', id: playlistId }, null, newUrl);
            // Update the content of your SPA with the playlist data
            $('#playlist-container2').html(response);
            // loadExternalScript('feature-playlist.js');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status, error);
        }
    });
    // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
    return false;
}

// document.addEventListener("DOMContentLoaded", function() {
//     var trackImages = document.querySelectorAll(".track-img");

//     trackImages.forEach(function(trackImg) {
//         var indexN = trackImg.getAttribute("data-index"); // Get the data-index attribute value for each track image

//         trackImg.addEventListener("click", function() {
//             loadTrack(indexN); // Pass the retrieved indexN to the loadTrack function
//         });
//     });
// });


// function sortTracks(order) {
//     var playlistId = getPlaylistId(); 
//     var xhr = new XMLHttpRequest();

//     xhr.onreadystatechange = function () {
//         if (xhr.readyState == 4 && xhr.status == 200) {
//             document.querySelector('.playlist-container2').innerHTML = xhr.responseText;
//         }
//     };

//     // Send both the sorting order and playlist ID to your PHP script
//     xhr.open('GET', 'Sort.php?order=' + order + '&id=' + playlistId, true);
//     xhr.send();
// }

function getPlaylistId() {
    // Adjust this logic based on how you obtain the playlist ID
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id') || ''; 
}
