<?php
  include('../backend/conn.php');
  $db = mysqli_connect($host,$user,$pass,$schema);
  $errors = array();
  $query = "";
  // Check connection
  if($db === false){
      die("ERROR: Could not connect. " . mysqli_connect_error());
      echo "Error: Could not connect to db ". mysqli_connect_error();
  }
  else { }
  $user_type = "senior citizen";
  $with_address = true;
  include('../backend/import_post_variables.php');
  include('../backend/validate_user_inputs.php');

  if($array_length == 0)
  {
    $query1 = "SELECT `osca_id` FROM `member` WHERE `osca_id` = '$osca_id';";
    $result1 = $mysqli->query($query1);
    $rows1 = mysqli_num_rows($result1);
    $query2 = "SELECT `nfc_serial` FROM `member` WHERE `nfc_serial` = '$nfc_serial';";
    $result2 = $mysqli->query($query2);
    $rows2 = mysqli_num_rows($result2);

    if ($rows1 == 0 && $rows2 == 0) { // OSCA ID doesn't exist
      $query = "CALL `add_member`('$firstname', '$middlename', '$lastname', '$birthdate', '$sex2', '$contact_number', '$membership_date', '$password', '$osca_id', '$nfc_serial', '$address_line1', '$address_line2', '$address_city', '$address_province')";
      if(mysqli_query($db, $query)){
        echo "Records inserted successfully.";
      }
      else {
        echo "ERROR: Could not able to execute. \r\n" . mysqli_error($db);
      }
    }
    else {
      if($rows1 > 0){echo "OSCA ID exists \r\n";} else {}
      if($rows2 > 0){echo "NFC Serial exists";} else {}
    }
  }
  else {
    echo "Errors have been found. Could not execute addition of account.";
    // Displaying records from external php file "validate_user_inputs.php"
    echo "\r\n";
    for( $i = 0 ; $i < $array_length ; $i++ )
    {
      echo "\r\n";
      echo $errors[$i];
    }

    $errors= array();
  }
  mysqli_close($db);

?>
