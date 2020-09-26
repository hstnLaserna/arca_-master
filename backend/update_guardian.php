<?php
    include('../backend/conn.php');
    
    $query = "";
    if(isset($_POST['selected_g_id'])) {

        $selected_osca_id = $mysqli->real_escape_string($_POST['selected_osca_id']);
        $selected_g_id = $mysqli->real_escape_string($_POST['selected_g_id']);
        
        $query = "  SELECT * FROM `member` m
                    INNER JOIN `guardian` g ON g.member_id = m.id
                    WHERE m.`osca_id` = '$selected_osca_id' AND g.`id` = '$selected_g_id';";
                    echo $query;
                            
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "false" ;} else 
        {
            if(isset($_GET['action']) && $_GET['action'] == "delete") {
                $query = "CALL `delete_guardian`('$selected_osca_id', '$selected_g_id', @`msg`)";
                if($mysqli->query($query)){
                    echo "true";
                } else {
                    echo "false " . mysqli_error($mysqli);
                }
            } else {
                
                $with_guardian = true;
                include('../backend/import_post_variables.php');

                if($array_length == 0) {
                    $query = "CALL `edit_guardian`('$selected_g_id', '$selected_osca_id', '$g_firstname', '$g_middlename', '$g_lastname', '$g_sex2', 
                                                    '$g_relationship', '$g_contact_number', '$g_email', @`msg`)";
                    if($mysqli->query($query)){
                        echo "true";
                    } else {
                        echo "false " . mysqli_error($mysqli);
                    }
                } else {
                    echo "Errors have been found. Could not execute update of address.";

                    echo "\r\n";
                    for( $i = 0 ; $i < $array_length ; $i++ )
                    {
                    echo "\r\n";
                    echo $errors[$i];
                    }

                    $errors = array();
                }
                    
                mysqli_close($mysqli);
            }
        }

    } else {
        echo "Invalid id OSCA ID : $selected_osca_id && Guardian ID: $selected_g_id";
    }
?>
