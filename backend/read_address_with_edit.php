<?php
    include('../backend/conn.php');

    if(isset($selected_id))
    {
        $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);
        $address_query = "SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active` FROM `member` `m` LEFT JOIN `address` a ON m.`id` = a.`member_id` WHERE m.`id` = $selected_id";
        $result = $mysqli1->query($address_query);
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
                echo "<p id='memNum_$address_id'>Address $address_counter: $address1, $address2, $city, $province ";
                if($is_active){echo "<small class='text-muted'>(Primary)</small> ";}else{}
                echo '<button class="btn btn-link edit edit_address"><i class="fa fa-edit"></i></button></p>';
                $address_counter++;
            }
        }
        mysqli_close($mysqli1);
    } else {echo "ID does not exist";}
?>


