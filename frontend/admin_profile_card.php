<div class="modal-dialog digital-card">
    <div class="modal-content">
        <div class="modal-body">
            <?php
                if(isset($_POST['user']))
                {
                    include('../backend/conn.php');
                    include('../backend/php_functions.php');
                    $user = $_POST['user'];
                    $query = "SELECT `user_name`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `position`, `answer1`, `answer2`, `avatar`
                                FROM `admin` WHERE `user_name` = '$user'";
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
                            $sex2 = $row['sex'];
                            $contact_number = $row['contact_number'];
                            $email = $row['email'];
                            $position = strtolower($row['position']);
                            $answer1 = $row['answer1'];
                            $answer2 = $row['answer2'];
                            $avatar = '../resources/avatars/'.$row["avatar"];
                            if (file_exists($avatar) && $row["avatar"] != null) { 
                                // something
                            } else {
                                $avatar = "../resources/images/unknown_m_f.png"; 
                            }
                        }
                        ?>

                        
                            <div class="card card digital-card-contents">
                                <div class="card-right">
                                    <img class="picture" src=<?php echo $avatar;?>>
                                </div>
                                <p><?php echo $last_name; ?>,</p>
                                <p><?php echo $first_name; ?> <?php echo $middle_name; ?></p>
                                <p>Username: <?php echo $user_name; ?> </p>
                                <p>Position: <?php echo $position; ?> </p>
                                <p>Sex:  <?php echo determine_sex($sex2, "display_long"); ?> </p>
                                <p>Birthdate: <?php echo $birthdate; ?> </p>
                                <p>Contact Number: <?php echo $contact_number; ?> </p>
                                <p>E-mail: <?php echo $email; ?> </p>
                                
                                <p>
                                </p>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" id="view" class="btn btn-light btn-lg btn-block">View</button>
                                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
                            </div>
                            <input type="hidden" id="user_<?php echo $user_name;?>" name="user_name">
                        
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
        
        
        var user = $('input[name="user_name"]').attr("id").replace("user_", "")
        var url = '../frontend/user_profile.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="user" value="' + user + '" />' +
                        '</form>');
        $('div.card').append(form);
        form.submit();
        
    });
});
</script>