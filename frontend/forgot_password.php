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
        <link rel="stylesheet" type="text/css" href="../css/login.css">
        <link rel="icon" href="../resources/images/OSCA_square.png">
    </head>
    <body>
        <div class="box">
            <img src="../resources/images/OSCA_logo.png" class="icon" alt="User Icon">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="forgot_password">
                <input type="text" name="username" placeholder="Username" required="" class="fadeIn second" autofocus>
                <input type="text" name="answer1" placeholder="Security Answer 1"  required="" class="fadeIn second">
                <input type="text" name="answer2" placeholder="Security Answer 2"  required="" class="fadeIn second">
                <p class="message fadeIn third"><?php echo $error; ?></p><br>
                <input type="submit" name="submit" value=" Login " class="fadeIn fourth">
            </form>
        </div>
    </body>
</html>
