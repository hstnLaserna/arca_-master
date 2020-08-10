<div id="management">
  MANAGEMENT
  <!-- Trigger the modal with a button -->
  <div>
    <button type="button" class="btn btn-info btn-lg" id="addNewAdmin">Add</button>
  </div>

  <div id="admin">
    <?php
      include("../backend/display_admin.php")
    ?>
  </div>
  <!-- Modal Create -->
  <div id="modal_newAdmin" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Admin Account</h4>
        </div>

        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" autocomplete="off" id="newAdmin">
            <table class="table modal-form">
              <tr>
                <td colspan="2">
                  Username
                  <input type="text" class="form-control " name="user_name" placeholder="" >
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Password
                  <input type="password" class="form-control " name="password" placeholder="" >
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Firstname
                  <input type="text" class="form-control " name="first_name" placeholder="" >
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Middlename
                  <input type="text" class="form-control " name="middle_name" placeholder="">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Lastname
                  <input type="text" class="form-control " name="last_name" placeholder="" >
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Birthdate
                  <input type="date" class="form-control" name="birthdate" >
                </td>
              </tr>
              <tr>
                <td>
                  Gender
                  <div class="form-group">
                    <select class="form-control" name="gender">
                      <option>Female</option>
                      <option>Male</option>
                    </select>
                  </div>
                </td>
                <td>
                  Position
                  <div class="form-group">
                    <select class="form-control" name="position">
                      <option>Admin</option>
                      <option>User</option>
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Security Answer 1
                  <input type="text" class="form-control" name="security_answer_1" placeholder="" >
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Security Answer 2
                  <input type="text" class="form-control" name="security_answer_2" placeholder="" >
                </td>
              </tr>
              <!--
              <tr>
                <td colspan="2">
                  Picture
                </td>
                <td colspan="2">
                  <input type="file" name="photo" id="fileSelect" >
                </td>
              </tr>
              -->

            </table>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-lg btn-block" id="submit" value="Submit">Ipasa</button>
          <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div> <!-- End modal -->

  <div>
    <!-- Modal Edit -->
    <div id="modal_editAdmin" class="modal fade" role="dialog">
    </div> <!-- End modal -->
  </div>
  <script>
    $(document).ready(function(){
      $("#addNewAdmin").click(function(){
        $('#modal_newAdmin').modal();
      });

      $( ".inactive" ).parent().css({"background-color": "#d3d3d3", "font-style": "italic"});

      $('input[name!="middle_name"]').blur(function(){
        if($(this).val().length === 0 ) {
            $(this).addClass('input_error');
        }
        else{
              $(this).removeClass('input_error');
        }
      });

      $("#submit").click(function(){
        $.post("../backend/create_admin.php", $("#newAdmin").serialize(), function(d){
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

      $('.admin-row').click(function () {
        var adminID= $(this).parent().attr("id").replace("adminNum_", "");
        $('#modal_editAdmin').load("edit_admin.php", { id: adminID },function(){
          $('#modal_editAdmin').modal();
        });
      });

  //*************************************//

    });
  </script>


</div>