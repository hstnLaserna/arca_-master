<?php
  include('../backend/conn.php');
  $query = "";
    if(isset($_POST['selected_id'])) {

        $selected_id = $mysqli->real_escape_string($_POST['selected_id']);
        //$query = "SELECT `id` FROM `member` WHERE `id` = '$selected_id'";
        $address_query = "  SELECT * FROM `member` `m` 
                            WHERE `m`.`id` = '$selected_id';";
        $result = $mysqli->query($address_query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "ID Does not exist $address_query";} else {
            
            $with_address = true;
            include('../backend/import_post_variables.php');
            
            if($array_length == 0) {

                $query = "CALL `add_address`('$address_line1', '$address_line2', '$address_city', '$address_province', '$address_is_active', '$selected_id', @`mem_exists`)";
                if($mysqli->query($query)){
                    echo "true";
                } else {
                    echo "ERROR: Could not execute query" . mysqli_error($mysqli);
                }
            } else {
                echo "Errors have been found. Could not execute update of address.";

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
        echo "Invalid id Member ID : $selected_id";
    }
?>