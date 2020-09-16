<?php
  include('../backend/conn.php');
  
  if(!isset($errrors)){$errors = array();}

  $query = "";

  $user_type = "osca";
  $personal_details = true;
  include('../backend/import_post_variables.php');

  if($array_length == 0 && isset($validated) && $validated)
  {
    $query1 = "SELECT `user_name` FROM `admin` WHERE `user_name` = '$username';";
    $result1 = $mysqli->query($query1);
    $rows1 = mysqli_num_rows($result1);

    if ($rows1 == 0) { // username doesn't exist
      $query = "CALL `add_admin`('$username', '$password', '$firstname', '$middlename', '$lastname', '$birthdate', '$sex2', '$contact_number', '$email', '$position', '1', '$answer1', '$answer2', @`msg`)";
      if($mysqli->query($query)){
        echo ucwords($user_type) . "$username created successfully.";
      }
      else {
        echo "ERROR: Could not execute. \r\n" . mysqli_error($mysqli);
      }
    }
    else {
        echo "Username '$username' exists";
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
      //echo "Query: " . $query;
      
  mysqli_close($mysqli);

?>
