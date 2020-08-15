<?php
    include('../backend/conn.php');

    if(isset($selected_id))
    {
        $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);
        $address_query = "SELECT `address1`, `address2`, `city`, `province`, `is_active` 
                            FROM `member` `m` LEFT JOIN `address` a 
                            ON m.`id` = a.`member_id` WHERE m.`id` = $selected_id";
        $result = $mysqli1->query($address_query);
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
        mysqli_close($mysqli1);// Closing Connection
    } else {echo "ID does not exist";}
?>


