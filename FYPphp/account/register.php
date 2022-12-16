<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'D:/Xampp/htdocs/FYPphp/phpmailer/src/Exception.php';
    require 'D:/Xampp/htdocs/FYPphp/phpmailer/src/PHPMailer.php';
    require 'D:/Xampp/htdocs/FYPphp/phpmailer/src/SMTP.php';
    $con = mysqli_connect('localhost','root','','fyp');
    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }
    if(isset($_POST['registerAcc'])){
      $id =uniqid('U');
      $email = $con -> real_escape_string($_POST['email']);
      $password = $con -> real_escape_string($_POST['password']);
      $masterPassword = $con -> real_escape_string($_POST['masterpassword']);
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $hashedMasterPassword = password_hash($masterPassword, PASSWORD_BCRYPT);
          $stm=mysqli_stmt_init($con);
          $query ="SELECT * FROM user WHERE User_Email= ?";
          mysqli_stmt_prepare($stm, $query);
          mysqli_stmt_bind_param($stm,"s", $email);
          mysqli_stmt_execute($stm);
          $result= mysqli_stmt_get_result($stm);
          $resultCheck=mysqli_num_rows($result);
          if($resultCheck!=0)  
          {
            header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/register/registerErr.html");
          }else{
            $OTP = random_int(100000, 999999);
            $_SESSION['OTP']=$OTP;
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth   = true;  
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465; 
            $mail->Host       = 'smtp.gmail.com';
            $mail->Username   = "melvinawh-wm19@student.tarc.edu.my";
            $mail->Password   = 'cakwrsqbbonsexna';
            $mail->isHTML(true);
            
            $mail->setFrom("melvinawh-wm19@student.tarc.edu.my");
            $mail->addAddress($email);
            
            $mail->Subject = 'Welcome To Password Shield';
            $mail->Body    = '<html><body><h1 style="color: #396eb3;">Welcome To Password Shield</h1>
            <br><p>The following code is a 6 digit one time password. Use this OTP to register the account Do not share it with anyone! <br>The OTP is:'.$OTP.'</p></body></html>';
            if(!$mail->send()){
                echo "something went wrong";
            }else{
              $_SESSION['id']=$id;
              $_SESSION['email']=$email;
              $_SESSION['hashedPassword']=$hashedPassword;
              $_SESSION['hashedMasterPassword']=$hashedMasterPassword;
                header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/register/receiveRegisterEmail.html");
            }
          }
    }else if(isset($_POST['verifyRegOTPbtn'])){
      $verifyOTP = $con -> real_escape_string($_POST['verifyRegOTP']);
      $existingOTP = $_SESSION['OTP'];
      if($verifyOTP==$existingOTP){
        $passedId = $_SESSION['id'];
        $passedEmail = $_SESSION['email'];
        $passedHashedPassword = $_SESSION['hashedPassword'];
        $passedHashedMasterPassword = $_SESSION['hashedMasterPassword'];
          unset($_SESSION['OTP']);
          $query ="INSERT INTO user (User_ID, User_Email, User_Password, User_Master_Password) VALUES ('$passedId', '$passedEmail', '$passedHashedPassword', '$passedHashedMasterPassword')";
          if($con->query($query) === TRUE){
              $defaultReminderPeriod = "Never";
              unset($_SESSION['id']);
              unset($_SESSION['email']);
              unset($_SESSION['hashedPassword']);
              unset($_SESSION['hashedMasterPassword']);
              $reminderId =uniqid('R');
              $query ="INSERT INTO reminder (Reminder_ID, Reminder_Period, User_ID) VALUES ('$reminderId', '$defaultReminderPeriod', '$passedId')";
              if($con->query($query) === TRUE){
                $con->close();
                header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/login/login.html");
                exit();
              }
              
          }else{
              echo 'something went wrong';
          }   
      }else{
        
          header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/register/receiveRegisterEmailErr.html");
      }
    }


?>