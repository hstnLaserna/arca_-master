<?php
  include('../frontend/header.php');
?>

<div class="osca-members">
  <div class="card">
    <div class="">
      <h4 class="registration-title">Register New Member</h4>
    </div>

    <div class="registration-form">
      <form method="post" enctype="multipart/form-data" autocomplete="off" id="newMember">
        <table class="table modal-form">
          <tr>
            <td>
              Firstname
              <input type="text" class="form-control " name="first_name">
            </td>
          </tr>
          <tr>
            <td>
              Middlename
              <input type="text" class="form-control " name="middle_name">
            </td>
          </tr>
          <tr>
            <td>
              Lastname
              <input type="text" class="form-control " name="last_name">
            </td>
          </tr>
          <tr>
            <td>
              <div class ="row">
                <div class ="col col-md-6 col-sm-12">
                    Birthdate
                    <input type="date" class="form-control" name="birthdate" >
                </div>
                <div class ="col col-md-6 col-sm-12">
                  Gender
                    <select class="form-control" name="gender">
                      <option>Female</option>
                      <option>Male</option>
                    </select>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              Address
              <div class ="row">
                <div class ="col col-sm-12">
                  <small>Line 1 <em>(House Number, Street)</em></small>
                  <input type="text" class="form-control " name="address_line1">
                </div>
                <div class ="col col-sm-12">
                  <small>Line 2 <em>(Barangay, Subdivision)</em></small>
                  <input type="text" class="form-control " name="address_line2">
                </div>
                <div class ="col col-md-6 col-sm-12">
                  <small>City</small>
                  <input type="text" class="form-control " name="address_city">
                </div>
                <div class ="col col-md-6 col-sm-12">
                  <small>Province</small>
                  <input type="text" class="form-control " name="address_province">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              Contact number
              <input type="text" class="form-control " name="contact_number">
            </td>
          </tr>
          <tr>
              <td>
                Email
                <input type="text" class="form-control " name="email">
              </td>
          </tr>
          <!--
          <tr>
            <td>
              Picture
            </td>
            <td>
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
$("#members_management").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>OSCA - Member Registration</title>');
  $(document).ready(function(){

    $("#submit").click(function(){
      $.post("../backend/create_member.php", $("#newMember").serialize(), function(d){
      alert(d);
      });
    });
    
  });
</script>
