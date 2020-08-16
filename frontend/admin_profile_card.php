
<div class="modal-dialog personal-card">
    <div class="modal-content">
        <div class="modal-body">

            <?php
            include('../backend/conn.php');
            if(isset($_POST['admin_id']))
            {
                $admin_id = $_POST['admin_id'];
                $query = "SELECT `user_name`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `position`, `answer1`, `answer2`, `avatar` FROM `admin` WHERE `id` = $admin_id";
                $result = $mysqli->query($query);
                $row_count = mysqli_num_rows($result);
                $row = mysqli_fetch_assoc($result);
                if($row_count == 0) { echo 'No record found';} else
                {
                    if($row_count > 1) { echo 'Admin returns more than 1 record';} else{}
                    {
                        $user_name = $row['user_name'];
                        $first_name = $row['first_name'];
                        $middle_name = $row['middle_name'];
                        $last_name = $row['last_name'];
                        $birthdate = $row['birth_date'];
                        $sex = $row['sex'];
                        $position = strtolower($row['position']);
                        $answer1 = $row['answer1'];
                        $answer2 = $row['answer2'];
                        $avatar = $row['avatar'];
                    }
        
                    ?>

                    
                        <div class="card">
                            <img class="card-picture" src=<?php $avatar = '../resources/avatars/'.$row["avatar"]; if (file_exists($avatar) && $row["avatar"] != null) { echo '"'.$avatar.'"'; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
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
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" id="view" class="btn btn-light btn-lg btn-block">View</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
                    </div>


                    
                    <?php

                }
                mysqli_close($mysqli);
            } else {
                echo 'Invalid admin'; 
            }
            ?>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    $('#view').click(function () {
        var url = '../frontend/user_profile.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="admin_id" value="' + <?php echo $admin_id?> + '" />' +
                        '</form>');
        $('div.card').append(form);
        form.submit();
        
    });
});
</script>