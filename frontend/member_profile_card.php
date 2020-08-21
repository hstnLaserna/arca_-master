<?php 
  include('../backend/php_functions.php');
?>
<div class="modal-dialog digital-card">
    <div class="modal-content">
        <div class="modal-body">
            <?php
                if(isset($_POST['member_id']) || isset($_POST['input_nfc']))
                {
                    include('../backend/conn.php');
                    if(isset($_POST['member_id']))
                    {
                        $member_id = $_POST['member_id'];
                        $nfc_or_id = "`member_id` = '$member_id'";
                        $is_modal = true;
                    } else {
                        $input_nfc = $_POST['input_nfc'];
                        $nfc_or_id = "`nfc_serial` = '$input_nfc'";
                        $is_modal = false;
                    }
                    $format_memdate = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
                    $format_bdate = "concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`))";

                    $query = "SELECT * FROM view_members_with_guardian WHERE $nfc_or_id";

                    $result = $mysqli->query($query);
                    $row_count = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                    if($row_count == 0) { echo 'No record found';} else
                    {
                        if($row_count > 1) { echo 'Member returns more than 1 record';} else{}
                        $member_id = $row['member_id'];
                        $osca_id = $row['osca_id'];
                        $first_name = $row['first_name'];
                        $middle_name =  $row['middle_name'];
                        $last_name =  $row['last_name'];
                        $sex2 =  $row['sex'];
                        $bdate =  $row['bdate'];
                        $age =  $row['age'];
                        $memship_date =  $row['memship_date'];
                        $contact_number =  $row['contact_number'];
                        $email =  $row['email'];
                        
                        ?>

                    
                        <div class="card digital-card-contents">
                            <div class="card-right">
                                <img class="picture" src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'" '; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                                <p><?php echo $osca_id; ?> <br><i>OSCA ID</i></p>
                                <p><?php echo $memship_date; ?> <br><i>Membership Date</i></p>
                            </div>
                            <div class="card-left">
                                <p class="mb-0"><?php echo $last_name; ?>,</p>
                                <p><?php echo $first_name; ?> <?php echo $middle_name; ?></p>
                                
                                <p>Sex:  <?php echo determine_sex($sex2, "display_long"); ?> </p>
                                <p>Birthdate: <?php echo "$bdate (Age: $age y.o.)"; ?></p>
                                    <?php
                                        read_address($member_id);
                                    ?>
                                <p>Phone Number: <?php echo $contact_number; ?> </p>
                                <p>E-mail: <?php echo $email; ?> </p>
                            </div>
                        </div>
                    
                        <div class="footer">
                            <button type="button" id="view" class="btn btn-light btn-lg btn-block">View</button>
                                <?php 
                                if($is_modal)
                                {
                                ?>
                            <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
                        </div>  <?php
                                } else { ?>
                        </div>  <?php }
                    }
                    mysqli_close($mysqli);// Closing Connection
                } else {
                    echo 'Invalid member'; 
                }
            ?>


        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    $('#view').click(function () {
        var url = '../frontend/member_profile.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="member_id" value="' + <?php echo $member_id?> + '" />' +
                        '</form>');
        $('div.card').append(form);
        form.submit();
        
    });
});
</script>