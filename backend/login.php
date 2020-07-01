<?php
include('backend/conn.php');
  session_start(); // Starting Session
  $error=''; // Variable To Store Error Message
  if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
    $error = "Username or Password must not be blank";
    }
    else
    {
      // Establishing Connection with Server by passing server_name, user_id and password as a parameter
      $mysqli = new mysqli($host,$user,$pass,$schema) or die($mysqli->error);


      // To protect MySQL injection for Security purpose
      $username = strtolower($mysqli->escape_string($_POST['username']));
      $password = $mysqli->escape_string($_POST['password']);


      // Selecting Database
      $db = mysqli_connect($host,$user,$pass,$schema);

      $query1 = "SELECT * FROM `admin` WHERE `user_name`='$username'";
      $query2 = "SELECT * FROM `admin` WHERE `user_name`='$username' AND `password`=MD5('$password')";
      $query3 = "SELECT * FROM `admin` WHERE `user_name`='$username' AND `password`=MD5('$password') AND `is_enabled`=1";
      $result1 = $mysqli->query($query1);
      $rows1 = mysqli_num_rows($result1);
      $result2 = $mysqli->query($query2);
      $rows2 = mysqli_num_rows($result2);
      $result3 = $mysqli->query($query3);
      $rows3 = mysqli_num_rows($result3);

      if ($rows1 == 1) {
        if($rows2 == 1) {
          if($rows3 == 1) {
            $_SESSION['login_user'] = $username; // Initializing Session
            header("location: ../frontend/gpio.php"); // Redirecting To Other Page
          }
          else {
            $error = "User is deactivated";
          }
        }
        else {
          $query_invalidPW = "CALL `invalid_login`('".$username."')";
          $result_invalidPW = $mysqli->query($query_invalidPW);
          $error = "Invalid password";
        }
      }
      else {
          $error = "Username does not exist";
      }
      mysqli_close($mysqli);// Closing Connection
    }
  }
?>
