<?php
    include('../backend/conn.php');
    $query = "";

    // IF ADDRESS IS FOR A MEMBER
    if(isset($_POST['selected_id']) &&
        isset($_POST['selected_address_id']) &&
        isset($_POST['type'])) {

        $type = $_POST['type'];
        $selected_id = $_POST['selected_id'];
        $selected_address_id = $_POST['selected_address_id'];
        
        if($type == "member") {
            $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active` 
                                FROM member m
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`member_id` = m.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE m.`id` = '$selected_id' AND `a`.`id` = '$selected_address_id';";
        } else if($type == "company") {
            $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active` 
                                FROM company c
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`company_id` = c.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE c.`id` = '$selected_id' AND `a`.`id` = '$selected_address_id';";

        } else if($type == "guardian") {
            $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active` 
                                FROM guardian g
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`guardian_id` = g.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE g.`id` = '$selected_id' AND `a`.`id` = '$selected_address_id';";

        } else { return false;}
                            
        $result = $mysqli->query($address_query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 1) {
            if(isset($_GET['action']) && $_GET['action'] == "delete") {
                $query = "CALL `delete_member_address`('$selected_id', '$selected_address_id', @`msg`)";
                if($mysqli->query($query)){
                    echo "true";
                } else {
                    echo "ERROR: Could not execute query" . mysqli_error($mysqli);
                }
            } else {
                
                $with_address = true;
                include('../backend/import_post_variables.php');

                if(isset($_POST['address_is_active'])) {
                    $address_is_active = 1;
                } else {$address_is_active = 0;}

                if($array_length == 0) {
                    
                    $query = "CALL `edit_".$type."_address`('$address_line1', '$address_line2', '$address_city', '$address_province', '$address_is_active', '$selected_address_id', '$selected_id', @`msg`)";
                    if($mysqli->query($query)){
                        $query2 = "SELECT @`msg` msg";
                        $result2 = $mysqli->query($query2);
                        $row2 = mysqli_fetch_assoc($result2);
                        $msgxx = $row2['msg'];
                        if($msgxx == "0") {
                            echo "User does not exist";
                        } else if ($msgxx == "1") {
                            echo "\r\nUser's address does not exist $msgxx \r\n $query";
                        } else if ($msgxx == "2") {
                            echo "true";
                        } else {echo "Query executed";
                        }

                    } else {
                        echo "ERROR: Could not execute.\r\n" . mysqli_error($mysqli);
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
            echo "Record does not exist";
        }

    } else {
        echo "Invalid id Member ID OR Address ID";
    }
?>
