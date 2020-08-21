<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    if(isset($_GET['member_id']))
    {
        $member_id = $_GET['member_id'];
        
        $format_bdate = "concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`))";
        $format_memdate = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
        $query = "SELECT * FROM `view_members_with_guardian`
                    WHERE `member_id` = $member_id;";
                            
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        if($row_count == 0) { echo "ID Does not exist";} else
        {
            if($row_count > 1) { echo "ID returns more than 1 record";} else{}
            $osca_id = $row['osca_id'];
            $first_name = $row['first_name'];
            $middle_name =  $row['middle_name'];
            $last_name =  $row['last_name'];
            $sex2 =  $row['sex'];
            $contact_number =  $row['contact_number'];
            $email =  $row['email'];
            $bdate =  $row['bdate'];
            $age =  $row['age'];
            $memship_date =  $row['memship_date'];

            
            $g_first_name = $row['g_first_name'];
            $g_last_name =  $row['g_last_name'];
            $g_sex2 =  $row['g_sex'];
            $g_contact_number =  $row['g_contact_number'];
            $g_email =  $row['g_email'];
            $g_relationship =  $row['g_relationship'];
            
            ?>

            <div class="card digital-card-contents">
                <div class="card-right">
                    <img class="profile-picture" src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'" '; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                </div>
                <div class="card-bottom-right">
                    <button type="button" id="edit_basic" class="btn btn-secondary my-2 w-75">Edit Basic Details</button>
                    <button type="button" id="add_address" class="btn btn-secondary m   -2 w-75">Add Address</button>
                </div>
                <div class="card-left">
                    <div class="card">
                        <p>Lastname: <?php echo $last_name; ?>,</p>
                        <p>Firstname: <?php echo $first_name; ?> </p>
                        <p>Middlename: <?php echo $middle_name; ?></p>
                        <p>Sex:  <?php echo determine_sex($sex2, "display_long"); ?> </p>
                        <p>Birthdate: <?php echo "$bdate (Age: $age y.o.)"; ?></p>
                        <?php
                            read_address($member_id, true);
                        ?>
                        <p>Phone Number: <?php echo $contact_number; ?> </p>
                        <p>E-mail: <?php echo $email; ?></p>
                        <p>OSCA ID: <?php echo $osca_id; ?> </p>
                        <p>Member since: <?php echo $memship_date; ?> </p>
                    </div>

                    <div class="card">
                        <h3> Guardian's Details </h3>
                        <p>Full Name: <?php echo "$g_first_name $g_last_name"; ?></p>
                        <p>Relationship: <?php echo "$g_relationship"; ?></p>
                        <p>Sex: <?php echo determine_sex($g_sex2, "display_long"); ?> </p>
                        <p>Contact Number: <?php echo "$g_contact_number"; ?></p>
                        <p>Email: <?php echo "$g_email"; ?></p>
                    </div>
                </div>
            </div>
                    
            <?php

        }

        

        $transaction_query = "SELECT *
                                FROM view_all_transactions
                                WHERE member_id = $member_id --  AND date(trans_date) >= (LEFT(NOW() - INTERVAL 1 MONTH,10))
                                ORDER BY trans_date ASC";

        $result = $mysqli->query($transaction_query);
        $row_count_orig = mysqli_num_rows($result);


        $transaction_query = "SELECT * FROM (SELECT `member_id`, trans_date, vat_exempt_price, discount_price , `desc`, 
                                company_name `company`, branch `branch`, `business_type`
                                FROM view_all_transactions
                                WHERE member_id = $member_id AND date(trans_date) >= (LEFT(NOW() - INTERVAL 1 MONTH,10))
                                ORDER BY trans_date ASC) ttt ORDER BY `trans_date` DESC  LIMIT 3;";

        $result = $mysqli->query($transaction_query);
        $row_count_display = mysqli_num_rows($result);
        if($row_count_display != 0)
        {
            ?>
            <div class="col col-md-11 m-auto p-3 border border-dark rounded overflow-auto">
                <div class="table-responsive">
                    <table class="table table-hover users">
                        <th>Transaction Type</th>
                        <th>Transaction date</th>
                        <th>vat_exempt_price</th>
                        <th>Discount</th>
                        <th>Description</th>
                        <th>Company</th>
                        <th>Branch</th>
                                            
                        <?php
                    while($row = mysqli_fetch_array($result))
                    {
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
                    if ($row_count_orig > $row_count_display)
                    {?>
                        <button class="btn btn-block btn-secondary">Display All Transactions</button><br>
                        <?php
                    }
                    //echo "$row_count_orig,  $row_count_display";
                    ?>
                </div>
            </div>
            <?php
        } else {echo "<div class='rounded col col-sm-6 m-auto p-3 text-center'>No user transaction in the record</div>";}
        mysqli_close($mysqli);
        ?>

        <?php

    }

?>


<div>
    <!-- Modal Edit -->
    <div id="modal_edit_address" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>
<div class="container">
    <?php
    include('../frontend/foot.php');
    ?>
</div>

<script>
$('title').replaceWith('<title>Member profile - <?php echo "$first_name $last_name"; ?></title>');
$(document).ready(function(){
    $('#edit_basic').click(function () {
        var url = 'edit_member.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="member_id" value="' + <?php echo $member_id?> + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });
    $('.edit_address').click(function () {
        var address_id= $(this).parent().attr("id").replace("memNum_", "");
        var member_id= <?php echo $member_id;?>;
        $('#modal_edit_address').load("../frontend/edit_address.php?action=edit", {member_id:member_id, address_id:address_id},function(){
            $('#modal_edit_address').modal();
        });
    });
    $('#add_address').click(function () {
        var member_id= <?php echo $member_id;?>;
        $('#modal_edit_address').load("../frontend/edit_address.php?action=add", {member_id:member_id},function(){
            $('#modal_edit_address').modal();
        });
    });

        //*************************************//
    { // Sort thru Table headers
        $('th').click(function(){
        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc){rows = rows.reverse()}
        for (var i = 0; i < rows.length; i++){table.append(rows[i])}
        });
        function comparer(index) {
            return function(a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }
        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
    }
});
</script>