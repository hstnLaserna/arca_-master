<?php
  include('../frontend/head.php');
?>
<div class="member-profile">
    <?php
    include('../frontend/display_member_profile.php');
    ?>
    <button type="button" id="edit_basic" class="btn btn-light">Edit Basic Details</button>
</div>

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
});
</script>