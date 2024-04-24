
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MuziQ</title>
    <link rel="icon" href="Sources/Img/MuziQ.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <!-- This line imports the jQuery library from Google's servers for use in the web page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- This line links to the Material Icons library from Google Fonts for use in the web page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>
<body>

<?php

session_start();

$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID']);

if (!$loggedin) { // Check if the user is not logged in
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
} else {
    $UserID = $_SESSION['UserID'];

    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

    echo '
    <!-- Top Navigation Bar -->
    <div class="top-nav-bar" id="nav-bar">
    <div class="wrapper">
    <nav class="tabs">
        <span onclick="loadPage(\'Home\', \'Home\', \'Home.php\')" id="home"><i class="fa fa-home"></i>Home</span>
        <span onclick="loadPage(\'About-Us\', \'About Us\', \'About-us.php\')" id="about"><i class="fas fa-users"></i>About Us</span>
        <span onclick="loadPage(\'Explore\', \'Explore\', \'Explore.php\')" id="explore"><i class="fas fa-bolt"></i>Explore</span>
    </nav>
        <div class="logo">
            <span onclick="loadPage(\'Home\', \'Home\', \'Home.php\')">MuziQ</span>
        </div> 
        <div class="search-box">
            <input id="searchInput" type="text" placeholder="Search here" required/>
            <i class="fa fa-search" id="searchIcon"></i>
        </div>
        <div class="translator">
            <div id="google_translate_element"></div>
        </div>
        <div class="user-menu">
            <div class="user-icon" onclick="menuToggle();">';
            
            $userquery = "SELECT * FROM user WHERE UserID = $UserID ";
            $res = $mysqli->query($userquery);
            if ($res) {
                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    echo '<img src="Data/User/'.$row['UserImage'].'" />';
                }else{
                    echo '<script>alert("No records found."); window.location.href = "Index.html"</script>';
                }
            }else {
                echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");</script>';
            }
            echo '</div>
            <div class="user-dropdown"> 
                <ul>
                    <li><span onclick="loadPage(\'User-Dashboard\', \'User Dashboard\', \'User-Dashboard.php\')"><i class="fas fa-user-circle"></i>My Profile</span></li><hr>
                    <li><span onclick="loadPage(\'History\', \'History\', \'History.php\')"><i class="fa fa-history"></i>History</span></li><hr>
                    <li><span onclick="loadPage(\'Settings\', \'Settings\', \'Settings.php\')"><i class="fa fa-gear"></i>Settings</a></li><hr>
                    <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    </div>';

    echo '<div class="content" id="content">
    </div>';            
          
    echo '<div class="control-layout" id="control-layout">
    <div class="control-row">
        <!-- Track Playback Control -->
        <div class="display-control">
        <div class="bottom-control" id="bottom-control">

            <!-- Track Image -->
            <div class="player-img" id="player-img">
                <img src="Sources/Img/music.jpg"/>
            </div>

            <div class="info-container">
                <div class="info">
                    <p class="title" id="title"> ---- </p>
                    <p class="username" id="username"> --- </p>
                </div>
            </div>
            <div class="player-control" id="player-control">
                <div class="control" id="control">
                    <div class="track-progress">
                        <!-- Song duration -->
                        <div class="duration">
                            <span class="current-time">-- : --</span>
                            <input type="range" class="duration-slider" 
                                    min="0" max="100" value="0"/>
                            <span class="duration-time">-- : --</span>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="buttons">
                        <button class="track-shuffle">
                            <i class="fas fa-random"></i>
                        </button>
                        <button class="track-prev">
                            <i class="fas fa-step-backward"></i>
                        </button>
                        <button class="track-play">
                                <i class="fas fa-play"></i>
                            </button>
                        <button class="track-next">
                            <i class="fas fa-step-forward"></i>
                        </button>
                        <button class="track-repeat">
                            <i class="fas fa-undo-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="voice-control-btn">
                    <i id="voice-control-btn" class="fas fa-microphone" onclick="toggleVoice()"></i></i>
                </div>
                <!-- Volume Adjustment-->
                <div class="volume">
                    <i class="fas fa-volume-down" id="volume-icon"></i>
                    <input type="range" id="volume"  min="0" max="1" step="0.01" value="0.5" />
                </div>
                <div class="expand" onclick="showControl()">
                    <i id="expand" class="fas fa-expand"></i>
                </div>
            </div>
        </div>

        <div class="control-col2" id="control-col2">
            <h2>Playlist</h2>
            <div class="queue-scrollbar">
                <div class="queue-list">
                    
                </div>
            </div>
        </div>
        </div>
    </div>
</div>';

echo '<div class="overlay"></div>';

echo '<!-- Pop up window to view voice control guidance -->
 <div class="voice-guide" id="voice-guide">
    <span id="closeButton" onclick="hide()"><i class="fa fa-close"></i></span>
    <div class="voice-row">
        <div class="guide-cont">
        <h2>Voice Control</h2>
        <div class="voice-mic">
            <i class="fas fa-microphone"></i>
            <label class="switch">
                <input type="checkbox" id="voice-recognition-toggle">
                <span class="slider round"></span>
            </label>
        </div>
        <p>Voice control allows you to command playback of your music.<br> Below are the available commands:</p>
        <ul>
            <li><strong>Play :</strong> Start playing the current track.</li>
            <li><strong>Stop :</strong> Stop the playback of the current track.</li>
            <li><strong>Next :</strong> Skip to the next track in the playlist.</li>
            <li><strong>Previous :</strong> Go back to the previous track in the playlist.</li>
            <li><strong>Shuffle :</strong> Shuffle the order of tracks in the playlist.</li>
            <li><strong>Repeat :</strong> Repeat the current track.</li>
            <li><strong>Increase :</strong> Raise the volume level.</li>
            <li><strong>Decrease :</strong> Lower the volume level.</li>
        </ul>
        </div>
    </div>
</div>';
}
?>
    <!-- <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> -->
    <script src="./script.js"></script>

    <script>
    function hide() {
        document.querySelector(".overlay").style.display = "none";
        document.getElementById("voice-guide").style.display = "none";
    }

    function toggleVoice() {
        document.querySelector(".overlay").style.display = "block";
        document.getElementById("voice-guide").style.display = "block";
    }


    function showControl() {
        layout = document.getElementById("content") ;
        layout.style.display = layout.style.display === "none" ? "block" : "none";

        navbar = document.getElementById("nav-bar") ;
        navbar.style.display = navbar.style.display === "none" ? "block" : "none";
        
        var controlLayout = document.getElementById("control-layout");
        controlLayout.classList.toggle("active");

        var controlRow = document.querySelector(".control-row");
        controlRow.classList.toggle("active");

        var playerControl= document.querySelector(".player-control");
        playerControl.classList.toggle("active");

        var img = document.getElementById("player-img");
        img.classList.toggle("active");

        var expandIcon = document.getElementById("expand");
        if (expandIcon.classList.contains("fa-expand")) {
            expandIcon.classList.remove("fa-expand");
            expandIcon.classList.add("fa-compress");
        } else {
            expandIcon.classList.remove("fa-compress");
            expandIcon.classList.add("fa-expand");
        }

    }

    // Function to setup event listeners for track clicks and playback controls
    function setupEventListeners() {

        // Event listener for track clicks and play buttons
        document.querySelectorAll('.column-container').forEach(element => {
        element.addEventListener('click', function(event) {
        // Reset track index to 0 when a new track is clicked
        track_index = 0;
        
        // Get track information
        var trackInfo = element.getAttribute('data-track');
        if (trackInfo) {
            const trackDetails = JSON.parse(trackInfo);
                
            // Clearing the queue list before fetching recommended tracks
            queue_list = [];
                
            // Add the current track to the queue list
            queue_list.push({
                TrackID: trackDetails.trackid,
                TrackName: trackDetails.name,
                Username: trackDetails.individual,
                TrackImg: trackDetails.img,
                TrackFile: trackDetails.path
            });

            isShuffle = false;
            isRepeat = false;
            shuffle.classList.remove('active');
            repeat.classList.remove('active');

            // Pause the current track (if any)
            pauseTrack();
            getRecommend();

        }
            });

            // Prevent the click event from bubbling up to the parent .column-container
            const anchorTags = element.querySelectorAll('button');
            anchorTags.forEach(anchorTag => {
                anchorTag.addEventListener('click', event => {
                    event.stopPropagation(); // Prevent event propagation
                });
            });
        });
    }


    // Mapping between page names and page URLs
    const pageUrls = {
        'Home': 'Home.php',
        'About-Us': 'About-us.php',
        'Explore': 'Explore.php',
        'User-Dashboard': 'User-Dashboard.php',
        'History': 'History.php',
        'individual': 'individual.php',
        'Track': 'Track.php',
        'Moods-Genre': 'Moods-Genre.php',
        'Playlist': 'Playlist.php',
        'Playlist-User': 'Playlist-P.php',
        'Settings': 'Settings.php',
        'FAQ': 'FAQ.php'
    };

    // Function to initialize the page
    function initializePage() {
        // Load the initial page based on the URL
        const urlParams = new URLSearchParams(window.location.search);
        const pageName = urlParams.get('page');

        if (pageName && pageUrls.hasOwnProperty(pageName)) {
            if (pageName === 'individual') {
                // If individual page, check for individual ID
                const individualId = urlParams.get('id');
                if (individualId) {
                    loadIndividual(individualId);
                } else {
                    // If individual ID is missing, load default individual page
                    loadPage(pageName, pageName, pageUrls[pageName], true);
                }
            } else if (pageName === 'Track') {
                const trackId = urlParams.get('id');
                if (trackId) {
                    loadMusic(trackId);
                } else {
                    // If track ID is missing, load default track page
                    loadPage(pageName, pageName, pageUrls[pageName], true);
                }
            } else if (pageName === 'Moods-Genre') {
                const mgId = urlParams.get('id');
                if (mgId) {
                    loadMG(mgId);
                } else {
                    // If track ID is missing, load default track page
                    loadPage(pageName, pageName, pageUrls[pageName], true);
                }
            } else if (pageName === 'Playlist') {
                const playlistId = urlParams.get('id');
                if (playlistId) {
                    loadPlaylist(playlistId);
                } else {
                    // If track ID is missing, load default track page
                    loadPage(pageName, pageName, pageUrls[pageName], true);
                }
            } else if (pageName === 'Playlist-User') {
                const playlistId = urlParams.get('id');
                if (playlistId) {
                    loadPlaylistUser(playlistId);
                } else {
                    // If track ID is missing, load default track page
                    loadPage(pageName, pageName, pageUrls[pageName], true);
                }
            } else {
                // Load other pages normally
                const pageUrl = pageUrls[pageName];
                loadPage(pageName, pageName, pageUrl, true);
            }
        } else {
            // Default to Home page if no page name is specified
            loadPage('Home', 'Home', 'Home.php', true);
        }
        loadTrackScript();
    }

    // Function to load the page content using AJAX
    function loadPage(pageName, title, url, loadScripts = true) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const navLinks = document.querySelectorAll('.tabs span');
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });
                setActiveNavigation(pageName);
                // Update the content to show the page
                $('#content').html(data);
                if (loadScripts) {
                    loadPageScripts(pageName);
                }
                loadTrackScript();
                setupEventListeners(); // Re-setup event listeners after content update
                if (pageName === 'Home') {
                    initializeCarousel();
                }
            })
            .catch(error => {
                console.error('Error loading page:', error);
            });

        // Update the URL and push state
        const newUrl = 'MuziQ.php?page=' + pageName.replace(/\s/g, '-'); // Convert spaces to dashes
        history.pushState({ page: pageName }, title, newUrl);
    }

    function setActiveNavigation(pageName) {
        // Check if the control layout is active, if yes, hide it
        const controlLayout = document.getElementById("control-layout");
        if (controlLayout.classList.contains("active")) {
            showControl(); // Hide the control layout
        }

        // Loop through the navigation links and set the active state based on the current page
        const navLinks = document.querySelectorAll('.tabs span');
        navLinks.forEach(link => {
            const linkPage = link.getAttribute('onclick'); // Get the onclick attribute value
            const match = /loadPage\('([^']+)'/g.exec(linkPage); // Extract page name from onclick attribute
            if (match) {
            const linkPageName = match[1];
            if (linkPageName === pageName && ['Home', 'About-Us', 'Explore'].includes(pageName)) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        }
        });
    }


    function loadSearch(searchQuery) {
        const url = 'Search-Result.php?searchQuery=' + encodeURIComponent(searchQuery);
        
        fetch(url)
            .then(response => response.text())
            .then(data => {
                // Update the content to show the search results
                $('#content').html(data);
                setActiveNavigation();
                setupEventListeners(); // Re-setup event listeners after content update
            })
            .catch(error => {
                console.error("Error:", error);
            });

        // Update the URL and push state
        const newUrl = 'MuziQ.php?page=Search&searchQuery=' + encodeURIComponent(searchQuery);
        history.pushState({ page: 'Search', searchQuery: searchQuery }, 'Search Results', newUrl);
    }

    // Event listener for search button click
    document.getElementById("searchIcon").addEventListener("click", function() {
        var searchQuery = document.getElementById("searchInput").value.trim(); // Trim whitespace

        // Check if the search query is not empty
        if (searchQuery) {
            // Proceed with the search
            loadSearch(searchQuery);
        } else {
            // Display a message or handle the case where the search query is empty
            console.log("Search query is empty. Please enter a valid search query.");
        }
    });


    // Event listener for back button
    window.addEventListener('popstate', function(event) {
        var state = event.state;
        if (state) {
            // Load the specified page content directly
            if (state.page === 'individual') {
                loadIndividual(state.id);
            } else if (state.page === 'Track') {
                loadMusic(state.id);
            } else if (state.page === 'Moods-Genre') {
                loadMG(state.id);
            } else if (state.page === 'Playlist') {
                loadPlaylist(state.id);
            } else if (state.page === 'Playlist-User') {
                loadPlaylistUser(state.id);
            } else if (state.page === 'Search') {
                loadSearch(state.searchQuery);
            } else {
                loadPage(state.page, state.page, pageUrls[state.page], true);
            }
        }
    });

    // Function to check if a script is already loaded
    function isScriptLoaded(scriptName) {
        return document.querySelector('script[src="' + scriptName + '"]') !== null;
    }

    // Function to load scripts specific to each page
    function loadPageScripts(pageName) {
        // Define the scripts for each page
        const scripts = {
            'Home': ['track.js'],
            'About-Us': [], 
            'Explore': [],
            'User-Dashboard': ['UD-script.js'],
            'individual':[],
            'Track':[],
            'Playlist':[]
        };

        // Load scripts for the current page
        const pageScripts = scripts[pageName];
        if (pageScripts) {
            pageScripts.forEach(script => {
                // Check if the script is already loaded
                if (!isScriptLoaded(script)) {
                    const scriptElement = document.createElement('script');
                    scriptElement.src = script;
                    document.body.appendChild(scriptElement);
                }
            });
        }
    }

    // Function to load individual page using AJAX
    function loadIndividual(individualId) {
        $.ajax({
            url: 'individual.php',
            type: 'POST',
            dataType: 'json',
            data: { id: individualId },
            success: function(response) {
                // Update the URL without reloading the page
                var newUrl = 'MuziQ.php?page=individual&id=' + individualId; // Construct URL with individual ID
                history.pushState({ page: 'individual', id: individualId }, null, newUrl);
                // Update the content of your SPA with the individual data
                $('#content').html(response.html);
                setupEventListeners();
                setActiveNavigation("individual");
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
            }
        });
        // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
        return false;
    }

    // Function to load music page using AJAX
    function loadMusic(trackId) {
        $.ajax({
            url: 'Track.php',
            type: 'POST',
            dataType: 'json',
            data: { id: trackId},
            success: function(response) {
                // Update the URL without reloading the page
                var newUrl = 'MuziQ.php?page=Track&id=' + trackId; // Construct URL with track ID
                history.pushState({ page: 'Track', id: trackId }, null, newUrl);
                // Update the content of your SPA with the track data
                $('#content').html(response.html);
                loadExternalScript('feature-track.js', 'addIntoPlaylist.js');
                setupEventListeners();
                setActiveNavigation("Track");
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
            }
        });
        // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
        return false;
    }

    // Function to load playlist page using AJAX
    function loadPlaylist(playlistId) {
        loadTrackScript();
        $.ajax({
            url: 'Playlist.php',
            type: 'POST',
            // dataType: 'json',
            data: { id: playlistId},
            success: function(response) {
                // Update the URL without reloading the page
                var newUrl = 'MuziQ.php?page=Playlist&id=' + playlistId; // Construct URL with playlist ID
                history.pushState({ page: 'Playlist', id: playlistId }, null, newUrl);
                // Update the content of your SPA with the playlist data
                $('#content').html(response);

                loadExternalScript('feature-playlist.js');
                setActiveNavigation("Playlist");
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
            }
        });
        // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
        return false;
    }

    // Function to load playlist page using AJAX
    function loadPlaylistUser(playlistId) {
        loadTrackScript();
        $.ajax({
            url: 'Playlist-P.php',
            type: 'POST',
            // dataType: 'json',
            data: { id: playlistId},
            success: function(response) {
                // Update the URL without reloading the page
                var newUrl = 'MuziQ.php?page=Playlist-User&id=' + playlistId; // Construct URL with playlist ID
                history.pushState({ page: 'Playlist-User', id: playlistId }, null, newUrl);

                // Update the content of your SPA with the playlist data
                $('#content').html(response);
                loadExternalScript('feature-playlist-p.js');
                setActiveNavigation("Playlist-P");

            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
            }
        });
        // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
        return false;
    }

    // Function to load external JavaScript file dynamically
    function loadExternalScript(src) {
        const scriptElement = document.createElement('script');
        scriptElement.src = src;
        document.head.appendChild(scriptElement);
    }

    // Function to load individual page using AJAX
    function loadMG(mgId) {
        $.ajax({
            url: 'Moods-Genre.php',
            type: 'POST',
            dataType: 'json',
            data: { id: mgId },
            success: function(response) {
                // Update the URL without reloading the page
                var newUrl = 'MuziQ.php?page=Moods-Genre&id=' + mgId; // Construct URL with Moods-Genre ID
                history.pushState({ page: 'Moods-Genre', id: mgId }, null, newUrl);
                // Update the content of your SPA with the Moods-Genre data
                $('#content').html(response.html);
                setActiveNavigation("Moods-Genre");
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
            }
        });
        // Prevent the default behavior of the <a> tag (i.e., prevent navigation)
        return false;
    }


    // Flags to track whether scripts are already loaded
    let trackScriptLoaded = false;

    // Function to load track.js script
    function loadTrackScript() {

        if (!trackScriptLoaded) {
            var trackScript = document.createElement('script');
            trackScript.src = 'track.js';
            document.body.appendChild(trackScript);
            trackScriptLoaded = true;
        }
    }

    // Call initializePage when DOM is ready
    document.addEventListener('DOMContentLoaded', initializePage);
</script>


<script type="text/javascript">
   function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: "en"}, "google_translate_element");
        
        // Prevent translation of specific elements after Google Translate initializes
        preventTranslation();
      }
      
      function preventTranslation() {
        // Select elements containing text that should not be translated
        var elementsToExclude = document.querySelectorAll('.logo', 'title');
        
        // Loop through the selected elements and prevent translation
        elementsToExclude.forEach(function(element) {
          element.setAttribute('translate', 'no'); // Prevent translation of the element's content
        });
    }

    // Function to initialize the carousel
function initializeCarousel() {
    const next = document.querySelector('.carousel-container .btn-next');
    const previous = document.querySelector('.carousel-container .btn-prev');
    const carouselRow = document.querySelector('.carousel-container .carousel-row');
    const carouselWidth1 = document.querySelector('.carousel-container').offsetWidth;

    next.addEventListener('click', () => {
        carouselRow.style.transform = `translateX(-${carouselWidth1}px)`;
    });
    
    previous.addEventListener('click', () => {
        carouselRow.style.transform = `translateX(0)`;
    });

    // Carousel navigation for the second carousel
    const next2 = document.querySelector('.carousel-container2 .btn-next');
    const previous2 = document.querySelector('.carousel-container2 .btn-prev');
    const carouselRow2 = document.querySelector('.carousel-container2 .carousel-row');
    const carouselWidth2 = document.querySelector('.carousel-container2').offsetWidth;

    next2.addEventListener('click', () => {
        carouselRow2.style.transform = `translateX(-${carouselWidth2}px)`;
    });
    
    previous2.addEventListener('click', () => {
        carouselRow2.style.transform = `translateX(0)`;
    });
}

</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
</body>
</html>