
<div class="modal-dialog modal-edit-address">
    <div class="modal-content">
        <div class="modal-body">
<?php
if(isset($_POST['id']) &&
    isset($_POST['lost_id']))
{
    ?>
    <div>
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="lost_response_form">
            <?php 
            include("../backend/conn.php");
            include("../backend/php_functions.php");
            {
                $selected_id = $_POST['id'];
                $lost_id = $_POST['lost_id'];
                $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);
                $error_msg = "<p class='lead'>No address on record</p>";

                $query_1 = " SELECT *
                            FROM `view_lost_report` v
                            INNER JOIN `member` m on v.member_id = m.id
                            WHERE v.`osca_id` = '$selected_id'
                            AND v.`lost_id` = '$lost_id';";
                            ?>
                            <script>
                            console.log("<?php echo $query_1;  ?>");
                            </script>

                            <?php
                
                $result = $mysqli1->query($query_1);
                $row_count = mysqli_num_rows($result);
                if($row_count != 0) {
                    while($row = mysqli_fetch_array($result))
                    {
                        // member_id	lost_id	desc	osca_id	report_date
                        $lost_id = $row['lost_id'];
                        $member_id = $row['member_id'];
                        $osca_id = $row['osca_id'];
                        $desc = $row['desc'];
                        $report_date = $row['report_date'];
                        
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $fullname = ucwords("$last_name, $first_name");
                        $nfc_active = ($row['nfc_active'] == 1)? true:false;
                        $account_enabled = ($row['account_enabled'] == 1)? true:false;
                        
                        ?>
                        <input type="hidden" name="selected_id" value="<?php echo $member_id;?>">
                        <input type="hidden" name="lost_id" value="<?php echo $lost_id;?>">
                        
                        <div class="container py-3">
                            <h4><?php echo $fullname?></h4>

                            <div class="desc">
                                <label for="desc">Action taken</label>
                                <textarea wrap="off" cols="30" rows="5" class="form-control" name="desc"></textarea>
                            </div>
                            <div class="date">
                                <label for="address_1">Date</label>
                                <input type="text" class="form-control" name="desc" value="<?php echo $report_date; ?>">
                            </div>
                            <div class="action">
                                    <div class="status">
                                        <div class="status-label">NFC Tag</div> 
                                        <div class="status-content">
                                            
                                        <input type="checkbox" id="test1" />
                                        <label for="test1">Red</label>
                                            <?php 
                                                if($nfc_active){
                                                    echo "<button class='btn status_active'>Active</button>";
                                                } else {
                                                    echo "<button class='btn status_inactive'>Inactive</button>";
                                                }
                                                ?>
                                        </div>
                                    </div>
                                    <div class="status">
                                        <div class="status-label">Account</div> 
                                        <div class="status-content">
                                            <?php
                                                if($account_enabled){
                                                    echo "<button class='btn status_active'>Enabled</button>";
                                                } else {
                                                    echo "<button class='btn status_inactive'>Disabled</button>";
                                                }
                                                
                                                ?>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    include('../backend/fail_data.php');
                }
                
                mysqli_close($mysqli1);
            }
            ?>
            <button type="button" class="btn btn-light btn-lg btn-block" id="submit_edit">Submit</button>
            <button type="button" data-dismiss="modal" class="btn btn-close btn-lg btn-block">Close</button>
        </form>
    </div>
    <?php
} else {
    include('../backend/fail_data.php');
}
?>