<?php
include('../backend/forgot_password.php');

if (isset($_SESSION['login_user'])) {
    header("location: ../frontend/dashboard.php");
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>OSCA - Home</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link rel="icon" href="../resources/images/OSCA_square.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="content">
            <div class="first-column">
                <img class="img-logo" src="../resources/images/osca_logo_new.png" class="icon" alt="User Icon">
                <h2 class="title title-primary">OFFICE FOR THE SENIOR CITIZEN AFFAIRS</h2>
                <p class="description description-primary">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <!-- <p class="description description-primary">please login with your personal info</p> -->
            </div>
            <div class="second-column">
                <form class="form" method="post" enctype="multipart/form-data" autocomplete="off" id="">
                    <h2 class="title title-secondary">Please fill-up the required field</h2>
                    <label class="label-input" for="">
                        <i class="far fa-user icon-modify"></i>
                        <input type="text" name="username" placeholder="Username" required="" class="fadeIn second" autofocus>
                        <!-- <input type="text" name="username" placeholder="<?php /*shell_exec("hostname -I");*/ echo 'Username' ?>" required=""> -->
                    </label>
                    <label class="label-input" for="">
                        <i class="fas fa-lock icon-modify"></i>
                        <input type="text" name="answer1" placeholder="Security Answer 1"  required="" class="fadeIn second">
                        <!-- <input type="password" name="password" placeholder="Pasword" required=""> -->
                    </label>
                    <label class="label-input" for="">
                        <i class="fas fa-lock icon-modify"></i>
                        <input type="text" name="answer2" placeholder="Security Answer 2"  required="" class="fadeIn second">
                        <!-- <input type="password" name="password" placeholder="Pasword" required=""> -->
                    </label>
                    <p class="message"><?php echo $error; ?></p>
                    <button class="btn btn-second" type="submit" name="submit" value=" Login ">Login</button>
                    <!-- <p class=footer>Copyright © 2020</p> -->
                </form>
            </div>
        </div>
    </div>






    <!-- <div class="box">
            <img src="../resources/images/OSCA_logo.png" class="icon" alt="User Icon">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="forgot_password">
                <input type="text" name="username" placeholder="Username" required="" class="fadeIn second" autofocus>
                <input type="text" name="answer1" placeholder="Security Answer 1"  required="" class="fadeIn second">
                <input type="text" name="answer2" placeholder="Security Answer 2"  required="" class="fadeIn second">
                <p class="message fadeIn third"><?php echo $error; ?></p><br>
                <input type="submit" name="submit" value=" Login " class="fadeIn fourth">
            </form>
        </div> -->
</body>

</html>