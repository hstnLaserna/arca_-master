<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

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

    if(isset($_GET['member_id']))
    {
        $selected_osca_id = $_GET['member_id'];

        $query = "SELECT m.`id` member_id, m.`osca_id`, m.`nfc_serial`, m.`password`, m.`first_name`, m.`middle_name`, m.`last_name`, m.`sex`, 
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

            $member_buttons = '
            <button type="button" id="edit_basic" class="btn btn-secondary my-2 w-75">Edit Basic Details</button>
            <button type="button" id="add_address" class="btn btn-secondary m-2 w-75">Add Address</button>';
        } else {
        }

        
        mysqli_close($mysqli);

    } else {}
?>
            <div class="card digital-card-contents">
                <div class="card-right">
                    <img class="profile-picture" src="<?php echo $picture; ?>">
                </div>
                <div class="card-bottom-right">
                <?php echo $member_buttons;?>
                </div>
                <div class="card-left">
                    <div class="card">
                        <p>Lastname: <?php echo $last_name; ?></p>
                        <p>Firstname: <?php echo $first_name; ?> </p>
                        <p>Middlename: <?php echo $middle_name; ?></p>
                        <p>Sex:  <?php echo determine_sex($sex2, "display_long"); ?> </p>
                        <p>Birthdate: <?php echo "$bdate (Age: $age y.o.)"; ?></p>
                        <?php
                            read_address($member_id, true);
                        ?>
                        <p>Phone Number: <?php echo $contact_number; ?> </p>
                        <p>E-mail: <?php echo $email; ?></p>
                        <p>OSCA ID: <?php echo $osca_id; ?> </p>
                        <p>Member since: <?php echo $memship_date; ?> </p>
                    </div>

                    <div class="card">
                        <h3> Guardian's Details </h3>
                        <div>
                            <?php
                                read_guardian($osca_id);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-3 border border-dark rounded overflow-auto transactions">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all-transactions" aria-selected="true">All</a>
                        <a class="nav-item nav-link" id="nav-ph-tab" data-toggle="tab" href="#nav-ph" role="tab" aria-controls="nav-pharmacy-transactions" aria-selected="false">Pharmacy</a>
                        <a class="nav-item nav-link" id="nav-rs-tab" data-toggle="tab" href="#nav-rs" role="tab" aria-controls="nav-restaurant-transactions" aria-selected="false">Restaurant</a>
                        <a class="nav-item nav-link" id="nav-tr-tab" data-toggle="tab" href="#nav-tr" role="tab" aria-controls="nav-transportation-transactions" aria-selected="false">Transportation</a>
                        <a class="nav-item nav-link ml-auto" id="nav-complaints-tab" data-toggle="tab" href="#nav-complaints" role="tab" aria-controls="nav-complaints" aria-selected="true">Complaints</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-transactions-tab"> </div>
                    <div class="tab-pane fade" id="nav-ph" role="tabpanel" aria-labelledby="nav-pharmacy-transactions-tab"> </div>
                    <div class="tab-pane fade" id="nav-rs" role="tabpanel" aria-labelledby="nav-restaurant-transactions-tab"> </div>
                    <div class="tab-pane fade" id="nav-tr" role="tabpanel" aria-labelledby="nav-transportation-transactions-tab"> </div>
                    <div class="tab-pane fade" id="nav-complaints" role="tabpanel" aria-labelledby="nav-complaints-tab"> </div>
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
$('title').replaceWith('<title>Member profile - <?php echo "$first_name $last_name"; ?></title>');
$(document).ready(function(){
    var member_id = <?php echo $member_id; ?>;
    var osca_id = <?php echo $osca_id; ?>;
    var counter = 1;
    var ctr2 = 1;
    var type = "";

    $("#nav-all").load("../backend/display_transactions_all.php", {member_id : member_id});
    $("#nav-ph").load("../backend/display_transactions_pharmacy.php", {member_id : member_id});
    $("#nav-rs").load("../backend/display_transactions_restaurant.php", {member_id : member_id});
    $("#nav-tr").load("../backend/display_transactions_transportation.php", {member_id : member_id});
    $("#nav-complaints").load("../backend/display_complaints_member.php", {osca_id : osca_id});



    

    $('#edit_basic').click(function () {
        var url = 'edit_member.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="member_id" value="' + <?php echo $osca_id?> + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });
    
    $('#add_address').click(function () {
        var member_id= <?php echo $member_id;?>;
        $('#_kf939s').load("../frontend/edit_address.php?action=add", {member_id:member_id},function(){
            $('#_kf939s').modal();
        });
    });
    
    $('.edit_address').click(function () {
        var address_id= $(this).parent().attr("id").replace("addNum_", "");
        var member_id= <?php echo $member_id;?>;
        alert(address_id + " , " + member_id);
        $('#_kf939s').load("../frontend/edit_address.php?action=edit", {id:member_id, address_id:address_id, type:"member"},function(){
            $('#_kf939s').modal();
        });
    });
    
    $('.edit_guardian').click(function () {
        var gid= $(this).parent().attr("id").replace("gid", "");
        var osca_id= <?php echo $osca_id;?>;
        $('#_kf939s').load("../frontend/edit_guardian.php?action=edit", {osca_id:osca_id, gid:gid}, function(){
            $('#_kf939s').modal();
        });
    });
    
    $('.add_guardian').click(function () {
        var osca_id= <?php echo $osca_id;?>;
        $('#_kf939s').load("../frontend/edit_guardian.php?action=add", {osca_id:osca_id}, function(){
            $('#_kf939s').modal();
        });
    });
    
});
</script>