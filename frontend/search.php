    SENIOR CITIZEN LOOKUP

<div id="lookup">
    <form method="post" enctype="multipart/form-data" autocomplete="off" id="lookup_form">
        <input type="text" name="first_name" id="first_name" placeholder="First name">
        <input type="text" name="middle_name" id="middle_name" placeholder="Middle name">
        <input type="text" name="last_name" id="last_name" placeholder="Last name">
        <input type="text" name="osca_id" id="osca_id" placeholder="OSCA ID">
        <button type="button" class="btn btn-dark" id="search">Search</button>
    </form>
</div>

<div id="display_search">
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


