<?php
  include('../frontend/head.php');
?>
<div class="member-profile">
    <?php
    include('../frontend/display_member_profile.php');
    ?>
</div>

<?php
    if(isset($member_id))
    {
        echo '<div class="card transactions">';
        $selected_id = $member_id;
        include("../backend/read_transactions.php");
        echo '</div>';
    }

?>


<div>
    <!-- Modal Edit -->
    <div id="modal_edit_address" class="modal fade" role="dialog">
    </div> <!-- End modal -->

</div>
<div class="container">
    <?php
    include('../frontend/foot.php');
    ?>
</div>

<script>
$(document).ready(function(){
    $('#edit_basic').click(function () {
        var url = 'edit_member.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="member_id" value="' + <?php echo $member_id?> + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });
    $('.edit_address').click(function () {
        var address_id= $(this).parent().attr("id").replace("memNum_", "");
        var member_id= <?php echo $member_id;?>;
        $('#modal_edit_address').load("../frontend/edit_address.php?action=edit", {member_id:member_id, address_id:address_id},function(){
            $('#modal_edit_address').modal();
        });
    });
    $('#add_address').click(function () {
        var member_id= <?php echo $member_id;?>;
        $('#modal_edit_address').load("../frontend/edit_address.php?action=add", {member_id:member_id},function(){
            $('#modal_edit_address').modal();
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