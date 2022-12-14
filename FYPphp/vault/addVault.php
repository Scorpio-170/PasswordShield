<?php
    session_start();
    $con = mysqli_connect('localhost','root','','fyp');
    $user_id= $_SESSION['sess_user'];
    $vaultId =uniqid('V');
    $websiteId =uniqid('W'); 
    $URL = $con -> real_escape_string($_POST['WebSiteURL']);
    $NickName = $con -> real_escape_string($_POST['WebSiteNickName']) ;
    $Username = $con -> real_escape_string($_POST['siteEmailUser']);
    $sitePassword = $con -> real_escape_string($_POST['sitePassword']) ;
    $date = time();
    $newURLPath = "/assets/img/default.png";

    if(isset($_POST['addPassword'])){
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
          }
          $stm=mysqli_stmt_init($con);
          $selectSQL ="SELECT * FROM user WHERE User_ID= ?";
          mysqli_stmt_prepare($stm, $selectSQL);
          mysqli_stmt_bind_param($stm,"s", $user_id);
          mysqli_stmt_execute($stm);
          $result= mysqli_stmt_get_result($stm);
          $resultCheck=mysqli_num_rows($result);
          if($resultCheck!=0)  
          {
              if($row= mysqli_fetch_assoc($result)){
                  $userMasterPw=$row['User_Master_Password'];
              }
          }
          $cipher = "aes-256-cbc"; 
          
          $encryption_key = $userMasterPw; 
      
          $iv = "1757598876277162"; 
      
          $encrypted_pw = openssl_encrypt($sitePassword, $cipher, $encryption_key, 0, $iv); 
          $stm=mysqli_stmt_init($con);
          $selectWebSiteSQL ="SELECT * FROM website WHERE Website_URL= ?";
          mysqli_stmt_prepare($stm, $selectWebSiteSQL);
          mysqli_stmt_bind_param($stm,"s", $URL);
          mysqli_stmt_execute($stm);
          $result= mysqli_stmt_get_result($stm);
          $resultCheck=mysqli_num_rows($result);
          if($resultCheck==0)  
          {
              $query ="INSERT INTO website (Website_ID, Website_URL, Website_Path) VALUES ('$websiteId', '$URL', '$newURLPath')";
              if($con->query($query) === TRUE){
                $query1 ="INSERT INTO vault (Vault_ID, Vault_URL, Vault_Name, Vault_Email, Vault_Password, Vault_Date_Create, User_ID, Website_ID) VALUES ('$vaultId', '$URL', '$NickName', '$Username', '$encrypted_pw', '$date', '$user_id', '$websiteId')";
                if($con->query($query1) === TRUE){
                    $con->close();
                    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
                    exit();
        
                }else{
      
                    echo 'something went wrong';
                }
                }
                  else{
                  echo 'something went wrong';
              }
          }else{
            $row= mysqli_fetch_assoc($result);
            $existingWebsiteId=$row['Website_ID'];
            $checkExisting ="SELECT * FROM vault WHERE User_ID='$user_id' AND Website_ID='$existingWebsiteId'";
            $result = $con->query($checkExisting);
            if ($result->num_rows > 0){
                $con->close();
                header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_addErr.html");
            }else{
                $query1 ="INSERT INTO vault (Vault_ID, Vault_URL, Vault_Name, Vault_Email, Vault_Password, Vault_Date_Create, User_ID, Website_ID) VALUES ('$vaultId', '$URL', '$NickName', '$Username', '$encrypted_pw', '$date', '$user_id', '$existingWebsiteId')";
                if($con->query($query1) === TRUE){
                    $con->close();
                    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
                    exit();
        
                }else{
      
                    echo 'something went wrong';
                }
            }

        }


    }


?>