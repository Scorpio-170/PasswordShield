<?php
$con = mysqli_connect('localhost','root','','fyp');
$newWebsiteId =uniqid('W');
$date = time();
$URL = $con -> real_escape_string($_POST['WebSiteURL']);
$NickName = $con -> real_escape_string($_POST['WebSiteNickName']);
$Username = $con -> real_escape_string($_POST['siteEmailUser']);
$sitePassword = $con -> real_escape_string($_POST['sitePassword']);
$newURLPath = "/assets/img/default.png";
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
session_start();
$user_id = $_SESSION['sess_user'];
$website_id = $_SESSION['sess_webSite'];
$iv = "1757598876277162";
if(isset($_POST['editPassword'])){
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
        $query ="INSERT INTO website (Website_ID, Website_URL, Website_Path) VALUES ('$newWebsiteId', '$URL', '$newURLPath')";
        if($con->query($query) === TRUE){
            $sql = "UPDATE vault SET Vault_URL='$URL', Vault_Name='$NickName', Vault_Email='$Username', Vault_Password='$encrypted_pw', Vault_Date_Create='$date', Website_ID='$newWebsiteId' WHERE User_ID='$user_id' AND Website_ID='$website_id'";
          if($con->query($sql) === TRUE){
              $con->close();
              unset($_SESSION['sess_webSite']);
              header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
              exit();
  
          }else{

              echo 'something went wrong';
          }
          }else{
            echo 'something went wrong';
        }
    }else{
        $row= mysqli_fetch_assoc($result);
        $existingWebsiteId=$row['Website_ID'];
        $query1 = "UPDATE vault SET Vault_URL='$URL', Vault_Name='$NickName', Vault_Email='$Username', Vault_Password='$encrypted_pw', Vault_Date_Create='$date', Website_ID='$existingWebsiteId'  WHERE User_ID='$user_id' AND Website_ID='$website_id'";
        if($con->query($query1) === TRUE){
            $stm=mysqli_stmt_init($con);
            $selectSQL ="SELECT Vault_ID FROM vault WHERE User_ID= ? AND Website_ID = ?";
            mysqli_stmt_prepare($stm, $selectSQL);
            mysqli_stmt_bind_param($stm,"ss", $user_id, $existingWebsiteId);
            mysqli_stmt_execute($stm);
            $result= mysqli_stmt_get_result($stm);
            $resultCheck=mysqli_num_rows($result);
            if($resultCheck>1){
                $row = $result->fetch_assoc() ;
                $oldRecord = $row["Vault_ID"];    
                $sql = "DELETE FROM vault WHERE vault_ID='$oldRecord'";
                if ($con->query($sql) === TRUE) {
                    unset($_SESSION['sess_webSite']);
                    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
                    exit();
                }     
            }else{
            unset($_SESSION['sess_webSite']);
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
            exit();
            }
             $con->close();

        }else{

            echo 'something went wrong';
        }
    }


}else{
    echo "something went wrong";
}


?>