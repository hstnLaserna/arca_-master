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
  $ses_sql = $mysqli->query("SELECT * FROM `admin` WHERE `user_name`='$user_check'");
  $row = mysqli_fetch_assoc($ses_sql);
  $user_name = $row['user_name'];
  $first_name = ucfirst($row['first_name']);
  $middle_name = ucfirst($row['middle_name']);
  $last_name = ucfirst($row['last_name']);
  $sex = $row['sex'];
  $position = strtolower($row['position']);
  $user_avatar = $row['avatar'];

?>
