<?php
  include('../frontend/head.php');
?>
<div class="admin-profile">
<?php
    include('../backend/conn.php');
    if(isset($_GET['admin_id']) && $logged_position == "admin")
    {
        $admin_id = $_GET['admin_id'];
        $query_basis = "`id` = $admin_id";
    } else {
        $user_name=$_SESSION['login_user'];
        $query_basis = "`user_name` = '$user_name'";
    }

    $query = "SELECT `id`, `user_name`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `position`, `answer1`, `answer2`, `avatar` FROM `admin` WHERE $query_basis";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
    if($row_count == 0) { echo 'No record found  ' . $query;} else
    {
        if($row_count > 1) { echo 'Admin returns more than 1 record';} else{}
        {
            $admin_id = $row['id'];
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
            if($user_name == $_SESSION['user_name']){
                $personal_profile = true;
            }  else {$personal_profile = false;}
        }

        ?>
            <div class="card">
                <img class="profile-picture"src=<?php $avatar = '../resources/avatars/'.$row["avatar"]; if (file_exists($avatar) && $row["avatar"] != null) { echo  "$avatar"; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                <p><?php echo $last_name; ?>,</p>
                <p><?php echo $first_name; ?> <?php echo $middle_name; ?></p>
                <p>Username: <?php echo $user_name; ?> </p>
                <p>Position: <?php echo $position; ?> </p>
                <p>Sex: <?php if($sex == 'f'|| $sex == 'F'){echo "Female";}else{echo "Male";} ?> </p>
                <p>Birthdate: <?php echo $birthdate; ?> </p>
                <p>Phone Number: </p>
                <p>E-mail:  </p>
                
                <p>
                </p>
                <button type="button" id="edit" class="btn btn-secondary btn-lg btn-block">Edit</button>
            </div>
        <?php

    }
    mysqli_close($mysqli);
?>
</div>


<div class="container">
</div>


<?php
  include('../frontend/foot.php');
  if($logged_position == "admin" && !$personal_profile)
  {
    $user_edit_method = "get";
  } else {
    $user_edit_method = "post";
  }
?>
<script>
$(document).ready(function(){
    $('#edit').click(function () {
        var url = 'edit_admin.php';
        var form = $(   '<form action="' + url + '" method="<?php echo $user_edit_method?>">' +
                            '<input type="hidden" name="admin_id" value="' + <?php echo $admin_id?> + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });
});
</script>