<?php
  include('conn.php');
  session_start();

  // Storing Session
  if(!isset($_SESSION['login_user'])){
    mysqli_close($mysqli);
    header('Location: ../index.php');
    exit();
  }
  
  $user_check=$_SESSION['login_user'];
  // SQL Query To Fetch Complete Information Of User
  $ses_sql = $mysqli->query("SELECT `user_name` FROM `admin` WHERE `user_name`='$user_check'");
  $row = mysqli_fetch_assoc($ses_sql);
  $login_session = strtoupper($row['user_name']);

?>
