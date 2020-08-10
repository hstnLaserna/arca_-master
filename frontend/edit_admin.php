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
                <table class="table modal-form">
                  <tr>
                    <td colspan="2">
                      Username
                      <input type="text" class="form-control " name="user_name" placeholder="<?php echo $user_name?>" value="<?php echo $user_name?>">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Password <small> <i> (leave blank to keep old password) </i> </small>
                      <input type="password" class="form-control " name="password">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Firstname
                      <input type="text" class="form-control " name="first_name" placeholder="<?php echo $first_name?>" value="<?php echo $first_name?>">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Middlename
                      <input type="text" class="form-control " name="middle_name" id="midname" placeholder="<?php echo $middle_name?>" value="<?php echo $middle_name?>">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Lastname
                      <input type="text" class="form-control " name="last_name" placeholder="<?php echo $last_name?>" value="<?php echo $last_name?>">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Birthdate
                      <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate?>">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Gender
                      <div class="form-group">
                        <select class="form-control" name="gender">
                          <option <?php if($sex2 == 'f'){echo "selected";}else{};?>>Female</option>
                          <option <?php if($sex2 == 'm'){echo "selected";}else{};?>>Male</option>
                        </select>
                      </div>
                    </td>
                    <td>
                      Position
                      <div class="form-group">
                        <select class="form-control" name="position">
                          <option <?php if($position == 'admin'){echo "selected";}else{echo "$position";};?>>Admin</option>
                          <option <?php if($position == 'user'){echo "selected";}else{};?>>User</option>
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Security Answer 1
                      <input type="text" class="form-control" name="security_answer_1"  placeholder="<?php echo $answer1?>" value="<?php echo $answer1?>">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      Security Answer 2
                      <input type="text" class="form-control" name="security_answer_2"  placeholder="<?php echo $answer2?>" value="<?php echo $answer2?>">
                    </td>
                  </tr>
                  <!--
                  <tr>
                    <td colspan="2">
                      Picture
                    </td>
                    <td colspan="2">
                      <input type="file" name="photo" id="fileSelect" >
                    </td>
                  </tr>
                  -->

                </table>
                <input type="hidden" name="selected_id" value="<?php echo $admin_id;?>">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-lg btn-block" id="commit_edit" value="Submit">Ipasa</button>
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

    $('input[name!="middle_name"]').blur(function(){
      if($(this).val().length === 0) {
          $(this).addClass('input_error');
      }
      else{
            $(this).removeClass('input_error');
      }
    });

    $("#commit_edit").click(function(){
      $.post("../backend/update_admin.php", $("#editUser").serialize(), function(d){
        alert(d);
      });
    });
  });
</script>
