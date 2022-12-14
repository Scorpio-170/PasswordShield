<?php
session_start();
$count=0;
$con = mysqli_connect('localhost','root','','fyp');
$user_id= $_SESSION['sess_user'];
$todayDate = time();
$stm=mysqli_stmt_init($con);
$selectSQL ="SELECT * FROM reminder WHERE User_ID= ?";
mysqli_stmt_prepare($stm, $selectSQL);
mysqli_stmt_bind_param($stm,"s", $user_id);
mysqli_stmt_execute($stm);
$result= mysqli_stmt_get_result($stm);
$resultCheck=mysqli_num_rows($result);
if($resultCheck!=0){
    $row= mysqli_fetch_assoc($result);
    $reminderPeriod=$row['Reminder_Period'];
    
}
$sql = "SELECT * FROM vault WHERE User_ID ='$user_id' ";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $createDate = $row["Vault_Date_Create"];
        if($reminderPeriod == "1 Month"){
            $checkDate = strtotime('+1 month', $createDate );
            if ($todayDate>$checkDate){
                
                echo "<br><h4>Password ". $row["Vault_Name"]." needs to be updated</h4>";
                $count++;
            }
        }else if($reminderPeriod == "3 Months"){
            $checkDate = strtotime('+3 months', $createDate );
            if ($todayDate>$checkDate){
                echo "<br><h4>Password ". $row["Vault_Name"]." needs to be updated</h4>";
                $count++;
            }
        }else if($reminderPeriod == "6 Months"){
            $checkDate = strtotime('+6 months', $createDate );
            if ($todayDate>$checkDate){
                echo "<br><h4>Password ". $row["Vault_Name"]." needs to be updated</h4>";
                $count++;
            }
        }else if($reminderPeriod == "1 Year"){
            $checkDate = strtotime('+1 year', $createDate );
            if ($todayDate>$checkDate){
                echo "<br><h4>Password ". $row["Vault_Name"]." needs to be updated</h4>";
                $count++;
            }
        }else if($reminderPeriod == "Never"){
        }else{
            echo "something went wrong";
        }

    }
    if($count ==0){
        echo "<br><h2>All Passwords Are Up To Date</h2>";
    }
}else{
    echo "<br><h2>No Passwords Yet</h2>";
}
  
?>