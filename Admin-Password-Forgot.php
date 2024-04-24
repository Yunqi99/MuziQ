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
  <!-- This line links to the Material Icons library from Google Fonts for use in the web page -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
  <div class="ps-layout">
    <div class="ps-container">
      <h1 class="logo-text">MuziQ</h1>
      <h1 class="title">Admin</h1>
      <h1 class="title">Forgot Password</h1>

      <form method="post" action="" name="reset" enctype=" multipart/form-data">
        <div class="ps-input-col">
          <div class="input-col">
          <p><i class="fas fa-envelope" id="icon"></i><input type="email" placeholder="Email" name="email" required>
            </p>
          </div>
        </div>
        <br>
        <input type="submit" class="button-send" value="Send" name="submit">
      </form>
    </div>
  </div>

  <script src="./login-script.js"></script>

</body>

<?php
  $con = mysqli_connect("localhost","root","","muziq-test");
  if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die();
  }

  date_default_timezone_set('Asia/Kuala_Lumpur');	
  $error="";	

  if(isset($_POST["email"]) && (!empty($_POST["email"]))){
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
      $error .="<p>Invalid email address ! Kindly provide a valid email address.</p>";
    } else {
      $sel_query = "SELECT * FROM `admin` WHERE AdminEmail='" . $email . "'";
      $results = mysqli_query($con, $sel_query);
      $row = mysqli_num_rows($results);
    
      if ($row == "") {
        $error .= "<p>No admin is registered with this email address!</p>";
      }
    }
        
    if ($error != "") {
      echo "<div class='error'>" . $error . "</div><br /><a href='javascript:history.go(-1)'>Go Back</a>";
    } else {
      $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
      $expiration = date("Y-m-d H:i:s", $expFormat);
      $tokenReset = md5(2418 * 2 . $email);
      $addToken = substr(md5(uniqid(rand(), 1)), 3, 10);
      $tokenReset = $tokenReset . $addToken;

      $res = mysqli_query($con, "SELECT * FROM `admin` WHERE AdminEmail='" . $email . "'");
      if (!$res) {
        die("Error in querying the admin table: " . mysqli_error($con));
      }

      // Insert into reset_password_user table
       $stmt = $con->prepare("INSERT INTO password_reset_admin (AdminID, Email, `Key`, ExpDate) VALUES (?, ?, ?, ?)");
      if (!$stmt) {
        die("Error in preparing statement: " . $con->error);
      }

      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $adminID = $row['AdminID'];
        $adminName = $row['AdminName'];
        $stmt->bind_param("isss", $adminID, $email, $tokenReset, $expiration);
        if (!$stmt->execute()) {
          die("Error in executing statement: " . $stmt->error);
        }
      } else {
        echo "Error: User not found.";
      }

      $output.='<p>Hello '. $adminName . ',</p>';
      $output.='<p>We received a request to reset your password for your Muziq account.</p><br>';
      $output.='<p>To reset your password, click on the following link:</p>';
      $output.='<p><a href="http://localhost/WebMusicPlayer/Admin-Password-Reset.php?tokenReset='.$tokenReset.'&email='.$email.'&action=reset" target="_blank">http://localhost/WebMusicPlayer/Admin-Password-Reset.php?tokenReset='.$tokenReset.'&email='.$email.'&action=reset</a></p>';		
      $output.='<br><p>Please note that this link is valid for the next 24 hours. After that, you will need to make another request if necessary.</p>';
      $output.='<p>If you have any questions or concerns, feel free to contact our support team.</p><br>';   	
      $output.='<p>Thanks,</p>';
      $output.='<p>Muziq</p>';
      $body = $output; 
      $subject = "Muziq - Admin Password Reset Request";

      $email_to = $email;
      $fromserver = "noreply@yourwebsite.com"; 
      require("PHPMailer/PHPMailerAutoload.php");
      $mail = new PHPMailer();
      $mail->SMTPDebug = 0;
      $mail->IsSMTP();
      $mail->Host = "smtp.gmail.com"; 
      $mail->SMTPAuth = true;
      $mail->Username = "yunqi0426@gmail.com"; 
      $mail->Password = "asxh qtpe vazs szwg"; 
      $mail->Port = 25;
      $mail->IsHTML(true);
      $mail->From = "Muziq@gmail.com";
      $mail->FromName = "Muziq";
      $mail->Sender = $fromserver; 
      $mail->Subject = $subject;
      $mail->Body = $body;
      $mail->AddAddress($email_to);

      error_reporting(0);
      ini_set('display_errors', 0);
      if(!$mail->Send()){
        echo "Mailer Error: " . $mail->ErrorInfo;
      }else{
        header("Location: Password-Reset-Success.php");
        exit();
      }
    }	
  }
?>

</html>