<?php
session_start();

if(isset($_SESSION['sess_user']) && isset($_SESSION['userMasterPW'])){
    echo "already submit both user and Master Password";
}else if(isset($_SESSION['sess_user'])){
echo "got user";
}else if(!(isset($_SESSION['userMasterPW']))&& isset($_SESSION['sess_user'])){
    echo "got user but no master";
}
else{
    echo "no user";
}

?>