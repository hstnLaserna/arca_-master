<?php
    include('../backend/conn.php');
    include('../backend/php_functions.php');
    $query = "";
    if(isset($_POST['selected_g_id'])) {

        $selected_osca_id = $_POST['selected_osca_id'];
        $selected_g_id = $_POST['selected_g_id'];
        
        $query = " SELECT * FROM `view_members_with_guardian`
                            WHERE `osca_id` = '$selected_osca_id' AND `g_id` = '$selected_g_id';";
                            
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
                { // Group for import and validation
                    $g_firstname = $mysqli->escape_string($_POST['g_first_name']);
                    $g_middlename = $mysqli->escape_string($_POST['g_middle_name']);
                    $g_lastname = $mysqli->escape_string($_POST['g_last_name']);
                    $g_sex = strtolower($mysqli->escape_string($_POST['g_gender']));
                    $g_sex2 = determine_sex($g_sex, "post");
                    $g_relationship = $mysqli->escape_string($_POST['g_relationship']);
                    $g_contact_number = $mysqli->escape_string($_POST['g_contact_number']);
                    $g_email = $mysqli->escape_string($_POST['g_email']);
                    
                    $errors = array();
                    {
                        if(strlen($g_firstname) < 1 || strlen($g_firstname) > 120)
                        {
                            array_push($errors, "Guardian's Firstname must be less than 120 characters");
                        }
                        if(strlen($g_lastname) < 1 || strlen($g_lastname) > 120)
                        {
                            array_push($errors, "Guardian's Lastname must be less than 120 characters");
                        }
                        if($g_sex != "male")
                        {
                            if($g_sex != "female")
                            {
                                array_push($errors, "Guardian's Gender must either be Male or Female.");
                            }
                        }
                        if(strlen($g_relationship) < 1 || strlen($g_relationship) > 120)
                        {
                            array_push($errors, "Guardian's Relationship must be less than 120 characters");
                        }
                        if(strlen($g_contact_number) < 4 || strlen($g_contact_number) > 30)
                        {
                            array_push($errors, "Guardian's Contact number must be between 4 - 30 digits");
                        }
                        if(strlen($g_email) < 4 || strlen($g_email) > 30)
                        {
                            array_push($errors, "Guardian's Email must be valid");
                        }
                    }
                    $array_length=count($errors);
                }
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
