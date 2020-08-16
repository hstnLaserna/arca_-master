
<div class="dropdown">
    <button class="btn btn-secondary btn-block dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Search
    </button>
    <div class="dropdown-menu w-100" aria-labelledby="dropdown_search">
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="lookup_form">
            <div class="col">
                <input type="text" class="w-100 form-control mb-1" name="first_name" id="first_name" placeholder="First name">
                <input type="text" class="w-100 form-control mb-1"  name="middle_name" id="middle_name" placeholder="Middle name">
                <input type="text" class="w-100 form-control mb-1"  name="last_name" id="last_name" placeholder="Last name">
                <input type="text" class="w-100 form-control mb-1"  name="osca_id" id="osca_id" placeholder="OSCA ID">
            </div>
            <div class="dropdown-divider"></div> 
            <button class="btn btn-dark btn-block" type="button" id="search" data-toggle="dropdown"> Search </button>
        </form>
    </div>
</div>



<div id="display_search">
    
    <div class="table-responsive">
        <table class="table table-hover users">
            <th>Picture</th>
            <th>OSCA ID</th>
            <th>NFC Serial</th>
            <th>Firstname</th>
            <th>Middlename</th>
            <th>Lastname</th>
        </table>
    </div>
</div>

<div>
    <!-- Modal Edit -->
    <div id="modal_display_search" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>

<script>
$(document).ready(function(){

    $("#search").click(function() {
        $("#display_search").load("../backend/display_members_search.php", $("#lookup_form").serializeArray());
    });

});
</script>


