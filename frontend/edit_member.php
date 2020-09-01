<?php
include('header.php');
include('../backend/php_functions.php');
if(isset($_GET['member_id'])/* && isset($_GET['last_name'])*/)
{
    $osca_id = $_GET['member_id'];
    $query = "SELECT 	`id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	`birth_date`,
                        `sex`,	`contact_number`, `email`, `membership_date`,	`picture` FROM `member` WHERE `osca_id` = $osca_id";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);

    if($row_count == 1) {
        $row = mysqli_fetch_assoc($result);
        {
            $member_id = $row['id'];
            $osca_id = $row['osca_id'];
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = $row['last_name'];
            $birthdate = $row['birth_date'];
            $sex2 = $row['sex'];
            $contact_number = $row['contact_number'];
            $email =  $row['email'];
            $nfc_serial = $row['nfc_serial'];
            $membership_date = $row['membership_date'];
            $membership_date = date('Y-m-d', strtotime($membership_date));
        }
        ?>
        <div class="registration-form" id="accordion">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="editMember">
            
                    <div class="collapse-header" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <h3> PERSONAL DETAILS</H3>
                    </div>
                    <div id="collapseOne" class="form-contents collapse show" aria-labelledby="headingOne">
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
                        <div class ="row">
                            <div class ="col col-lg-6 col-12">
                                Birthdate
                                <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate?>">
                            </div>
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
                        </div>
                        <div>
                            Contact Number
                            <input type="text" class="form-control " name="contact_number" placeholder="<?php echo $contact_number?>" value="<?php echo $contact_number?>">
                        </div>
                        <div>
                            Email
                            <input type="text" class="form-control " name="email" placeholder="<?php echo $email?>" value="<?php echo $email?>">
                        </div>
                    </div>
                    <div class="collapse-header" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h3> MEMBERSHIP DETAILS </H3>
                    </div>
                    <div id="collapseTwo" class="form-contents collapse" aria-labelledby="headingTwo">
                        <div>
                            Membership date
                            <input type="date" class="form-control" name="membership_date" value="<?php echo $membership_date?>">
                        </div>
                        <div>
                            OSCA ID
                            <input type="text" class="form-control" name="osca_id"  placeholder="<?php echo $osca_id?>" value="<?php echo $osca_id?>">
                        </div>
                        <div>
                            NFC Serial
                            <input type="text" class="form-control" name="nfc_serial"  placeholder="<?php echo $nfc_serial?>" value="<?php echo $nfc_serial?>">
                        </div>
                        <div>
                            Password 
                            </br><small> <i> (leave blank to keep old password) </i> </small>
                            <input type="password" class="form-control " name="password">
                        </div>
                    </div>

                <input type="hidden" name="selected_id" id="selected_id" value="<?php echo $osca_id;?>">
                <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
                <button type="reset" class="btn btn-secondary btn-lg btn-block">Reset Values</button>
            </form>
        </div>
        <?php
        mysqli_close($mysqli);
    } else{
        echo "ID and Lastname does not match";
    }
} else
{
    echo "ID and Lastname could not be read";
    return false;
}
include('foot.php');
?>


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

    $("#submit").click(function(){
        $.post("../backend/update_member.php", $("#editMember").serialize(), function(d){
            if(d == "true") {
                var member_id = $("#selected_id").val();
                location.replace("member_profile.php?member_id=" + member_id);
            } else {
                alert(d);
            }
        });
    });
  });
</script>
