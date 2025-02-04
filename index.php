<?php
    include('backend/login.php');

    if (isset($_SESSION['login_user'])) {
        header("location: frontend/dashboard.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>OSCA - Home</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="icon" href="resources/images/OSCA_square.png">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="first-column">
                    <img class="img-logo" src="resources/images/OSCA logo.png" class="icon" alt="User Icon">
                    <h2 class="title title-primary">OFFICE FOR THE SENIOR CITIZEN AFFAIRS</h2>
                    <p class="description description-primary">Giving our beloved Senior Citizens the possibilities of a Smart City experience!</p>
                </div>
                <div class="second-column">
                    <form class="form" method="post" enctype="multipart/form-data" autocomplete="off" id="">
                        <h2 class="title title-secondary">Sign into your Account</h2>
                        <label class="label-input" for="">
                            <i class="far fa-user icon-modify"></i>
                            <input type="text" name="username" placeholder="<?php /*shell_exec("hostname -I");*/ echo 'Username' ?>" required="">
                        </label>
                        <label class="label-input" for="">
                            <i class="fas fa-lock icon-modify"></i>
                            <input type="password" name="password" placeholder="Pasword" required="">
                        </label>
                        <p><a class="forgot-password" href="frontend/forgot_password.php">Forgot Password?</a></p>
                        <p class="message"><?php echo $error; ?></p>
                        <button class="btn btn-second" type="submit" name="submit" value=" Login ">Login</button>
                        <!-- <p class=footer>Copyright © 2020</p> -->
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>