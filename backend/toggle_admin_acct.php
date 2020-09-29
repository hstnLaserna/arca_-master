<?php
  include('../backend/conn.php');

  if(isset($_POST['user'])) {
    $user = $mysqli->real_escape_string($_POST['user']);
    $qry = "CALL `toggle_admin_acct`('$user', @msg)";
    $result = $mysqli->query($qry);
    $result2 = $mysqli->query("SELECT @`msg` AS `msg`;");
    $row = mysqli_fetch_assoc($result2);
    echo $row['msg'];
    mysqli_close($mysqli);// Closing Connection
  }
?>
