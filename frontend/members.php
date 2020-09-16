<?php
    include('../frontend/header.php');
?>

<div id="members">
        <?php
            include("../backend/display_members.php")
        ?>

    <div>
        <div id="modal_displayMember" class="modal fade" role="dialog">
        </div>

    </div>
</div>

<?php
    include('../frontend/foot.php');
?>

<script>
$("#members_management").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>OSCA - Members</title>');
    $(document).ready(function(){
        $('.view-member').click(function () {
            var member_id= $(this).closest("tr").attr("id").replace("memNum_", "");
            $('#modal_displayMember').load("../frontend/member_profile_card.php", { member_id: member_id },function(){
                $('#modal_displayMember').modal();
            });
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
