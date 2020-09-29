<?php
session_start();
include("conn.php");

$oscaID = $_POST["oscaID"];
$reportDate = $_POST["reportDate"];


$qry = "call `add_lost_report`('$oscaID','$reportDate', @msg)";

if ($result = $conn->query($qry)) {

  $result = $conn->query('select @msg as msg');
  $row = mysqli_fetch_assoc($result);
  $msg = $row['msg'];
  if ($msg == 1) {
    echo "success";
  } else {
    echo "Invalid OSCA ID";
  }
} else {
  echo "Error: Could not execute.<br>". mysqli_error($conn);
}
