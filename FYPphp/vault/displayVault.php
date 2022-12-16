<?php
session_start();
$con = mysqli_connect('localhost','root','','fyp');
$user_id= $_SESSION['sess_user'];
$count =0;
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }

      $sql = "SELECT * FROM website WHERE website_id in( SELECT website_id FROM vault where user_id='$user_id') ";
      $result = $con->query($sql);
      if ($result->num_rows > 0) {
        echo "<table style='margin-left:100px;' ><tr>";
        while($row = $result->fetch_assoc()) {
          if($count % 3 == 0 && $count != 0){
            echo"</tr><tr>";
          }
          echo "<td style='padding: 30px;'><form action='http://localhost/FYPphp/vault/editBtnRedirect.php' id='vaultBtn' method='POST'>
            <button type='submit' name='edit'
          value='"
          .$row["Website_ID"]
          ."' style='border:none;'><img src='"
            
          .$row["Website_Path"]
          ."' alt='"
          .$row["Website_ID"]
          ."' width='350' height='150' style='cursor: pointer;' name='image'></button></form></td>";
            
          $count++;
        }
        echo "</table>";
      } else {
        echo "<br><h2>No Passwords Yet</h2>";
      }
      $con->close();



?>