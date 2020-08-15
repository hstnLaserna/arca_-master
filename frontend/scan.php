    SENIOR CITIZEN LOOKUP

<div id="nfc">
    <input type="text" id="sr_serial">
    <button id="read_serial">Read ID</button>
    <button id="clear">Clear</button>

</div>

<div id="display_nfcread">
</div>

<script>
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


