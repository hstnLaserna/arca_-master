<?php
  include('../frontend/header.php');
  include('../backend/position_check.php');
?>

<div id="management">
  <div class="card">
    <div class="registration-form">
      <h3 class="registration-title">Register Administrator</h3>
      <form method="post" enctype="multipart/form-data" autocomplete="off" id="newAdmin">
        <div class="form-contents">
          <div>
              Username
              <input type="text" class="form-control " name="user_name">
          </div>
          <div>
              Password
              <input type="password" class="form-control " name="password">
          </div>
          <div>
              Firstname
              <input type="text" class="form-control " name="first_name">
          </div>
          <div>
              Middlename
              <input type="text" class="form-control " name="middle_name">
          </div>
          <div>
              Lastname
              <input type="text" class="form-control " name="last_name">
          </div>
          <div>
              Birthdate
              <input type="date" class="form-control" name="birthdate" >
          </div>
          <div>
            <div class ="row">
              <div class ="col col-lg-6 col-12">
                Gender
                <div class="form-group">
                  <select class="form-control" name="gender">
                    <option>-</option>
                    <option>Female</option>
                    <option>Male</option>
                  </select>
                </div>
              </div>
              <div class ="col col-lg-6 col-12">
                Position
                <div class="form-group">
                  <select class="form-control" name="position">
                    <option>-</option>
                    <option>Admin</option>
                    <option>User</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div>
              Contact Number
              <input type="text" class="form-control " name="contact_number">
          </div>
          <div>
              Email
              <input type="text" class="form-control " name="email">
          </div>
          <div>
              Security Answer 1
              <input type="text" class="form-control" name="security_answer_1">
          </div>
          <div>
              Security Answer 2
              <input type="text" class="form-control" name="security_answer_2">
          </div>
          <!--
          <div>
              Picture
              <input type="file" name="photo" id="fileSelect" >
          </div>
          -->
       </div>
      </form>
      <button type="button" class="btn btn-light btn-lg btn-block" id="submit">Submit</button>
    </div>

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