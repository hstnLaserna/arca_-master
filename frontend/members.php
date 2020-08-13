<div id="members">
  MEMBERS
  <div>
    <button type="button" class="btn btn-info btn-lg" id="addNewMember">Add</button>
  </div>

  <div id="member">
    <?php
      include("../backend/display_members.php")
    ?>
  </div>

  
  <!-- Modal Create -->
  <div id="modal_newMember" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Member</h4>
        </div>

        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" autocomplete="off" id="newMember">
            <table class="table modal-form">
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
                <td>
                  Birthdate
                  <input type="date" class="form-control" name="birthdate" >
                </td>
                <td>
                  Gender
                    <select class="form-control" name="gender">
                      <option>Female</option>
                      <option>Male</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Address
                  <div>
                    <small>Line 1 <em>(House Number, Street)</em></small>
                    <input type="text" class="form-control " name="address_line1">
                    <small>Line 2 <em>(Barangay, Subdivision)</em></small>
                    <input type="text" class="form-control " name="address_line2">
                    <small>City</small>
                    <input type="text" class="form-control " name="address_city">
                    <small>Province</small>
                    <input type="text" class="form-control " name="address_province">
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  NFC Serial
                  <input type="text" class="form-control" name="nfc_serial">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  OSCA Number
                  <input type="text" class="form-control" name="osca_id">
                </td>
              </tr> 
              <tr>
                <td colspan="2">
                  Membership date     <small>Default: <em>(<?php echo date("Y-m-d"); ?>)</em></small>
                  <input type="date" class="form-control" name="membership_date" value ="<?php echo date("Y-m-d"); ?>">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Contact number
                  <input type="text" class="form-control " name="contact_number">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  Password
                  <input type="password" class="form-control " name="password">
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
    <div id="modal_displayMember" class="modal fade" role="dialog">
    </div> <!-- End modal -->

  </div>
</div>




<script>
  $(document).ready(function(){

    $("#addNewMember").click(function(){
      $('#modal_newMember').modal();
    });

    $('.view-member').click(function () {
      var member_id= $(this).closest("tr").attr("id").replace("memNum_", "");
      $('#modal_displayMember').load("../frontend/member_profile_card.php", { member_id: member_id },function(){
        $('#modal_displayMember').modal();
      });
    });

    $("#submit").click(function(){
      $.post("../backend/create_member.php", $("#newMember").serialize(), function(d){
      alert(d);
      });
    });


/*
    $( "form" ).on( "submit", function() {
       var has_empty = false;
       $(this).find( 'input[type!="hidden"]' ).each(function () {
          if ( ! $(this).val() ) { has_empty = true; return false; }
       });
       if ( has_empty ) { return false; }
    });
*/

  });
</script>
