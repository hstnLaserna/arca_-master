<?php
  include('../frontend/header.php');
?>

<div class="card">
  <div class="">
    <h4 class="registration-title">Register New Company</h4>
  </div>

  <div class="registration-form" id="accordion">
    <form method="post" enctype="multipart/form-data" autocomplete="off" id="newCompany">
      <div class="collapse-header" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        <h3> COMPANY DETAILS</H3>
      </div>
      <div id="collapseOne" class="form-contents collapse show" aria-labelledby="headingOne">
        <div>
          Company Name
          <input type="text" class="form-control " name="company_name">
        </div>
        <div>
          Company Branch
          <input type="text" class="form-control " name="company_branch">
        </div>
        <div class="row">
          <div class ="col col-lg-6 col-12">
            Company TIN
            <input type="text" class="form-control " name="company_tin">
          </div>
          <div class ="col col-lg-6 col-12">
            Business Type
            <select class="form-control" name="business_type">
              <option>-</option>
              <option>Pharmacy</option>
              <option>Restaurant</option>
              <option>Transportation</option>
            </select>
          </div>
        </div>
        <div>
            Address
            <div class ="row">
              <div class ="col col-12">
                <small>Line 1 <em>(House Number, Street)</em></small>
                <input type="text" class="form-control " name="address_line1">
              </div>
              <div class ="col col-12">
                <small>Line 2 <em>(Barangay, Subdivision)</em></small>
                <input type="text" class="form-control " name="address_line2">
              </div>
              <div class ="col col-lg-6 col-md-12">
                <small>City</small>
                <input type="text" class="form-control " name="address_city">
              </div>
              <div class ="col col-lg-6 col-md-12">
                <small>Province</small>
                <input type="text" class="form-control " name="address_province">
            </div>
        </div>
        </div>
      </div>
    </form>
    <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
  </div>
</div>
<div id="_1asg2">
</div>

<?php
  include('../frontend/foot.php');
?>


<script>
$("#company_management").addClass('active').siblings().removeClass('active');
$('title').replaceWith('<title>OSCA - Company Registration</title>');
  $(document).ready(function(){
    $("#submit").click(function(){
      $.post("../backend/create_company.php", $("#newCompany").serialize(), function(d){
      var osca_id = $('#osca_id').val();
      if(d == "true") {
        
        var url = '../frontend/company_profile.php';
        var form = $(   '<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="company_id" value="' + osca_id + '" />' +
                        '</form>');
        $('div#_1asg2').append(form);
        form.submit();
      } else {
        alert(d);
      }
      });
    });
    
  });
</script>
