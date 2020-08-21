<?php
  include('../backend/session.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>OSCA</title>

        <link rel="icon" href="../resources/images/OSCA_square.png">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

        <link href="../css/osca.css" rel="stylesheet" type="text/css">

    </head>

    <body class="home">

        <nav class="navbar sticky-top navbar-expand-lg navbar-dark" style="background-color: #122143; box-shadow: 0 1px 12px rgba(0, 0, 0, 0.04);">
            <a class="navbar-brand" href="#">
                <img src="../resources/images/OSCA_square.png" alt="OSCA_square">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active" id="home">
                        <a class="nav-link" href="../frontend/dashboard.php">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item" id="scan">
                        <a class="nav-link" href="../frontend/dashboard.php?page=scan">Scan</a>
                    </li>
                    <li class="nav-item" id="search">
                        <a class="nav-link" href="../frontend/search.php">Search</a>
                    </li>
                        <?php if($logged_position == "admin")
                            {?>
                    <li class="nav-item dropdown" id="members_management">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Members
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../frontend/member_registration.php">Member Registration</a>
                            <a class="dropdown-item" href="../frontend/members.php">Members List</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown" id="administrator_management">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Administrators
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../frontend/administrator_registration.php">Administrator Registration</a>
                            <a class="dropdown-item" href="../frontend/administrators.php">Administrator List</a>
                        </div>
                    </li>
                        <?php
                        } else {
                            ?>
                    <li class="nav-item dropdown" id="members_management">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Members
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../frontend/member_registration.php">Member Registration</a>
                            <a class="dropdown-item" href="../frontend/members.php">Members List</a>
                        </div>
                    </li>
                            <?php
                        }
                        ?>
                </ul>
                
                <div class="">
                    <ul class="navbar-nav">
                        <!--li class="nav-item"><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                        <li class="nav-item"><a href="#"><i class="fa fa-bell" aria-hidden="true"></i></a></li-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="../frontend/dashboard.php" id="avatarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="../resources/avatars/<?php echo $user_avatar ?>" alt="<?php echo $user_name ?> avatar" class="rounded-circle" width="50" height="50">
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="navbar-content">
                                        <span><?php echo $first_name . " " . $last_name; ?></span>
                                        <div class="dropdown-divider"></div>
                                        <a href="user_profile.php" class="view btn-sm active">View Profile</a>
                                        <a href="../backend/logout.php" class="view btn-sm active">Log Out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="col col-md" id="body">
            <div class="workspace">