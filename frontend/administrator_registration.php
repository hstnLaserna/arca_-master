<?php
  include('../frontend/header.php');
?>

<div id="management">
  <div class="card">
    <div class="">
      <h4 class="registration-title">Register Administrator Account</h4>
    </div>

    <div class="registration-form">
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
              Contact Number
              <input type="text" class="form-control " name="contact_number">
            </td>
          </tr>
          <tr>
            <td colspan="2">
              Email
              <input type="text" class="form-control " name="email">
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
    <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>

  </div>
</div>
<?php
  include('../frontend/foot.php');
?>



<script>
$("#administrator_management").addClass('active').siblings().removeClass('active');
  $('title').replaceWith('<title>OSCA - Admin Registration</title>');
  $(document).ready(function(){
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

  });
</script>