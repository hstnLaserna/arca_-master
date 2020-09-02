<?php
include('header.php');
include('../backend/php_functions.php');
if(isset($_GET['company_tin'])/* && isset($_GET['last_name'])*/)
{
    $company_tin = $_GET['company_tin'];
    $query = "SELECT 	`id`,	`company_tin`,	`company_name`,	`branch`,	`business_type`
                FROM `company` 
                WHERE `company_tin` = '$company_tin'";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);

    if($row_count == 1) {
        $row = mysqli_fetch_assoc($result);
        {
            $company_id = $row['id'];
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
                                <option>-</option>
                                <option>Pharmacy</option>
                                <option>Restaurant</option>
                                <option>Transportation</option>
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
                                <small>City</small>
                                <input type="text" class="form-control " name="address_city" value="<?php echo $city; ?>" placeholder="<?php echo $city; ?>">
                            </div>
                            <div class ="col col-lg-6 col-md-12">
                                <small>Province</small>
                                <input type="text" class="form-control " name="address_province" value="<?php echo $province; ?>" placeholder="<?php echo $province; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
        </div>
        <?php
        mysqli_close($mysqli);
    } else{
        echo "Company TIN does not match";
    }
} else
{
    echo "Company TIN could not be read";
    return false;
}
include('foot.php');
?>


<script>
  $(document).ready(function(){

    $("#submit").click(function(){
        $.post("../backend/update_company.php", $("#editCompany").serialize(), function(d){
            if(d == "truetrue") {
                var company_tin = $("#selected_id").val();
                location.replace("../frontend/companies.php");
            } else {
                alert(d);
            }
        });
    });
  });
</script>
