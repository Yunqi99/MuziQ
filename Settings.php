
<?php
session_start();

if ($loggedin = isset($_COOKIE["webmusicplayer"]) && isset($_SESSION['UserID'])) { // Check if the user is not logged in
    $mysqli = new mysqli("localhost", "root", "", "muziq-test");
    // Check if there's an error in the database connection
    if ($mysqli->connect_errno) {
        echo '<script>alert("Database error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }
        
    $UserID = $_SESSION['UserID'];

    echo '<div class="settings-layout">
    <h1>Settings</h1>
    <div class="settings-container">
    <div class="settings-col1">';
 
    $userquery = "SELECT * FROM user WHERE UserID = $UserID";
    $res = $mysqli->query($userquery);
    if ($res) {
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $maskedPassword = str_repeat('●', strlen($row['UserPassword']));
            echo '<h2>Manage Account</h2>
            <h4>Email</h4>
            <p>'.$row['UserEmail'].'</p>
            <br>
            <h4>Password</h4>
            <p>'.$maskedPassword.'</p>';
        } else{
            echo '<script>alert("No records found.");</script>';
        }
    } else {
        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
    }

    echo '<button onclick="editAccount()">Manage</button>
    
    </div>

    <div class="vl"></div>
        <div class="settings-col2">
            <h2>Account Deactivation</h2>
            <h4>If you delete or deactive your account permanently: </h4>
            <ul>
                <li>You won\'t be able to reactive the account.</li>
                <li>You\'ll no longer be able to use.</li>
                <li>Your tracks, playlist and others content will be erased, won\'t be able to recover.</li>
            <ul>
            <form id="deactivateForm" action="Deactivate.php" method="post">
                <button type="submit">Deactivate</button>
            </form>

                <br>
                <div class="col-about">
                    <h2>About</h2>
                    <div class="col-about-content">
                        <p>Terms of Service</p>
                        <button onclick="popUpTOS()">Read</button>
                    </div>
                    <div class="col-about-content">
                        <p>Privacy Policy</p>
                        <button onclick="popUpPP()">Read</button>
                    </div>
                </div>
            </div>
        </div>
    </div>';

    echo '<div class="overlay" id="overlay"></div>';

     echo '<!-- Pop up window to edit account -->
     <div class="editAcc" id="editAcc">
        <form id="editAccs" action="Settings-Update.php" method="POST" enctype="multipart/form-data">
            <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
            <div class="editAcc-cont">';
                    if ($res = $mysqli->query($userquery)) {
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { 
                                echo '
                                <div class="edit-Acc">
                                    <div class="input-col">
                                        <p><i class="fas fa-envelope" id="icon"></i><input type="email" placeholder="Email" name="email-adress" disabled value="'.$row['UserEmail'].'"></p>
                                    </div>
                                    <div class="input-col">
                                        <p><i class="fas fa-key" id="icon"></i>
                                        <input type="password" placeholder="Password" id="enter-ps" onkeyup="validate();" name="pwd" required
                                         title="Must contain at least 8 characters, including one each of: a number, an uppercase letter, a lowercase letter, and a special character." />
                                        <span id="pwd-requirement"></span>
                                        </p>          
                                    </div>
                                    <div class="input-col">
                                        <p><i class="fas fa-shield-alt" id="icon"></i><input type="password" placeholder="Confirm Password"
                                            name="repeatPwd" id="repeat-ps" required>
                                        <span id="confirmation"></span>
                                        </p>
                                    </div>
                                    <br>
                                    <input type="hidden" name="email" value="'.$row['UserEmail'].'">
                                    <input type="hidden" name="userid" value="' .$row['UserID'] . '">
                                    <div class="btn-editAcc">
                                        <input type="submit" class="button-editAcc" name="updateAcc" value="Update">        
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<script>alert("Something went wrong ! Please try again."); window.history.back();</script>';
                        }
                    } else {
                        echo '<script>alert("Query error: Please contact Muziq platform to solve the issue."); window.history.back();</script>';
                    } 
            echo '</div>                          
        </form>
    </div>'; 

    echo '<!-- Pop up window to read TOS -->
    <div class="popupTOS" id="TOS">
        <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
        <div class="TOS-cont">
            <h2>Terms of Service</h2>
            <h4>1. Acceptance of Terms</h4>
            <p>By using the Muziq web music player ("Service"), you agree to comply with and be bound by these Terms of Service. If you do not agree to these terms, please do not use the Service.</p>
            <br>
            <h4>2. Use of the Service</h4>
            <p>a. Eligibility: You must be at least 13 years old to use the Service. If you are under 13, you may only use the Service under the supervision of a parent or legal guardian.</p>
            <p>b. Account: To use certain features of the Service, you may be required to create a Muziq account. You are responsible for maintaining the confidentiality of your account information and are fully responsible for all activities that occur under your account.</p>
            <br>
            <h4>3. Prohibited Conduct</h4>
            <p>You agree not to engage in any conduct that violates these Terms of Service, including but not limited to: unauthorized access to the Service, interference with the functionality of the Service, and violation of applicable laws.</p>
            <br>
            <h4>4. Termination</h4>
            <p>Muziq reserves the right to suspend or terminate your access to the Service at its sole discretion, with or without notice, for any reason, including if you violate these Terms of Service.</p>
            <br>
            <h4>5. Changes to Terms</h4>
            <p>Muziq reserves the right to modify or replace these Terms of Service at any time. Changes will be effective immediately upon posting. Your continued use of the Service after changes are posted constitutes your acceptance of the revised terms.</p>
            <br>
            <h4>6. Contact Information</h4>
            <p>If you have any questions or concerns regarding these Terms of Service, you may contact us at Muziq@gmail.com .</p>
        </div>
    </div>

     <!-- Pop up window to read Privacy and Policy -->
     <div class="popupPP" id="PP">
        <span id="closeButton" onclick="hidePopup()"><i class="fa fa-close"></i></span>
        <div class="PP-cont">
            <h2>Privacy and Policy</h2>
            <br>
            <p><b>Welcome to Muziq! This Privacy Policy outlines how we collect, use, and protect your personal information when you use our web music player service. By using Muziq, you agree to the terms outlined in this Privacy Policy. If you do not agree with these terms, please refrain from using our services.</b></p>
            <br>
            <h4>Information We Collect</h4>
            <p>1. Account Information</p>
            <p>When you create an account, we may collect personal information such as your email address and password. This information is necessary for user authentication and personalized services.</p>
            <br>
            <p>2. Usage Data</p>
            <p>We collect information about how you interact with Muziq, including the tracks you listen to, playlists you create, and other user activities. This data helps us enhance your user experience and improve our services.</p>
            <br>
            <h4>How We Use Your Information</h4>
            <p><b>1. Personalization</b></p>
            <p>We use the information collected to personalize your Muziq experience, recommend tracks, and create personalized playlists based on your preferences.</p>
            <br>
            <p><b>2. Analytics and Improvement</b></p>
            <p>We analyze aggregated and anonymized data to improve our services, troubleshoot issues, and optimize performance.</p>
            <br>
            <h4>Information Sharing</h4>
            <p>We do not sell, trade, or rent your personal information to third parties.</p>
            <br>
            <h4>Security</h4>
            <p>We implement security measures to protect your personal information. However, no method of transmission over the internet or electronic storage is completely secure. Therefore, we cannot guarantee absolute security.</p>
            <br>
            <h4>Cookies</h4>
            <p>Muziq uses cookies to enhance user experience and collect analytics data.</p>
            <br>
            <h4>Changes to Privacy Policy</h4>
            <p>We reserve the right to update this Privacy Policy. Any changes will be effective immediately upon posting. Continued use of Muziq after changes constitutes acceptance of the revised policy.</p>
            
        </div>
    </div>';
}
else {
    echo '<script>alert("Kindly login to proceed! "); window.location.href = "index.html";</script>';
}
?>
    
    <script>
        function editAccount() {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("editAcc").style.display = "block";
    }

    function hidePopup() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("editAcc").style.display = "none";

        document.getElementById("TOS").style.display = "none";
        document.getElementById("TOS").classList.remove("active");
        document.getElementById("PP").style.display = "none";
        document.getElementById("PP").classList.remove("active");
    }

    function popUpTOS() {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("TOS").style.display = "block";
        document.getElementById("TOS").classList.add("active");
    }

    function popUpPP() {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("PP").style.display = "block";
        document.getElementById("PP").classList.add("active");
    }

        // Function for password checking
        function validate() {
            var password = document.getElementById("enter-ps").value;
            var message = "";

            if (password.length >= 8) {
                // Check for lowercase, uppercase, numbers, and special characters
                var lowercase = /[a-z]/;
                var uppercase = /[A-Z]/;
                var numerals = /[0-9]/;
                var specialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;

                if (lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)) {
                    // Password meets all requirements
                    message = "<i class='fa fa-check'></i>";
                } else {
                    // Password does not meet all requirements
                    message = "At least 1 lowercase, 1 uppercase, 1 number, 1 special character";
                }

            } else {
                message = "At least 8 characters.";
            }
            document.getElementById("pwd-requirement").innerHTML = message;
        }

        document.getElementById("enter-ps").addEventListener("input", validate);

        // Function for validating password matching
        var confirmationPS = function () {
            var enterPs = document.getElementById('enter-ps').value;
            var repeatPs = document.getElementById('repeat-ps').value;
            if (enterPs.trim() !== "") {
                if (enterPs == repeatPs){
                    document.getElementById('confirmation').style.color = 'green';
                    document.getElementById('confirmation').innerHTML = "<i class='fa fa-check'></i>";
                } else {
                document.getElementById('confirmation').style.color = 'red';
                document.getElementById('confirmation').innerHTML = 'Password not matched.';
                }
            }
        }
            document.getElementById('repeat-ps').addEventListener('keyup', confirmationPS);


        // Function for validating the form
        function validateForm() {
            var password = document.getElementById("enter-ps").value;
            var confirmPassword = document.getElementById("repeat-ps").value;

            var lowercase = /[a-z]/;
            var uppercase = /[A-Z]/;
            var numerals = /[0-9]/;
            var specialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;

            if (password.length >= 8 && password == confirmPassword && lowercase.test(password) && uppercase.test(password) && numerals.test(password) && specialChar.test(password)){
                return true;
            }
            else {
                alert("Please fill in all required fields correctly before submitting.");
                return false;
            }
        }

        document.getElementById('deactivateForm').addEventListener('submit', function(event) {
        if (!confirm('Are you sure you want to deactivate your account?')) {
            event.preventDefault(); // Prevent the form from being submitted
        }
    });

    $(document).ready(function() {

    // Handle user dashboard form submission
    $("#editAccs").submit(function(ev) {
        ev.preventDefault(); // Prevent default form submission
        if (validateForm()) {
        var form = $(this);
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
                    alert("Account updated successfully!");
                    hidePopup();
                    form[0].reset(); // Clear form inputs
                    loadPage('Settings', 'Settings', 'Settings.php');
                } else {
                    alert("Failed to update account. Please try again later.");
                }
            },
            error: function() {
                alert("An error occurred while processing your request. Please try again later.");
            }
        });
    }
    });

});

</script>



