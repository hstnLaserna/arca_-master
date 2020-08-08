<?php
include('backend/login.php'); // Includes Login Script

if(isset($_SESSION['login_user'])){
  header("location: frontend/dashboard.php");
  }
?>


<!DOCTYPE html>
<html>
  <head>
    <title>OSCA - Home</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="resources/images/OSCA_square.png">
</head>
  <body>
    <div class="wrapper fadeInDown">
      <div id="formContent">
        <div class="fadeIn first">
          <img src="resources/images/OSCA_logo.png" id="icon" alt="User Icon">
        </div>
        <form action="" method="post" autocomplete="off">
          <input type="text" id="name" name="username" placeholder="<?php /*shell_exec("hostname -I");*/ echo 'Username' ?>" required="" class="fadeIn second" autofocus>
          <input type="password" id="password" name="password" placeholder="Pasword"  required="" class="fadeIn third">
          <p class="message fadeIn third"><?php echo $error; ?></p>
          <a href="#"><p>Forgot Password</p></a><br>
          <input type="submit" name="submit" value=" Login "class="fadeIn fourth">
        </form>
      </div>
    </div>
  </body>
</html>
