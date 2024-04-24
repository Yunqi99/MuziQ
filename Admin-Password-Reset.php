<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MuziQ</title>
    <link rel="icon" href="Sources/Img/MuziQ.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>

<?php
    $con = mysqli_connect("localhost", "root", "", "muziq-test");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die();
    }

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $error = "";

    if (isset($_GET["tokenReset"]) && isset($_GET["email"])
        && isset($_GET["action"]) && ($_GET["action"] == "reset")
        && !isset($_POST["action"])
    ) {
        $tokenReset = $_GET["tokenReset"];
        $email = $_GET["email"];
        $curDate = date("Y-m-d H:i:s");
        $query = mysqli_query($con, "SELECT * FROM password_reset_admin WHERE `Key`='$tokenReset' and `Email`='$email';");
        $row = mysqli_num_rows($query);

        if ($row == 0) {
            $error .= '<h2>Invalid Link</h2>
                <p>The link is invalid/expired. Either you did not copy the correct link from the email, 
                or you have already used the key, in which case it is deactivated.</p>
                <p><a href="Admin-Password-Forgot.php">Click here</a> to reset password.</p>';
        } else {
            $row = mysqli_fetch_assoc($query);
            $expiration = $row['ExpDate'];

            if ($expiration >= $curDate) {
?>

<div class="ps-layout">
    <div class="ps-container">
        <h1 class="logo-text">MuziQ</h1><br>
        <h1 class="title">Admin Reset Password</h1>
        <form method="post" action="" name="reset" onsubmit="return validateForm()">
            <input type="hidden" name="action" value="update"/>  
            <div class="ps-input-col">
                <div class="input-col">
                    <p>
                        <i class="fas fa-key" id="icon"></i>
                        <input type="password" placeholder="Password" id="enter-ps" onkeyup="validate();" name="pwd" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8}$"
                            title="Must contain at least 8 characters, including one each of: a number, an uppercase letter, a lowercase letter, and a special character." />
                        <span id="pwd-requirement"></span>
                    </p> 
                </div>
                <div class="input-col">
                    <p>
                        <i class="fas fa-shield-alt" id="icon"></i><input type="password" placeholder="Confirm Password"
                        name="pwd_confirmation" id="repeat-ps" required>
                        <span id="confirmation"></span>
                    </p>
                </div>
            </div>
            <br>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <input type="submit" class="button-send" value="Reset Password" name="submit">
        </form>
    </div>
</div>

<?php
            } else {
                $error .= "<script>alert('The link is expired. You are trying to use the expired link which is valid only 24 hours (1 day after request).'); history.back();</script>";
            }
        }
        if ($error != "") {
            echo "<div class='error'>" . $error . "</div><br />";
        }
    }

    if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "update")) {
        $error = "";
        $pwd1 = $_POST["pwd"];
        $pwd2 = $_POST["pwd_confirmation"];
        $email = $_POST["email"];
        $curDate = date("Y-m-d H:i:s");

        if ($error != "") {
            echo "<div class='error'>" . $error . "</div><br />";
        } else {
            // Update the admin password and delete the reset entry
            mysqli_query($con,
                "UPDATE `admin` SET AdminPassword='$pwd2', Trn_date='$curDate' 
                WHERE AdminEmail='$email';"
            );

            mysqli_query($con, "DELETE FROM password_reset_admin WHERE Email='$email';");

            echo '<script>
                if (confirm("Your password has been successfully updated. Click OK to proceed to login and access your account.")) {
                    window.location.href = "index.html";
                }
            </script>';
        }
    }
?>

<script src="./login-script.js"></script>

</body>

</html>
