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
    if(isset($_POST['checkEmailBtn'])){
        
        $resetEmail = $con -> real_escape_string($_POST['resetEmail']);
        $OTP = random_int(100000, 999999);
        $_SESSION['OTP']=$OTP;
        $_SESSION['resetEmail']=$resetEmail;

          $stm=mysqli_stmt_init($con);
          $query ="SELECT * FROM user WHERE User_Email= ?";
          mysqli_stmt_prepare($stm, $query);
          mysqli_stmt_bind_param($stm,"s", $resetEmail);
          mysqli_stmt_execute($stm);
          $result= mysqli_stmt_get_result($stm);
          $resultCheck=mysqli_num_rows($result);
          if($resultCheck!=0)  
          {
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
            $mail->addAddress($resetEmail);
            
            $mail->Subject = 'Password Shield Reset Password';
            $mail->Body    = '<html><body><h1 style="color: #396eb3;">Password Shield Reset Password</h1>
            <br><p>The following code is a 6 digit one time password. Do not share it with anyone! <br>The OTP is:'.$OTP.'</p></body></html>';
            if(!$mail->send()){
                echo "something went wrong";
            }else{
                header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/forgot_Pw/receiveEmail.html");
            }
          }else{
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/forgot_Pw/forgot_PwErr.html");
          }
    }else if(isset($_POST['verifyOTPbtn'])){
        $verifyOTP = $con -> real_escape_string($_POST['verifyOTP']);
        $existingOTP = $_SESSION['OTP'];
        if($verifyOTP==$existingOTP){
            unset($_SESSION['OTP']);
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/forgot_Pw/resetUserPass.html");
        }else{
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/forgot_Pw/receiveEmailErr.html");
        }
    }else if(isset($_POST['resetPWbtn'])){
        $newPw = $con -> real_escape_string($_POST['newPw']);
        $resetEmail=  $_SESSION['resetEmail'];
        $stm=mysqli_stmt_init($con);
        $query ="SELECT * FROM user WHERE User_Email= ?";
        mysqli_stmt_prepare($stm, $query);
        mysqli_stmt_bind_param($stm,"s", $resetEmail);
        mysqli_stmt_execute($stm);
        $result= mysqli_stmt_get_result($stm);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck!=0)  
        {
            if($row= mysqli_fetch_assoc($result)){
                $oldPW=$row['User_Password'];
                if(password_verify($newPw, $oldPW)){
                    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/forgot_Pw/resetUserPassErr.html");
                }else{
                    $hashedPassword = password_hash($newPw, PASSWORD_BCRYPT);
                    $sql = "UPDATE user SET User_Password='$hashedPassword' WHERE User_Email='$resetEmail'";
                    if($con->query($sql) === TRUE){
                        $con->close();
                        unset($_SESSION['resetEmail']);
                        header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/login/login.html");
                        exit();
            
                    }else{
                        echo "something went wrong";
                    }
                }
            }
        }else{
            
            echo "something went wrong";
        }

    }
?>