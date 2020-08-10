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
  include('../backend/import_post_variables.php');
  include('../backend/validate_user_inputs.php');

  if($array_length == 0)
  {
    $query1 = "SELECT `user_name` FROM `admin` WHERE `user_name` = '$username';";
    $result1 = $mysqli->query($query1);
    $rows1 = mysqli_num_rows($result1);

    if ($rows1 == 0) { // username doesn't exist
      $query = "CALL `add_admin`('$username', '$password', '$firstname', '$middlename', '$lastname', '$birthdate', '$sex2', '$position', '1', '$answer1', '$answer2', @`msg`)";
      if(mysqli_query($db, $query)){
        echo "Records inserted successfully.";
      }
      else {
        echo "ERROR: Could not able to execute [$query]" . mysqli_error($db);
      }
    }
    else {
        echo "Username exists";
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
      //echo "Query: " . $query;
      
  mysqli_close($db);

?>
