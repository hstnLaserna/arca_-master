<div id="complaints-list">
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
        $items_per_page = 2;

        $WHERE_CLAUSE = " WHERE osca_id = '$osca_id' ";

        if(isset($_POST['counter'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
            $counter = filter_input(INPUT_POST, 'counter', FILTER_VALIDATE_INT);
            if(false === $counter) {
                $counter = 1;
            }
        }
        //echo "$counter AND $type";
        
        $displayed_items = ($counter) * $items_per_page;
        $transaction_query = "SELECT *
                                FROM `view_complaints`
                                WHERE osca_id = '$osca_id'
                                ORDER BY report_date ASC";

        $result = $mysqli->query($transaction_query);
        $row_count_orig = mysqli_num_rows($result);


        $transaction_query = "SELECT *
                                FROM `view_complaints`
                                WHERE osca_id = '$osca_id'
                                ORDER BY report_date ASC
                                LIMIT $displayed_items;";

        $result = $mysqli->query($transaction_query);
        $row_count_display = mysqli_num_rows($result);
        if($row_count_display != 0)
        {?>
            <table class="table table-hover">
                <th>Company</th>
                <th>Date reported</th>
                <th>Description</th>
                <?php
                while($row = mysqli_fetch_array($result))
                {
                    $company_tin = $row['company_tin'];
                    $company_name = $row['company_name'];
                    $branch = $row['branch'];
                    $report_date = $row['report_date'];
                    $desc = $row['desc'];
                    ?>
                    <tr>
                        <td>
                            <a href="../frontend/company_profile.php?company_tin=<?php echo $company_tin;?>" class="view_">
                                <?php echo "$company_name <br> <i> $branch</i>" ?>
                            </a>
                        </td>
                        <td><?php echo $report_date ?></td>
                        <td><?php echo $desc ?></td>
                    </tr>
                    <?php
                }
            
                ?>

            </table>
            
            <?php
            
        } else {
            echo "<div class='rounded col col-sm-6 m-auto p-3 text-center'>No filed complaints </div>";
        }
        
        //echo "Displayed: $row_count_display All: $row_count_orig Displayed Items: $displayed_items";
        mysqli_close($mysqli);
    }
    ?>

</div>
<div class="_dfg987">
</div>

<button class="btn btn-block btn-dark" id="expand-complaints">Show More</button>

<script>
$(document).ready(function(){
    var osca_id = <?php echo $osca_id; ?>;
    var ctr_complaints = 1;

    $("#expand-complaints").click(function() {
        ctr_complaints++;
        $("#complaints-list").load("../backend/display_complaints_member.php #complaints-list", {osca_id : "<?php echo $osca_id;?>", counter: ctr_complaints});
    });
});
</script>