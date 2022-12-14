<?php
$con = mysqli_connect('localhost','root','','fyp');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
session_start();
$user_id = $_SESSION['sess_user'];
$stm=mysqli_stmt_init($con);
$selectSQL ="SELECT * FROM reminder WHERE User_ID= ?";
mysqli_stmt_prepare($stm, $selectSQL);
mysqli_stmt_bind_param($stm,"s", $user_id);
mysqli_stmt_execute($stm);
$result= mysqli_stmt_get_result($stm);
$resultCheck=mysqli_num_rows($result);
if($resultCheck!=0)  
{
    echo "<label for='reminderPeriod'> Password Reminder Frequency:</label>";
    $row= mysqli_fetch_assoc($result);
    $reminderPeriod=$row['Reminder_Period'];
    if($reminderPeriod == "1 Month"){
        echo"<select name='reminderPeriod' id='reminderPeriod' style='margin-left: 8px;'>
        <option selected value='1 Month'>1 Month</option>
        <option value='3 Months'>3 Months</option>
        <option value='6 Months'>6 Months</option>
        <option value='1 Year'>1 Year</option>
        <option value='Never'>Never</option>
      </select>";
    }else if($reminderPeriod == "3 Months"){
        echo"<select name='reminderPeriod' id='reminderPeriod' style='margin-left: 8px;'>
        <option value='1 Month'>1 Month</option>
        <option selected value='3 Months'>3 Months</option>
        <option value='6 Months'>6 Months</option>
        <option value='1 Year'>1 Year</option>
        <option value='Never'>Never</option>
      </select>";
    }else if($reminderPeriod == "6 Months"){
        echo"<select name='reminderPeriod' id='reminderPeriod' style='margin-left: 8px;'>
        <option value='1 Month'>1 Month</option>
        <option value='3 Months'>3 Months</option>
        <option selected value='6 Months'>6 Months</option>
        <option value='1 Year'>1 Year</option>
        <option value='Never'>Never</option>
      </select>";
    }else if($reminderPeriod == "1 Year"){
        echo"<select name='reminderPeriod' id='reminderPeriod' style='margin-left: 8px;'>
        <option value='1 Month'>1 Month</option>
        <option value='3 Months'>3 Months</option>
        <option value='6 Months'>6 Months</option>
        <option selected value='1 Year'>1 Year</option>
        <option value='Never'>Never</option>
      </select>";
    }else if($reminderPeriod == "Never"){
        echo"<select name='reminderPeriod' id='reminderPeriod' style='margin-left: 8px;'>
        <option value='1 Month'>1 Month</option>
        <option value='3 Months'>3 Months</option>
        <option value='6 Months'>6 Months</option>
        <option value='1 Year'>1 Year</option>
        <option selected value='Never'>Never</option>
      </select>";
    }
}



?>