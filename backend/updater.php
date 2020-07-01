<?php
  $control_msg ='<i> Message </i>';

  if($_POST['mode'] == "1")
  {
    if(isset($_POST['delete'])){
      shell_exec("sudo rm -r /var/www/html/arca");
      $control_msg = "Package Deleted! 1";
    }
    else {
      //$control_msg = shell_exec("ls /wwww/var/www/html -l");
      $control_msg = shell_exec("echo %TIME%") . shell_exec("echo %COMPUTERNAME%") . shell_exec("ls /wwww/var/www/html -l");
    }
  }

  if($_POST['mode'] == "2")
  {
    if(isset($_POST['update'])){
      shell_exec("sudo cp -r /home/pi/Desktop/arca /var/www/html");
      $control_msg = "Package Updated! 1";
    }
    else {
      $control_msg = shell_exec("ls /wwww/var/www/html -l");
    }
  }

  echo shell_exec("echo %TIME%") . shell_exec("echo %COMPUTERNAME%") . " >> " . $control_msg;
?>
