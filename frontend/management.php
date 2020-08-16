<div id="management">
  <div>
    <button type="button" class="btn btn-secondary btn-lg" id="addNewAdmin">Add</button>
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
                  <input type="text" class="form-control " name="user_name">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Password
                  <input type="password" class="form-control " name="password">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Firstname
                  <input type="text" class="form-control " name="first_name">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Middlename
                  <input type="text" class="form-control " name="middle_name">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Lastname
                  <input type="text" class="form-control " name="last_name">
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
                  <input type="text" class="form-control" name="security_answer_1">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Security Answer 2
                  <input type="text" class="form-control" name="security_answer_2">
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
          <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
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


  
  <div>
    <!-- Modal Edit -->
    <div id="modal_displayAdmin" class="modal fade" role="dialog">
    </div> <!-- End modal -->

  </div>
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