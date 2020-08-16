<?php
  include('../backend/session.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>OSCA - <?php echo $first_name; ?></title>
        <link rel="icon" href="../resources/images/OSCA_square.png">


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

        <link href="../css/style2.css" rel="stylesheet" type="text/css">
        <link href="../css/osca.css" rel="stylesheet" type="text/css">

    </head>

    <body class="home container-fluid">
        <div class="row">
            <div class="col col-md-2" id="navigation">
                <div class="logo">
                    <a href="../frontend/dashboard.php">
                        <img src="../resources/images/OSCA_full.png" alt="OSCA_full">
                        <img src="../resources/images/OSCA_square.png" alt="OSCA_square" class="d-lg-none">
                    </a>
                </div>
                <div class="nav">
                    <ul class="pl-0">
                        <li><a href="../frontend/dashboard.php?page=home"><i class="fa fa-home" aria-hidden="true"></i><span>Home</span></a></li>
                        <li><a href="../frontend/dashboard.php?page=scan"><i class="fa fa-qrcode" aria-hidden="true"></i><span>Scan</span></a></li>
                        <li><a href="../frontend/dashboard.php?page=search"><i class="fa fa-search" aria-hidden="true"></i><span>Search</span></a></li>
                        <li class="active" ><a href="../frontend/dashboard.php?page=members"><i class="fa fa-users" aria-hidden="true"></i><span>Members</span></a></li>
                        <?php if($logged_position == "admin")
                        {?>
                        <li><a href="../frontend/dashboard.php?page=management"><i class="fa fa-tasks" aria-hidden="true"></i><span>Management</span></a></li>
                        <?php
                        } else {}
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col col-md-10" id="body">
                    <header>
                        <div>
                            <div class="header-rightside">
                                <ul class="list-inline header-top pull-right">
                                    <li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-bell" aria-hidden="true"></i></a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <img src="../resources/avatars/<?php echo $user_avatar ?>" alt="<?php echo $user_name ?> avatar" class="rounded-circle">
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <div class="navbar-content">
                                                    <span><?php echo $first_name . " " . $last_name; ?></span>
                                                    <div class="divider">
                                                    </div>
                                                    <a href="user_profile.php" class="view btn-sm active">View Profile</a>
                                                    <a href="../backend/logout.php" class="view btn-sm active">Log Out</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>
                <div class="workspace">