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
    <th style="text-align: center;">Active</th>
  </tr>


<?php

while($row = mysqli_fetch_array($result))
{
?>
  <tr id="adminNum_<?php echo $row['id']?>">
    <td><img src="<?php $picture = '../resources/avatars/'.$row["avatar"]; if (file_exists($picture)) { echo $picture; } else{ echo "../resources/images/unknown_m_f.png"; } ?>" class="avatar rounded-circle view-admin" alt="<?php echo $row['user_name'] ?>"></td>
    <td><?php echo $row['user_name'] ?></td>
    <td><?php echo $row['first_name'] ?></td>
    <td><?php echo $row['last_name'] ?></td>
    <td><?php echo ucfirst($row['position']) ?></td>
    <td class="<?php if($row['is_enabled'] == 1){echo "active";}else{echo "inactive";}?>"></td>
  </tr>
<?php

}
  echo "</table>";
  mysqli_close($mysqli);// Closing Connection
?>
</div>
