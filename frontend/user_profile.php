<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    // declare variable
    
    $admin_id = "";
    $user_name = "";
    $first_name = "";
    $middle_name = "";
    $last_name = "";
    $birthdate = "";
    $sex2 = "";
    $contact_number = "";
    $email = "";
    $position = "";
    $answer1 = "";
    $answer2 = "";
    $avatar = "";
    $member_buttons = "";
    $personal_profile = false;

    if(isset($_GET['user']) && $logged_position == "admin")
    {
        $user = $_GET['user'];
        $query_basis = "`user_name` = '$user'";
    } else {
        $user_name=$_SESSION['login_user'];
        $query_basis = "`user_name` = '$user_name'";
    }

    $query = "SELECT `id`, `user_name`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, 
                    `contact_number`, `email`, `position`, `answer1`, `answer2`, `avatar` 
                    FROM `admin` WHERE $query_basis";
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
    if($row_count == 1)
    {
        $admin_id = $row['id'];
        $user_name = $row['user_name'];
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = strtoupper($row['last_name']);
        $fullname = strtoupper("$first_name $middle_name $last_name");
        $birthdate = $row['birth_date'];
        $sex2 = $row['sex'];
        $contact_number = $row['contact_number'];
        $email = $row['email'];
        $position = strtolower($row['position']);
        $answer1 = $row['answer1'];
        $answer2 = $row['answer2'];
        
        $member_buttons = '';//'<button type="button" id="edit" class="btn btn-secondary btn-lg btn-block">Edit</button>';

        $avatar = '../resources/avatars/'.$row["avatar"]; 
        if (file_exists($avatar) && $row["avatar"] == null) { $avatar = '../resources/images/unknown_m_f.png'; }

        if($user_name == $_SESSION['user_name']){
            $personal_profile = true;
        }  else {
            $personal_profile = false;
        }
    } else {
        if($row_count == 0) { echo 'No record found';}
        if($row_count > 1) { echo 'Admin returns more than 1 record';}
    }
    mysqli_close($mysqli);
?>
    
    <div class="digital-card-contents">
        <div class="card-right">
            <div class="profile-picture-container">
                <form action="../backend/upload.php" id="form_photo" method="post" enctype="multipart/form-data" >
                    <img class="profile-picture" src="<?php echo $avatar; ?>" id="output">
                    <div class="middle">
                        <input type="file" name="photo" accept="image/x-png,image/jpeg" onchange="loadFile(event)" id="file" class="inputfile">
                        <input type="hidden" name="entity_key" value="<?php echo $user_name;?>">
                        <input type="hidden" name="entity_type" value="admin">
                        <label for="file" class="text">Change</label>
                        <button type="submit" value="upload" id="submit" class="hidden btn-photo btn">Apply</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-left">
            <div class="basic">
                <button class="ml-auto btn btn-link edit" id="edit_basic"><i class="fa fa-edit"></i></button>
                <h4 class="ml-1"> Basic Information </h4>
                <ul class="profile-details">
                    <li class="profile-item">
                        <div class="title">Fullname</div> 
                        <div class="content"><?php echo $fullname; ?></div>
                    </li>
                    <li class="profile-item">
                        <div class="title">Birthdate</div> 
                        <div class="content"><?php echo $birthdate; ?></div>
                    </li>
                    <li class="profile-item">
                        <div class="title">Sex</div> 
                        <div class="content"><?php echo determine_sex($sex2, "display_long"); ?></div>
                    </li>
                    <li class="profile-item">
                        <div class="title">Contact Number</div> 
                        <div class="content"><?php echo $contact_number; ?></div>
                    </li>
                    <li class="profile-item">
                        <div class="title">E-mail</div> 
                        <div class="content"><?php echo $email; ?></div>
                    </li>
                </ul>
            </div>
            <div class="basic">
                <h4 class="ml-1"> Account Information </h4>
                <ul class="profile-details">
                    <li class="profile-item">
                        <div class="title">Username</div> 
                        <div class="content"><?php echo $user_name; ?></div>
                    </li>
                    <li class="profile-item">
                        <div class="title">Position</div> 
                        <div class="content"><?php echo $position; ?></div>
                    </li>
                </ul>
                    <?php echo $member_buttons;?>
            </div>
        </div>
        <input type="hidden" id="user_<?php echo $user_name;?>" name="user_name">
    </div>


<div class="container">
</div>


<?php
  include('../frontend/foot.php');
  if($logged_position == "admin" && !$personal_profile)
  {
    $user_edit_method = "get";
  } else {
    $user_edit_method = "post";
  }
?>
<script>
    $('title').replaceWith('<title>User profile - <?php echo $user_name; ?></title>');


    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
        }
        
        if( document.getElementById("file").files.length == 0 ){
            document.getElementById("submit").classList.add("hidden");
            console.log("no files selected");
        } else {
            document.getElementById("submit").classList.remove("hidden");
            console.log("File is selected");
        }
    };
    var inputs = document.querySelectorAll('.inputfile');

    Array.prototype.forEach.call(inputs, function(input)
    {
        var label	 = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener('change', function(e)
        {
            var fileName = '';

            if(fileName)
                label.querySelector('span').innerHTML = fileName;
            else
                label.innerHTML = labelVal;
        });
    });
    //input.addEventListener('focus', function(){ input.classList.add('has-focus'); });
    //input.addEventListener('blur', function(){ input.classList.remove('has-focus'); });


    $(document).ready(function(){
        if( document.getElementById("file").files.length == 0 ){
            document.getElementById("submit").classList.add("hidden");
            console.log("no files selected");
        } else {
            document.getElementById("submit").classList.remove("hidden");
            console.log("File is selected");
        }
        /*
        $('#a').click(function () {
            var entity_type = $('input[name="entity_type"]').val();
            var entity_key = $('input[name="entity_key"]').val();
            alert(entity_type + " " + entity_key);
            $.post("../backend/upload.php?entity_type="+entity_type+"&entity_key="+entity_key,$('form#form_photo'), function(d){
                alert(d);
                location.reload();
            });
        });

        $("form#form_photo").submit(function() {
            var formData = new FormData(this);
            $.post($(this).attr("action"), formData, function(d) {
                alert(d);
                location.reload();
            });
            return false;
        });

        

        $("#submit").click(function(){
            $("#form_photo").submit(function(){
                var formData = new FormData(this);
                $.post("../backend/upload.php?entity_key=&entity_type=admin", formData, function(d){
                    if(d == "true") {
                        location.replace("../frontend/user_profile.php?user=);
                    } else {
                        alert(d);
                    }
                });
            });
        });
        */

        

        $('#edit_basic').click(function () {
            var user = $('input[name="user_name"]').attr("id").replace("user_", "");
            location.replace("../frontend/edit_admin.php?user=" + user);
            
        });
    });
</script>