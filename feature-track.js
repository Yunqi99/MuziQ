 
    $(document).ready(function() {
        $(".btn-AIP").click(function() {
            var playlistId = $(this).data('playlist-id');
            var trackId = $(this).data('track-id');

            addIntoPlaylist(playlistId, trackId);
        });

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
    });
    
    $(document).mouseup(function (e) {
        var playlistContainer = $("#playlist-available");
        var addTrackButton = $("#add-track-button");
        // Check if the click is outside the playlist and addTrack button
        if (!playlistContainer.is(e.target) && playlistContainer.has(e.target).length === 0 &&
            !addTrackButton.is(e.target) && addTrackButton.has(e.target).length === 0) {
            playlistContainer.removeClass('show');
        }
    });

    function addTrack() {
        var playlistContainer = document.getElementById("playlist-available");
        playlistContainer.classList.toggle("show");
    }

    function showPopup() {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("qr-container").style.display = "block";
    }
    

    function hidePopup() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("qr-container").style.display = "none";
    }

    var shareCompleted = false;
        
    // Function to copy QR code URL to clipboard
    function copy(TrackID) {
        var tempInput = document.createElement("input");
        tempInput.value = window.location.href;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Copied to clipboard!");
        if (!shareCompleted) {

            updateShareCount(TrackID);

            shareCompleted = true;
        }
    }

    function updateShareCount(TrackID) {
        $.ajax({
            type: "GET",
            url: "Share-Update.php",
            data: { TrackID: TrackID },
            success: function(response) {
                console.log("Shared successfully."); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); 
                alert("Error updating ShareCount. Please try again.");
            }
        });
    }

    // Function to handle downloading QR code image
    document.getElementById("btn-download").addEventListener("click", function() {
        var qrContainer = document.getElementById("qr-img");
        var link = document.createElement("a");
        var imageData = qrContainer.querySelector('img').src; // Get the src attribute of the img element inside qrContainer
        link.href = imageData;
        link.download = "QR_Code.png";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        alert("Downloaded!");

        if (!shareCompleted) {

            updateShareCount(TrackID);

            shareCompleted = true;
        }
    });

    function share() {
        var xhr = new XMLHttpRequest();
        var trackURL = window.location.href;
        xhr.open("GET", "QR.php?url=" + encodeURIComponent(trackURL), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var qrContainer = document.getElementById("qr-img");
                qrContainer.innerHTML = ""; // Clear existing content
                qrContainer.innerHTML = xhr.responseText; 
                qrContainer.style.display = "block"; // Display the QR container
            }
        };
        xhr.send();
    }

    