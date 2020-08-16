<?php
  include('../frontend/head.php');
    
    include('../backend/conn.php');

    if(isset($_GET['member_id']))
    {
        ?>
        <div class="member-profile">
        <?php
        $member_id = $_GET['member_id'];
        
        $format_memdate = "concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`))";
        $format_bdate = "concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`))";
        $query = "SELECT `id`,	`osca_id`,	`nfc_serial`,	`password`,	`first_name`,	`middle_name`,	`last_name`,	
                            $format_bdate  `bdate`,	`sex`,	`contact_number`,	 $format_memdate `memship_date`, 
                            `picture` `pix` FROM `member` WHERE `id` = $member_id;";
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
            $sex =  $row['sex'];
            $bdate =  $row['bdate'];
            $memship_date =  $row['memship_date'];
            $contact_number =  $row['contact_number'];
            
            ?>

            <div class="col col-md-11 mx-auto mb-3 border p-0 border-dark rounded">
                <div class="user-card px-3">
                    <img class="profile-picture" src=<?php $picture = '../resources/members/'.$row["pix"]; if (file_exists($picture) && $row["pix"] != null) { echo '"'.$picture.'" '; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?>>
                    <p>Lastname: <?php echo $last_name; ?>,</p>
                    <p>Firstname: <?php echo $first_name; ?> </p>
                    <p>Middlename: <?php echo $middle_name; ?></p>
                    <p>Sex: <?php if($sex == 'f'|| $sex == 'F'){echo "Female";}else{echo "Male";} ?> </p>
                    <p>Birthdate: <?php echo $bdate; ?> </p>
                    <?php
                        $selected_id = $member_id;
                        include("../backend/read_address_with_edit.php");
                    ?>
                    <button type="button" id="add_address" class="btn btn-info">Add Address</button>
                    <p>Phone Number: <?php echo $contact_number; ?> </p>
                    <p>E-mail:  </p>
                    <p>OSCA ID: <?php echo $osca_id; ?> </p>
                    <p>Member since: <?php echo $memship_date; ?> </p>
                </div>
            
                <button type="button" id="edit_basic" class="btn btn-secondary btn-block">Edit Basic Details</button>
            </div>
                    
            <?php

        }

        $transaction_query = "SELECT `member_id`, trans_date, vat_exempt_price, discount_price , `desc`, 
                                company_name `company`, branch `branch`, `business_type`
                                FROM view_all_transactions WHERE member_id = $selected_id ORDER BY trans_date;";

        $result = $mysqli->query($transaction_query);
        $row_count = mysqli_num_rows($result);
        if($row_count != 0)
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