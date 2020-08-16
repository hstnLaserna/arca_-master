

<?php
    include('../backend/conn.php');

    
    $query = "SELECT 	`id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	
                `middle_name`,	`last_name`,	`birth_date`,	`sex`,	`contact_number`,	
                `membership_date`,	`picture` FROM `member`;";

    
    $result = $mysqli->query($query);
    $row = mysqli_num_rows($result);
    ?>
    <div class="table-responsive">
        <table class="table table-hover users">
            <th>Picture</th>
            <th>OSCA ID</th>
            <th>NFC Serial</th>
            <th>Firstname</th>
            <th>Middlename</th>
            <th>Lastname</th>
    <?php
    while($row = mysqli_fetch_array($result))
    {
        $id = $row['id'];
        $osca_id = $row['osca_id'];
        $nfc_serial = $row['nfc_serial'];
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        
        ?>
        <tr class="member-row" id="memNum_<?php echo $id?>">
            <td class="member-avatar">
            <img src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'"'; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?> class="avatar view-member">
            </td>
            <td><?php echo $osca_id ?></td>
            <td><?php echo $nfc_serial ?></td>
            <td><?php echo $first_name ?></td>
            <td><?php echo $middle_name ?></td>
            <td><?php echo $last_name ?></td>
        </tr>
        <?php

    }
    ?>
        </table>
    </div>
    <?php
    mysqli_close($mysqli);

?>
