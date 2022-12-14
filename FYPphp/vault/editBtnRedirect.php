<?php
    session_start();
if (isset($_POST["edit"])){
    $website_id=$_POST["edit"];
    $_SESSION['sess_webSite']=$website_id;
    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/pw_vault/pw_vault_edit.html");
}else{
    echo "something went wrong";
}

?>