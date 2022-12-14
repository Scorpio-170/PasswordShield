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
    if(isset($_POST['resetwithOTPbtn'])){
        $userID = $_SESSION['sess_user'];
        $stm=mysqli_stmt_init($con);
        $query ="SELECT * FROM user WHERE User_ID= ?";
        mysqli_stmt_prepare($stm, $query);
        mysqli_stmt_bind_param($stm,"s", $userID);
        mysqli_stmt_execute($stm);
        $result= mysqli_stmt_get_result($stm);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck!=0)  
        {
          if($row= mysqli_fetch_assoc($result)){
            $userEmail=$row['User_Email'];
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
            $mail->addAddress($userEmail);
            
            $mail->Subject = 'Welcome To Password Shield';
            $mail->Body    = '<html><body><h1 style="color: #396eb3;">Welcome To Password Shield</h1>
            <br><p>The following code is a 6 digit one time password. Use this OTP to register the account Do not share it with anyone! <br>The 
            OTP is:'.$OTP.'</p></body></html>';
            if(!$mail->send()){
                echo "something went wrong";
            }else{
                header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/accountSettings/chgMstPassword/forgetMstPwOtpReceive.html");
            }
          }else{
            echo "something went wrong";
          }
    }else{
        echo "something went wrong";
    }
    }else if(isset($_POST['forgetPwVerifyOTP'])){
        $verifyOTP = $con -> real_escape_string($_POST['verifyMasterOTP']);
        $existingOTP = $_SESSION['OTP'];
        if($verifyOTP==$existingOTP){
            unset($_SESSION['OTP']);
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/accountSettings/chgMstPassword/OTPResetMstPw.html");
        }else{
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/accountSettings/chgMstPassword/forgetMstPwOtpReceiveErr.html");

        }
    }else if(isset($_POST['OTPResetMaster'])){
        $userID = $_SESSION['sess_user'];
        $newPw = $con -> real_escape_string($_POST['newPw']);
        $stm=mysqli_stmt_init($con);
        $query ="SELECT * FROM user WHERE USER_ID= ?";
        mysqli_stmt_prepare($stm, $query);
        mysqli_stmt_bind_param($stm,"s", $userID);
        mysqli_stmt_execute($stm);
        $result= mysqli_stmt_get_result($stm);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck!=0)  
        {
            if($row= mysqli_fetch_assoc($result)){
                $oldPW=$row['User_Master_Password'];
                $resetEmail=$row['User_Email'];
                if(password_verify($newPw, $oldPW)){
                    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/accountSettings/chgMstPassword/OTPResetMstPwErr.html");
                }else{
                    $hashedPassword = password_hash($newPw, PASSWORD_BCRYPT);
                    $sql = "UPDATE user SET User_Master_Password='$hashedPassword' WHERE User_Email='$resetEmail'";
                    if($con->query($sql) === TRUE){
                        $con->close();
                        header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
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