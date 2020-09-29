<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    // declare variables 

    $osca_id = "";
    $nfc_active = false;
    $account_enabled = false;
    $member_id = "";
    $fullname = "";
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
    $g_id = "";
    $g_first_name = "";
    $g_middle_name = "";
    $g_last_name =  "";
    $g_sex2 =  "";
    $g_contact_number =  "";
    $g_email =  "";
    $g_relationship =  "";
    $g_fullname = "";

    if(isset($_GET['member_id']))
    {
        $selected_osca_id = $_GET['member_id'];

        $query = "SELECT m.`id` member_id, m.`osca_id`, m.`nfc_serial`, m.`nfc_active`, m.`password`, m.`account_enabled`, m.`first_name`, m.`middle_name`, m.`last_name`, m.`sex`, 
                    concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`)) `bdate`, 
                    YEAR(CURDATE()) - YEAR(birth_date) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)) ,'%Y-%c-%e') > CURDATE(), 1, 0) age,
                    concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`)) `memship_date`, 
                    m.`contact_number`, m.`email`, m.`picture` `picture`
                    FROM `member` m
                    WHERE `osca_id` = '$selected_osca_id';";
                    
        $result = $mysqli->query($query);
        $row_count_member = mysqli_num_rows($result);
        $row_member = mysqli_fetch_assoc($result);

        if($row_count_member == 1)
        {
            $osca_id = $row_member['osca_id'];
            $member_id = $row_member['member_id'];
            $first_name = $row_member['first_name'];
            $middle_name =  $row_member['middle_name'];
            $last_name =  $row_member['last_name'];
            $fullname = strtoupper("$first_name $middle_name $last_name");
            $sex2 =  $row_member['sex'];
            $contact_number =  $row_member['contact_number'];
            $email =  $row_member['email'];
            $bdate =  $row_member['bdate'];
            $age =  $row_member['age'];
            $memship_date =  $row_member['memship_date'];
            
            $picture =  "../resources/members/".$row_member["picture"]; 

            if (file_exists($picture) && $row_member["picture"] != null) {
                $picture =  "../resources/members/".$row_member["picture"]; 
            } else {
                $picture = "../resources/images/unknown_m_f.png";
            }

            $nfc_active = ($row_member['nfc_active'] == 1)? true:false;
            $account_enabled = ($row_member['account_enabled'] == 1)? true:false;

            $member_buttons = '
            <!--button type="button" class="btn btn-secondary my-2 w-75">Edit Basic Details</button>
            <button type="button" id="add_address" class="btn btn-secondary m-2 w-75">Add Address</button-->';
        } else {
        }

        
        mysqli_close($mysqli);

    } else {}
?>
            <div class="digital-card-contents">
                <div class="card-right">
                    <div class="profile-picture-container">
                        <form action="../backend/upload.php" id="form_photo" method="post" enctype="multipart/form-data" >
                            <img class="profile-picture" src="<?php echo $picture; ?>" id="output">
                            <div class="middle">
                                <input type="file" name="photo" accept="image/x-png,image/jpeg" onchange="loadFile(event)" id="file" class="inputfile">
                                <input type="hidden" name="entity_key" value="<?php echo $osca_id;?>">
                                <input type="hidden" name="entity_type" value="member">
                                <label for="file" class="text">Change</label>
                                <button type="submit" value="upload" id="submit" class="hidden btn btn-photo">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--div class="card-bottom-right">
                <?php echo $member_buttons;?>
                </div-->
                <div class="card-left" >
                    <div class="left">
                        <div class="basic">
                            <button class="ml-auto btn btn-link edit" id="edit_basic"><i class="fa fa-edit"></i></button>
                            <h4 class="ml-1"> Basic Information </h4>
                            <ul class="profile-details">
                                <li class="profile-item">
                                    <div class="title">Fullname</div> 
                                    <div class="content"><?php echo $fullname; ?></div>
                                </li>
                                <li class="profile-item">
                                    <div class="title">Sex</div> 
                                    <div class="content"><?php echo determine_sex($sex2, "display_long"); ?></div>
                                </li>
                                <li class="profile-item">
                                    <div class="title">Birthdate</div> 
                                    <div class="content"><?php echo "$bdate ($age y/o)"; ?></div>
                                </li>
                                <li class="profile-item">
                                    <div class="title">Phone Number</div> 
                                    <div class="content"><?php echo $contact_number; ?></div>
                                </li>
                                <li class="profile-item">
                                    <div class="title">E-mail</div> 
                                    <div class="content"><?php echo $email; ?></div>
                                </li>
                                    <?php  //address
                                        $addresses = read_address2($member_id, true);
                                        $address_id = $addresses['address_id'];
                                        $address1 = $addresses['address1'];
                                        $address2 = $addresses['address2'];
                                        $city = $addresses['city'];
                                        $province = $addresses['province'];
                                        $address_string = "$address1, $address2, $city, $province";
                                    ?>
                                <li class='profile-item disp_address' id='addNum_<?php echo $address_id?>'> 
                                    <div class='title'>Address</div>
                                    <div class="content"><?php echo $address_string;?></div>
                                    <button class="ml-auto btn btn-link edit edit_address"><i class="fa fa-edit"></i></button>
                                </li>
                            </ul>
                        </div>
                        <div class="membership">
                            <h4 class="ml-1"> Membership Details</h4>
                            <ul class="profile-details">
                                <li class="profile-item">
                                    <div class="title">OSCA ID</div> 
                                    <div class="content"><?php echo $osca_id; ?></div>
                                </li>
                                <li class="profile-item">
                                    <div class="title">Member since</div> 
                                    <div class="content"><?php echo $memship_date; ?></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right">
                        <div class="guardian">
                            <h4 class="ml-1"> Guardian's Details </h4>
                            <div>
                                <?php
                                    $guardian = read_guardian($osca_id);
                                
                                    foreach($guardian as $item => $row)
                                    {
                                        $g_id = $row['g_id'];
                                        $g_first_name = $row['g_first_name'];
                                        $g_middle_name = $row['g_middle_name'];
                                        $g_last_name =  $row['g_last_name'];
                                        $g_sex2 =  $row['g_sex'];
                                        $g_contact_number =  $row['g_contact_number'];
                                        $g_email =  $row['g_email'];
                                        $g_relationship =  $row['g_relationship'];
                                        $g_fullname = strtoupper("$g_first_name $g_middle_name $g_last_name");
                                    }
                                ?>
                                
                                <ul class="disp_guardian" id="gid<?php echo $g_id ?>" >
                                    <li class="profile-item">
                                        <div class="title">Full Name</div> 
                                        <div class="content"><?php echo $g_fullname; ?></div>
                                    </li>
                                    <li class="profile-item">
                                        <div class="title">Relationship</div> 
                                        <div class="content"><?php echo $g_relationship; ?></div>
                                    </li>
                                    <li class="profile-item">
                                        <div class="title">Sex</div> 
                                        <div class="content"><?php echo determine_sex($g_sex2, "display_long"); ?></div>
                                    </li>
                                    <li class="profile-item">
                                        <div class="title">Contact Number</div> 
                                        <div class="content"><?php echo $g_contact_number; ?></div>
                                    </li>
                                    <li class="profile-item">
                                        <div class="title">Email</div> 
                                        <div class="content"><?php echo $g_email; ?></div>
                                    </li>
                                    
                                    <button class="btn btn-link edit edit_guardian"><i class="fa fa-edit"></i></button>
                                </ul>
                            </div>
                        </div>
                        <div class="lost">
                            <h4 class="ml-1"></h4>
                            <ul class="profile-details">
                                <li class="profile-item">
                                    <div class="title">NFC Tag</div> 
                                    <div class="content">
                                        <?php 
                                        echo ($nfc_active)? "<span class='nfc-status status_active'>Active</span>":"<span class=' nfc-status status_inactive'>Inactive</span>";
                                        ?>
                                    
                                    </div>
                                </li>
                                <li class="profile-item">
                                    <div class="title">Account</div> 
                                    <div class="content">
                                        <?php 
                                        echo ($account_enabled)? "<span class='acct-status status_active'>Enabled</span>" :"<span class='acct-status status_inactive'>Disabled</span>";
                                        ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                
                <div class="nav-tab">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all-transactions" aria-selected="true">All</a>
                            <a class="nav-item nav-link" id="nav-ph-tab" data-toggle="tab" href="#nav-ph" role="tab" aria-controls="nav-pharmacy-transactions" aria-selected="false">Pharmacy</a>
                            <a class="nav-item nav-link" id="nav-rs-tab" data-toggle="tab" href="#nav-rs" role="tab" aria-controls="nav-restaurant-transactions" aria-selected="false">Restaurant</a>
                            <a class="nav-item nav-link" id="nav-tr-tab" data-toggle="tab" href="#nav-tr" role="tab" aria-controls="nav-transportation-transactions" aria-selected="false">Transportation</a>
                            <a class="nav-item nav-link ml-auto" id="nav-complaints-tab" data-toggle="tab" href="#nav-complaints" role="tab" aria-controls="nav-complaints" aria-selected="true">Complaints</a>
                            <a class="nav-item nav-link" id="nav-qr-tab" data-toggle="tab" href="#nav-qr" role="tab" aria-controls="nav-qr" aria-selected="true">Authorization Requests</a>
                            <a class="nav-item nav-link" id="nav-lost-tab" data-toggle="tab" href="#nav-lost" role="tab" aria-controls="nav-lost" aria-selected="true">Lost Reports</a>
                        </div>
                    </nav>
                </div>
            
                <div class="card transactions">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-transactions-tab"> </div>
                        <div class="tab-pane fade" id="nav-ph" role="tabpanel" aria-labelledby="nav-pharmacy-transactions-tab"> </div>
                        <div class="tab-pane fade" id="nav-rs" role="tabpanel" aria-labelledby="nav-restaurant-transactions-tab"> </div>
                        <div class="tab-pane fade" id="nav-tr" role="tabpanel" aria-labelledby="nav-transportation-transactions-tab"> </div>
                        <div class="tab-pane fade" id="nav-complaints" role="tabpanel" aria-labelledby="nav-complaints-tab"> </div>
                        <div class="tab-pane fade" id="nav-qr" role="tabpanel" aria-labelledby="nav-qr-tab"> </div>
                        <div class="tab-pane fade" id="nav-lost" role="tabpanel" aria-labelledby="nav-lost-tab"> </div>
                    </div>
                </div>
            </div>



<div class="container">
    <!-- Modal Edit -->
    <div id="_kf939s" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>

<?php
include('../frontend/foot.php');
?>

<script>
$(document).ready(function(){
    $('title').replaceWith('<title>Member profile - <?php echo "$first_name $last_name"; ?></title>');
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
        }
        
        if( document.getElementById("file").files.length == 0 ){
            document.getElementById("submit").classList.add("hidden");
            console.log("no files selected");
        } else {
            document.getElementById("submit").classList.remove("hidden");
            console.log("File is selected");
        }
    };
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
    //input.addEventListener('focus', function(){ input.classList.add('has-focus'); });
    //input.addEventListener('blur', function(){ input.classList.remove('has-focus'); });


    var member_id = <?php echo $member_id; ?>;
    var osca_id = "<?php echo $osca_id; ?>";

    $("#nav-all").load("../backend/display_transactions_all.php", {member_id : member_id});
    $("#nav-ph").load("../backend/display_transactions_pharmacy.php", {member_id : member_id});
    $("#nav-rs").load("../backend/display_transactions_restaurant.php", {member_id : member_id});
    $("#nav-tr").load("../backend/display_transactions_transportation.php", {member_id : member_id});
    $("#nav-complaints").load("../backend/display_complaints_member.php", {osca_id : osca_id});
    $("#nav-qr").load("../backend/display_qr_request.php", {osca_id : osca_id});
    $("#nav-lost").load("../backend/display_lost_report.php", {osca_id : osca_id});
    

    $('#edit_basic').click(function () {
        var url = 'edit_member.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="member_id" value="<?php echo $osca_id?>" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });

    $('.nfc-status').click(function () {
        var id= <?php echo $member_id;?>;
        $.post("../backend/toggle_member_nfc.php", {id:id},function(d){
            if(d.trim() == "1")
            {
                location.reload();
            }
        });
    });

    $('.acct-status').click(function () {
        var id= <?php echo $member_id;?>;
        $.post("../backend/toggle_member_acct.php", {id:id},function(d){
            if(d.trim() == "1")
            {
                location.reload();
            }
        });
    });
    
    $('#add_address').click(function () {
        var member_id= <?php echo $member_id;?>;
        $('#_kf939s').load("../frontend/edit_address.php?action=add", {id:member_id, type:"member"},function(){
            $('#_kf939s').modal();
        });
    });
    
    $('.edit_address').click(function () {
        var address_id= $(this).parent().attr("id").replace("addNum_", "");
        var member_id= <?php echo $member_id;?>;
        $('#_kf939s').load("../frontend/edit_address.php?action=edit", {id:member_id, address_id:address_id, type:"member"},function(){
            $('#_kf939s').modal();
        });
    });
    
    $('.edit_guardian').click(function () {
        var gid= $(this).parent().attr("id").replace("gid", "");
        var osca_id= "<?php echo $osca_id;?>";
        $('#_kf939s').load("../frontend/edit_guardian.php?action=edit", {osca_id:osca_id, gid:gid}, function(){
            $('#_kf939s').modal();
        });
    });
    
    $('.add_guardian').click(function () {
        var osca_id= "<?php echo $osca_id;?>";
        $('#_kf939s').load("../frontend/edit_guardian.php?action=add", {osca_id:osca_id}, function(){
            $('#_kf939s').modal();
        });
    });
    
    if(document.getElementById("file").files.length == 0 ){
        document.getElementById("submit").classList.add("hidden");
        console.log("no files selected");
    } else {
        document.getElementById("submit").classList.remove("hidden");
        console.log("File is selected");
    }
    
});
</script>