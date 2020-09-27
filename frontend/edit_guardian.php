
<div class="modal-dialog modal-edit-address">
    <div class="modal-content">
        <div class="modal-body">
<?php
if(isset($_POST['osca_id']) && isset($_GET['action']))
{
    ?>
    <div>
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="guardian_form">
            <div class="card border border-black">
                <?php 
                include("../backend/conn.php");
                {
                    $selected_osca_id = $_POST['osca_id'];

                    if(isset($_POST['gid']))
                    {
                        $selected_g_id = $_POST['gid'];
                        $query_1 = "SELECT * FROM `view_members_with_guardian`
                                    WHERE `osca_id` = '$selected_osca_id' AND `g_id` = '$selected_g_id' GROUP BY `g_id`";
                        $msg = "<p class='lead'>No guardian on record</p>";
                    }
                    else if ($_GET['action'] == "add")
                    {
                        
                        $query_1 = "SELECT * FROM `member` m
                                    WHERE m.`osca_id` = '$selected_osca_id';";
                        $msg = "<p class='lead'>Member does not exist</p>";
                    }

                    
                    $result = $mysqli->query($query_1);
                    $row_count = mysqli_num_rows($result);
                    if($row_count == 0) { echo $query_1;} else
                    {
                        while($row = mysqli_fetch_array($result))
                        {
                            $is_active = "";
                            $delete_button = "";
                            $g_sex2 = "";
                            if ($_GET['action'] == "edit")
                            {
                                $g_first_name = $row['g_first_name'];
                                $g_middle_name = $row['g_middle_name'];
                                $g_last_name = $row['g_last_name'];
                                $g_sex2 = $row['g_sex'];
                                $g_relationship = $row['g_relationship'];
                                $g_contact_number = $row['g_contact_number'];
                                $g_email = $row['g_email'];

                                
                                $ph_g_first_name = "placeholder='$g_first_name' value='$g_first_name'";
                                $ph_g_middle_name = "placeholder='$g_middle_name' value='$g_middle_name'";
                                $ph_g_last_name = "placeholder='$g_last_name' value='$g_last_name'";
                                $ph_g_sex = "placeholder='$g_sex2' value='$g_sex2'";
                                $ph_g_relationship = "placeholder='$g_relationship' value='$g_relationship'";
                                $ph_g_contact_number = "placeholder='$g_contact_number' value='$g_contact_number'";
                                $ph_g_email = "placeholder='$g_email' value='$g_email'";
                                
                                ?>
                                    <input type="hidden" name="selected_osca_id" value="<?php echo $selected_osca_id;?>">
                                    <input type="hidden" name="selected_g_id" value="<?php echo $selected_g_id;?>">
                                <?php 

                                //for script
                                $post_destination = "../backend/update_guardian.php";
                                $delete_button = '';//<button type="button" id="delete" class="btn btn-danger">Delete</button>';
                            } else {
                                $ph_g_first_name = "placeholder=''";
                                $ph_g_middle_name = "placeholder=''";
                                $ph_g_last_name = "placeholder=''";
                                $ph_g_sex = "placeholder=''";
                                $ph_g_relationship = "placeholder=''";
                                $ph_g_contact_number = "placeholder=''";
                                $ph_g_email = "placeholder=''";
                                ?>
                                    <input type="hidden" name="selected_osca_id" value="<?php echo $selected_osca_id;?>">
                                <?php 

                                //for script
                                $post_destination = "../backend/create_guardian.php";
                            }
                            ?>
                                <div>
                                    <h3> GUARDIAN </H3>
                                    <?php echo $delete_button?>
                                </div>
                                <div class="form-contents">
                                    <div class ="row">
                                        <div class ="col col-lg-4 col-md-12">
                                            Firstname
                                            <input type="text" class="form-control " name="g_first_name" <?php echo $ph_g_first_name;?>>
                                        </div>
                                        <div class ="col col-lg-4 col-md-12">
                                            Middlename
                                            <input type="text" class="form-control " name="g_middle_name" <?php echo $ph_g_middle_name;?>>
                                        </div>
                                        <div class ="col col-lg-4 col-md-12">
                                            Lastname
                                            <input type="text" class="form-control " name="g_last_name" <?php echo $ph_g_last_name;?>>
                                        </div>
                                    </div>
                                    <div class ="row">
                                        <div class ="col col-lg-6 col-md-12">
                                            Relationship
                                            <input type="text" class="form-control " name="g_relationship" <?php echo $ph_g_relationship;?>>
                                        </div>
                                        <div class ="col col-lg-6 col-md-12">
                                            Gender
                                            <select class="form-control" name="g_gender">
                                                <option <?php if($g_sex2 == "0" || $g_sex2 > "2"){echo "selected";}else{}; ?>>-</option>
                                                <option <?php if($g_sex2 == "2"){echo "selected";}else{}; ?>>Female</option>
                                                <option <?php if($g_sex2 == "1"){echo "selected";}else{}; ?>>Male</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class ="row">
                                        <div class ="col col-lg-6 col-md-12">
                                            Contact number
                                            <input type="text" class="form-control " name="g_contact_number" <?php echo $ph_g_contact_number;?>>
                                        </div>
                                        <div class ="col col-lg-6 col-md-12">
                                            Email
                                            <input type="text" class="form-control " name="g_email" <?php echo $ph_g_email;?>>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                    
                    mysqli_close($mysqli);
                }
                ?>
            </div>
                <button type="button" class="btn btn-primary btn-lg col-md-4" id="submit_guardian">Submit</button>
                <button type="reset" class="btn btn-secondary btn-lg col-md-4">Reset Values</button>
            <button type="button" data-dismiss="modal" class="btn btn-secondary btn-lg btn-block">Close</button>
        </form>
    </div>
    <?php
} else
{
    ?>
    <script>
        alert("Invalid Data. Redirecting.");
        window.location.replace("dashboard.php");
    </script>
    <?php
}
?>
        </div>
    </div>
</div>


<script>
  $(document).ready(function(){

    $('input[name!="g_middle_name"]').blur(function(){
        if($(this).val().length === 0) {
            $(this).addClass('input_error');
        }
        else{
            $(this).removeClass('input_error');
        }
    });

    $("#submit_guardian").click(function(){
        $.post("<?php  echo $post_destination ?>", $("#guardian_form").serialize(), function(d){
            if(d.trim() == "true") {
                location.reload();
            } else {
                alert(d);
            }
        });
    });

    $("#delete").click(function(){
        $.post("<?php  echo $post_destination ?>?action=delete", $("#guardian_form").serialize(), function(d){
            if(d == "true") {
                location.reload();
            } else {
                alert(d);
            }
        });
    });

  });
</script>
