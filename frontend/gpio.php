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
        <b id="welcome">Welcome <i><?php echo $login_session; ?></i>! :)</b>
        <b id="logout"><a href="../backend/logout.php">Log Out</a></b>
      </div>


      <form method="post" id="gpiocontrol" action="gpio.php">
      <div id="console">
        <p class="h6"><i>Control Message</i></p>
        <p class="scrollbar square scrollbar-lady-lips" id="msg"></p>
      </div>


        <table class="table table-hover table-striped" >
          <thead id="tblcontrolhead">
            <tr>
              <th scope="col">#</th>
              <th scope="col">CONTROLLING Pins with PHP and GPIO</th>
              <th scope="col">Controls</th>
            </tr>
          </thead>
          <tbody id="tblcontrolbody">
            <tr>
              <th scope="row">1</th>
              <td>AC Bulb (thru GPIO 4)</td>
              <td class="controls">
                <label class="switch">
                  <input type="checkbox" name="bulb_switch" id="bulb_switch" value="bulb">
                  <span class="slider round"></span>
                </label>
              </td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>DC Motor (thru GPIO 17 and 27)</td>
              <td>
                <label class="switch">
                  <input type="checkbox" name="dc_motor_switch" id="dc_motor_switch">
                  <span class="slider round"></span>
                </label>
              </td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>AC Motor (thru GPIO 18)</td>
              <td>
                <label class="switch">
                  <input type="checkbox" name="ac_motor_switch" id="ac_motor_switch">
                  <span class="slider round"></span>
                </label>
              </td>
            </tr>
          </tbody>
        </table>


        <table class="table table-hover table-striped">
          <thead id="tblreadhead">
            <tr>
              <th scope="col">#</th>
              <th scope="col">READING Pin Status with PHP and GPIO</th>
              <th scope="col">Controls</th>
            </tr>
          </thead>
          <tbody id="tblreadbody">
            <tr>
              <th scope="row">1</th>
              <td>Button Read (thru GPIO 22)</td>
              <td>
                <input type="button" value="Read" name="read_button" id="read_button">
              </td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>IR Sensor Read (thru GPIO 23)</td>
              <td>
                <input type="button" value="Read" name="read_ir" id="read_ir">
              </td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="mode" name="mode" value="0">
      </form>
    </div>



    <script>
      $(document).ready(function(){

        $("#bulb_switch").click(function(){
          $("#mode").val("1");
          $.post("../backend/gpiocontrols.php",$("form#gpiocontrol").serialize(),function(d){
            $("#msg").html(d);
          });
        });

        $("#dc_motor_switch").click(function(){
          $("#mode").val("2");
          $.post("../backend/gpiocontrols.php",$("form#gpiocontrol").serialize(),function(d){
            $("#msg").html(d);
          });
        });

        $("#ac_motor_switch").click(function(){
          $("#mode").val("3");
          $.post("../backend/gpiocontrols.php",$("form#gpiocontrol").serialize(),function(d){
            $("#msg").html(d);
        });
      });

      $("#read_button").click(function(){
        $("#mode").val("4");
        $.post("../backend/gpiocontrols.php",$("form#gpiocontrol").serialize(),function(d){
          $("#msg").html(d);
        });
      });

      $("#read_ir").click(function(){
        $("#mode").val("5");
        $.post("../backend/gpiocontrols.php",$("form#gpiocontrol").serialize(),function(d){
          $("#msg").html(d);
        });
      });

      $("#console").click(function(){
        $("#mode").val("9");
        $.post("../backend/gpiocontrols.php",$("form#gpiocontrol").serialize(),function(d){
          $("#msg").html(d);
        });
      });

      $("#tblcontrolhead").click(function(){
        $("#tblcontrolbody").toggle();
      });

      $("#tblreadhead").click(function(){
        $("#tblreadbody").toggle();
      });

      })
    </script>
  </body>
</html>
