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

    echo '<div class="content" id="content">';
    echo '<div class="about-layout" id="about-layout">
        <div class="about-container1">
            <h1 class="logo-title">About MuziQ</h1>
            <p>We\'re passionate about bringing the joy of music to your fingertips. Our mission is to make your musical
                journey enjoyable and memorable. Join us on this melodious journey as we redefine the way you experience
                and share music. Welcome to a world where every note matters!</p>
            <div class="about-image">
                <img src="Sources/Img/Music.png" />
            </div>
        </div>
        <div class="about-container2">
            <div class="about-row">
                <div class="about-column1">
                    <img src="Sources/Img/Support.png" />
                </div>
                <div class="about-column2">
                    <p>Whether you\'re seeking for any assistance or want to share your thoughts, we\'re here to help.
                        Please feel free to reach out to us by clicking the button
                        below, and we\'ll do our best to respond promptly. </p>
                    <button class="btn-contact" onclick="contact()">Contact Us</button>
                    <h4>Frequently Asked Questions</h4>
                    <p>Discover quick solution and instant answers to common queries in our </p>
                    <p><span onclick="loadPage(\'FAQ\', \'FAQ\', \'FAQ.php\')"">FAQ section</span>.</p>
                    <h4>Social Links</h4>
                    <!-- Links open in new tab -->
                    <a href="https://www.facebook.com/yun.qi.1044/" target="_blank"><img class="social-link" src="Sources/Img/facebook.png" alt="Facebook"></a>
                    <a href="https://www.instagram.com/yunqi.26/" target="_blank"><img class="social-link" src="Sources/Img/ig.png" alt="Instagram"></a>
                    <a href="https://twitter.com/qi_than23212" target="_blank"><img class="social-link" src="Sources/Img/X.png" alt="Twitter / X"></a>
                </div>

            </div>
        </div>
        </div>
    </div>';

    echo '<!-- Dark overlay -->
    <div class="overlay" id="overlay"></div>
                           
   <!-- Pop up window to create new playlist -->
   <div class="contact-con" id="contact-con">
       <form id="contactForm" class="contactForm" action="Contact.php" method="POST" enctype="multipart/form-data">
           <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
           <h2>Your input is valuable to us !</h2>
           <img src="Sources/Img/Share.png" alt="Image of sharing">
           <p><input name="title" class="cont-input" placeholder="Feedback Title" required></p>
           <p><textarea name="feedback" class="feedback-input" rows="4" cols="50" placeholder="Kindly share your feedback here." required></textarea></p>
           <br>
           <input type="submit" class="button-feedback" name="sendFeedback" value="Send">                                        
       </form>
   </div>';

}
?> 
    <script>
    $(document).ready(function() {
        // Handle contact form submission
        $("#contactForm").submit(function(ev) {
            ev.preventDefault(); // Prevent default form submission

            var form = $(this);
            url = form.attr('action');
            url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this), // Use FormData to correctly handle file uploads
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.trim() === 'success') {
                        alert("Feedback sent successfully!");
                        hidePopup();
                        form[0].reset(); // Clear form inputs
                        loadPage('About-us', 'About-us', 'About-us.php');
                    } else {
                        alert("Failed to send feedback. Please try again later.");
                    }
                },
                error: function() {
                    alert("An error occurred while processing your request. Please try again later.");
                }
            });
        });
    });

        function contact() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("contact-con").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("contact-con").style.display = "none";
            document.querySelector(".overlay").style.display = "none";
            document.getElementById("voice-guide").style.display = "none";
        }

    </script>
    
