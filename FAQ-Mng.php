
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MuziQ</title>
    <link rel="icon" href="Sources/Img/MuziQ.png" type="image/png">
    <link rel="stylesheet" href="style-admin.css">
    <!-- This line imports the jQuery library from Google's servers for use in the web page -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- This line links to the Material Icons library from Google Fonts for use in the web page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>
<body>

<?php

session_start();
$loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['AdminID']);

if (!$loggedin) { // Check if the user is not logged in
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
} else {
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
        
    echo '<!-- Top Navigation Bar -->
    <div class="wrapper">
        <nav class="tabs">
            <div class="selector"></div>
            <a href="Homepage-admin.php"><i class="fa fa-home"></i>Home</a>
            <a href="Validation-List.php"><i class="fa fa-pencil"></i>Validation</a>
            <a href="Insights.php"><i class="fa fa-pie-chart"></i>Insights</a>
        </nav>
        <div class="logo">
            <a href="Homepage-admin.php">MuziQ</a>
        </div> 

        
        <div class="user-menu">
        <div class="user-icon" onclick="menuToggle();">';
        $AdminID = $_SESSION['AdminID'];
        $adminquery = "SELECT * FROM `admin` WHERE AdminID = $AdminID";
        $res = $mysqli->query($adminquery);
        if ($res) {
            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                echo '<img src="Data/Admin/'.$row['AdminImg'].'" />';
            } else{
                echo '<script>alert("No records found.");</script>';
            }
        }else {
            echo '<script>alert("Query error: Please contact Muziq platform to solve the issue.");</script>';
        }
        echo '</div>
        <div class="user-dropdown"> 
            <ul>
            <li><a href="Admin-Dashboard.php"><i class="fas fa-user-circle"></i>My Profile</a></li><hr>
            <li><a href="Settings-Admin.php"><i class="fa fa-gear"></i>Settings</a></li><hr>
            <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</div>';

    echo '<div class="admin-layout">
    <div class="admin-cont">
        <div class="bar-row">
            <h1>LIST OF FAQS</h1>
            <div class="bar-cont">
                <button class="btn-create-new" onclick="createNew() "><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <br>
        <div class="track-table-container">
            <table class="track-table">
                <thead>
                    <tr class="title-row">
                        <th class="number">#</th>
                        <th class>FAQ Question </th>
                        <th class="FAQ">FAQ Answer</th>
                        <th class="action"></th>
                    </tr>
                </thead>
                <tbody>';
                $query1 = "SELECT * FROM FAQ";
                    $res = $mysqli->query($query1);
                    if ($res) {
                        $trackNumber = 1;
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo '<tr>
                                    <td class="number">'.$trackNumber.'</td>
                                    <td class="FAQ">' . $row['Question'] . '</td>
                                    <td class="FAQA">' . $row['Answer'] . '</td>
                                    <td class="action">
                                        <button onclick="editFAQ('. $row['FAQID'] .')" class="btn-track-info"><i class="fas fa-pen"></i></button>
                                        <a href="FAQ-Delete.php?id='. $row['FAQID'] .'" onclick="return confirm(\'Are you sure to delete?\');" class="btn-track-info"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>';
                                $trackNumber++;
                            }
                        }
                    }
                echo '</tbody>
            </table>
        </div>
    </div>
</div>';

    echo '<!-- Dark overlay -->
    <div class="overlay" id="overlay"></div>
                            
    <!-- Pop up window to create new FAQ -->
    <div class="FAQ-con" id="newFAQ">
        <form id="FAQForm" class="FAQForm" action="FAQ-Upload.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <h3>Question : </h3>
            <p><textarea name="question" class="FAQ-input" rows="4" cols="50" placeholder="Write question for FAQ." required></textarea></p>
            <br>
            <h3>Answer : </h3>
            <p><textarea name="answer" class="FAQ-input" rows="5" cols="50" placeholder="Write answer for FAQ." required></textarea></p>
            <br>
            <input type="submit" class="button-FAQ" name="createFAQ" value="Create">                          
        </form>
    </div>
    
    <!-- Pop up window to edit FAQ -->
    <div class="FAQ-con" id="editFAQ">
        <form id="FAQForm" class="FAQForm" action="FAQ-Update.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
                <h3>Question : </h3>
                <p><textarea name="question" id="question" class="FAQ-input" rows="4" cols="50" placeholder="Write question for FAQ." required></textarea></p>
                <br>
                <h3>Answer : </h3>
                <p><textarea name="answer" id="answer" class="FAQ-input" rows="5" cols="50" placeholder="Write answer for FAQ." required></textarea></p> 
                <br>
            <input type="hidden" id="faqid" name="faqid">
            <input type="submit" class="button-FAQ" name="editFAQ" value="Update">                          
        </form>
    </div>';
}
?>
    <script src="./script.js"></script>
    <script>
        function createNew() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("newFAQ").style.display = "block";
        }

        function editFAQ(id) {
            // Show overlay and edit FAQ popup
            document.getElementById("overlay").style.display = "block";
            document.getElementById("editFAQ").style.display = "block";

            $.ajax({
            type: 'GET',
            url: 'FAQ-Edit.php',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                $("#question").val(response.question);
                $("#answer").val(response.answer);
                $("#faqid").val(id);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
        }


        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("newFAQ").style.display = "none";
            document.getElementById("editFAQ").style.display = "none";
        }

    </script>
</body>
</html>