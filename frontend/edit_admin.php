<div class="modal-dialog">
  <div class="modal-content">
  <?php
      include('../backend/conn.php');

      $db = mysqli_connect($host,$user,$pass,$schema);

      $admin_id = $_POST['id'];

      if(isset($_POST['id'])) {
          $input_nfc = $_POST['id'];
          $query = "SELECT `user_name`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `position`, `answer1`, `answer2`, `avatar` FROM `admin` WHERE `id` = $admin_id";
          $result = $mysqli->query($query);
          $row_count = mysqli_num_rows($result);

          if($row_count == 1)
          {
            $row = mysqli_fetch_assoc($result);
            {
              $user_name = $row['user_name'];
              $first_name = $row['first_name'];
              $middle_name = $row['middle_name'];
              $last_name = $row['last_name'];
              $birthdate = $row['birth_date'];
              $sex2 = $row['sex'];
              $position = strtolower($row['position']);
              $answer1 = $row['answer1'];
              $answer2 = $row['answer2'];
              $avatar = $row['avatar'];
            }

          ?>
            <div class="modal-body">
              <form method="post" enctype="multipart/form-data" autocomplete="off" id="editUser">
              <table class="table align-middle">
                <tr>
                  <td class="align-middle">
                    Username
                  </td>
                  <td class="align-middle">
                    <input type="text" class="form-control " name="edit_user_name" placeholder="<?php echo $user_name?>" value="<?php echo $user_name?>">
                  </td>
                </tr>
                <tr>
                  <td class="align-middle">
                    Password
                  </td>
                  <td class="align-middle">
                    <input type="password" class="form-control " name="edit_password">
                  </td>
                </tr>
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
                    Position
                  </td>
                  <td class="align-middle">
                    <div class="form-group">
                      <select class="form-control" name="edit_position">
                        <option <?php if($position == 'admin'){echo "selected";}else{echo "$position";};?>>Admin</option>
                        <option <?php if($position == 'user'){echo "selected";}else{};?>>User</option>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="align-middle">
                    Security Answer 1
                  </td>
                  <td class="align-middle">
                    <input type="text" class="form-control" name="edit_security_answer_1"  placeholder="<?php echo $answer1?>" value="<?php echo $answer1?>">
                  </td>
                </tr>
                <tr>
                  <td class="align-middle">
                    Security Answer 2
                  </td>
                  <td class="align-middle">
                    <input type="text" class="form-control" name="edit_security_answer_2"  placeholder="<?php echo $answer2?>" value="<?php echo $answer2?>">
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
        } else{
            echo "That ID does not exist ";
        }
      } else {
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
