<?php
include('head.php');
if(isset($_GET['member_id'])/* && isset($_GET['last_name'])*/)
{
    $member_id = $_GET['member_id'];
    $query = "SELECT 	`id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	`birth_date`,
                        `sex`,	`contact_number`, `membership_date`,	`picture` FROM `member` WHERE `id` = $member_id";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);

    if($row_count == 1) {
        $row = mysqli_fetch_assoc($result);
        {
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = $row['last_name'];
            $birthdate = $row['birth_date'];
            $sex2 = $row['sex'];
            $contact_number = $row['contact_number'];
            $osca_id = $row['osca_id'];
            $nfc_serial = $row['nfc_serial'];
            $membership_date = $row['membership_date'];
            $membership_date = date('Y-m-d', strtotime($membership_date));
        }
        ?>
        <div>
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="editMember">
                <table class="table modal-form">
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
                                <option <?php if($sex2 == 'f'){echo "selected";}else{};?>>Female</option>
                                <option <?php if($sex2 == 'm'){echo "selected";}else{};?>>Male</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Membership date
                        </td>
                        <td>
                            <input type="date" class="form-control" name="membership_date" value="<?php echo $membership_date?>">
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
                            OSCA ID
                        </td>
                        <td>
                            <input type="text" class="form-control" name="osca_id"  placeholder="<?php echo $osca_id?>" value="<?php echo $osca_id?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            NFC Serial
                        </td>
                        <td>
                            <input type="text" class="form-control" name="nfc_serial"  placeholder="<?php echo $nfc_serial?>" value="<?php echo $nfc_serial?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Password 
                            </br><small> <i> (leave blank to keep old password) </i> </small>
                        </td>
                        <td>
                            <input type="password" class="form-control " name="password">
                        </td>
                    </tr>
                        <!--
                    <tr>
                        <td>
                            Picture
                        </td>
                        <td>
                            <input type="file" name="photo" id="fileSelect" >
                        </td>
                    </tr>
                        -->

                </table>
                <input type="hidden" name="selected_id" id="selected_id" value="<?php echo $member_id;?>">
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
