<?php
    $ismodal = true;
    if($ismodal) { ?>

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
            <?php
    }else { ?>
        <div class="mbody">
    <?php
    }

?>

    <?php
    include('../backend/conn.php');
    $db = mysqli_connect($host,$user,$pass,$schema);

        if(isset($_POST['member_id']) || isset($_POST['input_nfc']))
        {
            if(isset($_POST['member_id']))
            {
                $member_id = $_POST['member_id'];
                $nfc_or_id = "`id` = '$member_id'";
            } else {
                $input_nfc = $_POST['input_nfc'];
                $nfc_or_id = "`nfc_serial` = '$input_nfc'";
            }
            $format_memdate = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
            $format_bdate = "concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`))";
            $query = "SELECT `id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	$format_bdate  `bdate`,	`sex`,	`contact_number`,	 $format_memdate `memship_date`,	`picture` FROM `member` WHERE $nfc_or_id";
            $result = $mysqli->query($query);
            $row_count = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if($row_count == 0) { echo 'No record found';} else
            {
                if($row_count > 1) { echo 'Member returns more than 1 record';} else{}
                $member_id = $row['id'];
                $osca_id = $row['osca_id'];
                $first_name = $row['first_name'];
                $middle_name =  $row['middle_name'];
                $last_name =  $row['last_name'];
                $sex =  $row['sex'];
                $bdate =  $row['bdate'];
                $contact_number =  $row['contact_number'];
                
                ?>

                
                    <div class="card user-card">
                        <img src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'" class="rounded-circle"'; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                        <p><?php echo $last_name; ?>,</p>
                        <p><?php echo $first_name; ?> <?php echo $middle_name; ?></p>
                        <p>OSCA ID: <?php echo $osca_id; ?> </p>
                        <p>Sex: <?php if($sex == 'f'|| $sex == 'F'){echo "Female";}else{echo "Male";} ?> </p>
                        <p>Birthdate: <?php echo $bdate; ?> </p>
                        <?php
                            $selected_id = $member_id;
                            include("../backend/read_address.php");
                        ?>
                        <p>Phone Number: <?php echo $contact_number; ?> </p>
                        <p>E-mail:  </p>
                        
                        <p>
                        </p>
                    </div>
                
                <div class="footer">
                    <button type="button" id="view" class="btn btn-light btn-lg btn-block">View</button>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
                </div>


                
                <?php

            }
            mysqli_close($mysqli);// Closing Connection
        } else {
            echo 'Invalid member'; 
        }
    if($ismodal) { ?>

                    </div>
                </div>
            </div>
            <?php
    }else { ?>
            </div>
    <?php
    }

    ?>


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