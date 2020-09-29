<?php
session_start();
include("conn.php");

$oscaID = $_POST["oscaID"];
$desc = $_POST["desc"];
$token = $_POST["token"];
$requestDate = $_POST["requestDate"];

$qry = "call `add_qr_request`('$oscaID', '$desc', '$token', '$requestDate', @msg)";

if ($result = $conn->query($qry)) {

  $result = $conn->query('select @msg as msg');
  $row = mysqli_fetch_assoc($result);
  $msg = $row['msg'];
  if ($msg == 1) {
    echo "success";
  } else {
    echo "osca ID does not exist";
  }
} else {
  echo "Error: Could not execute.<br>". mysqli_error($conn);
}
