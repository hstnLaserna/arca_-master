
<div class="modal-dialog modal-edit-address">
    <div class="modal-content">
        <div class="modal-body">
<?php
if(isset($_POST['member_id']) && isset($_GET['action']))
{
    ?>
    <div>
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="address_form">
            <table class="table">
                <?php 
                include("../backend/conn.php");
                {
                    $selected_member_id = $_POST['member_id'];
                    $mysqli1 = new mysqli($host,$user,$pass,$schema) or die($mysqli1->error);

                    if(isset($_POST['address_id']))
                    {
                        $selected_address_id = $_POST['address_id'];
                        $query_1 = " SELECT `a`.`id` `address_id`, `address1`, `address2`, `city`, `province`, `is_active` 
                                    FROM member m
                                    INNER JOIN `address_jt` `ajt` ON `ajt`.`member_id` = m.`id`
                                    INNER JOIN `address` `a` ON `ajt`.`address_id` = a.`id`
                                    WHERE m.`id` = '$selected_member_id' AND `a`.`id` = '$selected_address_id';";
                        $msg = "<p class='lead'>No address on record</p>";
                    }
                    else if ($_GET['action'] == "add")
                    {
                        $query_1 = "SELECT * FROM `member` `m` WHERE m.`id` = '$selected_member_id';";
                        $msg = "<p class='lead'>Member does not exist</p>";
                    }

                    
                    $result = $mysqli1->query($query_1);
                    $row_count = mysqli_num_rows($result);
                    if($row_count == 0) { echo $query_1;} else
                    {
                        while($row = mysqli_fetch_array($result))
                        {
                            $is_active = "";
                            $delete_button = "";
                            if ($_GET['action'] == "edit")
                            {
                                $address_id = $row['address_id'];
                                $address1 = $row['address1'];
                                $address2 = $row['address2'];
                                $city = $row['city'];
                                $province = $row['province'];
                                if($row['is_active'] == '1'){$is_active = "<p><small class='text-muted'>(Primary)</small></p>";}else{}

                                $placeholder_and_value_add1 = "placeholder='$address1' value='$address1'";
                                $placeholder_and_value_add2 = "placeholder='$address2' value='$address2'";
                                $placeholder_and_value_city = "placeholder='$city' value='$city'";
                                $placeholder_and_value_province = "placeholder='$province' value='$province'";
                                
                                ?>
                                    <input type="hidden" name="selected_member_id" value="<?php echo $selected_member_id;?>">
                                    <input type="hidden" name="selected_address_id" value="<?php echo $selected_address_id;?>">
                                <?php 

                                //for script
                                $post_destination = "../backend/update_address.php";
                                $delete_button = '<button type="button" id="delete" class="btn btn-danger">Delete</button>';
                            } else {
                                $placeholder_and_value_add1 = "placeholder='Address Line 1'";
                                $placeholder_and_value_add2 = "placeholder='Address Line 2'";
                                $placeholder_and_value_city = "placeholder='City'";
                                $placeholder_and_value_province = "placeholder='Province'";
                                ?>
                                    <input type="hidden" name="selected_member_id" value="<?php echo $selected_member_id;?>">
                                <?php 

                                //for script
                                $post_destination = "../backend/create_address.php";
                            }
                            ?>
                            
                            <tr> 
                                <td rowspan="5">
                                    Address:
                                    
                                    <?php echo $delete_button . " <br> " . $is_active ?>
                                </td>
                                <td>
                                    <label for="address_1">Line 1 <small> <em> (Unit no., Street, Zone) </em> </small></label>
                                    <input type="text" class="form-control" name="address_line1" <?php echo $placeholder_and_value_add1?>>
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                    <label for="address_2">Line 2 <small> <em> (Brgy/Subdivision) </em> </small></label>
                                    <input type="text" class="form-control" name="address_line2" <?php echo $placeholder_and_value_add2?>>
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="address_city"  <?php echo $placeholder_and_value_city?>>
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                    <label for="provice">Province</label>
                                    <input type="text" class="form-control" name="address_province"  <?php echo $placeholder_and_value_province?>>
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                    <label for="is_active">Primary Address?</label>
                                    <input type="checkbox" name="address_is_active"  <?php if($is_active != ""){echo "checked";}else{}?>>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    
                    mysqli_close($mysqli1);
                }
                ?>
            </table>
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
        $.post("<?php  echo $post_destination ?>", $("#address_form").serialize(), function(d){
            if(d == "true") {
                location.reload();
            } else {
                alert(d);
            }
        });
    });
    $("#delete").click(function(){
        $.post("<?php  echo $post_destination ?>?action=delete", $("#address_form").serialize(), function(d){
            if(d == "true") {
                location.reload();
            } else {
                alert(d);
            }
        });
    });

  });
</script>
