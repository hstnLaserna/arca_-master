<?php
  include('../backend/conn.php');

  if(isset($_POST['admin_id'])) {
    $admin_id = $_POST['admin_id'];
    $result = $mysqli->query("CALL `activate_admin_account`('".$admin_id."')");
    mysqli_close($mysqli);// Closing Connection
  } else {}
?>
