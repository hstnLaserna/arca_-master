<?php
  include('conn.php');
  session_start();

  // Storing Session
  if(!isset($_SESSION['login_user'])){
    mysqli_close($mysqli);
    header('Location: ../index.php');
    exit();
  }

  $user_name=$_SESSION['login_user'];
  // SQL Query To Fetch Complete Information Of User
  $ses_sql = $mysqli->query("SELECT * FROM `admin` WHERE `user_name`='$user_name'");
  $row = mysqli_fetch_assoc($ses_sql);
  $logged_user_id = $row['id'];
  $logged_user_name = $row['user_name'];
  $first_name = ucfirst($row['first_name']);
  $middle_name = ucfirst($row['middle_name']);
  $last_name = ucfirst($row['last_name']);
  $sex = $row['sex'];
  $logged_position = strtolower($row['position']);
  $user_avatar = $row['avatar'];

?>
