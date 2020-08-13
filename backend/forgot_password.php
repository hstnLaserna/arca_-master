<?php
  include('../backend/conn.php');
  $error = "";
  
  if(isset($_POST['submit'])){
    if(isset($_POST['username']) && isset($_POST['answer1']) && isset($_POST['answer2'])) {
      $username = $_POST['username'];
      $answer1 = $_POST['answer1'];
      $answer2 = $_POST['answer2'];
      $result = $mysqli->query("CALL `forgot_pw_admin`('$username', '$answer1', '$answer2', @`tempopw`, @`msg`)");
      
      $query_status = $mysqli->query("SELECT @`tempopw` `tempo_pw`, @`msg` output_msg;");
      $row = mysqli_fetch_assoc($query_status);
      $tempo_pw = $row['tempo_pw'];
      $output_msg = $row['output_msg'];

      if($output_msg  == 1) {
        $error =  "Password successful. <br>Your temporary password is: <B><em>$tempo_pw</em></B>";
      } else {
        $error =  "Username did not match security answers. Try again.";
      }

      mysqli_close($mysqli);// Closing Connection
    } else {
      $error =  "Please complete all details";
    }
  }
?>
