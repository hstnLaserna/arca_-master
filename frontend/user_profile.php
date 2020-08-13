<?php
  include('../frontend/head.php');
?>
<div class="admin-profile">
    <?php
    include('../frontend/display_admin_profile.php');
    ?>
</div>


<div class="container">
<?php
  include('../frontend/foot.php');
  if($logged_position == "admin" && !$personal_profile)
  {
    $user_edit_method = "get";
  } else {
    $user_edit_method = "post";
  }
?>
<script>
$(document).ready(function(){
    $('#edit').click(function () {
        var url = 'edit_admin.php';
        var form = $(   '<form action="' + url + '" method="<?php echo $user_edit_method?>">' +
                            '<input type="hidden" name="admin_id" value="' + <?php echo $admin_id?> + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });
});
</script>