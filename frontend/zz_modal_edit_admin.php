<div class="modal-dialog">
  <div class="modal-content">
  <?php
      include('../backend/conn.php');

      $db = mysqli_connect($host,$user,$pass,$schema);


      if(isset($_POST['admin_id'])) {
          $admin_id = $_POST['admin_id'];
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
