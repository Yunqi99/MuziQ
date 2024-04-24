
<body>
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

    echo '<div class="FAQ-layout">
        <h1>Frequently Asked Questions</h1>
        <div class="FAQ-container">
            <div class="FAQ-row">';
                $FAQquery = "SELECT * FROM FAQ";
                $res = $mysqli->query($FAQquery);
                if ($res = $mysqli->query($FAQquery)) {
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) { 
                            echo '<div class="FAQ-column">
                            <button class="btn-FAQ" onclick="changeContent(this)">
                                <div class="FAQ-Q">
                                    <h2>'.$row['Question'].'</h2>
                                </div>
                                <div class="FAQ-A">
                                    <h3>'.$row['Answer'].'</h3>
                                </div>
                            </button>
                        </div>';
                        }
                    } else{
                        echo '<script>alert("No FAQ found. Please contact Muziq platform to solve the issue"); window.history.back();</script>';
                    }
                }else {
                    echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                }
                
                
        echo '</div>
        </div>
    </div>';
    }
    ?>

    <script>
    function changeContent(button) {
        const FAQQ = button.querySelector('.FAQ-Q');
        const FAQA = button.querySelector('.FAQ-A');

        if (FAQQ.style.display === "none") {
            FAQQ.style.display = "block";
            FAQA.style.display = "none";
        } else {
            FAQQ.style.display = "none";
            FAQA.style.display = "block";
        }
    }
    </script>
