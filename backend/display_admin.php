<?php
  include('../backend/conn.php');

  $db = mysqli_connect($host,$user,$pass,$schema);

  $query = "SELECT * FROM `admin`";
  $result = $mysqli->query($query);
  $row = mysqli_num_rows($result);
?>
<div class="table-responsive">
 <table class="table table-hover users">
  <tr>
    <th>Picture</th>
    <th>Username</th>
    <th>Firstname</th>
    <th>Lastname</th>
    <th>Position</th>
    <th>Active</th>
    <th></th>
  </tr>


<?php

while($row = mysqli_fetch_array($result))
{
?>
  <tr class="admin-row" id="adminNum_<?php echo $row['id']?>">
    <td><img src="../resources/<?php if($row['avatar'] == "null"){echo "images/unknown_m_f.png";}else{echo "avatars/".$row['avatar'];}?>" class="avatar rounded" alt="<?php echo $row['user_name'] ?>"></td>
    <td><?php echo $row['user_name'] ?></td>
    <td><?php echo $row['first_name'] ?></td>
    <td><?php echo $row['last_name'] ?></td>
    <td><?php echo ucfirst($row['position']) ?></td>
    <td class="<?php if($row['is_enabled'] == 1){echo "active";}else{echo "inactive";}?>"></td>
    <td><button class="edit_button">...</button></td>
  </tr>
<?php

}
  echo "</table>";
  mysqli_close($mysqli);// Closing Connection
?>
</div>
