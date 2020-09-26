<?php 
    include('../backend/php_functions.php');

    // declare variables 
    $osca_id = "";
    $member_id = "";
    $first_name = "";
    $middle_name =  "";
    $last_name =  "";
    $sex2 =  "";
    $contact_number =  "";
    $email =  "";
    $bdate =  "";
    $age =  "";
    $memship_date =  "";
    $picture = "../resources/images/unknown_m_f.png";
    $member_buttons = '';
    
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
                        $nfc_or_id = " m.`id` = '$member_id' ";
                        $is_modal = true;
                    } else {
                        $input_nfc = $_POST['input_nfc'];
                        $nfc_or_id = " m.`nfc_serial` = '$input_nfc' ";
                        $is_modal = false;
                    }
                    $format_memdate = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
                    $format_bdate = "concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`))";
                    $format_age = "YEAR(CURDATE()) - YEAR(birth_date) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)) ,'%Y-%c-%e') > CURDATE(), 1, 0)";

                    $query = "  SELECT *, $format_bdate `bdate`, $format_memdate `memship_date`, $format_age `age`, m.id `member_id`
                                FROM `member` m
                                INNER JOIN `address` a
                                WHERE $nfc_or_id GROUP BY `osca_id`";

                    $result = $mysqli->query($query);
                    $row_count = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                    if($row_count == 1) {
                        $member_id = $row['member_id'];
                        $osca_id = $row['osca_id'];
                        $first_name = $row['first_name'];
                        $middle_name =  $row['middle_name'];
                        $last_name =  $row['last_name'];
                        $fullname = strtoupper("$first_name $middle_name $last_name");
                        $last_name =  $row['last_name'];
                        $sex2 =  $row['sex'];
                        $bdate =  $row['bdate'];
                        $age =  $row['age'];
                        $memship_date =  $row['memship_date'];
                        $contact_number =  $row['contact_number'];
                        $email =  $row['email'];
                        ?>
                        <div class="card digital-card-contents">
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
                                            <div class="content"><?php echo "$bdate (Age: $age y.o.)"; ?></div>
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
                                            <?php  //address
                                                $addresses = read_address2($member_id, true);
                                                $address_id = $addresses['address_id'];
                                                $address1 = $addresses['address1'];
                                                $address2 = $addresses['address2'];
                                                $city = $addresses['city'];
                                                $province = $addresses['province'];
                                            ?>
                                        <li class='profile-item disp_address'>
                                            <div class="content"><?php echo "$address1, $address2, $city, $province";?></div>
                                            <div class="subtitle">Address</div> 
                                            
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-card-right">
                                <img class="picture" src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'" '; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                                <div class="content"><?php echo $osca_id; ?></div>
                                <div class="subtitle">OSCA ID</div> 
                                <div class="content"><?php echo $memship_date; ?></div>
                                <div class="subtitle">Membership Date</div> 
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
                    } else {
                        include('../backend/fail_data.php');
                    }
                    mysqli_close($mysqli);// Closing Connection
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
        var url = '../frontend/member_profile.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="member_id" value="<?php echo $osca_id ?>" />' +
                        '</form>');
        $('div.card').append(form);
        form.submit();
        
    });
});
</script>