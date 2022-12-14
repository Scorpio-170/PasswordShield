<?php
    $currentPw = $con -> real_escape_string($_POST['currentMasterPassword']);
    $newPw = $con -> real_escape_string($_POST['newPassword']);
    $con = mysqli_connect('localhost','root','','fyp');
    session_start();
    $user_id = $_SESSION['sess_user'];
    if(isset($_POST['updatePwWithMstBtn'])){
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
       }
       $stm=mysqli_stmt_init($con);
       $query ="SELECT * FROM user WHERE User_ID= ?";
       mysqli_stmt_prepare($stm, $query);
       mysqli_stmt_bind_param($stm,"s", $user_id);
       mysqli_stmt_execute($stm);
       $result= mysqli_stmt_get_result($stm);
       $resultCheck=mysqli_num_rows($result);
       if($resultCheck!=0)  
       {
         if($row= mysqli_fetch_assoc($result)){
             $userMstPW=$row['User_Master_Password'];
             $userPW=$row['User_Password'];
             if(password_verify($currentPw, $userMstPW)){
                if(password_verify($newPw, $userPW)){
                    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/accountSettings/chgPassword/forgetPwMstErr1.html");
                }else{
                    $hashedPassword = password_hash($newPw, PASSWORD_BCRYPT);
                    $sql = "UPDATE user SET User_Password='$hashedPassword' WHERE User_ID='$user_id'";
                    if($con->query($sql) === TRUE){
                        $con->close();
                        header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
                        exit();
            
                    }else{
                        echo "something went wrong";
                    }
                }
             }else{
                 header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/accountSettings/chgPassword/forgetPwMstErr.html");
             }
        }else{
        echo "something went wrong";
             }
        }else{
            echo "something went wrong";
        }
    }
        
?>