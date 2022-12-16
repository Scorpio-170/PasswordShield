<?php
    $con = mysqli_connect('localhost','root','','fyp');
    $masterpassword = $con -> real_escape_string($_POST['masterpassword']);
    if(isset($_POST['checkMasterPW'])){
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
          }
        session_start();
        $stm=mysqli_stmt_init($con);
        $query ="SELECT * FROM user WHERE User_ID= ?";
        mysqli_stmt_prepare($stm, $query);
        mysqli_stmt_bind_param($stm,"s", $_SESSION['sess_user']);
        mysqli_stmt_execute($stm);
        $result= mysqli_stmt_get_result($stm);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck!=0)  
        {
            if($row= mysqli_fetch_assoc($result)){
                $userMasterPW=$row['User_Master_Password'];
                if(password_verify($masterpassword, $userMasterPW)){
                    $_SESSION['userMasterPW']=$userMasterPW;
                    header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/pw_vault/pw_vault_index.html");
                }else{
                    header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/mainPage/mainPageErr.html");

                }
            }
        }else{
                    header("Location: chrome-extension://aecdplfdmmhbdjflimkoagbocjnaaifl/mainPage/mainPageErr.html");

        }

    }

?>