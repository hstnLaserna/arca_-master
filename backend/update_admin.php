<?php
  include('../backend/conn.php');
  $db = mysqli_connect($host,$user,$pass,$schema);
  $query = "";
  $selected_id = $_POST['selected_id'];
    if(isset($_POST['selected_id'])) {
        // Check connection
        if($db === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
            echo "Error: Could not connect to db ". mysqli_connect_error();
        }
        else { }
        $query = "SELECT `id` FROM `admin` WHERE `id` = '$selected_id'";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "ID Does not exist $query";} else {

            {
                $user_type = "osca";
                $with_address = false;
                include('../backend/import_post_variables.php');
                include('../backend/validate_user_inputs.php');
            }

            if($array_length == 0) {
                $query1 = "SELECT `id`, `user_name`, `first_name`, `last_name` FROM `admin` WHERE `user_name` = '$username' AND `id` != '$selected_id';";
                $result1 = $mysqli->query($query1);
                $rows1 = mysqli_num_rows($result1);

                if ($rows1 == 0) { // username doesn't exist
                    if(strlen($password) != 0){
                        $query = "CALL `edit_admin_with_pw`('$username', '$password', '$firstname', '$middlename', '$lastname', '$birthdate', '$sex2', '$position', '$answer1', '$answer2','$password', $selected_id)";
                    } else
                    {
                        $query = "CALL `edit_admin_no_pw`('$username', '$firstname', '$middlename', '$lastname', '$birthdate', '$sex2', '$position', '$answer1', '$answer2', $selected_id)";
                    }
                    if(mysqli_query($db, $query)){
                        echo "true";
                    } else {
                        echo "ERROR: Could not execute. ". mysqli_error($db);
                    }
                } else {
                    echo "Username exists";
                }
            } else {
                echo "Errors have been found. Could not execute addition of account.";

                echo "\r\n";
                for( $i = 0 ; $i < $array_length ; $i++ )
                {
                echo "\r\n";
                echo $errors[$i];
                }

                $errors= array();
            }
                
            mysqli_close($db);
        }
    } else {
        echo "Invalid id ".$selected_id;
    }
?>
