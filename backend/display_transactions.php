<div id="trans__">
    <?php
    include("../backend/conn.php");
    //declare
    $business_type = "null";
    $transaction_date = "null";
    $vat_exempt_price = "null";
    $discount_price = "null";
    $description = "null";
    $company = "null";
    $branch = "null";
    $row_count_orig = 0;
    $row_count_display = 0;
    
    $SELECT_CLAUSE = "  SELECT `member_id`, trans_date, vat_exempt_price, discount_price , `desc`, 
                        company_name `company`, branch `branch`, `business_type` ";
    $FROM_CLAUSE =  " FROM view_all_transactions ";


    if(isset($_POST['member_id']))
    {

        $member_id = $mysqli->real_escape_string($_POST['member_id']);
        
        $query  = "SELECT * FROM `member`
                WHERE `id` = '$member_id'";

        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 1) {
        
            $counter = 1;
            $items_per_page = 5;

            $WHERE_CLAUSE = " WHERE member_id = '$member_id' AND date(trans_date) >= (LEFT(NOW() - INTERVAL 1 MONTH,10)) ";

            if(isset($_POST['counter'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
                $counter = filter_input(INPUT_POST, 'counter', FILTER_VALIDATE_INT);
                $WHERE_CLAUSE = " WHERE member_id = '$member_id' ";
                if(false === $counter) {
                    $counter = 1;
                    $WHERE_CLAUSE = " WHERE member_id = '$member_id' AND date(trans_date) >= (LEFT(NOW() - INTERVAL 1 MONTH,10)) ";
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
                        $SELECT_CLAUSE = " SELECT `member_id`, trans_date, vat_exempt_price, discount_price, concat('[', UCASE(LEFT(generic_name, 1)), LCASE(SUBSTRING(generic_name, 2)), '], ', UCASE(LEFT(brand, 1)), LCASE(SUBSTRING(brand, 2)),  ', ', dose, unit,  ', ', quantity,  'pcs, P ',  unit_price, '/pc') AS `desc`,
                                                    company_name `company`, branch `branch`, `business_type` ";
                        $FROM_CLAUSE =  " FROM view_pharma_transactions ";
                        break;
                    case 'restaurant':
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
            
            $displayed_items = ($counter) * $items_per_page;
            $transaction_query = "SELECT *
                                    $FROM_CLAUSE
                                    WHERE member_id = '$member_id'
                                    ORDER BY trans_date ASC";

            $result = $mysqli->query($transaction_query);
            $row_count_orig = mysqli_num_rows($result);


            $transaction_query = "SELECT * FROM (
                                    $SELECT_CLAUSE
                                    $FROM_CLAUSE
                                    $WHERE_CLAUSE
                                    ORDER BY trans_date ASC) ttt ORDER BY `trans_date` DESC  
                                    LIMIT $displayed_items;";

            $result = $mysqli->query($transaction_query);
            $row_count_display = mysqli_num_rows($result);
            if($row_count_display != 0)
            {?>
                <input type="hidden" id="<?php echo $type; ?>">
                <table class="table table-hover users">
                    <th>Transaction Type</th>
                    <th>Transaction date</th>
                    <th>VAT Exempt</th>
                    <th>Discount</th>
                    <th>Description</th>
                    <th>Company</th>
                    <th>Branch</th>
                    <?php
                    while($row = mysqli_fetch_array($result))
                    {
                        $member_id = $row['member_id'];
                        $business_type = $row['business_type'];
                        $transaction_date = $row['trans_date'];
                        $vat_exempt_price = $row['vat_exempt_price'];
                        $discount_price = $row['discount_price'];
                        $description = $row['desc'];
                        $company = $row['company'];
                        $branch = $row['branch'];
                        
                        ?>
                        <tr>
                            <td><?php echo $business_type ?></td>
                            <td><?php echo $transaction_date ?></td>
                            <td><?php echo $vat_exempt_price ?></td>
                            <td><?php echo $discount_price ?></td>
                            <td><?php echo $description ?></td>
                            <td><?php echo $company ?></td>
                            <td><?php echo $branch ?></td>
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

<button class="btn btn-block btn-dark" id="xexpand">Show More</button>

<script>
$(document).ready(function(){
    var member_id = <?php echo $member_id; ?>;
    var counter = 1;

    $("#xexpand").click(function() {
        counter++;
        var business_type = "<?php echo $type; ?>";
        alert(business_type);
        $("#trans__").load("../backend/display_transactions.php #trans__", {member_id : member_id, counter: counter, business_type: business_type });
    });
});
</script>