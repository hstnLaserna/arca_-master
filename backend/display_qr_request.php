<div id="qr-request-list">
    <?php
    include("../backend/conn.php");
    //declare
    $osca_id = "null";
    $customer = "null";
    $report_date = "null";
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
                                FROM `view_qr_request_transactions`
                                WHERE `osca_id` = '$osca_id'
                                ORDER BY `request_date` ASC";

        $result = $mysqli->query($transaction_query);
        $row_count_orig = mysqli_num_rows($result);

        $transaction_query = "SELECT *
                                FROM `view_qr_request_transactions`
                                WHERE `osca_id` = '$osca_id'
                                ORDER BY `request_date` ASC
                                LIMIT $displayed_items;";
        $result = $mysqli->query($transaction_query);
        $row_count_display = mysqli_num_rows($result);
        if($row_count_display != 0)
        {?>
            <table class="table table-hover">
                <th>Date Requested</th>
                <th>Description</th>
                <th>Status</th>
                <?php
                while($row = mysqli_fetch_array($result))
                {
                    $request_date = $row['request_date'];
                    $desc = $row['desc'];
                    ?>
                    <tr>
                        <td><?php echo $request_date ?></td>
                        <td><?php echo $desc ?></td>
                        <td>
                                <?php 
                                if($row['company_tin'] != null && $row['company_tin'] != ""){
                                    $company_tin = $row['company_tin'];
                                    $company_name = $row['company_name'];
                                    $branch = $row['branch'];
                                ?>
                            <a href="../frontend/company_profile.php?company_tin=<?php echo $company_tin;?>" class="view_">
                                <?php echo "$company_name <br><i> $branch </i>" ?>
                            </a>
                                <?php 
                                } else {
                                    echo "Unserved";
                                }
                                ?>
                        </td>
                    </tr>
                    <?php
                }
            
                ?>

            </table>
            
            <?php
            if($displayed_items < $row_count_orig){
                echo '<button class="btn btn-block btn-dark" id="expand-qr">Show More</button>';
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

</div>
<div class="_dfg987">
</div>

<script>
$(document).ready(function(){
    var osca_id = <?php echo $osca_id; ?>;
    var ctr_qr = 1;

    $("body").on('click', "#expand-qr", function () {
        ctr_qr++;
        $("#qr-request-list").load("../backend/display_qr_request.php #qr-request-list", {osca_id : "<?php echo $osca_id;?>", counter: ctr_qr});
    });
});
</script>