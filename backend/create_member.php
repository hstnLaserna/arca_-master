<?php
  include('../backend/conn.php');
  $query = "";
  $user_type = "senior citizen";
  $personal_details = true;
  $with_password = true;
  $with_address = true;
  $with_guardian = true;
  include('../backend/import_post_variables.php');
  // '$nfc_serial', '$osca_id', 
  if($array_length == 0 && isset($validated) && $validated)
  {
    $query1 = "SELECT `osca_id` FROM `member` WHERE `osca_id` = '$osca_id';";
    $result1 = $mysqli->query($query1);
    $rows1 = mysqli_num_rows($result1);
    $query2 = "SELECT `nfc_serial` FROM `member` WHERE `nfc_serial` = '$nfc_serial';";
    $result2 = $mysqli->query($query2);
    $rows2 = mysqli_num_rows($result2);

    if ($rows1 == 0 && $rows2 == 0) { // OSCA ID is unique
      $query = "CALL `add_member`('$firstname', '$middlename', '$lastname', '$birthdate', 
                  '$sex2', '$contact_number', '$email', '$membership_date',
                  '$address_line1', '$address_line2', '$address_city', '$address_province', '$password', 
                  '$g_firstname', '$g_middlename', '$g_lastname',
                  '$g_contact_number', '$g_sex2', '$g_relationship', '$g_email')";
      if($mysqli->query($query)){
        echo "true";
      }
      else {
        echo "ERROR: Unable to execute. \r\n $query" . mysqli_error($mysqli);
      }
    }
    else {
      if($rows1 > 0){echo "OSCA ID exists \r\n";} else {}
      if($rows2 > 0){echo "NFC Serial exists";} else {}
    }
  }
  else {
    echo "Errors have been found. Could not execute addition of account. ";
    // Displaying records from external php file "validate_user_inputs.php"
    echo "\r\n";
    for( $i = 0 ; $i < $array_length ; $i++ )
    {
      echo "\r\n";
      echo $errors[$i];
    }

    $errors= array();
  }
  mysqli_close($mysqli);

?>
