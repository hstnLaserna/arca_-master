<div id="trans-all">
    <?php
    include("../backend/conn.php");
    //declare
    $business_type = "";
    $transaction_date = "";
    $vat_exempt_price = "";
    $discount_price = "";
    $description = "";
    $company = "";
    $branch = "";
    $row_count_orig = 0;
    $row_count_display = 0;

    if(isset($_POST['member_id']))
    {

        $member_id = $_POST['member_id'];
        
        $query  = "SELECT * FROM `member`
                WHERE `id` = '$member_id'";

        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 1) {
        
            $ctr1 = 1;
            $items_per_page = 2;

            $WHERE_CLAUSE = " WHERE member_id = '$member_id' AND date(trans_date) >= (LEFT(NOW() - INTERVAL 1 MONTH,10)) ";

            if(isset($_POST['ctr1'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
                $ctr1 = filter_input(INPUT_POST, 'ctr1', FILTER_VALIDATE_INT);
                $WHERE_CLAUSE = " WHERE member_id = '$member_id' ";
                if(!$ctr1) {
                    $ctr1 = 1;
                    $WHERE_CLAUSE = " WHERE member_id = '$member_id' AND date(trans_date) >= (LEFT(NOW() - INTERVAL 1 MONTH,10)) ";
                }
            }

            $displayed_items = ($ctr1) * $items_per_page;
            $transaction_query = "SELECT *
                                    FROM view_all_transactions
                                    WHERE member_id = '$member_id'
                                    ORDER BY trans_date ASC";

            $result = $mysqli->query($transaction_query);
            $row_count_orig = mysqli_num_rows($result);


            $transaction_query = "SELECT * FROM (
                                    SELECT `member_id`, `trans_date`, `vat_exempt_price`, `discount_price` , `payable_price`, `desc`, 
                                            company_name `company`, `branch`, `business_type`
                                    FROM view_all_transactions
                                    $WHERE_CLAUSE
                                    ORDER BY trans_date ASC) ttt ORDER BY `trans_date` DESC  
                                    LIMIT $displayed_items;";

            $result = $mysqli->query($transaction_query);
            $row_count_display = mysqli_num_rows($result);
            if($row_count_display != 0)
            {?>
                <table class="table table-hover users">
                    <th>Transaction Type</th>
                    <th>Transaction date</th>
                    <th>Company</th>
                    <th>Description</th>
                    <th>VAT Exempt</th>
                    <th>Discount</th>
                    <th>Price Paid</th>
                    <?php
                    while($row = mysqli_fetch_array($result))
                    {
                        $member_id = $row['member_id'];
                        $business_type = $row['business_type'];
                        $transaction_date = $row['trans_date'];
                        $vat_exempt_price = $row['vat_exempt_price'];
                        $discount_price = $row['discount_price'];
                        $payable_price = $row['payable_price'];
                        $description = $row['desc'];
                        $company = $row['company'];
                        $branch = $row['branch'];
                        
                        ?>
                        <tr>
                            <td><?php echo $business_type ?></td>
                            <td><?php echo $transaction_date ?></td>
                            <td>
                                <?php echo $company ?> 
                                <p><i><?php echo $branch ?></i></p>
                            </td>
                            <td><?php echo $description ?></td>
                            <td><?php echo $vat_exempt_price ?></td>
                            <td><?php echo $discount_price ?></td>
                            <td><?php echo $payable_price ?></td>
                        </tr>
                        <?php
                    }
                
                    ?>

                </table>
                
                <?php
                
            } else {
                echo "<div class='rounded col col-sm-6 m-auto p-3 text-center'>No transaction in the record for this user</div>";
            }
            
            //echo "Displayed: $row_count_display All: $row_count_orig Displayed Items: $displayed_items";
            mysqli_close($mysqli);
        }
    }
    ?>

</div>

<button class="btn btn-block btn-dark" id="expand">Show More</button>

<script>
$(document).ready(function(){
    var member_id = <?php echo $member_id; ?>;
    var ctr1 = 1;

    $("#expand").click(function() {
        ctr1++;
        $("#trans-all").load("../backend/display_transactions_all.php #trans-all", {member_id : member_id, ctr1: ctr1 });
    });
});
</script>