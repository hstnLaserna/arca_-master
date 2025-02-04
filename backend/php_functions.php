<?php 
    function determine_sex($sex_, $mode)
    {
        $sex_ = strtolower($sex_);
        if($mode == "display_long") {
            switch ($sex_){
                case 0:
                    return "Unknown";
                    break;
                case 1:
                    return "Male";
                    break;
                case 2:
                    return "Female";
                    break;
                case 9:
                    return "Indeterminate";
                    break;
                default:
                    return "NA";
                    break;
            }
        } else if($mode == "display_short") {
            switch ($sex_){
                case 0:
                    return "NA";
                    break;
                case 1:
                    return "M";
                    break;
                case 2:
                    return "F";
                    break;
                case 9:
                    return "NA";
                    break;
                default:
                    return "NA";
                    break;
            }
        } else if($mode == "post") {
            switch ($sex_){
                case "unkown":
                    return "0";
                    break;
                case "male":
                    return "1";
                    break;
                case "female":
                    return "2";
                    break;
                case "indeterminate":
                    return "9";
                    break;
                default:
                    return "0";
                    break;
            }
        }
        else {
            return false;
        }
    }

    function read_address($selected_id, $edit=false, $type="member")
    {
        if(isset($selected_id))
        {
            include('../backend/conn.php');
            if($type=="member"){
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  
                                FROM `member` m
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`member_id` = m.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE m.`id` = '$selected_id'";
            } else if($type == "company") {
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  
                                FROM `company` c
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`company_id` = c.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE c.`company_tin` = '$selected_id'";
            } else if($type == "guardian") {
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  
                                FROM `guardian` g
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`guardian_id` = g.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE g.`id` = '$selected_id'";
            }
            if($edit) {
                $result = $mysqli->query($address_query);
                $row_count = mysqli_num_rows($result);
                if($row_count == 0) { echo "<p class='lead'>No address on record</p>";} else
                {
                    $address_counter = 1;
                    while($row = mysqli_fetch_array($result))
                    {
                        $address_id = $row['address_id'];
                        $address1 = $row['address1'];
                        $address2 = $row['address2'];
                        $city = $row['city'];
                        $province = $row['province'];
                        if($row['is_active'] == '1'){
                            $is_active = true;
                        } else {
                            $is_active=false;
                        }
                        echo "<p class='disp_address' id='addNum_$address_id'>Address $address_counter: $address1, $address2, $city, $province ";
                        if($is_active){
                            echo "<small class='text-muted'>(Primary)</small> ";
                        }
                        echo '<button class="ml-auto btn btn-link edit edit_address"><i class="fa fa-edit"></i></button></p>';
                        $address_counter++;
                    }
                }
            }
            else {
                $result = $mysqli->query($address_query);
                $row_count = mysqli_num_rows($result);
                if($row_count == 0) { echo "<p class='lead'>No address on record</p>";} else
                {
                    $address_counter = 1;
                    while($row = mysqli_fetch_array($result))
                    {
                        $address1 = $row['address1'];
                        $address2 = $row['address2'];
                        $city = $row['city'];
                        $province = $row['province'];
                        if($row['is_active'] == '1'){$is_active = true;}else{$is_active=false;}
                        echo "<p>Address $address_counter: $address1, $address2, $city, $province ";
                        if($is_active){
                            echo "<small class='text-muted'>(Primary)</small></p>";
                        } else {
                            echo "</p>";
                        }
                        $address_counter++;
                    }
                }
            }
            mysqli_close($mysqli);
        } else {
            echo "ID does not exist";
        }
    }

    function read_address2($selected_id, $type="member")
    {
        if(isset($selected_id))
        {
            include('../backend/conn.php');
            if($type=="member"){
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  
                                FROM `member` m
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`member_id` = m.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE m.`id` = '$selected_id'";
            } else if($type == "company") {
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  
                                FROM `company` c
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`company_id` = c.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE c.`company_tin` = '$selected_id'";
            } else if($type == "guardian") {
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  
                                FROM `guardian` g
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`guardian_id` = g.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE g.`id` = '$selected_id'";
            }
            
            $result = $mysqli->query($address_query);
            $row_count = mysqli_num_rows($result);
            if($row_count > 0){
                return mysqli_fetch_array($result);
            } else {
                // id does not have address
                return false;
            }
            mysqli_close($mysqli);
        } else {
            // no id given
            return false;
        }
    }

    function read_guardian($osca_id, $edit=true)
    {
        if(isset($osca_id))
        {
            include('../backend/conn.php');
            $query = "SELECT * 
            FROM member m
            INNER JOIN guardian g ON g.member_id = m.id
            WHERE m.`osca_id` = '$osca_id';";

            $result = $mysqli->query($query);
            $row_count = mysqli_num_rows($result);
            if($row_count = 1) {
                if($edit) {
                    $query = "SELECT g.id `g_id`, g.`first_name` `g_first_name`, g.`middle_name` `g_middle_name`, g.`last_name` `g_last_name`, 
                                g.`sex` `g_sex`, g.`contact_number` `g_contact_number`, g.`email` `g_email`, g.`relationship` `g_relationship`
                                FROM `guardian` g
                                INNER JOIN `member` m on g.`member_id` = m.`id`        
                                WHERE m.`osca_id` = '$osca_id';";

                    return $mysqli->query($query);
                }
            }

            mysqli_close($mysqli);
        } else {
            return false;
        }
    }

    function validate_date($date_to_validate = "", $year_offset = 0)
    {
        if($date_to_validate != "" && (preg_match("/^((18|19|20)[0-9]{2}[\-.](0[13578]|1[02])[\-.](0[1-9]|[12][0-9]|3[01]))|(18|19|20)[0-9]{2}[\-.](0[469]|11)[\-.](0[1-9]|[12][0-9]|30)|(18|19|20)[0-9]{2}[\-.](02)[\-.](0[1-9]|1[0-9]|2[0-8])|(((18|19|20)(04|08|[2468][048]|[13579][26]))|2000)[\-.](02)[\-.]29$/",$date_to_validate)))
        {
            // Year offset is the valid date from the CURRENT DATE.
            // e.g. Year offset : "-60" means must be 60 years prior the date of input


            $test_arr  = explode('-', $date_to_validate);
            $timestamp = mktime(0, 0, 0, $test_arr[1], $test_arr[2], $test_arr[0]);

            if($year_offset != 0){
                $valid_date = strtotime(date("Y-m-d").' '. $year_offset .' year');
            } else {
                $valid_date = strtotime(date("Y-m-d").' +1 day');
            }

            if (count($test_arr) == 3) {
                if (checkdate($test_arr[1], $test_arr[2], $test_arr[0]) && $timestamp <= $valid_date) {
                    return true;
                 } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    function validate_date_month($date_to_validate = "", $month = 0)
    {
        if($date_to_validate != "" && (preg_match("/^((18|19|20)[0-9]{2}[\-.](0[13578]|1[02])[\-.](0[1-9]|[12][0-9]|3[01]))|(18|19|20)[0-9]{2}[\-.](0[469]|11)[\-.](0[1-9]|[12][0-9]|30)|(18|19|20)[0-9]{2}[\-.](02)[\-.](0[1-9]|1[0-9]|2[0-8])|(((18|19|20)(04|08|[2468][048]|[13579][26]))|2000)[\-.](02)[\-.]29$/",$date_to_validate)))
        {
            // Year offset is the valid date from the CURRENT DATE.
            // e.g. Year offset : "-60" means must be 60 years prior the date of input


            $test_arr  = explode('-', $date_to_validate);
            $timestamp = mktime(0, 0, 0, $test_arr[1], $test_arr[2], $test_arr[0]);



            //if($is_birthdate){
            //    $valid_date = strtotime(date("Y-m-d").' -18 year');
            //} else
            if($year_offset != 0){
                $valid_date = strtotime(date("Y-m-d").' '. $year_offset .' month');
            } else {
                $valid_date = strtotime(date("Y-m-d").' +1 day');
            }

            if (count($test_arr) == 3) {
                if (checkdate($test_arr[1], $test_arr[2], $test_arr[0]) && $timestamp <= $valid_date) {
                    return true;
                 } else {
                    return false;
                }
            } else {
                return false;
            }
        //} else {
        //    array_push($errors, "Invalid date");
        }
    }

    function populate_province()
    {
        include("../backend/conn_address.php");
        $qry = "SELECT p.`provDesc` `province` FROM province p ORDER BY `province` ASC;";
        $result_province = $mysqli->query($qry);
        
        echo "<option></option>";
        while($provinces = mysqli_fetch_assoc($result_province)) {
            $province = strtolower($provinces['province']);
            $province = ucwords($province);
            echo "<option value='$province'>$province</option>";
        }
    }

    function arrange_generic_name($generic_name_in)
    {   
        $generic_name_collective = str_replace('  ', ' ', $generic_name_in);
        $generic_name_collective = str_replace(', ', ',', $generic_name_collective);
        $generic_names = explode(',', $generic_name_collective);
        $generic_name_string = "";
        $count_generic_names=count($generic_names);
        sort($generic_names);

        for( $i = 0 ; $i < $count_generic_names ; $i++ ){
            $generic_name = $generic_names[$i];
            $generic_name_string .= ucwords($generic_name);
            if ($i >= 0 && $count_generic_names > 1 && ($count_generic_names - 1) != $i) {
                $generic_name_string .= ", ";
            }
        }
        return $generic_name_string;
    }

?>

