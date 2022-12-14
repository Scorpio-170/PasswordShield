<?php
session_start();
if(isset($_POST['signOut'])){
    session_unset();
    session_destroy();
    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/login/login.html");

}else if(isset($_POST['signOut1'])){
    session_unset();
    session_destroy();
    header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/popup_index/popup_index.html");
}

?>
