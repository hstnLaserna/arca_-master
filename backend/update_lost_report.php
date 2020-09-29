<?php
    include('../backend/conn.php');
    $query = "";
    $lost_report = true;
    include('../backend/import_post_variables.php');
    
    if($array_length == 0 && isset($validated) && $validated)
    {
        $query = "CALL `edit_lost_report`('$lost_id', '$id', '$desc', '$nfc_status', '$account_status', @`msg`)";
        if($mysqli->query($query)){
            echo "true";
        }
        else {
            echo "ERROR: Unable to execute. \r\n $query" . mysqli_error($mysqli);
        }
    } else {
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
