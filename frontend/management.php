<div id="management">
  MANAGEMENT
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modal_newAdmin">Add</button>

  <!-- Modal Create -->
  <div id="modal_newAdmin" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Admin Account</h4>
        </div>

        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" autocomplete="off" id="newAdmin">
          <table class="table align-middle">
            <tr>
              <td class="align-middle">
                Username
              </td>
              <td class="align-middle">
                <input type="text" class="form-control " name="user_name" placeholder="" >
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Password
              </td>
              <td class="align-middle">
                <input type="password" class="form-control " name="password" placeholder="" >
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Firstname
              </td>
              <td class="align-middle">
                <input type="text" class="form-control " name="first_name" placeholder="" >
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Middlename
              </td>
              <td class="align-middle">
                <input type="text" class="form-control " name="middle_name" placeholder="">
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Lastname
              </td>
              <td class="align-middle">
                <input type="text" class="form-control " name="last_name" placeholder="" >
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Birthdate
              </td>
              <td class="align-middle">
                <input type="date" class="form-control" name="birthdate" >
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Gender
              </td>
              <td class="align-middle">
                <div class="form-group">
                  <select class="form-control" name="gender">
                    <option>Female</option>
                    <option>Male</option>
                  </select>
                </div>
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Position
              </td>
              <td class="align-middle">
                <div class="form-group">
                  <select class="form-control" name="position">
                    <option>Admin</option>
                    <option>User</option>
                  </select>
                </div>
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Security Answer 1
              </td>
              <td class="align-middle">
                <input type="text" class="form-control" name="security_answer_1" placeholder="" >
              </td>
            </tr>
            <tr>
              <td class="align-middle">
                Security Answer 2
              </td>
              <td class="align-middle">
                <input type="text" class="form-control" name="security_answer_2" placeholder="" >
              </td>
            </tr>
            <!--
            <tr>
              <td class="align-middle">
                Picture
              </td>
              <td class="align-middle">
                <input type="file" name="photo" id="fileSelect" >
              </td>
            </tr>
            -->

          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-lg btn-block" id="submit" value="Submit">Ipasa</button>
          <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div> <!-- End modal -->


</div>
<div>
  <!-- Modal Edit -->
  <div id="modal_editAdmin" class="modal fade" role="dialog">
  </div> <!-- End modal -->

</div>



<div id="admin">
  <?php
    include("../backend/display_admin.php")
  ?>
</div>

<script>
  $(document).ready(function(){

    $( ".inactive" ).parent().css({"background-color": "#d3d3d3", "font-style": "italic"});

    $('#newAdmin input').blur(function(){
      if($(this).val().length === 0 ) {
          $(this).addClass('input_error');
      }
      else{
            $(this).removeClass('input_error');
      }
    });

    $("#submit").click(function(){
      $.post("../backend/add_admin.php", $("#newAdmin").serialize(), function(d){
       alert(d);
      });
    });

    $('.active').click(function () {
      var adminID = $(this).parent().attr("id").replace("adminNum_", "");
      $("#admin").load("../backend/deactivate_admin.php" + " #admin",{ id: adminID }, function(d){
        location.reload();
      });
    });

    $('.inactive').click(function () {
      var adminID= $(this).parent().attr("id").replace("adminNum_", "");
      $("#admin").load("../backend/activate_admin.php" + " #admin",{ id: adminID }, function(d){
        location.reload();
      });
    });

    $('.edit_button').click(function () {
      var adminID= $(this).parents().eq(1).attr("id").replace("adminNum_", "");
      $('#modal_editAdmin').load("edit_admin.php", { id: adminID },function(){
        $('#modal_editAdmin').modal();
      });
    });







//*************************************//
    $( "form" ).on( "submit", function() {
       var has_empty = false;
       $(this).find( 'input[type!="hidden"]' ).each(function () {
          if ( ! $(this).val() ) { has_empty = true; return false; }
       });
       if ( has_empty ) { return false; }
    });


  });
</script>
