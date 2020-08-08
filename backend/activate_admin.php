<?php
  include('../backend/conn.php');

  $db = mysqli_connect($host,$user,$pass,$schema);

  $id = $_POST['id'];

  echo "hello $id";
  if(!isset($_POST['input_id'])) {
    $query_invalidPW = "CALL `activate_admin_account`('".$id."')";
    $result_invalidPW = $mysqli->query($query_invalidPW);
    echo "Successfully Activated";
    mysqli_close($mysqli);// Closing Connection
  } else {
      echo "invalid id";
  }
?>
