<?php
  include('../backend/conn.php');

  if(isset($_POST['id'])) {
    $id = $mysqli->real_escape_string($_POST['id']);
    $qry = "CALL `toggle_member_nfc`('$id', @msg)";
    $result = $mysqli->query($qry);
    $result2 = $mysqli->query("SELECT @`msg` AS `msg`;");
    $row = mysqli_fetch_assoc($result2);
    echo $row['msg'];
    mysqli_close($mysqli);// Closing Connection
  }
?>
