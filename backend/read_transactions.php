<?php
    include('../backend/conn.php');
    //$transtype = new array();
    if(isset($selected_id))
    {
        ?>
        <div class="table-responsive">
            <table class="table table-hover users">
                <th>Transaction Type</th>
                <th>Transaction date</th>
                <th>Amount</th>
                <th>Discount</th>
                <th>Description</th>
                <th>Company</th>
                <th>Branch</th>
                					
                <?php

        $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);

        $transaction_query = "SELECT m.id `member_id`, f.transaction_date `transaction_date`, f.total_amount `amount`,
                                f.total_discount `discount`, f.description `description`, 
                                c.company_name `company`, c.branch `branch`
                            FROM member m, company c, food_transaction f
                            WHERE m.id = f.member_id and f.company_id = c.id and f.member_id = $selected_id";
        $result = $mysqli1->query($transaction_query);
        $row_count = mysqli_num_rows($result);
        if($row_count != 0)
        {
                $address_counter = 1;
                while($row = mysqli_fetch_array($result))
                {
                    $transaction_date = $row['transaction_date'];
                    $amount = $row['amount'];
                    $discount = $row['discount'];
                    $description = $row['description'];
                    $company = $row['company'];
                    $branch = $row['branch'];
                    
                    ?>
                    <tr>
                        <td>Food</td>
                        <td><?php echo $transaction_date ?></td>
                        <td><?php echo $amount ?></td>
                        <td><?php echo $discount ?></td>
                        <td><?php echo $description ?></td>
                        <td><?php echo $company ?></td>
                        <td><?php echo $branch ?></td>
                    </tr>
                    <?php
            
                }
        } else {}

        $transaction_query = "SELECT m.id `member_id`, p.transaction_date `transaction_date`, p.total_amount `amount`,
                                p.total_discount `discount`, p.description `description`, 
                                c.company_name `company`, c.branch `branch`
                            FROM member m, company c, pharmacy_transaction p
                            WHERE m.id = p.member_id and p.company_id = c.id and p.member_id = $selected_id";
        $result = $mysqli1->query($transaction_query);
        $row_count = mysqli_num_rows($result);
        if($row_count != 0)
        {
                $address_counter = 1;
                while($row = mysqli_fetch_array($result))
                {
                    $transaction_date = $row['transaction_date'];
                    $amount = $row['amount'];
                    $discount = $row['discount'];
                    $description = $row['description'];
                    $company = $row['company'];
                    $branch = $row['branch'];
                    
                    ?>
                    <tr>
                        <td>Pharmacy</td>
                        <td><?php echo $transaction_date ?></td>
                        <td><?php echo $amount ?></td>
                        <td><?php echo $discount ?></td>
                        <td><?php echo $description ?></td>
                        <td><?php echo $company ?></td>
                        <td><?php echo $branch ?></td>
                    </tr>
                    <?php
            
                }
        } else {}

        $transaction_query = "SELECT m.id `member_id`, t.transaction_date `transaction_date`, t.total_amount `amount`,
                                t.total_discount `discount`, t.description `description`, 
                                c.company_name `company`, c.branch `branch`
                            FROM member m, company c, transport_transaction t
                            WHERE m.id = t.member_id and t.company_id = c.id and t.member_id = $selected_id";
        $result = $mysqli1->query($transaction_query);
        $row_count = mysqli_num_rows($result);
        if($row_count != 0)
        {
                $address_counter = 1;
                while($row = mysqli_fetch_array($result))
                {
                    $transaction_date = $row['transaction_date'];
                    $amount = $row['amount'];
                    $discount = $row['discount'];
                    $description = $row['description'];
                    $company = $row['company'];
                    $branch = $row['branch'];
                    
                    ?>
                    <tr>
                        <td>Transportation</td>
                        <td><?php echo $transaction_date ?></td>
                        <td><?php echo $amount ?></td>
                        <td><?php echo $discount ?></td>
                        <td><?php echo $description ?></td>
                        <td><?php echo $company ?></td>
                        <td><?php echo $branch ?></td>
                    </tr>
                    <?php
            
                }
        } else {}
        mysqli_close($mysqli1);

        
            ?>
            </table>
        </div>
        <?php
    } else {echo "ID does not exist";}
?>
