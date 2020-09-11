<?php
    include('../backend/conn.php');
    $query = "";
  
    if(isset($_POST['selected_id'])) {
        
        $selected_id = $mysqli->real_escape_string($_POST['selected_id']);
        
        $query = "SELECT `id` FROM `admin` WHERE `id` = '$selected_id'";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "ID Does not exist $query";} else {

            {
                $user_type = "osca";
                $personal_details = true;
                include('../backend/import_post_variables.php');
            }

            if($array_length == 0 && isset($validated) && $validated) {
                $query1 = "SELECT `id`, `user_name`, `first_name`, `last_name` FROM `admin` WHERE `user_name` = '$username' AND `id` != '$selected_id';";
                $result1 = $mysqli->query($query1);
                $rows1 = mysqli_num_rows($result1);

                if ($rows1 == 0) { // username doesn't exist
                    if(strlen($password) != 0){
                        $query = "CALL `edit_admin_with_pw`('$username', '$password', '$firstname', '$middlename', '$lastname', '$birthdate', '$sex2',  '$contact_number', '$email', '$position', '$answer1', '$answer2','$password', $selected_id)";
                    } else
                    {
                        $query = "CALL `edit_admin_no_pw`('$username', '$firstname', '$middlename', '$lastname', '$birthdate', '$sex2',  '$contact_number', '$email', '$position', '$answer1', '$answer2', $selected_id)";
                    }
                    if($mysqli->query($query)){
                        //include('../backend/photo_upload.php');
                        //$result = $mysqli->query("CALL `edit_admin_avatar`('$user_name', '$photo_upload', @msg)");
                        echo "true";
                    } else {
                        echo "ERROR: Could not execute. ". mysqli_error($mysqli);
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
                
            mysqli_close($mysqli);
        }
    } else {
        echo "Invalid id ".$selected_id;
    }
?>
