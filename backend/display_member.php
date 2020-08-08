<?php
  include('../backend/conn.php');

  $db = mysqli_connect($host,$user,$pass,$schema);

  $query = "SELECT * FROM `member`";
  $result = $mysqli->query($query);
  $row = mysqli_num_rows($result);
?>
<div class="table-responsive">
 <table class="table table-hover">
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
    <td><img src="../resources/members/<?php echo $row['picture'] ?>" class="member_pictureimg-circle" alt="<?php echo $row['osca_id'] ?>"></td>
    <td><?php echo $row['osca_id'] ?></td>
    <td><?php echo $row['nfc_serial'] ?></td>
    <td><?php echo $row['first_name'] ?></td>
    <td><?php echo $row['middle_name'] ?></td>
    <td><?php echo $row['last_name'] ?></td>
    <td><?php echo $row['birth_date'] ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($row['sex'] == 'f'||$row['sex'] == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $row['contact_number'] ?></td>
    <td><?php echo $row['membership_date'] ?></td>
  </tr>
<?php

}
  echo "</table>";
  mysqli_close($mysqli);// Closing Connection
?>
</div>
