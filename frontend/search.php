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
                <input type="text" class="w-100 form-control mb-1" name="fname" id="fname" placeholder="First name">
                <input type="text" class="w-100 form-control mb-1"  name="mname" id="mname" placeholder="Middle name">
                <input type="text" class="w-100 form-control mb-1"  name="lname" id="lname" placeholder="Last name">
                <input type="text" class="w-100 form-control mb-1"  name="oid" id="oid" placeholder="OSCA ID">
            </div>
            <div class="dropdown-divider"></div> 
            <button class="btn btn-dark btn-block" type="button" id="search_" data-toggle="dropdown"> Search </button>
        </form>
    </div>
</div>



<div id="display_search">
    
    <div class="table-responsive">
        <table class="table table-hover users">
            <th>Picture</th>
            <th>OSCA ID</th>
            <th>Firstname</th>
            <th>Middlename</th>
            <th>Lastname</th>
            <th>City</th>
            <th>Province</th>
        </table>
    </div>
</div>

<div>
    <!-- Modal Edit -->
    <div id="modal_display_search" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>

<?php
  include('../frontend/foot.php');
?>

<script>
$("#search").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>Scan OSCA Member tag</title>');

$(document).ready(function(){
    $("#search_").click(function() {
        $("#display_search").load("../backend/display_members_search.php", $("#lookup_form").serializeArray());
    });

});

/*

        var fname = $("#fname").val();
        var mname = $("#mname").val();
        var  mname = $("#lname").val();
        var oid = $("#oid").val();
        
        $("#display_search").load("../backend/display_members_search.php?fname="+fname+"&mname="+mname+"&lname="+lname+"&oid="+oid});
*/
</script>


