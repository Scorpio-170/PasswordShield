<?php
    $con = mysqli_connect('localhost','root','','fyp');
    $email = $con -> real_escape_string($_POST['email']);
    $password = $con -> real_escape_string($_POST['password']);
    if(isset($_POST['loginAcc'])){
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
          }
          
        $stm=mysqli_stmt_init($con);
        $query ="SELECT * FROM user WHERE User_Email= ?";
        mysqli_stmt_prepare($stm, $query);
        mysqli_stmt_bind_param($stm,"s", $email);
        mysqli_stmt_execute($stm);
        $result= mysqli_stmt_get_result($stm);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck!=0)  
        {
            if($row= mysqli_fetch_assoc($result)){
                $userID=$row['User_ID'];
                $userPW=$row['User_Password'];
                if(password_verify($password, $userPW)){
                    session_start();
                    $_SESSION['sess_user']=$userID;
                    header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/mainPage/mainPage.html");
                }else{
                    header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/login/loginErr.html");
                }
            }
        }else{
                    header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/login/loginErr.html");
        }

    }

?>