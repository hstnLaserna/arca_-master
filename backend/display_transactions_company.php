<div id="trans">
    <h3 class="text-center"> TRANSACTIONS </H3>
    <?php
    include("../backend/conn.php");
    //declare
    $business_type = "null";
    $transaction_date = "null";
    $vat_exempt_price = "null";
    $discount_price = "null";
    $desc = "null";
    $company = "null";
    $branch = "null";
    $row_count_orig = 0;
    $row_count_display = 0;
    
    $SELECT_CLAUSE = "  SELECT `member_id`, `osca_id`, `first_name`, `last_name`, trans_date, vat_exempt_price, discount_price ,  `payable_price`, `desc`, 
                        `company_tin`, company_name `company`, branch `branch`, `business_type` ";
    $FROM_CLAUSE =  " FROM view_all_transactions ";


    if(isset($_POST['company_tin']))
    {

        $company_tin = $mysqli->real_escape_string($_POST['company_tin']);
        $counter = 1;
        $items_per_page = 2;

        $WHERE_CLAUSE = " WHERE company_tin = '$company_tin' ";

        if(isset($_POST['counter'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
            $counter = filter_input(INPUT_POST, 'counter', FILTER_VALIDATE_INT);
            $WHERE_CLAUSE = " WHERE company_tin = '$company_tin' ";
            if(false === $counter) {
                $counter = 1;
                $WHERE_CLAUSE = " WHERE company_tin = '$company_tin' ";
            }
        }
        
        if(isset($_POST['business_type'])){
            switch ($_POST['business_type']) {
                case 'all':
                    $type = "all";
                    $FROM_CLAUSE =  " FROM view_all_transactions ";
                    break;
                case 'pharmacy':
                    $type = "pharmacy";
                    $SELECT_CLAUSE = " SELECT `member_id`,  `osca_id`, `first_name`, `last_name`, trans_date, vat_exempt_price, discount_price, `payable_price`,
                                                `desc_nondrug` `desc1`,
                                                concat('[', UCASE(LEFT(generic_name, 1)), LCASE(SUBSTRING(generic_name, 2)), '], ', UCASE(LEFT(brand, 1)), LCASE(SUBSTRING(brand, 2)),  ', ', dose, unit,  ', ', quantity,  'pcs, P ',  unit_price, '/pc') AS `desc`,
                                                company_tin, company_name `company`, branch `branch`, `business_type` ";
                    $FROM_CLAUSE =  " FROM view_pharma_transactions_all ";
                    break;
                case 'food':
                    $type = "restaurant";
                    $FROM_CLAUSE =  " FROM view_food_transactions ";
                    break;
                case 'transportation':
                    $type = "transportation";
                    $FROM_CLAUSE =  " FROM view_transportation_transactions ";
                    break;
                
                default:
                    $type = "all";
                    $FROM_CLAUSE =  " FROM view_all_transactions ";
                    break;
            }
        }
        //echo "$counter AND $type";
        
        $displayed_items = ($counter) * $items_per_page;
        $transaction_query = "SELECT *
                                $FROM_CLAUSE
                                WHERE company_tin = '$company_tin'
                                ORDER BY trans_date ASC";
                                //echo $transaction_query;

        $result = $mysqli->query($transaction_query);
        $row_count_orig = mysqli_num_rows($result);


        $transaction_query = "  $SELECT_CLAUSE
                                $FROM_CLAUSE
                                $WHERE_CLAUSE
                                ORDER BY `trans_date` DESC  
                                LIMIT $displayed_items;";

        $result = $mysqli->query($transaction_query);
        $row_count_display = mysqli_num_rows($result);
        if($row_count_display != 0)
        {?>
            <input type="hidden" id="<?php echo $type; ?>">
            <table class="table table-hover">
                <th>Customer</th>
                <th>Transaction date</th>
                <th>VAT Exempt</th>
                <th>Discount</th>
                <th>Amount Paid</th>
                <th>Description</th>
                <?php
                while($row = mysqli_fetch_array($result))
                {
                    $osca_id = $row['osca_id'];
                    $customer = $row['last_name'] . ", " . $row['first_name'];
                    $transaction_date = $row['trans_date'];
                    $desc = $row['desc'];
                    if(isset($row['desc1']) && $row['desc1'] != "") {
                        $desc = $row['desc1'];
                    }
                    $vat_exempt_price = $row['vat_exempt_price'];
                    $discount_price = $row['discount_price'];
                    $payable_price = $row['payable_price'];
                    
                    ?>
                    <tr>
                        <td><a href="../frontend/member_profile.php?member_id=<?php echo $osca_id;?>" class="view_"><?php echo $customer ?></td>
                        <td><?php echo $transaction_date ?></td>
                        <td><?php echo $desc ?></td>
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
            echo "<div class='rounded col col-sm-6 m-auto p-3 text-center'>No transaction in the record for this Company</div>";
        }
        
        //echo "Displayed: $row_count_display All: $row_count_orig Displayed Items: $displayed_items";
        mysqli_close($mysqli);
    }
    ?>

</div>
<div class="_dfg987">
</div>

<button class="btn btn-block btn-dark" id="expand">Show More</button>

<script>
$(document).ready(function(){
    var osca_id = <?php echo $osca_id; ?>;
    var counter = 1;

    $("#expand").click(function() {
        counter++;
        var type = "<?php echo $type; ?>";
        $("#trans").load("../backend/display_transactions_company.php #trans", {company_tin : "<?php echo $company_tin;?>", counter: counter, business_type: "<?php echo $type; ?>" });
    });

});
</script>