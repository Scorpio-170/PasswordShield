<?php
session_start();
$URL=$_SESSION['urlName'];
$con = mysqli_connect('localhost','root','','fyp');
$user_id= $_SESSION['sess_user'];
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
$decryption_key = $userMasterPw; 
$iv = "1757598876277162"; 

$stm=mysqli_stmt_init($con);
$selectSQL ="SELECT * FROM website WHERE Website_URL= ?";
mysqli_stmt_prepare($stm, $selectSQL);
mysqli_stmt_bind_param($stm,"s", $URL);
mysqli_stmt_execute($stm);
$result= mysqli_stmt_get_result($stm);
$resultCheck=mysqli_num_rows($result);
if($resultCheck!=0)  
{
    if($row= mysqli_fetch_assoc($result)){
      $websitePath=$row["Website_Path"];  
      $websiteID=$row['Website_ID'];
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
          }
          $query ="SELECT website.Website_Path, vault.Vault_Name, vault.Vault_Email, 
          vault.Vault_Password FROM vault, website WHERE vault.User_ID = '$user_id' AND vault.Website_ID= '$websiteID' ";
          $result = mysqli_query($con, $query);
          if (mysqli_num_rows($result) > 0) {
            echo"<table>";
            $row = mysqli_fetch_array($result);
              $encrypted_pw=$row["Vault_Password"];
              $decrypted_data = openssl_decrypt($encrypted_pw, $cipher, $decryption_key, 0, $iv); 
              echo "<tr><td style='padding-right: 40px;'><img style='height:30px; width:80px' alt='websiteLogo' src='".$websitePath.
              "'></td><td style='padding-right: 40px;'>".$row["Vault_Name"]."</td>";
              echo "<td style='padding-left: 40px;'><button type='button' class='btn btn-secondary btn-lg' id='auto-fill' style='background-color:#396eb3; border:0px; font-size: small; margin-left: 25px; '>FILL IN</button></td></tr>";            
            echo "</table>";
            echo "<input type='hidden' style= 'width: 150px' id='password' readonly value='".$decrypted_data."'>";
            echo "<input type='hidden' style= 'width: 100px' id='username' readonly value='".$row["Vault_Email"]."'>";
          } else {
            echo "0 results";
          }
          $con->close();
    }

}else{
    echo "<input type='hidden' <button type='button' id='auto-fill'></button>";
    echo "There are no websites registered under the current page URL.";
}




?>