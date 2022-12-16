<?php
$con = mysqli_connect('localhost','root','','fyp');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
session_start();
$user_id = $_SESSION['sess_user'];
$website_id = $_SESSION['sess_webSite'];
$iv = "1757598876277162";
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

$sql = "SELECT * FROM vault WHERE website_id='$website_id' AND user_id='$user_id' ";
$result = $con->query($sql);

if ($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $encrypted_pw = $row["Vault_Password"];
    $decrypted_data = openssl_decrypt($encrypted_pw, $cipher, $decryption_key, 0, $iv);
    echo"
                          <li>
                        <h1 style='text-align: center; font-family: Georgia, 'Times New Roman', Times, serif; font-size: 30px;'>Update Password</h2>
                      </li>
                      <li>
                        <div class='form-outline mb-4'>
                          <label class='form-label' for='WebSiteURL' style='text-align:left; margin-bottom: 5px;'>Website URL:</label>
                          <input type='text' id='WebSiteURL' name='WebSiteURL' class='form-control' value='"
                          . $row["Vault_URL"].
                          "'/>
                          <div id='WebsiteURLErr' style='color: red;'></div>
                        </div>
                      </li> 
                      <li>
                      <div class='form-outline mb-4'>
                        <label class='form-label' for='WebSiteNickName' style='text-align:left; margin-bottom: 5px;'>Website Nickname:</label>
                        <input type='text' id='WebSiteNickName' name='WebSiteNickName' class='form-control' value='"
                        . $row["Vault_Name"].
                        "'/>
                        <div id='WebsiteNickNameErr' style='color: red;'></div>
                      </div>
                    </li>
                      <li>
                      <div class='form-outline mb-4'>
                      <label class='form-label' for='siteEmailUser' style='text-align:left; margin-bottom: 5px;'>Email/Username:</label>
                      <input type='text' id='siteEmailUser' name='siteEmailUser' class='form-control' value='"
                      . $row["Vault_Email"].
                      "'/>
                      <div id='siteEmailUserError' style='color: red;'></div>
          </div>
                  </li>
                  <li>
                    <div class='form-outline mb-4'>
                    <label class='form-label' for='sitePassword' style='text-align:left; margin-bottom: 5px;'>Password:</label>
                    <input type='password' id='sitePassword' name='sitePassword' class='form-control' placeholder='Password' value='"
                    . $decrypted_data.
                    "'/>
                    <div id='sitePasswordError' style='color: red;'></div>
        </div>
        </li>   
                            ";
}else{
    echo"something went wrong";
}


?>