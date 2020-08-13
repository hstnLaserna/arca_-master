  <!-- Modal Create -->
  <div id="modal_newAdmin" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Admin Account</h4>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" autocomplete="off" id="modal_id">
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
          <button type="button" class="btn btn-primary btn-lg btn-block" id="submit" value="Submit">Ipasa</button>
          <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div> <!-- End modal -->