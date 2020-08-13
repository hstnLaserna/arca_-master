SENIOR CITIZEN LOOKUP

<div>
  <input type="text" id="sr_serial">
  <button id="read_serial">Read ID</button>
  <button id="clear">Clear</button>

  <div>
    <!-- Modal Edit -->
    <div id="modal_displayMember" class="modal fade" role="dialog">
    </div> <!-- End modal -->

  </div>
  <div id="read_nfc"> </div>

</div>

<script>
  $(document).ready(function(){


    $('#read_serial2').click(function () {
      var input_nfc= $("#sr_serial").val();
        $('#modal_displayMember').load("../frontend/member_profile_card.php", { input_nfc: input_nfc },function(){
          $('#modal_displayMember').modal();
        });
    });

    $('#read_serial').click(function () {
      var input_nfc= $("#sr_serial").val();
        $('#read_nfc').load("../frontend/member_profile_card.php", { input_nfc: input_nfc });
    });

    $('#clear').click(function () {
        $('#sr_serial').val('');
        $('#read_nfc').replaceWith('<div id="read_nfc"> </div>');
    });

  });
</script>


