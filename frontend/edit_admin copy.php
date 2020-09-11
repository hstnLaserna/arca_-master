<?php
include('header.php');
include('../backend/position_check.php');
include('../backend/php_functions.php');
if(isset($_GET['user']) && $logged_position == "admin")
{
    $full_edit = true;
    $user = $_GET['user'];
    $query_basis = "`user_name` = '$user'";
} else {
    $full_edit = false;
    $user_name=$_SESSION['login_user'];
    $query_basis = "`user_name` = '$user_name'";
}
{
    $query = "SELECT `id`, `user_name`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `position`, `answer1`, `answer2`, `avatar`
                FROM `admin` WHERE $query_basis";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);

    if($row_count == 1) {
        $row = mysqli_fetch_assoc($result);
        {
            $admin_id = $row['id'];
            $user_name = $row['user_name'];
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = $row['last_name'];
            $birthdate = $row['birth_date'];
            $sex2 = $row['sex'];
            $contact_number = $row['contact_number'];
            $email = $row['email'];
            $position = strtolower($row['position']);
            $answer1 = $row['answer1'];
            $answer2 = $row['answer2'];
            
            $avatar = '../resources/avatars/'.$row["avatar"]; 
            if (file_exists($avatar) && $row["avatar"] == null) { $avatar = '../resources/images/unknown_m_f.png'; }
        }
        ?>
        <div>
            <div class="profile-picture-container">
                <img class="profile-picture" src="<?php echo $avatar; ?>" id="output">
            </div>
            <form action="../backend/photo_upload.php" method="post" enctype="multipart/form-data" autocomplete="off" id="edit_admin">
                <table class="table modal-form">
                    <tr>
                        <td>
                        Username
                        </td>
                        <td>
                        <input type="text" class="form-control " name="user_name" placeholder="<?php echo $user_name?>" value="<?php echo $user_name?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Password <small> <i> (leave blank to keep old password) </i> </small>
                        </td>
                        <td>
                        <input type="password" class="form-control " name="password">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Firstname
                        </td>
                        <td>
                        <input type="text" class="form-control " name="first_name" placeholder="<?php echo $first_name?>" value="<?php echo $first_name?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Middlename
                        </td>
                        <td>
                        <input type="text" class="form-control " name="middle_name" id="midname" placeholder="<?php echo $middle_name?>" value="<?php echo $middle_name?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Lastname
                        </td>
                        <td>
                        <input type="text" class="form-control " name="last_name" placeholder="<?php echo $last_name?>" value="<?php echo $last_name?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Birthdate
                        </td>
                        <td>
                        <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Gender
                        </td>
                        <td>
                        <div class="form-group">
                        <select class="form-control" name="gender">
                            <option <?php if($sex2 == "0" || $sex2 > "2"){echo "selected";}else{}; ?>>-</option>
                            <option <?php if($sex2 == "2"){echo "selected";}else{}; ?>>Female</option>
                            <option <?php if($sex2 == "1"){echo "selected";}else{}; ?>>Male</option>
                        </select>
                        </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Contact Number
                        </td>
                        <td>
                            <input type="text" class="form-control " name="contact_number" placeholder="<?php echo $last_name?>" value="<?php echo $contact_number?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email
                        </td>
                        <td>
                            <input type="text" class="form-control " name="email" placeholder="<?php echo $email?>" value="<?php echo $email?>">
                        </td>
                    </tr>

                    <?php
                     if($full_edit && $_SESSION['user_name'] != $user_name)
                     { ?>
                        <tr>
                            <td>
                            Position
                            </td>
                            <td>
                            <div class="form-group">
                            <select class="form-control" name="position">
                                <option <?php if($position != 'admin' & $position != "user"){echo "selected";}else{echo "$position";};?>>-</option>
                                <option <?php if($position == 'admin'){echo "selected";}else{echo "$position";};?>>Admin</option>
                                <option <?php if($position == 'user'){echo "selected";}else{};?>>User</option>
                            </select>
                            </div>
                            </td>
                        </tr>
                        <?php 
                    }?>
                    <tr>
                        <td>
                        Security Answer 1
                        </td>
                        <td>
                        <input type="text" class="form-control" name="security_answer_1"  placeholder="<?php echo $answer1?>" value="<?php echo $answer1?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Security Answer 2
                        </td>
                        <td>
                        <input type="text" class="form-control" name="security_answer_2"  placeholder="<?php echo $answer2?>" value="<?php echo $answer2?>">
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="selected_id" id ="selected_id" value="<?php echo $admin_id;?>">
                <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
                <button type="reset" class="btn btn-secondary btn-lg btn-block">Reset Values</button>
            </form>
        </div>
        <?php
        mysqli_close($mysqli);
    } else{
        include('../backend/fail_data.php');
    }
}
include('foot.php');
?>


<script>
    
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
  /*

    var inputs = document.querySelectorAll('.inputfile');

    Array.prototype.forEach.call(inputs, function(input)
    {
        var label	 = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener('change', function(e)
        {
            var fileName = '';

            if(fileName)
                label.querySelector('span').innerHTML = fileName;
            else
                label.innerHTML = labelVal;
        });
    });
    */

  $(document).ready(function(){

    $('input[name!="middle_name"]').blur(function(){
        if($(this).val().length === 0) {
            $(this).addClass('input_error');
        }
        else{
            $(this).removeClass('input_error');
        }
    });

    $("#submit").click(function(){
        $.post("../backend/update_admin.php", $("#edit_admin").serialize(), function(d){
            if(d == "true") {
                var new_user = $('input[name="user_name"]').val();
                location.replace("../frontend/user_profile.php?user=" + new_user);
            } else {
                alert(d);
            }
        });
    });
  });
</script>
