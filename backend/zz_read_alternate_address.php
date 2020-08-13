<?php
    include('../backend/conn.php');

    if(isset($selected_id))
    {
        $mysqli2 = new mysqli($host,$user,$pass,$schema) or die($mysqli2->error);
        $a_address_query = "SELECT `address1`, `address2`, `city`, `province` FROM `member` m LEFT JOIN `address` a ON m.`id` = a.`member_id` WHERE m.`id` = $selected_id AND a.`is_active` <> 1 ORDER BY a.`id`";
        $a_result = $mysqli2->query($a_address_query);
        $a_row_count = mysqli_num_rows($a_result);
        if($a_row_count == 0) { echo "<p>Address 2: None</p>";} else
        {
            $nth_address = 2;
            while($a_row = mysqli_fetch_array($a_result))
            {
                $a_address1 = $a_row['address1'];
                $a_address2 = $a_row['address2'];
                $a_city = $a_row['city'];
                $a_province = $a_row['province'];
                echo "<p> Address $nth_address: $a_address1, $a_address2, $a_city, $a_province </p>";
                $nth_address = $nth_address + 1;
            }
        }
        mysqli_close($mysqli2);// Closing Connection
    } else {echo "ID does not exist";}
?>


