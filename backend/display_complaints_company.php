<div id="comps">

    <h3 class="text-center"> COMPANY COMPLAINTS </H3>
    <?php
    include("../backend/conn.php");
    //declare
    $osca_id = "null";
    $customer = "null";
    $report_date = "null";
    $desc = "null";
    $row_count_orig = 0;
    $row_count_display = 0;

    if(isset($_POST['company_tin']))
    {
        $company_tin = $mysqli->real_escape_string($_POST['company_tin']);
        
        $query = "SELECT * FROM `company` WHERE company_tin = '$company_tin'";

        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 1){

            $counter = 1;
            $items_per_page = 5;
            $WHERE_CLAUSE = " WHERE company_tin = '$company_tin' ";

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
                                    WHERE company_tin = '$company_tin'
                                    ORDER BY report_date ASC";

            $result = $mysqli->query($transaction_query);
            $row_count_orig = mysqli_num_rows($result);


            $transaction_query = "SELECT *
                                    FROM `view_complaints`
                                    WHERE company_tin = '$company_tin'
                                    ORDER BY report_date ASC
                                    LIMIT $displayed_items;";

            $result = $mysqli->query($transaction_query);
            $row_count_display = mysqli_num_rows($result);
            if($row_count_display != 0)
            {?>
                <table class="table table-hover users">
                    <th>Customer</th>
                    <th>Date reported</th>
                    <th>Description</th>
                    <?php
                    while($row = mysqli_fetch_array($result))
                    {
                        $osca_id = $row['osca_id'];
                        $customer = $row['last_name'] . ", " . $row['first_name'];
                        $report_date = $row['report_date'];
                        $desc = $row['desc'];
                        // BUTTON OF SENIOR CITIZEN NAME NOT WORKING
                        ?>
                        <tr>
                            <td><a href="../frontend/member_profile.php?member_id=<?php echo $osca_id;?>" class="view_"><?php echo $customer ?></a></td>
                            <td><?php echo $report_date ?></td>
                            <td><?php echo $desc ?></td>
                        </tr>
                        <?php
                    }
                
                    ?>

                </table>
                <?php
                    echo "($displayed_items < $row_count_orig)";
                    if($displayed_items < $row_count_orig){
                        echo '<button class="btn btn-block btn-dark" id="expand">Show More</button>';
                    }
                
            } else {
                echo "<div class='rounded col col-sm-6 m-auto p-3 text-center'>No Complaints for this Company</div>";
            }
            
            //echo "Displayed: $row_count_display All: $row_count_orig Displayed Items: $displayed_items";
            mysqli_close($mysqli);
        }
    }
    ?>

</div>
<div class="_dfg987">
</div>



<script>
$(document).ready(function(){
    var osca_id = <?php echo $osca_id; ?>;
    var counter = 1;

    $("body").on('click', "#expand", function () {
        counter++;
        var type = "<?php echo $type; ?>";
        $("#comps").load("../backend/display_complaints_company.php #comps", {company_tin : "<?php echo $company_tin;?>", counter: counter});
    });
});
</script>