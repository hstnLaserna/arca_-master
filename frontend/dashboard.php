<?php
  include('../backend/session.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>OSCA - <?php echo $first_name; ?></title>
    <link rel="icon" href="../resources/images/OSCA_square.png">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    
    <link href="../css/style2.css" rel="stylesheet" type="text/css">
    <link href="../css/osca.css" rel="stylesheet" type="text/css">

  </head>
  <body class="home">
      <div class="container-fluid display-table">
          <div class="row display-table-row">
              <div class="col-sm-1 col-md-2 hidden-xs display-table-cell v-align box" id="navigation">
                  <div class="logo">
                      <a hef="home.php">
                          <img src="../resources/images/OSCA_full.png" alt="1" class="d-none d-lg-block">
                          <img src="../resources/images/OSCA_square.png" alt="2" class="d-lg-none">
                      </a>
                  </div>
                  <div class="navi">
                      <ul>
                          <li class="active"><a href="?page=home"><i class="fa fa-home" aria-hidden="true"></i><span class="d-none d-lg-block">Home</span></a></li>
                          <li><a href="?page=schedules"><i class="fa fa-tasks" aria-hidden="true"></i><span class="d-none d-lg-block">Schedules</span></a></li>
                          <li><a href="?page=members"><i class="fa fa-user" aria-hidden="true"></i><span class="d-none d-lg-block">Members</span></a></li>
                          <?php if($position == "admin")
                          {?>
                            <li><a href="?page=management"><i class="fa fa-calendar" aria-hidden="true"></i><span class="d-none d-lg-block">Management</span></a></li>
                            <?php
                          } else {}

                          ?>

                      </ul>
                  </div>
              </div>
              <div class="col-md-10 col-sm-11 display-table-cell v-align">
                  <!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
                  <div class="row">
                      <header>
                          <div class="col-md-7">
                              <nav class="navbar-default pull-left">
                                  <div class="navbar-header">
                                      <button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#side-menu" aria-expanded="false">
                                          <span class="sr-only">Toggle navigation</span>
                                          <span class="icon-bar"></span>
                                          <span class="icon-bar"></span>
                                          <span class="icon-bar"></span>
                                      </button>
                                  </div>
                              </nav>
                          </div>
                          <div class="col-md-12">
                              <div class="header-rightside">
                                  <ul class="list-inline header-top pull-right">
                                      <li class="hidden-xs"><a href="#" class="add-project" data-toggle="modal" data-target="#add_project">Add Project</a></li>
                                      <li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                                      <li>
                                          <a href="#" class="icon-info">
                                              <i class="fa fa-bell" aria-hidden="true"></i>
                                              <span class="label label-primary">3</span>
                                          </a>
                                      </li>
                                      <li class="dropdown">
                                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="../resources/avatars/<?php echo $user_avatar ?>" alt="<?php echo $user_name ?> avatar"></a>
                                          <ul class="dropdown-menu">
                                              <li>
                                                  <div class="navbar-content">
                                                      <span><?php echo $first_name . " " . $last_name; ?></span>
                                                      <div class="divider">
                                                      </div>
                                                      <a href="#" class="view btn-sm active">View Profile</a>
                                                      <a href="../backend/logout.php" class="view btn-sm active">Log Out</a>
                                                  </div>
                                              </li>
                                          </ul>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </header>
                  </div>
                  <div class="user-dashboard">
                    <?php
                      if (!isset($_GET['page'])) {
                          include "home.php";
                      } else {
                      switch ($_GET['page']) {
                          case "home":
                               include "home.php";
                          break;
                          case "schedules":
                               include "schedules.php";
                          break;
                          case "members":
                               include "members.php";
                          break;
                          case "management":
                               include "management.php";
                          break;
                          default:
                               include "home.php";
                          };
                      }
                    ?>
                  </div>
              </div>
          </div>

      </div>
  </body>

</html>
