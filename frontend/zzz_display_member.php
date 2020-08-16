<div class="modal-dialog">
  <div class="modal-content">
  <?php
    include('../backend/conn.php');
    $db = mysqli_connect($host,$user,$pass,$schema);


    if(isset($_POST['id']))
    {
      $member_id = $_POST['id'];
      $query = "SELECT 	`id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	`birth_date`,
      `sex`,	`contact_number`,	`membership_date`,	`picture` FROM `member` WHERE `id` = $member_id";
      $result = $mysqli->query($query);
      $row_count = mysqli_num_rows($result);

      if($row_count == 1)
      {
        $row = mysqli_fetch_assoc($result);
        {
          $osca_id = $row['osca_id'];
          $first_name = $row['first_name'];
          $middle_name = $row['middle_name'];
          $last_name = $row['last_name'];
          $birthdate = $row['birth_date'];
          $membership_date = $row['membership_date'];
          $sex2 = $row['sex'];
          $contact_number = $row['contact_number'];
          $avatar = $row['picture'];
        }

        ?>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="displayMember">
            <table class="table align-middle">
              <tr>
                <td class="align-middle">
                  Firstname
                </td>
                <td class="align-middle">
                  <input type="text" class="form-control " name="edit_first_name" placeholder="<?php echo $first_name?>" value="<?php echo $first_name?>">
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  Middlename
                </td>
                <td class="align-middle">
                  <input type="text" class="form-control " name="edit_middle_name"placeholder="<?php echo $middle_name?>" value="<?php echo $middle_name?>">
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  Lastname
                </td>
                <td class="align-middle">
                  <input type="text" class="form-control " name="edit_last_name" placeholder="<?php echo $last_name?>" value="<?php echo $last_name?>">
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  Birthdate
                </td>
                <td class="align-middle">
                  <input type="date" class="form-control" name="edit_birthdate" value="<?php echo $birthdate?>">
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  Gender
                </td>
                <td class="align-middle">
                  <div class="form-group">
                    <select class="form-control" name="edit_gender">
                      <option <?php if($sex2 == 'f'){echo "selected";}else{};?>>Female</option>
                      <option <?php if($sex2 == 'm'){echo "selected";}else{};?>>Male</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  Contact Number
                </td>
                <td class="align-middle">
                  <input type="text" class="form-control" name="edit_contact_number"  placeholder="<?php echo $contact_number?>" value="<?php echo $contact_number?>">
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  OSCA ID
                </td>
                <td class="align-middle">
                  <input type="text" class="form-control" name="edit_osca_id"  placeholder="<?php echo $osca_id?>" value="<?php echo $osca_id?>">
                </td>
              </tr>
              <!--
              <tr>
                <td class="align-middle">
                  Picture
                </td>
                <td class="align-middle">
                  <input type="file" name="photo" id="fileSelect" >
                </td>
              </tr>
              -->

            </table>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-lg btn-block" id="submit" value="Submit">Ipasa</button>
            <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">Close</button>
          </div>
        <?php

        mysqli_close($mysqli);// Closing Connection
      } else
      {
        echo "That ID does not exist ";
      }
    } else
    {
        echo "Invalid ID";
        return false;
    }
 ?>

 </div>
</div>


<script>
  $(document).ready(function(){

    $('#editUser input').blur(function(){
      if($(this).val().length === 0 ) {
          $(this).addClass('input_error');
      }
      else{
            $(this).removeClass('input_error');
      }
    });

    $("#submit").click(function(){
      $.post("../backend/add_admin.php", $("#newAdmin").serialize(), function(d){
       alert(d);
      });
    });
  });
</script>
