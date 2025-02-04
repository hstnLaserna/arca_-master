<?php
  include('../frontend/header.php');
  include('../backend/position_check.php');
?>

<div id="management">

  <div id="osca-admin">
    <div id="admin">
      <?php
        $page=1;
        if(!empty($_GET['page'])) {
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
            if(false === $page) {
                $page = 1;
            }
        }
        include("../backend/display_admin.php")
      ?>
    </div> 
    
    
    <!-- Modal Display Admin Card -->
    <div id="modal_displayAdmin" class="modal fade" role="dialog">
    </div> <!-- End modal -->

  </div>
</div>
<?php
  include('../frontend/foot.php');
?>



<script>
  $("#administrator_management").addClass('active').siblings().removeClass('active');
  $('title').replaceWith('<title>OSCA - Administrators</title>');
  $(document).ready(function(){

    $(".inactive").parent().addClass("inactive");

    $('td.active').click(function () {
      var user_name = $(this).parent().attr("id").replace("admin_", "");
      $.post("../backend/deactivate_admin.php",{ user_name: user_name }, function(){
        location.reload();
      });
    });

    $('td.inactive').click(function () {
      var user_name= $(this).parent().attr("id").replace("admin_", "");
      $.post("../backend/activate_admin.php",{ user_name: user_name }, function(){
        location.reload();
      });
    });

    $('.view-admin').click(function () {
      var user= $(this).closest("tr").attr("id").replace("admin_", "");
      $('#modal_displayAdmin').load("../frontend/admin_profile_card.php", { user: user },function(){
        $('#modal_displayAdmin').modal();
      });
    });

  //*************************************//
  { // Sort thru Table headers
        $('th').click(function(){
        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc){rows = rows.reverse()}
        for (var i = 0; i < rows.length; i++){table.append(rows[i])}
        });
        function comparer(index) {
            return function(a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }
        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
    }

  });
</script>