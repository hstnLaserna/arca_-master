
<div class="modal-dialog modal-edit-address">
    <div class="modal-content">
        <div class="modal-body">
<?php
if(isset($_POST['member_id']) && isset($_POST['address_id']))
{
    ?>
    <div>
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="edit_address">
            <table class="table">
                <?php 
                include("../backend/conn.php");
                {
                    $selected_member_id = $_POST['member_id'];
                    $selected_address_id = $_POST['address_id'];
                    $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);
                    $address_query = "SELECT `a`.`id` `address_id`,`address1`, `address2`, `city`, `province`, `is_active` FROM `member` `m` LEFT JOIN `address` a ON m.`id` = a.`member_id` WHERE m.`id` = $selected_member_id AND `a`.`id` = $selected_address_id;";
                    $result = $mysqli1->query($address_query);
                    $row_count = mysqli_num_rows($result);
                    if($row_count == 0) { echo "<p class='lead'>No address on record</p>";} else
                    {
                        while($row = mysqli_fetch_array($result))
                        {
                            $address_counter = 1;
                            $address_id = $row['address_id'];
                            $address1 = $row['address1'];
                            $address2 = $row['address2'];
                            $city = $row['city'];
                            $province = $row['province'];
                            if($row['is_active'] == '1'){$is_active = true;}else{$is_active=false;}
                            ?>
                            
                            <tr id="<?php echo $address_id; ?>"> 
                                <td>
                                    Address:
                                    <?php if($is_active){echo "<small class='text-muted'>(Primary)</small></p>";}else{echo "</p>";} ?>
                                </td>
                                <td>
                                    <label for="address_1">Line 1 <small> <em> (Unit no., Street, Zone) </em> </small></label>
                                    <input type="text" class="form-control" name="address_line1" placeholder="<?php echo $address1; ?>" value="<?php echo $address1; ?>">
                                    <br>
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="address_city" placeholder="<?php echo $city; ?>" value="<?php echo $city; ?>">
                                </td>
                                <td>
                                    <label for="address_2">Line 2 <small> <em> (Brgy/Subdivision, Municipality) </em> </small></label>
                                    <input type="text" class="form-control" name="address_line2" placeholder="<?php echo $address2; ?>" value="<?php echo $address2; ?>">
                                    <br>
                                    <label for="provice">Province</label>
                                    <input type="text" class="form-control" name="address_province" placeholder="<?php echo $province; ?>" value="<?php echo $province; ?>">
                                </td>
                            </tr>
                            <?php
                            $address_counter++;
                        }
                    }
                    
                    mysqli_close($mysqli1);
                }
                ?>
            </table>
            <input type="hidden" name="selected_member_id" value="<?php echo $selected_member_id;?>">
            <input type="hidden" name="selected_address_id" value="<?php echo $selected_address_id;?>">
            <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
            <button type="reset" class="btn btn-secondary btn-lg btn-block">Reset Values</button>
            <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
        </form>
    </div>
    <?php
} else
{
    echo 'Invalid data'; 
}
?>


<script>
  $(document).ready(function(){

    $('input[name!="middle_name"]').blur(function(){
        if($(this).val().length === 0) {
            $(this).addClass('input_error');
        }
        else{
            $(this).removeClass('input_error');
        }
    });

    $("#submit").click(function(){
        $.post("../backend/update_address.php", $("#edit_address").serialize(), function(d){
            alert(d);
        });
    });
  });
</script>
