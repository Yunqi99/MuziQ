function getPlaylistQueue(fetchedTracks) {
    queue_list = [];
    
    if (!Array.isArray(fetchedTracks)) {
        return;
    }
    
    // Clear the existing content
    const queueList = document.querySelector('.queue-list');
    queueList.innerHTML = '';

    // Copy the fetched tracks to the queue list, avoiding duplicates
    fetchedTracks.forEach(track => {
        queue_list.push(track);
    });

    // Load the track if queue list is not empty
    // if (queue_list.length > 0) {
    //     loadTrack(track_index);
    // }

    // Add click event listeners to queue containers
    fetchedTracks.forEach(track => {
        // Create a container for each track
        const trackContainer = document.createElement('div');
        trackContainer.classList.add('queue-container');

        // Create an image element for the track image
        const img = document.createElement('img');
        img.src = 'Data/TrackImage/' + track.TrackImg; // Set the correct image source

        // Create a container for track information
        const infoContainer = document.createElement('div');
        infoContainer.classList.add('track-info');

        // Create elements for track information
        const title = document.createElement('h4');
        title.textContent = track.TrackName;
        const individual = document.createElement('p');
        individual.textContent = track.Username;

        // Append the elements to the track container
        infoContainer.appendChild(title);
        infoContainer.appendChild(individual);
        trackContainer.appendChild(img);
        trackContainer.appendChild(infoContainer);

        // Append the track container to the queue list
        queueList.appendChild(trackContainer);

        // Add click event listener to play the track when clicked
        trackContainer.addEventListener('click', function() {
            // Find the index of the clicked track in the queue
            const index = queue_list.findIndex(item => item.TrackID === track.TrackID);
            
            // Update the track index and load the clicked track
            if (index !== -1) {
                track_index = index;
                loadTrack(track_index);
            } else {
                console.error('Track not found in queue:', track);
            }
        });
    });
}