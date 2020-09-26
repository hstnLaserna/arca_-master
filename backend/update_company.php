<?php
  include('../backend/conn.php');
  $query = "";
    if(isset($_POST['company_tin_old'])) {

        $selected_tin = $mysqli->real_escape_string($_POST['company_tin_old']);
        $query = "SELECT `id` FROM `company` WHERE `company_tin` = '$selected_tin'";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 1) { 

            $row = mysqli_fetch_array($result);
            $company_id = $row['id'];

            $company_details = true;
            $with_address = true;
            include('../backend/import_post_variables.php');

            if($array_length == 0 && isset($validated) && $validated) {
                $query1 = "SELECT `company_tin` FROM `company` WHERE `company_tin` = '$company_tin' AND `id` != '$company_id';";
                $result1 = $mysqli->query($query1);
                $rows1 = mysqli_num_rows($result1);
                
                if ($rows1 == 0) { // OSCA ID && NFC serial doesn't match other member's
                    
                    // validate if company have existing Address
                    $query_2 = "SELECT 	a.`id`, `address1`,	`address2`,	`city`,	`province`
                        FROM `address` a
                        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.`id`
                        WHERE ajt.`company_id` = '$company_id'";
                        if($result_2 = $mysqli->query($query_2)) {
                            $row_count_2 = mysqli_num_rows($result_2);
                        } else {
                            echo "ERROR: Could not execute.\r\n" . mysqli_error($mysqli);
                            echo $query_2;
                        }
                    

                    if(isset($row_count_2) && $row_count_2 == 0) { // address for this company does not exist
                        
                        $query = "CALL `add_company_address`('$address_line1', '$address_line2', '$address_city', '$address_province', '$address_is_active', '$company_id', @`msg`)";
                        
                        if($mysqli->query($query)){
                            $query2 = "SELECT @`msg` msg";
                            $result2 = $mysqli->query($query2);
                            $row2 = mysqli_fetch_assoc($result2);
                            $msg = $row2['msg'];

                            if($msg == "0") {
                                echo "Company does not exist $query";
                            } else if ($msg == "1") {
                            }
    
                        } else {
                            echo "ERROR: Could not execute.\r\n" . mysqli_error($mysqli);
                        }
                    } else {
                        $row = mysqli_fetch_assoc($result_2);
                        $selected_address_id = $row['id'];

                        $query = "CALL `edit_company_address`('$address_line1', '$address_line2', '$address_city', '$address_province', '$address_is_active', '$selected_address_id', '$company_id', @`msg`)";
                        if($mysqli->query($query)){
                            $query2 = "SELECT @`msg` msg";
                            $result2 = $mysqli->query($query2);
                            $row2 = mysqli_fetch_assoc($result2);
                            $msg = $row2['msg'];
                            if($msg == "0") {
                                echo "Company does not exist";
                            } else if ($msg == "1") {
                                echo "\r\n Company's address does not exist \r\n $query";
                            } else if ($msg == "2") {
                            } else {echo "Query executed";
                            }
    
                        } else {
                            echo "ERROR: Could not execute.\r\n" . mysqli_error($mysqli);
                        }
                    }

                    
                    $query = "CALL `edit_company`('$company_tin','$company_name', '$branch', '$business_type', '$company_id', @`msg`)";
                    if($mysqli->query($query)){
                        $query2 = "SELECT @`msg` msg";
                        $result2 = $mysqli->query($query2);
                        $row2 = mysqli_fetch_assoc($result2);
                        $msg = $row2['msg'];

                        if($msg == "0") {
                            echo "Company does not exist";
                        } else if ($msg == "1") {
                            echo "true";
                        }

                    } else {
                        echo "ERROR: Could not execute.\r\n" . mysqli_error($mysqli);
                    }

                } else {
                    if($rows1 >= 1){ echo "Company TIN is already registered to other records.";}
                    else{echo " Errors have been found. Could not execute update of profile 2";}
                }
            } else {
                echo "Errors have been found. Could not execute update of profile 3.";

                echo "\r\n";
                for( $i = 0 ; $i < $array_length ; $i++ )
                {
                echo "\r\n";
                echo $errors[$i];
                }

                $errors= array();
            }
                
            mysqli_close($mysqli);
        } else {
            echo "TIN Does not exist : $selected_tin";
        }
    } else {
        echo "Invalid id ".$selected_tin;
    }
?>
