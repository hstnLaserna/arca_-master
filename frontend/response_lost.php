
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
                $id = $_POST['id'];
                $lost_id = $_POST['lost_id'];
                $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);
                $error_msg = "<p class='lead'>No address on record</p>";

                $query_1 = " SELECT *
                            FROM `view_lost_report` v
                            INNER JOIN `member` m on v.member_id = m.id
                            WHERE v.`osca_id` = '$id'
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
                        <input type="hidden" name="id" value="<?php echo $member_id;?>">
                        <input type="hidden" name="lost_id" value="<?php echo $lost_id;?>">
                        
                        <div class="container py-3">
                            <h4><?php echo $fullname?></h4>

                            <div class="desc">
                                <label for="desc">Action taken</label>
                                <textarea wrap="on" cols="30" rows="5" class="form-control" name="desc" id ="desc" placeholder="Enter action done to submit" ><?php echo $desc; ?></textarea>
                            </div>
                            <div class="date">
                                <label for="address_1">Date Reported</label>
                                <input type="text" class="form-control" name="date" disabled value="<?php echo $report_date; ?>">
                            </div>
                            <div class="action mt-3">
                                <div class="status">
                                    <div class="status-label">NFC Tag</div> 
                                    <div class="status-content">
                                        <label class="switch" for="nfc_status">
                                            <input type="checkbox" id="nfc_status" name="nfc_status"/>
                                            <div class="slider round"></div>
                                        </label>
                                        <input type="hidden" id="nfc_status_text" name="nfc_status_text">
                                    </div>
                                </div>
                                <div class="status">
                                    <div class="status-label">Account</div> 
                                    <div class="status-content">
                                        <label class="switch" for="account_status">
                                            <input type="checkbox" id="account_status" name="account_status"/>
                                            <div class="slider round"></div>
                                        </label>
                                        <input type="hidden" id="account_status_text" name="account_status_text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                        $(document).ready(function(){
                            function setDescValue() {
                                var today = new Date();
                                var ttt = today.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                                var dd = String(today.getDate()).padStart(2, '0');
                                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                                var yyyy = today.getFullYear();
                                today = mm + '/' + dd + '/' + yyyy;

                                txtNFC = $('#nfc_status_text').val();
                                txtAcct = $('#account_status_text').val();
                                var newline = String.fromCharCode(13, 10);
                                $('#desc').val("On " + today + " " + ttt + "; " + txtNFC + "; " + txtAcct + "; ");
                            }
                            function checkInputs() {
                                if ($("#desc").val().length == 0) {
                                    $("#submit_response").attr("disabled", true);
                                } else {
                                    $("#submit_response").removeAttr("disabled");
                                }
                            }

                            $('input[type=checkbox]#nfc_status').click(function () {
                                if ($('input[type=checkbox]').is(':checked')) {
                                    $('#nfc_status_text').val('NFC Activated');
                                }
                                else{
                                    $('#nfc_status_text').val('NFC Deactivated');
                                }
                                setDescValue();
                                checkInputs();
                            });
                            $('input[type=checkbox]#account_status').click(function () {
                                if ($('input[type=checkbox]').is(':checked')) {
                                    $('#account_status_text').val('Account Activated');
                                }
                                else{
                                    $('#account_status_text').val('Account Deactivated');
                                }
                                setDescValue();
                                checkInputs();
                            });

                            var oldVal = "";
                            $('#desc').on('change keyup paste', function() {
                                var currentVal = $(this).val();
                                if(currentVal == oldVal) {
                                    return; //check to prevent multiple simultaneous triggers
                                }

                                oldVal = currentVal;
                                $(':input[type="submit"]').prop('disabled', false);
                                checkInputs();
                            });
                            $("#desc").blur(function(){
                                checkInputs();
                            });

                            $("#submit_response").click(function(){
                                var nfc_status = "0";
                                var account_status = "0";
                                if ($('input#nfc_status').is(':checked')) {
                                    nfc_status = "1";
                                }
                                if ($('input#account_status').is(':checked')) {
                                    account_status = "1";
                                }

                                var desc = $('#desc').val();
                                $.post("../backend/update_lost_report.php", $("#lost_response_form").serialize(), function(d){
                                    if(d.trim() == "true") {
                                        location.reload();
                                    } else {
                                        alert(d);
                                    }
                                });
                            });
                        });
                        </script>
                        <?php
                    }
                } else {
                    include('../backend/fail_data.php');
                }
                mysqli_close($mysqli1);
            }
            ?>
            <button type="button" class="btn btn-light btn-lg btn-block" id="submit_response" disabled="true">Submit</button>
            <button type="button" data-dismiss="modal" class="btn btn-close btn-lg btn-block">Close</button>
        </form>
    </div>
    <?php
} else {
    include('../backend/fail_data.php');
}
?>