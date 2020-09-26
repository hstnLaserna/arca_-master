<?php
include('header.php');
include('../backend/php_functions.php');
if(isset($_GET['company_tin'])/* && isset($_GET['last_name'])*/)
{
    $company_tin = $_GET['company_tin'];
    $query = "SELECT c.`id` `company_id`, c.`company_tin`, c.`company_name`, c.`branch`, c.`business_type`
            FROM `company` c
            WHERE `company_tin` = '$company_tin'";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);

    if($row_count == 1) {
        $row = mysqli_fetch_assoc($result);
        {
            $company_id = $row['company_id'];
            $company_tin = $row['company_tin'];
            $company_name = $row['company_name'];
            $branch = $row['branch'];
            $business_type = $row['business_type'];
        }
        ?>
        <div class="registration-form" id="accordion">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="editCompany">
                <div class="collapse-header" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <h3> COMPANY DETAILS</H3>
                </div>
                <div id="collapseOne" class="form-contents collapse show" aria-labelledby="headingOne">
                    <div>
                        Company Name
                        <input type="text" class="form-control " name="company_name" value="<?php echo $company_name; ?>" placeholder="<?php echo $company_name; ?>">
                    </div>
                    <div>
                        Company Branch
                        <input type="text" class="form-control " name="company_branch" value="<?php echo $branch; ?>" placeholder="<?php echo $branch; ?>">
                    </div>
                    <div class="row">
                        <div class ="col col-lg-6 col-12">
                            Company TIN
                            <input type="text" class="form-control " name="company_tin" value="<?php echo $company_tin; ?>" placeholder="<?php echo $company_tin; ?>">
                        </div>
                        <div class ="col col-lg-6 col-12">
                            Business Type
                            <select class="form-control" name="business_type">
                                <option <?php if($business_type != "pharmacy" && $business_type != "food" && $business_type != "transportation"){echo "selected";}; ?>>-</option>
                                <option <?php if($business_type == "pharmacy"){echo "selected";}; ?>>Pharmacy</option>
                                <option <?php if($business_type == "food"){echo "selected";}; ?>>Restaurant</option>
                                <option <?php if($business_type == "transportation"){echo "selected";}; ?>>Transportation</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="company_tin_old" value="<?php echo $company_tin; ?>">
                    <?php 
                    
                        $query_2 = "SELECT 	`address1`,	`address2`,	`city`,	`province`
                            FROM `address` a
                            INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.`id`
                            WHERE ajt.`company_id` = '$company_id'";
                        $result_2 = $mysqli->query($query_2);
                        $row_count_2 = mysqli_num_rows($result_2);

                        if($row_count_2 == 1) {
                            $row_2 = mysqli_fetch_assoc($result_2);
                            $address1 = $row_2['address1'];
                            $address2 = $row_2['address2'];
                            $city = $row_2['city'];
                            $province = $row_2['province'];
                        } else {
                            $address1 = "";
                            $address2 = "";
                            $city = "";
                            $province = "";
                        }
                    
                    ?>
                    <div>
                        Address
                        <div class ="row">
                            <div class ="col col-12">
                                <small>Line 1 <em>(House Number, Street)</em></small>
                                <input type="text" class="form-control " name="address_line1" value="<?php echo $address1; ?>" placeholder="<?php echo $address1; ?>">
                            </div>
                            <div class ="col col-12">
                                <small>Line 2 <em>(Barangay, Subdivision)</em></small>
                                <input type="text" class="form-control " name="address_line2" value="<?php echo $address2; ?>" placeholder="<?php echo $address2; ?>">
                            </div>
                            <div class ="col col-lg-6 col-md-12">
                                <small>Province</small>
                                <select class="form-control" name="address_province" id="address_province">
                                    <?php populate_province(); ?>
                                </select>
                            </div>
                            <div class ="col col-lg-6 col-md-12">
                                <small>City</small>
                                <select class="form-control" name="address_city" id="address_city">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
        </div>
        <?php
        mysqli_close($mysqli);
    } else {
        echo "aaa";
    }
} else{
    echo "aaa";
    include('../backend/fail_data.php');
}
include('foot.php');
?>


<script>
  $(document).ready(function(){

    $("#submit").click(function(){
        $.post("../backend/update_company.php", $("#editCompany").serialize(), function(d){
            if(d == "true") {
                location.replace("../frontend/companies.php");
            } else {
                alert(d);
            }
        });
    });

    $("select option[value='<?php echo $province;?>']").attr("selected","selected");
    
    $.post('../backend/populate_city.php', { province: '<?php echo $province;?>' }, function(data){
        $("#address_city").replaceWith('<select class="form-control" name="address_city" id="address_city">'+data+'</select>');
        $("select option[value='<?php echo $city;?>']").attr("selected","selected");
    });

    $('#address_province').change(function(){
        var provinceSelected = $(this).val();
        $.post('../backend/populate_city.php', { province: provinceSelected }, function(data){
            $("#address_city").replaceWith('<select class="form-control" name="address_city" id="address_city">'+data+'</select>');
        });
    });
  });
  
  
</script>
