<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    // declare variables 

    $company_name = "";
    $branch = "";
    $company_tin = "";
    $business_type = "";
    $logo = "../resources/images/unknown_m_f.png";
    $buttons = "";

    if(isset($_GET['company_tin']))
    {
        $selected_company_tin = $_GET['company_tin'];

        $query = "SELECT c.`id` id, c.`company_name` `company_name`, c.`branch` `branch`, 
                        c.`company_tin` `company_tin`, c.`business_type` `business_type`, c.`logo` `logo`
                    FROM `company` c
                    WHERE `company_tin` = '$selected_company_tin';";
                    
        $result = $mysqli->query($query);
        $row_count_member = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);

        if($row_count_member == 1)
        {
            $company_id = $row['id'];
            $company_name = $row['company_name'];
            $branch = $row['branch'];
            $company_tin = $row['company_tin'];
            $business_type = $row['business_type'];
            
            $logo =  "../resources/logo/".$row["logo"]; 

            if (file_exists($logo) && $row["logo"] != null) {
                $logo =  "../resources/logo/".$row["logo"]; 
            } else {
                $logo = "../resources/images/unknown_m_f.png";
            }
            


            $buttons = '
            <button type="button" id="edit_basic" class="btn btn-secondary my-2 w-75">Edit Basic Details</button>';
        } else {}
        mysqli_close($mysqli);

    } else {}
?>
            <div class="digital-card-contents">
                <div class="card-right">
                    <div class="profile-picture-container">
                        <form action="../backend/upload.php" id="form_photo" method="post" enctype="multipart/form-data" >
                            <img class="profile-picture" src="<?php echo $logo; ?>" alt="logo" id="output">
                            <div class="middle">
                                <input type="file" name="photo" accept="image/x-png,image/jpeg" onchange="loadFile(event)" id="file" class="inputfile">
                                <input type="hidden" name="entity_key" value="<?php echo $company_tin;?>">
                                <input type="hidden" name="entity_type" value="company">
                                <label for="file" class="text">Change</label>
                                <button type="submit" value="upload" id="submit" class="hidden btn btn-photo">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="company-card-left">
                    <div class="basic">
                        <button class="ml-auto btn btn-link edit" id="edit_basic"><i class="fa fa-edit"></i></button>
                        <h4 class="ml-1"> Company Information </h4>
                        <ul class="profile-details">
                            <li class="profile-item">
                                <span class="title">Company Name</span> 
                                <span class="content"><?php echo $company_name; ?></span>
                            </li>
                            <li class="profile-item">
                                <span class="title">Branch</span> 
                                <span class="content"><?php echo $branch; ?></span>
                            </li>
                            <li class="profile-item">
                                <span class="title">Company TIN</span> 
                                <span class="content"><?php echo wordwrap($company_tin , 3 , '-' , true ); ?></span>
                            </li>
                            <li class="profile-item">
                                <span class="title">Business Type</span> 
                                <span class="content"><?php echo ucwords($business_type); ?></span>
                            </li>
                                <?php  //address
                                    $addresses = read_address2($company_tin, "company");
                                    $address_id = $addresses['address_id'];
                                    $address1 = $addresses['address1'];
                                    $address2 = $addresses['address2'];
                                    $city = $addresses['city'];
                                    $province = $addresses['province'];
                                ?>
                            <li class='profile-item disp_address' id='addNum_<?php echo $address_id?>'> 
                                <span class='title'>Address</span>
                                <span class="content"><?php echo "$address1, $address2, $city, $province";?></span>
                                <button class="ml-auto btn btn-link edit edit_address"><i class="fa fa-edit"></i></button>
                            </li>
                        </ul>
                    </div>
                </div>
            
            
                <div class="card overflow-auto transactions">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-trans-tab" data-toggle="tab" href="#nav-trans" role="tab" aria-controls="nav-transactions" aria-selected="true">Transactions</a>
                            <a class="nav-item nav-link" id="nav-complaints-tab" data-toggle="tab" href="#nav-complaints" role="tab" aria-controls="nav-complaints" aria-selected="true">Complaints</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active"  id="nav-trans" role="tabpanel" aria-labelledby="nav-transactions-tab"> </div>
                        <div class="tab-pane fade"              id="nav-complaints" role="tabpanel" aria-labelledby="nav-complaints-tab"> </div>
                    </div>
                </div>
            </div>


<div class="container">
    <!-- Modal Edit -->
    <div id="_kf939s" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>

<?php
include('../frontend/foot.php');
?>

<script>
$('title').replaceWith('<title>Member profile - <?php echo ""; ?></title>');


var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
    URL.revokeObjectURL(output.src) // free memory
    }
    
    if( document.getElementById("file").files.length == 0 ){
        document.getElementById("submit").classList.add("hidden");
        console.log("no files selected");
    } else {
        document.getElementById("submit").classList.remove("hidden");
        console.log("File is selected");
    }
};
var inputs = document.querySelectorAll('.inputfile');

Array.prototype.forEach.call(inputs, function(input)
{
    var label	 = input.nextElementSibling,
        labelVal = label.innerHTML;

    input.addEventListener('change', function(e)
    {
        var fileName = '';

        if(fileName)
            label.querySelector('span').innerHTML = fileName;
        else
            label.innerHTML = labelVal;
    });
});
$(document).ready(function(){
    
    // display_transactions_company
    // display_complaints_company
    $("#nav-trans").load("../backend/display_transactions_company.php", {company_tin : "<?php echo $company_tin;?>", business_type: "<?php echo $business_type; ?>" });
    
    $("#nav-complaints").load("../backend/display_complaints_company.php", {company_tin : "<?php echo $company_tin;?>" });
    
    $('.edit_address').click(function () {
        var address_id= $(this).parent().attr("id").replace("addNum_", "");
        var company_id= "<?php echo $company_id;?>";
        alert(company_id + " " + address_id);
        $('#_kf939s').load("../frontend/edit_address.php?action=edit", {id:company_id, address_id:address_id, type: "company"},function(){
            $('#_kf939s').modal();
        });
    });

    
    $('#edit_basic').click(function () {
        var url = 'edit_company.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="company_tin" value="' + <?php echo $company_tin?> + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });

    
    if( document.getElementById("file").files.length == 0 ){
            document.getElementById("submit").classList.add("hidden");
            console.log("no files selected");
        } else {
            document.getElementById("submit").classList.remove("hidden");
            console.log("File is selected");
        }
    
});
</script>