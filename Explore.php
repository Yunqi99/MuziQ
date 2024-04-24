

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
        
    $UserID = $_SESSION['UserID']; 

    echo '<div class="content" id="content">';
    echo '<div class="explore-layout" id="explore-layout">
        <div class="explore-container">
            <div class="explore-title">
            <h1>Moods and Genre</h1>
                <div class="explore-row">
                    <button onclick="loadMG(1)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Classical.png\'); background-size: 400px;">
                            <h2>Classical</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(2)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/C-Pop.png\'); background-size: 240px;">
                            <h2>C-Pop</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(3)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Electronic.png\'); background-size: 220px;">
                            <h2>Electronic</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(4)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Hindi.png\'); background-size: 300px;">
                            <h2>Hindi</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(5)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Jazz.png\'); background-size: 330px;">
                            <h2>Jazz</h2>
                        </div>
                    </button>
                </div>
                <div class="explore-row">
                    <button onclick="loadMG(6)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/J-Pop.png\'); background-size: 330px;">
                            <h2>J-Pop</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(7)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/K-Pop.png\'); background-size: 300px;">
                            <h2>K-Pop</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(8)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Lo-fi.png\'); background-size: 300px;">
                            <h2>Lo-fi</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(9)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Malay.png\'); background-size: 270px;">
                            <h2>Malay</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(10)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Pop.png\'); background-size: 350px;">
                            <h2>Pop</h2>
                        </div>
                    </button>
                </div>
                <div class="explore-row">
                    <button onclick="loadMG(11)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Rock.png\'); background-size: 300px;">
                            <h2>Rock</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(12)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/R&B.png\'); background-size: 210px;">
                            <h2>R & B</h2>
                        </div>
                    </button>
                    <button onclick="loadMG(13)">
                        <div class="explore-column" style="background-image: url(\'Sources/Genre/Others.png\'); background-size: 290px; ">
                            <h2>Others</h2>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>';
    echo '</div>';
}
?>
