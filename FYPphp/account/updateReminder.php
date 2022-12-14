<?php
    session_start();
    $con = mysqli_connect('localhost','root','','fyp');
    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }
    if(isset($_POST['editReminderBtn'])){
        $user_id = $_SESSION['sess_user'];
        $reminderPeriod = $con -> real_escape_string($_POST['reminderPeriod']);
        echo $reminderPeriod;
        $query1 = "UPDATE reminder SET Reminder_Period='$reminderPeriod' WHERE User_ID='$user_id'";
        if($con->query($query1) === TRUE){
            $con->close();
            header("Location: chrome-extension://kgnbabmgfmiogjhddmkkablkclfjdbcl/mainPage/mainPage.html");
        }else{
            echo "something went wrong";
        }
    }else{
        echo "something went wrong";
    }
?>