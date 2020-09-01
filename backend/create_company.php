<?php
  include('../backend/conn.php');
  $query = "";
  $company_details = true;
  $with_address = true;
  include('../backend/import_post_variables.php');
  
  if($array_length == 0 && isset($validated) && $validated)
  {
    $query1 = "SELECT `company_name` FROM `company` WHERE `company_name` = '$company_name' AND  `branch` = '$branch' AND  `company_tin` = '$company_tin';";
    $result1 = $mysqli->query($query1);
    $rows1 = mysqli_num_rows($result1);

    if ($rows1 == 0) { // Company Details is unique
      $query = "CALL `add_company`('$company_tin', '$company_name', '$branch', '$business_type',
                  '$address_line1', '$address_line2', '$address_city', '$address_province')";
      if($mysqli->query($query)){
        echo "true";
      }
      else {
        echo "ERROR: Unable to execute. \r\n $query" . mysqli_error($mysqli);
      }
    }
    else {
      if($rows1 > 0){echo "Business Details exists \r\n";} else {}
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
