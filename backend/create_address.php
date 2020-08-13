<?php
  include('../backend/conn.php');
  $db = mysqli_connect($host,$user,$pass,$schema);
  $query = "";
    if(isset($_POST['selected_member_id'])) {
        // Check connection
        if($db === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
            echo "Error: Could not connect to db ". mysqli_connect_error();
        }
        else { }

        $selected_member_id = $_POST['selected_member_id'];
        //$query = "SELECT `id` FROM `member` WHERE `id` = '$selected_id'";
        $address_query = "  SELECT * FROM `member` `m` 
                            WHERE `m`.`id` = '$selected_member_id';";
        $result = $mysqli->query($address_query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "ID Does not exist $address_query";} else {
            {
                $address_line1 = $mysqli->escape_string($_POST['address_line1']);
                $address_line2 = $mysqli->escape_string($_POST['address_line2']);
                $address_city = $mysqli->escape_string($_POST['address_city']);
                $address_province = $mysqli->escape_string($_POST['address_province']);
                $address_is_active = "1";//$mysqli->escape_string($_POST['address_is_active']);
                $errors = array();
                {
                    if(strlen($address_line1) < 4 || strlen($address_line1) > 50)
                    {
                        array_push($errors, "Address Line 1 must be between 4 - 50 digits");
                    } else {}
                    if(strlen($address_line2) < 4 || strlen($address_line2) > 50)
                    {
                        array_push($errors, "Address Line 2 must be between 4 - 50 digits");
                    } else {}
                    if(strlen($address_city) < 4 || strlen($address_city) > 50)
                    {
                        array_push($errors, "City must be between 4 - 50 digits");
                    } else {}
                    if(strlen($address_province) < 4 || strlen($address_province) > 50)
                    {
                        array_push($errors, "Province must be between 4 - 50 digits");
                    } else {}
                    if($address_is_active == 1 || $address_is_active == 0)
                    {} else {
                        array_push($errors, "Active status must be TRUE or FALSE");
                    }
                }
                $array_length=count($errors);
            }
            if($array_length == 0) {

                $query = "CALL `add_address`('$address_line1', '$address_line2', '$address_city', '$address_province', '$address_is_active', '$selected_member_id', @`mem_exists`)";
                if(mysqli_query($db, $query)){
                    echo "true";
                } else {
                    echo "ERROR: Could not able to execute [$query]" . mysqli_error($db);
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
                
            mysqli_close($db);
        }
    } else {
        echo "Invalid id Member ID : $selected_member_id";
    }
?>
