<?php
  include('../backend/conn.php');

  if(isset($_POST['user_name'])) {
    $user_name = $mysqli->real_escape_string($_POST['user_name']);
    $result = $mysqli->query("CALL `deactivate_admin_account`('$user_name', @msg)");
    mysqli_close($mysqli);// Closing Connection
  }
  echo $_POST['user_name'];
?>
