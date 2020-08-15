
<?php
  include('../backend/conn.php');
  $db = mysqli_connect($host,$user,$pass,$schema);

    if(isset($_GET['member_id']))
    {
        $member_id = $_GET['member_id'];
        
        $format_memdate = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
        $format_bdate = "concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`))";
        $query = "SELECT `id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	$format_bdate  `bdate`,	`sex`,	`contact_number`,	 $format_memdate `memship_date`,	`picture` FROM `member` WHERE `id` = $member_id;";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        if($row_count == 0) { echo "ID Does not exist";} else
        {
            if($row_count > 1) { echo "ID returns more than 1 record";} else{}
            $osca_id = $row['osca_id'];
            $first_name = $row['first_name'];
            $middle_name =  $row['middle_name'];
            $last_name =  $row['last_name'];
            $sex =  $row['sex'];
            $bdate =  $row['bdate'];
            $memship_date =  $row['memship_date'];
            $contact_number =  $row['contact_number'];
            
            ?>

            <div class="card user-card">
                <img src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'" class="rounded-circle"'; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                <p><?php echo $last_name; ?>,</p>
                <p><?php echo $first_name; ?> <?php echo $middle_name; ?></p>
                <p>OSCA ID: <?php echo $osca_id; ?> </p>
                <p>Member since: <?php echo $memship_date; ?> </p>
                <p>Sex: <?php if($sex == 'f'|| $sex == 'F'){echo "Female";}else{echo "Male";} ?> </p>
                <p>Birthdate: <?php echo $bdate; ?> </p>
                <?php
                    $selected_id = $member_id;
                    include("../backend/read_address_with_edit.php");
                ?>
                <button type="button" id="add_address" class="btn btn-info">Add Address</button>
                <p>Phone Number: <?php echo $contact_number; ?> </p>
                <p>E-mail:  </p>
                
            </div>
            
            <button type="button" id="edit_basic" class="btn btn-light">Edit Basic Details</button>
                    
            <?php

        }
        mysqli_close($mysqli);// Closing Connection
    } else {
        echo "No selected user"; 
    }
?>