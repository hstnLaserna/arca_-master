
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        SCAN
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdown_search">
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="lookup_form">
            <div class="col">
                <input type="text" id="sr_serial">
            </div>
                <button class="btn btn-dark btn-block" type="button" id="read_serial"> Read ID </button>
                <button class="btn btn-dark btn-block" type="button" id="clear"> Clear </button>
        </form>
    </div>
</div>

<div id="display_nfcread">
</div>

<script>

$("#scan").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>Scan OSCA Member tag</title>');

$(document).ready(function(){

    $('#read_serial').click(function () {
        var input_nfc= $("#sr_serial").val();
        $('#display_nfcread').load("../frontend/member_profile_card.php", { input_nfc: input_nfc });
    });

    $('#clear').click(function () {
        $('#sr_serial').val('');
        $('#display_nfcread').replaceWith('<div id="display_nfcread"> </div>');
    });


});
</script>


