<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    // declare variables 

    $company_name = "null";
    $branch = "null";
    $company_tin = "null";
    $business_type = "null";

    if(isset($_GET['company_tin']))
    {
        $company_tin = $_GET['company_tin'];

        $query = "SELECT c.`id` id, c.`company_name` `company_name`, c.`branch` `branch`, c.`company_tin` `company_tin`, c.`business_type` `business_type`
                    FROM `company` c
                    WHERE `company_tin` = '$company_tin';";
                    
        $result = $mysqli->query($query);
        $row_count_member = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);

        if($row_count_member == 1)
        {
            $company_id = $row['id'];
            $company_name = $row['company_name'];
            $branch = $row['branch'];
            $company_tin = $row['company_tin'];
            $business_type = $row['business_type'];


            $member_buttons = '
            <button type="button" id="edit_basic" class="btn btn-secondary my-2 w-75">Edit Basic Details</button>';
        } else {}
        mysqli_close($mysqli);

    } else {}
?>
            <div class="card digital-card-contents">
                <div class="card-right">
                    <img class="company_logo" src="<?php echo "null"; ?>" alt="<?php echo $company_name?>'s Logo">
                </div>
                <div class="card-bottom-right">
                <?php echo $member_buttons;?>
                </div>
                <div class="card-left">
                    <div class="card">
                        <p>Name: <?php echo $company_name; ?>,</p>
                        <p>Branch: <?php echo $branch; ?> </p>
                        <p>Company TIN: <?php echo $company_tin; ?></p>
                        <p>Business type: <?php echo $business_type; ?></p>
                        <?php
                            read_address($company_tin, true, "company");
                        ?>
                    </div>
                </div>
            </div>
            

            <div class="col col-md-9 p-3 border border-dark rounded overflow-auto" class="transactions">
                <div class="table-responsive" id="transactions_list">
                    <table class="table table-hover users" id="trans">
                    </table>
                </div>
            </div>

            <div class="col col-md-9 p-3 border border-dark rounded overflow-auto" class="transactions">
                <div class="table-responsive" id="transactions_list">
                    <table class="table table-hover" id="complaints">
                    </table>
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
$('title').replaceWith('<title>Member profile - <?php echo ""; ?></title>');
$(document).ready(function(){
    
    // display_transactions_company
    // display_complaints_company
    $("#transactions_list").load("../backend/display_transactions.php", {member_id : "2", business_type: "pharmacy" });
    $('.edit_address').click(function () {
        var address_id= $(this).parent().attr("id").replace("addNum_", "");
        var company_id= "<?php echo $company_id;?>";
        alert(company_id + " " + address_id);
        $('#_kf939s').load("../frontend/edit_address.php?action=edit", {id:company_id, address_id:address_id, type: "company"},function(){
            $('#_kf939s').modal();
        });
    });
    
});
</script>