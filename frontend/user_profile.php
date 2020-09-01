<?php
    include('../frontend/header.php');
    include('../backend/php_functions.php');
    include('../backend/conn.php');

    // declare variable
    
    $admin_id = "null";
    $user_name = "null";
    $first_name = "null";
    $middle_name = "null";
    $last_name = "null";
    $birthdate = "null";
    $sex2 = "null";
    $contact_number = "null";
    $email = "null";
    $position = "null";
    $answer1 = "null";
    $answer2 = "null";
    $avatar = "null";
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
    if($row_count == 0) { echo 'No record found  ' . $query;} else
    {
        if($row_count > 1) { echo 'Admin returns more than 1 record';} else{}
        {
            $admin_id = $row['id'];
            $user_name = $row['user_name'];
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = strtoupper($row['last_name']);
            $birthdate = $row['birth_date'];
            $sex2 = $row['sex'];
            $contact_number = $row['contact_number'];
            $email = $row['email'];
            $position = strtolower($row['position']);
            $answer1 = $row['answer1'];
            $answer2 = $row['answer2'];

            $avatar = '../resources/avatars/'.$row["avatar"]; 
            if (file_exists($avatar) && $row["avatar"] != null) { 
            } else { 
                $avatar = '../resources/images/unknown_m_f.png'; 
            }

            if($user_name == $_SESSION['user_name']){
                $personal_profile = true;
            }  else {
                $personal_profile = false;
            }
        }
    }
    mysqli_close($mysqli);
?>

    
<div class="card digital-card-contents">
    <div class="card-right">
        <img class="profile-picture" src="<?php echo $avatar; ?>">
    </div>
    
    <div class="card-left">
        <p class="mb-0"><?php echo $last_name; ?>,</p>
        <p><?php echo $first_name; ?> <?php echo $middle_name; ?></p>
        <p>Username: <?php echo $user_name; ?> </p>
        <p>Position: <?php echo $position; ?> </p>
        <p>Sex:  <?php echo determine_sex($sex2, "display_long"); ?> </p>
        <p>Birthdate: <?php echo $birthdate; ?> </p>
        <p>Contact Number: <?php echo $contact_number; ?> </p>
        <p>E-mail: <?php echo $email; ?> </p>
        
        <p>
        </p>
    </div>
    <button type="button" id="edit" class="btn btn-secondary btn-lg btn-block">Edit</button>
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
$(document).ready(function(){
    $('#edit').click(function () {
        var user = $('input[name="user_name"]').attr("id").replace("user_", "")
        var url = 'edit_admin.php';
        var form = $(   '<form action="' + url + '" method="<?php echo $user_edit_method?>">' +
                            '<input type="hidden" name="user" value="' + user + '" />' +
                        '</form>');
        $('div.container').append(form);
        form.submit();
        
    });
});
</script>