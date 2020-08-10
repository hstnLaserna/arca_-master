MEMBERS
<?php
include("../backend/display_members.php")
 ?>

<div>
  <!-- Modal Edit -->
  <div id="modal_displayMember" class="modal fade" role="dialog">
  </div> <!-- End modal -->

</div>















<script>
  $(document).ready(function(){

    $('.member-row').click(function () {
      var memberID= $(this).attr("id").replace("memNum_", "");
      $('#modal_displayMember').load("display_member.php", { id: memberID },function(){
        $('#modal_displayMember').modal();
      });
    });

    $("#submit").click(function(){
      $.post("../backend/add_admin.php", $("#newAdmin").serialize(), function(d){
      alert(d);
      });
    });


/*
    $( "form" ).on( "submit", function() {
       var has_empty = false;
       $(this).find( 'input[type!="hidden"]' ).each(function () {
          if ( ! $(this).val() ) { has_empty = true; return false; }
       });
       if ( has_empty ) { return false; }
    });
*/

  });
</script>
