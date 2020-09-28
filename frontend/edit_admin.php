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
        

    <div id="management">
        <div class="card">
            <div class="registration-form">
              <h3 class="registration-title">Edit <?php echo $first_name?>'s Account</h3>
                <form method="post" enctype="multipart/form-data" autocomplete="off" id="edit_admin">
                    <div class="form-contents">
                        <div>
                            Username
                            
                        <input type="text" class="form-control " name="user_name" placeholder="<?php echo $user_name?>" value="<?php echo $user_name?>">
                        </div>
                        <div>
                        Password <small> <i> (leave blank to keep old password) </i> </small>
                            <input type="password" class="form-control " name="password">
                        </div>
                        <div>
                            Firstname
                            <input type="text" class="form-control " name="first_name" placeholder="<?php echo $first_name?>" value="<?php echo $first_name?>">
                        </div>
                        <div>
                            Middlename
                            <input type="text" class="form-control " name="middle_name" id="midname" placeholder="<?php echo $middle_name?>" value="<?php echo $middle_name?>">
                        </div>
                        <div>
                            Lastname
                            <input type="text" class="form-control " name="last_name" placeholder="<?php echo $last_name?>" value="<?php echo $last_name?>">
                        </div>
                        <div>
                            Birthdate
                            <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate?>">
                        </div>
                        <div class ="row">
                            <div class ="col col-lg-6 col-12">
                                Gender
                                <div class="form-group">
                                    <select class="form-control" name="gender">
                                        <option <?php if($sex2 == "0" || $sex2 > "2"){echo "selected";}else{}; ?>>-</option>
                                        <option <?php if($sex2 == "2"){echo "selected";}else{}; ?>>Female</option>
                                        <option <?php if($sex2 == "1"){echo "selected";}else{}; ?>>Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class ="col col-lg-6 col-12">
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
                            </div>
                        </div>
                        <div>
                            Contact Number
                            <input type="text" class="form-control " name="contact_number" placeholder="<?php echo $last_name?>" value="<?php echo $contact_number?>">
                        </div>
                        <div>
                            Email
                            <input type="text" class="form-control " name="email" placeholder="<?php echo $email?>" value="<?php echo $email?>">
                        </div>
                        <div>
                            Security Answer 1
                            <input type="text" class="form-control" name="security_answer_1"  placeholder="<?php echo $answer1?>" value="<?php echo $answer1?>">
                        </div>
                        <div>
                            Security Answer 2
                            <input type="text" class="form-control" name="security_answer_2"  placeholder="<?php echo $answer2?>" value="<?php echo $answer2?>">
                        </div>
                        <!--
                        <div>
                        Picture
                        <input type="file" name="photo" id="fileSelect" >
                        </div>
                        -->
                        </div>
                        <input type="hidden" name="selected_id" id="selected_id" value="<?php echo $admin_id;?>">
                    </div>
                </form>
                <button type="button" class="btn btn-light btn-lg btn-block" id="submit">Submit</button>
                <button type="reset" class="btn btn-close btn-lg btn-block">Reset Values</button>
            </div>
        </div>
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
            var status = d.trim();
            if(status == "true") {
                var new_user_name = $('input[name="user_name"]').val();
                location.replace("../frontend/user_profile.php?user=" + new_user_name);
            } else {
                alert(d);
            }
        });
    });

  });
</script>
