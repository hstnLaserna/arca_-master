<?php
  include('../backend/conn.php');
  $query = "";
    if(isset($_POST['selected_id'])) {

        $selected_id = $_POST['selected_id'];
        $query = "SELECT `id` FROM `member` WHERE `osca_id` = '$selected_id'";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "ID Does not exist Member's ID : $selected_id";} else {

            $row = mysqli_fetch_array($result);
            $selected_id = $row['id'];
            {
                $user_type = "senior citizen";
                $personal_details = true;
                include('../backend/import_post_variables.php');

                if(strlen(preg_replace('/[^0-9]/', '', $membership_date)) == 8)
                {
                    $membership_date .= " 00:00:00";
                }
            }

            if($array_length == 0 && isset($validated) && $validated) {
                $query1 = "SELECT `osca_id` FROM `member` WHERE `osca_id` = '$osca_id' AND `id` != '$selected_id';";
                $result1 = $mysqli->query($query1);
                $rows1 = mysqli_num_rows($result1);
                $query2 = "SELECT `nfc_serial` FROM `member` WHERE `nfc_serial` = '$nfc_serial' AND `id` != '$selected_id';";
                $result2 = $mysqli->query($query2);
                $rows2 = mysqli_num_rows($result2);
                
                if ($rows1 == 0 && $rows2 == 0) { // OSCA ID && NFC serial doesn't match other member's
                    if(strlen($password) != 0){
                        $query = "CALL `edit_member_with_pw`('$osca_id', '$nfc_serial', '$password', 
                        '$firstname', '$middlename', '$lastname', '$birthdate', '$contact_number', 
                        '$email',  '$sex2', '$membership_date', $selected_id)";
                    } else
                    {
                        $query = "CALL `edit_member_no_pw`('$osca_id', '$nfc_serial', '$firstname', '$middlename', '$lastname', 
                        '$birthdate', '$contact_number', '$email', '$sex2', '$membership_date', $selected_id)";
                    }
                    if($mysqli->query($query)){
                        echo "true";
                    } else {
                        echo "ERROR: Could not execute. \r\n" . mysqli_error($mysqli);
                    }
                } else {
                    if($rows1 >= 1){ echo "OSCA ID is already registered to other records.";}
                    if($rows2 >= 1){ echo "NFC Serial is already registered to other records.";}
                }
            } else {
                echo "Errors have been found. Could not execute update of profile.";

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