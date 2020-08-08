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
  $username = $mysqli->escape_string($_POST['user_name']);
  $password = $mysqli->escape_string($_POST['password']);
  $firstname = $mysqli->escape_string($_POST['first_name']);
  $middlename = $mysqli->escape_string($_POST['middle_name']);
  $lastname = $mysqli->escape_string($_POST['last_name']);
  $birthdate = $mysqli->escape_string($_POST['birthdate']);
  $sex = strtolower($mysqli->escape_string($_POST['gender']));
  $sex2 = ($mysqli->escape_string(strtolower($_POST['gender'])) == 'female') ? "f" : "m";
  $position = strtolower($mysqli->escape_string($_POST['position']));
  $answer1 = $mysqli->escape_string($_POST['security_answer_1']);
  $answer2 = $mysqli->escape_string($_POST['security_answer_2']);


  if(strlen($username) < 4 || strlen($username) > 20)
  {
    array_push($errors, "Username length must be between 4 to 20 characters");
  } else {}
  if(strlen($password) < 8 || strlen($password) > 20)
  {
    array_push($errors, "Password length must be between 8 to 20 characters");
  } else {}
  if(strlen($firstname) < 1 || strlen($firstname) > 120)
  {
    array_push($errors, "Firstname must be less than 120 characters");
  } else {}
  if(strlen($middlename) < 1 || strlen($middlename) > 120)
  {
    array_push($errors, "Middlename must be less than 120 characters");
  } else {}
  if(strlen($lastname) < 1 || strlen($lastname) > 120)
  {
    array_push($errors, "Lastname must be less than 120 characters");
  } else {}
  if($birthdate == "")
  {
    array_push($errors, "Birthdate must be valid");
  } else {}
  if($sex != "male")
  {
    if($sex != "female")
    {
      array_push($errors, "Gender must either be Male or Female. Selected: " . $_POST['gender']);
    } else {}
  } else {}
  if($position != "user")
  {
    if($position != "admin")
    {
      array_push($errors, "Position must either be User or Admin. Selected: " . $_POST['position']);
    } else {}
  } else {}
  if(strlen($answer1) < 4 || strlen($answer1) > 20)
  {
    array_push($errors, "First Security answer must be between 4 - 20 characters");
  } else {}
  if(strlen($answer2) < 4 || strlen($answer2) > 20)
  {
    array_push($errors, "Second Security answer must be between 4 - 20 characters");
  } else {}

  $array_length=count($errors);

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
