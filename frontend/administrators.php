<?php
  include('../frontend/header.php');
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

    $( ".inactive" ).parent().css({"background-color": "#d3d3d3", "font-style": "italic"});

    $('.active').click(function () {
      var admin_id = $(this).parent().attr("id").replace("adminNum_", "");
      $("#admin").load("../backend/deactivate_admin.php" + " #admin",{ admin_id: admin_id }, function(d){
        location.reload();
      });
    });

    $('.inactive').click(function () {
      var admin_id= $(this).parent().attr("id").replace("adminNum_", "");
      $("#admin").load("../backend/activate_admin.php" + " #admin",{ admin_id: admin_id }, function(d){
        location.reload();
      });
    });

    $('.view-admin').click(function () {
      var admin_id= $(this).closest("tr").attr("id").replace("adminNum_", "");
      $('#modal_displayAdmin').load("../frontend/admin_profile_card.php", { admin_id: admin_id },function(){
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