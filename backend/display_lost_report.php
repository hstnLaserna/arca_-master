<div id="lost-report-list">
    <?php
    include("../backend/conn.php");
    //declare
    $osca_id = "null";
    $report_date = "null";
    $nfc_active = false;
    $account_enabled = false;
    $desc = "null";
    $row_count_orig = 0;
    $row_count_display = 0;

    if(isset($_POST['osca_id']))
    {
        $osca_id = $mysqli->real_escape_string($_POST['osca_id']);
        $counter = 1;
        $items_per_page = 5;


        if(isset($_POST['counter'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
            $counter = filter_input(INPUT_POST, 'counter', FILTER_VALIDATE_INT);
            if(false === $counter) {
                $counter = 1;
            }
        }
        //echo "$counter AND $type";
        
        $displayed_items = ($counter) * $items_per_page;
        $transaction_query = "SELECT *
                                FROM `view_lost_report`
                                WHERE `osca_id` = '$osca_id'
                                ORDER BY `report_date` ASC";

        $result = $mysqli->query($transaction_query);
        $row_count_orig = mysqli_num_rows($result);

        $transaction_query = "SELECT *
                                FROM `view_lost_report`
                                WHERE `osca_id` = '$osca_id'
                                ORDER BY `report_date` DESC
                                LIMIT $displayed_items;";
        $result = $mysqli->query($transaction_query);
        $row_count_display = mysqli_num_rows($result);
        if($row_count_display != 0)
        {?>
            <table class="table">
                <th class="lost">Date Reported</th>
                <th class="lost">Status</th>
                <?php
                while($row = mysqli_fetch_array($result))
                {
                    $report_date = $row['report_date'];
                    $id = $row['lost_id'];
                    $desc = ($row['desc'] == null || $row['desc'] == "")? "Unserved": $row['desc'];
                    $is_served = ($row['desc'] != null || $row['desc'] != "")? "served": "unserved";
                    ?>
                    <?php echo "<tr id='lostid_$id' class='lost_row $is_served'>"?>
                        <td><?php echo $report_date ?></td>
                        <td><?php echo $desc ?></td>
                    </tr>
                    <?php
                }
            
                ?>

            </table>
            <?php 
                if($displayed_items < $row_count_orig){
                    echo '<button class="btn btn-block btn-dark" id="expand-lost-report">Show More</button>';
                } else {
                    echo "<div class='eol'> * * *</div>";
                }
            
        } else {
            echo "<div class='eol'> * * *</div>";
        }
        
        //echo "Displayed: $row_count_display All: $row_count_orig Displayed Items: $displayed_items";
        mysqli_close($mysqli);
    }
    ?>
    <div id="_8d9s02" class="modal fade" role="dialog">
    </div>

</div>

<script>
$(document).ready(function(){
    var osca_id = <?php echo $osca_id; ?>;
    var ctr_lost_report = 1;

    $("body").on('click', "#expand-lost-report", function () {
        ctr_lost_report++;
        $("#lost-report-list").load("../backend/display_lost_report.php #lost-report-list", {osca_id : "<?php echo $osca_id;?>", counter: ctr_lost_report});
    });

    $("body").on('click', ".unserved", function () {
        var id= "<?php echo $osca_id;?>";
        var lost_id = $(this).attr("id").replace("lostid_", "");
        $('#_8d9s02').load("../frontend/response_lost.php", {id:id, lost_id:lost_id},function(){
            $('#_8d9s02').modal();
        });

    });
});
</script>