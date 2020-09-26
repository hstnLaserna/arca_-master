<div id="trans-ph">
    <?php
    include("../backend/conn.php");
    include("../backend/php_functions.php");
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
        
            $ctr2 = 1;
            $items_per_page = 5;


            if(isset($_POST['ctr2'])){ // Meaning user prompted to view more transaction data. Disable the "transactions for the last month" condition.
                $ctr2 = filter_input(INPUT_POST, 'ctr2', FILTER_VALIDATE_INT);
                if(!$ctr2) {
                    $ctr2 = 1;
                }
            }

            $displayed_items = ($ctr2) * $items_per_page;
            $transaction_query = "SELECT *
                                    FROM `view_pharma_transactions_all`
                                    WHERE `member_id` = '$member_id'
                                    ORDER BY `trans_date` ASC";

            $result = $mysqli->query($transaction_query);
            $row_count_orig = mysqli_num_rows($result);


            $transaction_query = "SELECT `member_id`, `trans_date`, `vat_exempt_price`, `discount_price`, `payable_price`,
                                        `desc_nondrug`, `generic_name`, `brand`, `dose`, `unit`, 
                                        concat(quantity,  'pcs, ') as `qty`, `unit_price`,
                                        company_name `company`, `branch`, `company_tin`
                                    FROM `view_pharma_transactions_all`
                                    WHERE `member_id` = '$member_id'
                                    ORDER BY `trans_date` DESC  
                                    LIMIT $displayed_items;";
            $result = $mysqli->query($transaction_query);
            $row_count_display = mysqli_num_rows($result);

            /*
            if($row_count_display == 0 && $row_count_orig > 0) {
                // remove the condition of the last 3 months
                $transaction_query = "SELECT `member_id`, `trans_date`, `vat_exempt_price`, `discount_price`, `payable_price`,
                                            concat(UCASE(LEFT(generic_name, 1)), LCASE(SUBSTRING(generic_name, 2))) as `generic_name`, 
                                            concat(UCASE(LEFT(brand, 1)), LCASE(SUBSTRING(brand, 2))) as `brand`, `dose`, `unit`, 
                                            concat(quantity,  'pcs, ') as `qty`, `unit_price`,
                                            company_name `company`, `branch`, `company_tin`
                                        FROM `view_pharma_transactions`
                                        WHERE `member_id` = '$member_id'
                                        ORDER BY `trans_date` DESC  
                                        LIMIT $displayed_items;";
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

                        $transaction_date = $row['trans_date'];
                        $company = $row['company'];
                        $company_tin = $row['company_tin'];
                        $branch = $row['branch'];

                        $desc_nondrug = $row['desc_nondrug'];
                        $brand = $row['brand'];
                        $generic_name = $row['generic_name'];

                        $generic_name = arrange_generic_name($row['generic_name']);

                        $dose = $row['dose'];
                        $unit = $row['unit'];
                        $qty = $row['qty'];
        
                        $formatter = new NumberFormatter("fil-PH", \NumberFormatter::CURRENCY);
                        $vat_exempt_price = $formatter->format($row['vat_exempt_price']);
                        $discount_price = $formatter->format($row['discount_price']);
                        $payable_price = $formatter->format($row['payable_price']);
                        $unit_price = $formatter->format($row['unit_price']);
                        
                        ?>
                        <tr>
                            <td><?php echo $transaction_date ?></td>
                            <td>
                                <a href="../frontend/company_profile.php?company_tin=<?php echo $company_tin;?>" class="view_">
                                    <?php echo "$company <br> <i> $branch</i>" ?>
                                </a>
                            </td>
                            <td class="med-desc">
                            <?php 
                                if ($desc_nondrug == "") {
                            ?>
                                <p><?php echo $brand ?> <?php echo $dose ?><?php echo $unit?>
                                <p>[<?php echo $generic_name?>]</p>
                                <p><?php echo $qty ?> (<?php echo $unit_price?>/pc)</p>
                                
                            <?php 
                                } else {
                                ?>
                                    <p><?php echo $desc_nondrug ?></p>
                                    <p><i>(Non medicine)</i></p>
                                <?php 
                                }
                            ?>
                            </td>
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

<button class="btn btn-block btn-dark" id="expand-ph">Show More</button>

<script>
$(document).ready(function(){
    var member_id = <?php echo $member_id; ?>;
    var ctr2 = 1;

    $("#expand-ph").click(function() {
        ctr2++;
        $("#trans-ph").load("../backend/display_transactions_pharmacy.php #trans-ph", {member_id : member_id, ctr2: ctr2 });
    });
});
</script>