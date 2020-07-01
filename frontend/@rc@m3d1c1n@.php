<?php
  include('../backend/session.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Arca Medicina - <?php echo $login_session; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../css/style2.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../backend/jquery.js"></script>
    <link rel="icon" href="../resources/images/med.png">
  </head>
  <body class="control-1">
    <div class="container-fluid">
      <div class="header">
        <b id="welcome">Welcome to Package Updater <i><?php echo $login_session; ?></i>! :)</b>
        <b id="logout"><a href="../backend/logout.php">Log Out</a></b>
      </div>

      <div class="console">
        <p class="h6"><i>Control Message</i></p>
        <p id="msg"></p>
      </div>

      <form method="post" id="frm" action="gpio.php">
        <table class="table table-hover table-striped">
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Click to delete directory: /var/www/html/arca</td>
              <td class="controls">
                <label class="switch">
                  <input type="checkbox" name="delete" id="delete" value="bulb">
                  <span class="slider round"></span>
                </label>
              </td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Click to update directory: /var/www/html/arca from Pi's desktop</td>
              <td>
                <label class="switch">
                  <input type="checkbox" name="update" id="update">
                  <span class="slider round"></span>
                </label>
              </td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="mode" name="mode" value="0">
      </form>
    </div>



    <script>
      $(document).ready(function(){

        $("#delete").click(function(){
          $("#mode").val("1");
          $.post("../backend/updater.php",$("form#frm").serialize(),function(d){
            $("#msg").html(d);
          });
        });

        $("#update").click(function(){
          $("#mode").val("2");
          $.post("../backend/updater.php",$("form#frm").serialize(),function(d){
            $("#msg").html(d);
          });
        });
      })
    </script>



  </body>
</html>
