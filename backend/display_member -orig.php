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
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)).(substr($last_name, 0,1));?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
  <tr id="<?php echo $id?>">
    <td><img src="../resources/members/<?php echo $picture ?>" class="member_picture img-circle" 
              alt="<?php echo (substr($first_name, 0,1)).(substr($middle_name, 0,1)). $last_name;?>"></td>
    <td><?php echo $osca_id ?></td>
    <td><?php echo $nfc_serial ?></td>
    <td><?php echo $first_name ?></td>
    <td><?php echo $middle_name ?></td>
    <td><?php echo $last_name ?></td>
    <td><?php echo $birth_date ?></td>
    <td><image class="table_icon" src="../resources/images/<?php if($sex == 'f'||$sex == 'F'){echo "female";}else{echo "male";}?>.png">
    <td><?php echo $contact_number ?></td>
    <td><?php echo $membership_date ?></td>
  </tr>
<?php

}
  echo "</table>";
  mysqli_close($mysqli);// Closing Connection
?>
</div>
