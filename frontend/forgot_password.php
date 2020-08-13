<?php
include('../backend/forgot_password.php');

if(isset($_SESSION['login_user'])){
  header("location: ../frontend/dashboard.php");
  }
?>


<!DOCTYPE html>
<html>
  <head>
    <title>OSCA - Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">


    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="icon" href="../resources/images/OSCA_square.png">
  </head>
  <body>
    <div class="wrapper fadeInDown">
      <div id="formContent">
        <div class="fadeIn first">
          <img src="../resources/images/OSCA_logo.png" id="icon" alt="User Icon">
        </div>
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="forgot_password">
          <input type="text" name="username" placeholder="Username" required="" class="fadeIn second" autofocus>
          <input type="text" name="answer1" placeholder="Security Answer 1"  required="" class="fadeIn second">
          <input type="text" name="answer2" placeholder="Security Answer 2"  required="" class="fadeIn second">
          <p class="message fadeIn third"><?php echo $error; ?></p><br>
          <button type="submit" name="submit" value=" Login " class="fadeIn fourth"> Verify </button>
        </form>
      </div>
    </div>
  </body>
</html>
