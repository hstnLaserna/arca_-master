<?php 
    function determine_sex($sex_, $mode) {
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
        else {return false;}
    }

    function read_address($selected_id, $edit=false){
        if(isset($selected_id))
        {
            include('../backend/conn.php');
            if($edit) {
                $address_query = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active`  FROM member m
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`member_id` = m.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                WHERE m.`id` = $selected_id";
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
                        if($row['is_active'] == '1'){$is_active = true;}else{$is_active=false;}
                        echo "<p class='disp_address' id='addNum_$address_id'>Address $address_counter: $address1, $address2, $city, $province ";
                        if($is_active){echo "<small class='text-muted'>(Primary)</small> ";}else{}
                        echo '<button class="ml-auto btn btn-link edit edit_address"><i class="fa fa-edit"></i></button></p>';
                        $address_counter++;
                    }
                }
            }
            else { 
                $address_query = " SELECT `address1`, `address2`, `city`, `province`, `is_active`  FROM member m
                                INNER JOIN `address_jt` `ajt` ON `ajt`.`member_id` = `m`.`id`
                                INNER JOIN `address` `a` ON `ajt`.`address_id` = `a`.`id`
                                WHERE `m`.`id` = $selected_id";
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
                        if($is_active){echo "<small class='text-muted'>(Primary)</small></p>";}else{echo "</p>";}
                        $address_counter++;
                    }
                }
            }
            mysqli_close($mysqli);
        } else {echo "ID does not exist";}
    }

    function read_guardian($osca_id, $edit=true){
        if(isset($osca_id))
        {
            include('../backend/conn.php');
            if($edit) {
                $query = "SELECT * FROM `view_members_with_guardian`
                    WHERE `osca_id` = '$osca_id';";

                $result = $mysqli->query($query);
                $row_count = mysqli_num_rows($result);
                if($row_count == 0) { echo "<p class='lead'>No guardian on record</p>";} else
                {
                    while($row = mysqli_fetch_array($result))
                    {
                        $g_id = $row['g_id'];
                        $g_first_name = $row['g_first_name'];
                        $g_middle_name = $row['g_middle_name'];
                        $g_last_name =  $row['g_last_name'];
                        $g_sex2 =  $row['g_sex'];
                        $g_contact_number =  $row['g_contact_number'];
                        $g_email =  $row['g_email'];
                        $g_relationship =  $row['g_relationship'];
                        ?>
                        <div id="gid<?php echo $g_id ?>">
                            <p>Full Name: <?php echo "$g_first_name $g_middle_name $g_last_name"; ?></p>
                            <p>Relationship: <?php echo "$g_relationship"; ?></p>
                            <p>Sex: <?php echo determine_sex($g_sex2, "display_long"); ?> </p>
                            <p>Contact Number: <?php echo "$g_contact_number"; ?></p>
                            <p>Email: <?php echo "$g_email"; ?></p>
                            <button class="btn btn-link edit edit_guardian"><i class="fa fa-edit"></i></button></p>
                        </div>
                        <?php
                    }
                }
            }
            mysqli_close($mysqli);
        } else {echo "Member does not exist";}
    }

?>