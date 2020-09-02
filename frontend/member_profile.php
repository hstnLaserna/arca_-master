<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    // declare variables 

    $osca_id = "null";
    $member_id = "null";
    $first_name = "null";
    $middle_name =  "null";
    $last_name =  "null";
    $sex2 =  "null";
    $contact_number =  "null";
    $email =  "null";
    $bdate =  "null";
    $age =  "null";
    $memship_date =  "null";
    $picture = "../resources/images/unknown_m_f.png";
    $member_buttons = '';

    if(isset($_GET['member_id']))
    {
        $osca_id = $_GET['member_id'];

        $query = "SELECT m.`id` member_id, m.`osca_id`, m.`nfc_serial`, m.`password`, m.`first_name`, m.`middle_name`, m.`last_name`, m.`sex`, 
                    concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`)) `bdate`, 
                    YEAR(CURDATE()) - YEAR(birth_date) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)) ,'%Y-%c-%e') > CURDATE(), 1, 0) age,
                    concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`)) `memship_date`, 
                    m.`contact_number`, m.`email`, m.`picture` `picture`
                    FROM `member` m
                    WHERE `osca_id` = '$osca_id';";
                    
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
                echo $picture;
                $picture = "../resources/images/unknown_m_f.png";
            }

            $member_buttons = '
            <button type="button" id="edit_basic" class="btn btn-secondary my-2 w-75">Edit Basic Details</button>
            <button type="button" id="add_address" class="btn btn-secondary m-2 w-75">Add Address</button>';
        } else {
        }

        
        mysqli_close($mysqli);

    } else {
    }
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
                        <p>Lastname: <?php echo $last_name; ?>,</p>
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
            
            <div class="row">
                <div class="col col-md-3 p-3 border border-dark rounded">
                    <button class="btn btn-block btn-secondary" id="all">All Transactions</button>
                    <button class="btn btn-block btn-secondary" id="ph">Pharmacy</button>
                    <button class="btn btn-block btn-secondary" id="res">Restaurant</button>
                    <button class="btn btn-block btn-secondary" id="transpo">Transportation</button>
                </div>

                <div class="col col-md-9 p-3 border border-dark rounded overflow-auto" class="transactions">
                    <div class="table-responsive" id="transactions_list">
                        <table class="table table-hover users" id="trans">
                        </table>
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
$('title').replaceWith('<title>Member profile - <?php echo "$first_name $last_name"; ?></title>');
$(document).ready(function(){
    var member_id = <?php echo $member_id; ?>;
    var counter = 1;
    var type = "";


    $("#transactions_list").load("../backend/display_transactions.php", {member_id : member_id, business_type: "all" });
    
    $("#all").click(function() {
        var business_type = "all";
        $("#trans__").load("../backend/display_transactions.php #trans__", {member_id : member_id, business_type: business_type });
    });    
    $("#ph").click(function() {
        var business_type = "pharmacy";
        $("#trans__").load("../backend/display_transactions.php #trans__", {member_id : member_id, business_type: business_type });
    });    
    $("#res").click(function() {
        var business_type = "restaurant";
        $("#trans__").load("../backend/display_transactions.php #trans__", {member_id : member_id, business_type: business_type });
    });    
    $("#transpo").click(function() {
        var business_type = "transportation";
        $("#trans__").load("../backend/display_transactions.php #trans__", {member_id : member_id, business_type: business_type });
    });    




    

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