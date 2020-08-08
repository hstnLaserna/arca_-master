SCHEDULE

<input type="text" id="sr_serial">
<button id="read_serial">Read ID</button>
<div id="senior_details">

</div>

<script>
  $(document).ready(function(){
    $("#read_serial").click(function(){
      var input_nfc = $("#sr_serial").val();
      $("#senior_details").load('../backend/read_id.php?input_nfc='+input_nfc);
    });
  });
</script>
