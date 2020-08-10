<?php
  include('../backend/conn.php');

  $db = mysqli_connect($host,$user,$pass,$schema);

  $query = "SELECT 	`id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	`birth_date`,	`sex`,	`contact_number`,	`membership_date`,	`picture` FROM `member`";
  $result = $mysqli->query($query);
  $row = mysqli_num_rows($result);
?>
<div class="table-responsive">
 <table class="table table-hover">
   
<?php
while($row = mysqli_fetch_array($result))
{
  $id = $row['id'];
  $osca_id = $row['osca_id'];
  $nfc_serial = $row['nfc_serial'];
  $first_name = $row['first_name'];
  $middle_name = $row['middle_name'];
  $last_name = $row['last_name'];
  $birth_date = $row['birth_date'];
  $sex = $row['sex'];
  $contact_number = $row['contact_number'];
  $membership_date = $row['membership_date'];
  $picture = $row['picture'];
  
?>
  <tr class="member-row" id="memNum_<?php echo $id?>">
    <td><?php echo $osca_id ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
  </tr>
<?php

}
  echo "</table>";
  mysqli_close($mysqli);// Closing Connection
?>
</div>
