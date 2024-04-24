let queue_list = [];
var track_index = 0;

let current_time = document.querySelector(".current-time");
let total_duration = document.querySelector(".duration-time");
let seek_slider = document.querySelector(".duration-slider");
let curr_track = document.createElement("audio");
let curr_column = document.querySelector(".column-container")
let playPause = document.querySelector(".track-play");
let playPauseIcon = document.querySelector(".track-play i");

let nextIcon = document.querySelector(".track-next i");
let prevIcon = document.querySelector(".track-prev i");
let nextT = document.querySelector(".track-next");
let prevT = document.querySelector(".track-prev");

let volumeControl = document.getElementById('volume');

let shuffle = document.querySelector(".track-shuffle");
let repeat = document.querySelector(".track-repeat");

let lastCheckTime = null;

let isPlaying = false;
let isExpand = false;

let updateTimer;

let globalVolume = 0.5;


var overlays = document.querySelectorAll('.track-overlay');
    
overlays.forEach(function(overlay) {
        overlay.addEventListener('click', playPause);
    });

    var playIcons = document.querySelectorAll('.play-btn');
    playIcons.forEach(function(icon) {
        icon.addEventListener('click', playPause);
    });

     // Toggle play/pause functionality
     playPause.addEventListener('click', function() {
        if (curr_track) {
            if (curr_track.paused) {
                playTrack();
            } else {
                pauseTrack();
            }
        }
    });
    
// Function to play the track
function playTrack() {
    if (curr_track && curr_track.paused) {
        curr_track.play();
        playPauseIcon.classList.remove('fa-play');
        playPauseIcon.classList.add('fa-pause');
        var playerImg = document.querySelector('.player-img img');
        playerImg.classList.add('active');
        isPlaying = true;
    }

}

// Function to pause the track
function pauseTrack() {
    if (curr_track && !curr_track.paused) {
        curr_track.pause();
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
        var playerImg = document.querySelector('.player-img img');
        playerImg.classList.remove('active');
        isPlaying = false;
    }
}

    function nextTrack() {
        // Check if repeat mode is enabled
        if (isRepeat) {
            // If repeat mode is enabled, simply reset the current track's playback time to start
            curr_track.currentTime = 0;
            return;
        }
    
        // Proceed to the next track based on shuffle mode
        if (isShuffle) {
            // If shuffle mode is enabled, play the next shuffled track
            track_index = (track_index + 1) % queue_list.length;
        } else {
            // If shuffle mode is not enabled, play the next track in order
            if (track_index < queue_list.length - 1) {
                track_index += 1;
            } else {
                // Stop playback if it's the last track
                stopTrack();
                return;
            }
        }
    
        // Load and play the new track
        loadTrack(track_index);
    }
    

// Go back to the previous track      
function previousTrack(){
    if (track_index > 0) {
        track_index -= 1;
        loadTrack(track_index);
    } else {
        track_index = queue_list.length - 1;
        loadTrack(track_index);
    }
}

    function stopTrack() {
        pauseTrack();
    }

    function shuffleQueue() {
        const index = [];
        const shuffledQueue = [];
        
        for (let i = Math.floor(Math.random() * queue_list.length); index.length < queue_list.length;) {
            if (!index.includes(i)) {
                index.push(i);
                shuffledQueue.push(queue_list[i]);
            }
            i = Math.floor(Math.random() * queue_list.length);
        }
        
        queue_list = shuffledQueue;
        
        if (isPlaying) {
            // Reset track index to start from the beginning
            track_index = 0;
        }
    }
    
let isShuffle = false;
let isRepeat = false;
    
// Shuffle Functionality
shuffle.addEventListener('click', function() {
    isShuffle = !isShuffle; // Toggle shuffle mode
    isRepeat = false; // Disable repeat mode when shuffle is enabled
    repeat.classList.remove('active'); // Remove active class from repeat button

    if (isShuffle) {
        shuffle.classList.add('active'); // Add active class to shuffle button
        shuffleQueue(); // Shuffle the queue
    } else {
        shuffle.classList.remove('active'); // Remove active class from shuffle button
    }
});

let repeatTrackListener;

function repeatFeature() {
    if (curr_track) {
        // Define the repeatTrack function
        const repeatTrack = function() {
            // Reset the current track's playback time to start
            curr_track.currentTime = 0;
            // Play the track
            playTrack();
        };
        // Add an event listener to loop the track when it ends
        curr_track.addEventListener('ended', repeatTrack, false);
        // Store the event listener function in the variable
        repeatTrackListener = repeatTrack;
    }
}

// Repeat Functionality
repeat.addEventListener('click', function() {
    isRepeat = !isRepeat; // Toggle repeat mode
    isShuffle = false; // Disable shuffle if repeat is enabled
    shuffle.classList.remove('active'); 
    repeat.classList.toggle('active'); // Toggle active class on repeat button

    if (isRepeat) {
        repeatFeature();
    } else {
        // Remove the event listener if it exists
        if (repeatTrackListener) {
            curr_track.removeEventListener('ended', repeatTrackListener);
        }
    }
});

// Function to load track information
function loadTrack(track_index) {

    window['track_index'] = track_index; // change global variable value

    // Check if the provided track index is valid
    if (track_index < 0 || track_index >= queue_list.length) {
        return;
    }

    const track = isShuffle ? queue_list[track_index] : queue_list[track_index];

    updateTrackUI(track);

    curr_track.src = "Data/TrackFile/" + queue_list[track_index].TrackFile;
    curr_track.id = queue_list[track_index].TrackID;

    insertHistory(curr_track.id);

    // Reset event listener for "timeupdate" event
    curr_track.removeEventListener('timeupdate', trackTimeUpdateHandler);

    // Add event listener for "timeupdate" event to insert track into trending after 10 seconds
    curr_track.addEventListener('timeupdate', trackTimeUpdateHandler);

    // Add event listener for "canplaythrough" event to ensure the audio is fully loaded before playing
    curr_track.addEventListener('canplaythrough', function() {
        playTrack();
    });

    updateTimer = setInterval(seekUpdate, 0);

    // Enable/disable previous and next buttons based on track index
    updatePlaybackButtons(track_index);

    // Add event listeners for previous and next buttons
    addPlaybackButtonListeners();

    // Set the volume of the track to the global volume
    curr_track.volume = globalVolume;

    // Update the volume icon based on the current volume
    updateVolumeIcon(curr_track.volume);
}

curr_track.addEventListener("ended", function() {
    if (isRepeat) {
        // If repeat mode is active, reset the track to start and play again
        curr_track.currentTime = 0;
        playTrack();
    } else {
        // Check if it is the last track in the queue
        if (track_index === queue_list.length - 1) {
            playPauseIcon.classList.remove('fa-pause');
            playPauseIcon.classList.add('fa-play');
            return;
        } else {
            // Play the next track in the queue
            const nextIndex = (track_index + 1) % queue_list.length;
            loadTrack(nextIndex);
        }
    }
});


// Function to update UI with track information
function updateTrackUI(track) {
    $(".info-container .info .title").text(track.TrackName);
    $(".info-container .info .username").text(track.Username);
    $(".player-img img").attr("src", "Data/TrackImage/" + track.TrackImg);
}

// Function to handle "timeupdate" event for track
function trackTimeUpdateHandler() {
    if (curr_track.currentTime >= 10) {
        insertTrending(curr_track.id);
        curr_track.removeEventListener('timeupdate', trackTimeUpdateHandler);
        trackInsertedIntoTrending = true;
    }
}

// Function to enable/disable previous and next buttons based on track index
function updatePlaybackButtons(track_index) {
    prevT.disabled = (track_index === 0);
    nextT.disabled = (track_index === queue_list.length - 1);
}

// Function to add event listeners for previous and next buttons
function addPlaybackButtonListeners() {
    prevT.removeEventListener('click', previousTrack);
    nextT.removeEventListener('click', nextTrack);

    if (prevT.disabled === false) {
        prevT.addEventListener('click', previousTrack);
    }

    if (nextT.disabled === false) {
        nextT.addEventListener('click', nextTrack);
    }
}

function insertHistory(trackid) {
    $.ajax({
        type: 'GET',
        url: 'History-Update.php',
        data: { trackid: trackid },
        dataType: 'json',
        success: function(response) {
        },
        error: function(xhr, status, error) {
            // Display an error message 
        }
    });
}


    // Function to update seek position
    function seekTo() {
        let seekto = curr_track.duration * (seek_slider.value / 100);
        curr_track.currentTime = seekto;
        playTrack(); // Resume playing after seeking
    }

    // Event listener for the seek slider
    seek_slider.addEventListener('input', function() {
        seekTo();
    });

    
    // Function to update seek position
function seekUpdate() {
    if (!isNaN(curr_track.duration)) {
        let currentMinutes = Math.floor(curr_track.currentTime / 60);
        let currentSeconds = Math.floor(curr_track.currentTime - currentMinutes * 60);

        let seekPosition = curr_track.currentTime * (100 / curr_track.duration);
        seek_slider.value = seekPosition;

        let durationMinutes = Math.floor(curr_track.duration / 60);
        let durationSeconds = Math.floor(curr_track.duration - durationMinutes * 60);

        currentMinutes = currentMinutes < 10 ? "0" + currentMinutes : currentMinutes;
        currentSeconds = currentSeconds < 10 ? "0" + currentSeconds : currentSeconds;
        durationMinutes = durationMinutes < 10 ? "0" + durationMinutes : durationMinutes;
        durationSeconds = durationSeconds < 10 ? "0" + durationSeconds : durationSeconds;

        current_time.textContent = currentMinutes + ":" + currentSeconds;
        total_duration.textContent = durationMinutes + ":" + durationSeconds;
    }
}

    // Function to update volume
    function updateVolume(volume) {
        globalVolume = volume;
        curr_track.volume = globalVolume;
        updateVolumeIcon(globalVolume);
    }

    // Event listener for volume control
    volumeControl.addEventListener('input', function() {
        updateVolume(parseFloat(volumeControl.value));
    });

    // Function to update volume icon based on volume level
    function updateVolumeIcon(volume) {
        const volumeIcon = document.getElementById('volume-icon');
        volumeIcon.className = volume === 0 ? 'fas fa-volume-mute' : (volume < 0.6 ? 'fas fa-volume-down' : 'fas fa-volume-up');
    }

    function addToQueue(tracks) {
        let uniqueTrackIds = new Set(); // Create a Set to store unique TrackIDs
    
        // Iterate through the tracks and add their TrackIDs to the Set
        tracks.forEach(track => {
            uniqueTrackIds.add(track.TrackID);
        });
    
        // Convert the Set back to an array
        let trackIds = Array.from(uniqueTrackIds);
    
        fetchTrackInfo(trackIds);
    }
    
    
    function fetchTrackInfo(trackIds) {
        // Make an AJAX request to fetch track information based on the TrackIDs
        $.ajax({
            type: 'GET',
            url: 'fetchTrackInfo.php', // This should be the endpoint where you fetch track info from the database
            data: { trackIds: trackIds },
            dataType: 'json',
            success: function(response) {
                // Once you have the track information, you can add them to the playlist queue
                addToPlaylistQueue(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching track info:', error);
            }
        });
    }
    
    function addToPlaylistQueue(fetchedTracks) {
        if (!Array.isArray(fetchedTracks)) {
            return;
        }
        
        // Clear the existing content
        const queueList = document.querySelector('.queue-list');
        queueList.innerHTML = '';

        // Copy the fetched tracks to the queue list, avoiding duplicates
        fetchedTracks.forEach(track => {
            if (!queue_list.some(queueTrack => queueTrack.TrackID === track.TrackID)) {
                queue_list.push(track);
            }
        });


        // Load the track if queue list is not empty
        if (queue_list.length > 0) {
            loadTrack(track_index);
        }
    
        // Include the current track with index 0 if it exists
        if (queue_list.length > 0 && track_index === 0) {
            fetchedTracks.unshift(queue_list[0]);
        }
    
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
                }
            });
        });
    }
    
    function loadPlaylistTracks(playlistId) {
        // Make an AJAX request to fetch tracks associated with the playlist
        $.ajax({
            type: 'GET',
            url: 'fetchPlaylistTracks.php', // Change to the endpoint that fetches playlist tracks
            data: { playlistId: playlistId },
            dataType: 'json',
            success: function(response) {
                // Once you have the playlist tracks, you can add them to the playlist queue
                addToPlaylistQueue2(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching playlist tracks:', error);
            }
        });
    }

   function addToPlaylistQueue2(fetchedTracks) {
    if (!Array.isArray(fetchedTracks)) {
        return;
    }
    
    // Clear the existing content
    const queueList = document.querySelector('.queue-list');
    queueList.innerHTML = '';

    // Copy the fetched tracks to the queue list, avoiding duplicates
    fetchedTracks.forEach(track => {
        if (!queue_list.some(queueTrack => queueTrack.TrackID === track.TrackID)) {
            queue_list.push(track);
        }
    });
    

    // Load the track if queue list is not empty
    if (queue_list.length > 0) {
        loadTrack(track_index);
    }

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
    
        // Append the title and individual to the info container
        infoContainer.appendChild(title);
        infoContainer.appendChild(individual);
    
        // Append the image and info container to the track container
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

    function getRecommend() {
        $.ajax({
            type: 'GET',
            url: 'recommendation.php',
            dataType: 'json',
            success: function(response) {
                addToQueue(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching recommended tracks:', error);
            }
        });
    }


    function insertTrending(trackId) {
        // Make an AJAX request to insert the track into the trending table
        $.ajax({
            type: 'POST', 
            url: 'insertTrending.php', 
            data: { trackId: trackId },
            dataType: 'text',
            success: function(response) {
            },
            error: function(xhr, status, error) {
                console.error('Error inserting track into trending:', error);
            }
        });
    }


let recognition;
let executedCommands = [];

try {
    var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.continuous = true; // Enable continuous speech recognition
}
catch(e) {
    console.error(e);
    // $('.no-browser-support').show();
    // $('.app').hide();
}

const CONFIDENCE_THRESHOLD = 0.5; // Adjust this threshold as needed

recognition.onstart = function() { 
    var voiceBtn = document.getElementById("voice-control-btn");
    voiceBtn.style.color="rgb(255, 30, 206)";
}

recognition.onerror = function(event) {
    console.error('Speech recognition error:', event.error);
    
    // Retry logic for network errors
    if (event.error === 'network' && voiceRecognitionToggle.checked) {
        recognition.start(); // Retry recognition if toggle is checked
    }
}

recognition.onresult = function(event) {
    for (let i = event.resultIndex; i < event.results.length; i++) {
        let transcript = event.results[i][0].transcript;
        let confidence = event.results[i][0].confidence;
        
        // Filter out results with low confidence
        if (confidence >= CONFIDENCE_THRESHOLD) {
            processCommand(transcript); // Process recognized command
        }
    }
}

recognition.onend = function(){
    if (voiceRecognitionToggle.checked) {
        recognition.start(); // Restart recognition if toggle is checked
    }
};

// Function to process the recognized command
function processCommand(transcript) {
    const commands = ['play', 'stop', 'next', 'previous', 'shuffle', 'repeat', 'increase', 'decrease'];
    const words = transcript.toLowerCase().split(' '); // Split transcript into individual words

    // Check if any of the words match the predefined commands
    for (let word of words) {
        if (commands.includes(word)) {
            executedCommands.push(word); // Add the executed command to the list

            // Call the corresponding function based on the recognized command
            switch (word) {
                case 'play':
                    playTrack();
                    break;
                case 'stop':
                    pauseTrack();
                    break;
                case 'next':
                    // Check if it's not the last track
                    if (track_index < queue_list.length - 1) {
                        nextTrack();
                    }
                    break;
                case 'previous':
                    // Check if it's not the first track
                    if (track_index > 0) {
                        previousTrack();
                    }
                    break;
                case 'shuffle':
                    shuffleQueue();
                    // Toggle shuffle button
                    isShuffle = !isShuffle;
                    shuffle.classList.toggle('active', isShuffle);
                    // Disable repeat mode when shuffle is enabled
                    isRepeat = false;
                    repeat.classList.remove('active');
                    break;
                case 'repeat':
                    repeatFeature();
                    // Toggle repeat button
                    isRepeat = !isRepeat;
                    repeat.classList.toggle('active', isRepeat);
                    // Disable shuffle if repeat is enabled
                    isShuffle = false;
                    shuffle.classList.remove('active');
                    break;
                case 'increase':
                    increaseVolume();
                    break;
                case 'decrease':
                    decreaseVolume();
                    break;
                default:
                    console.log('Unrecognized command:', word);
            }
            break; // Exit loop after executing the command
        }
    }
}

// Function to increase volume gradually to maximum
function increaseVolume() {
    const newVolume = 1.0; // Set volume directly to maximum
    volumeControl.value = newVolume;
    updateVolume(); // Update volume based on the new value
    updateVolumeIcon(newVolume);
}

// Function to decrease volume gradually to 0.5
function decreaseVolume() {
    const newVolume = 0.5; // Set volume directly to 0.5
    volumeControl.value = newVolume;
    updateVolume(); // Update volume based on the new value
    updateVolumeIcon(newVolume);
}

// Replay executed commands in case of speech recognition error
function replayExecutedCommands() {
    executedCommands.forEach(command => {
        switch (command) {
            case 'play':
                playTrack();
                break;
            case 'stop':
                pauseTrack();
                break;
            case 'next':
                nextTrack();
                break;
            case 'previous':
                previousTrack();
                break;
            case 'shuffle':
                shuffleQueue();
                break;
            case 'repeat':
                repeatFeature();
                break;
            default:
                playTrack();
                break;
        }
    });
}

const voiceRecognitionToggle = document.getElementById("voice-recognition-toggle");

// Add event listener for voice recognition toggle
voiceRecognitionToggle.addEventListener("change", () => {
    if (voiceRecognitionToggle.checked) {
        // Prompt user for microphone access
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(() => {
                // If permission is granted, start voice recognition
                recognition.start();
                alert("Microphone access granted. Voice control activated.");
            })
            .catch((error) => {
                // Uncheck the voice recognition toggle
                voiceRecognitionToggle.checked = false;
                // Show alert message to inform the user
                alert("Microphone access denied. Please allow microphone access and try again.");
            });
    } else {
        // If the toggle is unchecked, stop voice recognition
        recognition.stop();
        var voiceBtn = document.getElementById("voice-control-btn");
        voiceBtn.style.color = "#a5f7ff";
        alert("Voice control deactivated.");
    }
});


// Replay executed commands if speech recognition error occurs
window.addEventListener('error', (event) => {
    if (event.message.includes('Speech recognition error')) {
        replayExecutedCommands();
    }
});


// Function to update the queue list in the DOM
function updateQueueList() {
    const queueListElement = document.querySelector('.queue-list');
    queueListElement.innerHTML = ''; // Clear the existing content
    queue_list.forEach(track => {
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

        // Append the title and individual to the info container
        infoContainer.appendChild(title);
        infoContainer.appendChild(individual);

        // Append the image and info container to the track container
        trackContainer.appendChild(img);
        trackContainer.appendChild(infoContainer);

        // Append the track container to the queue list
        queueListElement.appendChild(trackContainer);
    });
}

