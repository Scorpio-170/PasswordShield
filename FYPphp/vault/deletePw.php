<?php
session_start();
$user_id = $_SESSION['sess_user'];
$website_id = $_SESSION['sess_webSite'];
$con = mysqli_connect('localhost','root','','fyp');

if(isset($_POST['deletePassword'])){
    $sql = "DELETE FROM vault WHERE website_id='$website_id' AND user_id='$user_id'";

    if ($con->query($sql) === TRUE) {
        $con->close();
        unset($_SESSION['sess_webSite']);
        header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_index.html");
    } else {
      echo "something went wrong ";
    }
}


?>