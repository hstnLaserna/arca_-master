<?php
  include('../backend/conn.php');

  $db = mysqli_connect($host,$user,$pass,$schema);

  $input_nfc = $_GET['input_nfc'];

  if(!isset($_GET['input_id'])) {
      $input_nfc = $_GET['input_nfc'];
      $dateformat = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
      $query = "SELECT `id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	`birth_date`,	`sex`,	`contact_number`,	 $dateformat `memship_date`,	`picture` FROM `member` WHERE `nfc_serial` = '$input_nfc'";
      $result = $mysqli->query($query);
      $row_count = mysqli_num_rows($result);
      if($row_count == 0) { echo "ID Does not exist $query";} else
      {
        if($row_count > 1) { echo "ID returns more than 1 record";} else{}
          ?>
          <div class="table-responsive">
           <table class="table">
            <tr>
              <th>picture</th>
              <th>osca_id</th>
              <th>nfc_serial</th>
              <th>first_name</th>
              <th>middle_name</th>
              <th>last_name</th>
              <th>birth_date</th>
              <th>sex</th>
              <th>contact_number</th>
              <th>membership_date</th>
            </tr>

          <?php
          while($row = mysqli_fetch_array($result))
          {
          ?>
            <tr id="<?php echo $row['id']?>">
              <td><img src="../resources/members/<?php echo $row['picture'] ?>" class="member_picture"></td>
              <td><?php echo $row['osca_id'] ?></td>
              <td><?php echo $row['nfc_serial'] ?></td>
              <td><?php echo $row['first_name'] ?></td>
              <td><?php echo $row['middle_name'] ?></td>
              <td><?php echo $row['last_name'] ?></td>
              <td><?php echo $row['birth_date'] ?></td>
              <td><image class="table_icon" src="../resources/images/<?php if($row['sex'] == 'f'||$row['sex'] == 'F'){echo "female";}else{echo "male";}?>.png">
              <td><?php echo $row['contact_number'] ?></td>
              <td><?php echo $row['memship_date'] ?></td>
            </tr>
          <?php

          }
            echo "</table></div>";
      }
      mysqli_close($mysqli);// Closing Connection
  } else {
      echo "invalid id";
  }
?>
