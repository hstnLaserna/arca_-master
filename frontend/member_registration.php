<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
?>

<div class="osca-members">
    <div class="card">
        <div class="registration-form">
            <h3 class="registration-title">Register New Member</h3>
        </div>

        <div class="registration-form" id="accordion">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="newMember">
                <div class="collapse-header" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <h3>Personal Details</H3>
                </div>
                <div id="collapseOne" class="form-contents collapse show" aria-labelledby="headingOne">
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
                        <div class ="row">
                            <div class ="col col-lg-6 col-12">
                                Birthdate
                                <input type="date" class="form-control" name="birthdate" >
                            </div>
                            <div class ="col col-lg-6 col-12">
                                Gender
                                <select class="form-control" name="gender">
                                    <option>-</option>
                                    <option>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        Contact number
                        <input type="text" class="form-control " name="contact_number">
                    </div>
                    <div>    
                        Email
                        <input type="text" class="form-control " name="email">
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
                                <small>Province</small>
                                <select class="form-control" name="address_province" id="address_province">
                                    <?php populate_province(); ?>
                                </select>
                            </div>
                            <div class ="col col-lg-6 col-md-12">
                                <small>City</small>
                                <select class="form-control" name="address_city" id="address_city">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="collapse-header" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h3> Guardian </H3>
                </div>
                    
                <div id="collapseTwo" class="form-contents collapse" aria-labelledby="headingTwo">
                    <div>
                        <div class ="row">
                            <div class ="col col-lg-4 col-md-12">
                                Firstname
                                <input type="text" class="form-control " name="g_first_name">
                            </div>
                            <div class ="col col-lg-4 col-md-12">
                                Middlename
                                <input type="text" class="form-control " name="g_middle_name">
                            </div>
                            <div class ="col col-lg-4 col-md-12">
                                Lastname
                                <input type="text" class="form-control " name="g_last_name">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class ="row">
                            <div class ="col col-lg-6 col-md-12">
                                Relationship
                                <input type="text" class="form-control " name="g_relationship">
                            </div>
                            <div class ="col col-lg-6 col-md-12">
                                Gender
                                <select class="form-control" name="g_gender">
                                    <option>-</option>
                                    <option>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class ="row">
                            <div class ="col col-lg-6 col-md-12">
                                Contact number
                                <input type="text" class="form-control " name="g_contact_number">
                            </div>
                            <div class ="col col-lg-6 col-md-12">
                                Email
                                <input type="text" class="form-control " name="g_email">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="collapse-header" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h3> Membership Details </H3>
                </div>

                <div id="collapseThree" class="form-contents collapse" aria-labelledby="headingThree">
                    <div>
                        NFC Serial (to be replaced with nfc serial validation from NFC reader)
                        <input type="text" class="form-control" name="nfc_serial">
                    </div>
                    <div>
                        OSCA Number
                        <input type="text" class="form-control" name="osca_id" id="osca_id">
                    </div> 
                    <div>
                        Membership date <small>Default: <em>(<?php echo date("Y-m-d"); ?>)</em></small>
                        <input type="date" class="form-control" name="membership_date" value ="<?php echo date("Y-m-d"); ?>">
                    </div>
                    <div>
                        System Password
                        <input type="password" class="form-control" name="password">
                    </div> 
                    <!--
                    <div>
                            Picture
                        
                        
                            <input type="file" name="photo" id="fileSelect" >
                        
                    </div>
                    -->
                </div>
            </form>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="submit">Submit</button>
        </div>
    </div>
</div>
<div id="_1asg2">
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
            var osca_id = $('#osca_id').val();
            if(d == "true") {
                
                var url = '../frontend/member_profile.php';
                var form = $('<form action="' + url + '" method="get">' +
                                    '<input type="hidden" name="member_id" value="' + osca_id + '" />' +
                            '</form>');
                $('div#_1asg2').append(form);
                form.submit();
            } else {
                alert(d);
            }
            });
        });

        

        $('#address_province').change(function(){
            //Selected value
            var provinceSelected = $(this).val();
            //Ajax for calling php function
            $.post('../backend/populate_city.php', { province: provinceSelected }, function(data){
                //do after submission operation in DOM
                $("#address_city").replaceWith('<select class="form-control" name="address_city" id="address_city">'+data+'</select>');
            });
        });
        
    });
</script>
