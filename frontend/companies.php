<?php
  include('../frontend/header.php');
?>

<div id="members">
    <?php
      include("../backend/display_companies.php")
    ?>

  <div>
    <div id="modal_displayCompany" class="modal fade" role="dialog">
    </div>

  </div>
</div>
<div class="_dfg987">
</div>

<?php
  include('../frontend/foot.php');
?>


<script>
$("#company_management").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>OSCA - Companies</title>');
  $(document).ready(function(){
    
    $('.view-company').click(function () {
      var company_tin= $(this).attr("id").replace("ct_", "");
        
      var url = '../frontend/company_profile.php';
      var form = $(   '<form action="' + url + '" method="get">' +
                          '<input type="hidden" name="company_tin" value="' + company_tin + '" />' +
                      '</form>');
      $('._dfg987').append(form);
      form.submit();
    });

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
