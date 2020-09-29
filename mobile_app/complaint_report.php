<?php
session_start();
include("conn.php");

$oscaID = $_POST["oscaID"];
$companyName = $_POST["companyName"];
$branch = $_POST["branch"];
$desc = $_POST["desc"];
$reportDate = $_POST["reportDate"];

$qry = "call `add_complaint_report`('$companyName', '$branch', '$oscaID', '$desc', '$reportDate', @msg)";

if ($result = $conn->query($qry)) {

  $result = $conn->query('select @msg as msg');
  $row = mysqli_fetch_assoc($result);
  $msg = $row['msg'];
  if ($msg == 1) {
    echo "success";
  } else {
    echo "Invalid Company Name, Branch or OSCA ID";
  }
} else {
  echo "Error: Could not execute.<br>". mysqli_error($conn);
}
