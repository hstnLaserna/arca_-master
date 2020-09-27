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
                    if($row_count == 1)
                    {
                        $user_name = $row['user_name'];
                        $first_name = $row['first_name'];
                        $middle_name = $row['middle_name'];
                        $last_name = $row['last_name'];
                        $fullname = strtoupper("$first_name $middle_name $last_name");
                        $birthdate = $row['birth_date'];
                        $sex2 = $row['sex'];
                        $contact_number = $row['contact_number'];
                        $email = $row['email'];
                        $position = strtolower($row['position']);
                        $answer1 = $row['answer1'];
                        $answer2 = $row['answer2'];
                        
                        $avatar = '../resources/avatars/'.$row["avatar"]; 
                        if (file_exists($avatar) && $row["avatar"] == null) { $avatar = '../resources/images/unknown_m_f.png'; }
                        ?>

                        
                            <div class="card card digital-card-contents">
                                <div class="modal-card-left">
                                    <div class="basic">
                                        <ul class="modal-profile-details">
                                            <li class="profile-item">
                                                <div class="content"><?php echo $fullname; ?></div>
                                                <div class="subtitle">Fullname</div> 
                                            </li>
                                            <li class="profile-item">
                                                <div class="content"><?php echo determine_sex($sex2, "display_long"); ?></div>
                                                <div class="subtitle">Sex</div> 
                                            </li>
                                            <li class="profile-item">
                                                <div class="content"><?php echo $position; ?></div>
                                                <div class="subtitle">Position</div> 
                                            </li>
                                            <li class="profile-item">
                                                <div class="content"><?php echo $birthdate ?></div>
                                                <div class="subtitle">Birthdate</div> 
                                            </li>
                                            <li class="profile-item">
                                                <div class="content"><?php echo $contact_number; ?></div>
                                                <div class="subtitle">Phone Number</div> 
                                            </li>
                                            <li class="profile-item">
                                                <div class="content"><?php echo $email; ?></div>
                                                <div class="subtitle">E-mail</div> 
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                                
                                <div class="modal-card-right">
                                    <img class="picture" src=<?php echo $avatar;?>>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" id="view" class="btn btn-light btn-lg btn-block">View</button>
                                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
                            </div>
                            <input type="hidden" id="user_<?php echo $user_name;?>" name="user_name">
                        
                        <?php

                    } else {
                        include('../backend/fail_data.php');
                    }
                    mysqli_close($mysqli);
                } else {
                    include('../backend/fail_data.php');
                }
            ?>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    $('#view').click(function () {
        var user = $('input[name="user_name"]').attr("id").replace("user_", "");
        location.replace("../frontend/user_profile.php?user=" + user);
    });
});
</script>