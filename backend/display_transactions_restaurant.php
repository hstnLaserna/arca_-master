<div id="trans-rs">
    <?php
    include("../backend/conn.php");
    //declare
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

        $member_id = $mysqli->real_escape_string($_POST['member_id']);
        
        $query  = "SELECT * FROM `member`
                WHERE `id` = '$member_id'";

        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 1) {
        
            $ctr3 = 1;
            $items_per_page = 5;


            if(isset($_POST['ctr3'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
                $ctr3 = filter_input(INPUT_POST, 'ctr3', FILTER_VALIDATE_INT);
                if(!$ctr3) {
                    $ctr3 = 1;
                }
            }

            $displayed_items = ($ctr3) * $items_per_page;
            $transaction_query_1 = "SELECT *
                                    FROM view_food_transactions
                                    WHERE member_id = '$member_id'
                                    ORDER BY trans_date ASC";

            $result = $mysqli->query($transaction_query_1);
            $row_count_orig = mysqli_num_rows($result);


            $transaction_query_2 = "SELECT * FROM (
                                    SELECT `member_id`, `trans_date`, `vat_exempt_price`, `discount_price` , `payable_price`, `desc`, 
                                            company_name `company`, `branch`, `company_tin`
                                    FROM view_food_transactions
                                    WHERE `member_id` = '$member_id' 
                                    ORDER BY trans_date ASC) ttt ORDER BY `trans_date` DESC  
                                    LIMIT $displayed_items;";

            $result = $mysqli->query($transaction_query_2);
            $row_count_display = mysqli_num_rows($result);

            /*
            if($row_count_display == 0 && $row_count_orig > 0) {
                // remove the condition of the last 3 months
                $transaction_query_2 = "SELECT * FROM (
                                        SELECT `member_id`, `trans_date`, `vat_exempt_price`, `discount_price` , `payable_price`, `desc`, 
                                                company_name `company`, `branch`, `company_tin`
                                        FROM view_food_transactions
                                        WHERE `member_id` = '$member_id' 
                                        ORDER BY trans_date ASC) ttt ORDER BY `trans_date` DESC  
                                        LIMIT $displayed_items;";
                $result = $mysqli->query($transaction_query_2);
                $row_count_display = mysqli_num_rows($result);
            }*/

            if($row_count_display != 0)
            {?>
                <table class="table table-hover">
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
                        $transaction_date = $row['trans_date'];
                        $description = $row['desc'];
                        $company = $row['company'];
                        $company_tin = $row['company_tin'];
                        $branch = $row['branch'];

                        $formatter = new NumberFormatter("fil-PH", \NumberFormatter::CURRENCY);
                        $vat_exempt_price = $formatter->format($row['vat_exempt_price']);
                        $discount_price = $formatter->format($row['discount_price']);
                        $payable_price = $formatter->format($row['payable_price']);
                        
                        ?>
                        <tr>
                            <td><?php echo $transaction_date ?></td>
                            <td>
                                <a href="../frontend/company_profile.php?company_tin=<?php echo $company_tin;?>" class="view_">
                                    <?php echo "$company <br> <i> $branch</i>" ?>
                                </a>
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
                    if($displayed_items < $row_count_orig){
                        echo '<button class="btn btn-block btn-dark" id="expand-res">Show More</button>';
                    } else {
                        echo "<div class='eol'> * * *</div>";
                    }
                
            } else {
                echo "<div class='eol'> * * *</div>";
            }
            
            mysqli_close($mysqli);
        }
    }
    ?>

</div>

<script>
$(document).ready(function(){
    var member_id = <?php echo $member_id; ?>;
    var ctr3 = 1;

    $("body").on('click', "#expand-res", function () {
        ctr3++;
        $("#trans-rs").load("../backend/display_transactions_restaurant.php #trans-rs", {member_id : member_id, ctr3: ctr3 });
    });
});
</script>