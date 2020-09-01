<?php
  include('../frontend/header.php');

?>
<div class="dropdown">
    <button class="btn btn-secondary btn-block dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Search
    </button>
    <div class="dropdown-menu w-100" aria-labelledby="dropdown_search">
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="lookup_form">
            <div class="col">
                <input type="text" class="w-100 form-control mb-1" name="company_name" placeholder="Company Name">
                <input type="text" class="w-100 form-control mb-1" name="branch" placeholder="Company Branch">
                <input type="text" class="w-100 form-control mb-1" name="company_tin" placeholder="Company TIN">
                <input type="text" class="w-100 form-control mb-1" name="business_type" placeholder="Business Type">
                
            </div>
            <div class="dropdown-divider"></div> 
            <button class="btn btn-dark btn-block" type="button" id="search_" data-toggle="dropdown"> Search </button>
        </form>
    </div>
</div>



<div id="display_search">
    
    <div class="table-responsive">
        <table class="table table-hover users">
            <th>Company Name</th>
            <th>Branch</th>
            <th>Business</th>
            <th>City</th>
            <th>Province</th>
        </table>
    </div>
</div>

<div class="_dfg987">
    <!-- Modal Edit -->
    <div id="modal_display_search" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>

<?php
  include('../frontend/foot.php');
?>

<script>
$("#search").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>Search Company</title>');

$(document).ready(function(){
    $("#search_").click(function() {
        $("#display_search").load("../backend/display_company_search.php", $("#lookup_form").serializeArray());
    });

});
</script>


