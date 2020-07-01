<?php

  $control_msg ='';
  $msg ='';


  // Control for AC Bulb
  if($_POST['mode'] == "1")
  {
     if(isset($_POST['bulb_switch'])){
       shell_exec("pigs w 04 1");
       $control_msg = "BULB is on";
     }
     else {
       shell_exec("pigs w 04 0");
       $control_msg = "BULB is off";
     }
   }

    //Control for DC Motor
  if($_POST['mode'] == "2")
  {
    if(isset($_POST['dc_motor_switch'])){
      shell_exec("pigs w 17 1");
      $control_msg = "DC Motor is ON";
    }
    else {
      shell_exec("pigs w 17 0");
      $control_msg = "DC Motor is OFF";
     }
   }

    //Control for AC Motor
  if($_POST['mode'] == "3")
  {
    if(isset($_POST['ac_motor_switch'])){
      shell_exec("pigs w 18 1");
      $control_msg = "AC Motor is on";
    }

    else {
      shell_exec("pigs w 18 0");
      $control_msg = "AC Motor is off";
    }
  }


  //Control for Button Read
  if($_POST['mode'] == "4")
  {
    if(isset($_POST['read_button'])){
      $button_status = shell_exec("pigs r 22");
      if($button_status == 1)
      {
        shell_exec("pigs w 10 1");
        $control_msg = "Button is 1";
      }
      else if ($button_status == 0)
      {
        shell_exec("pigs w 10 0");
        $control_msg = "Button is 0";
      }
      else
      {
        $control_msg = "Invalid Button read";
      }
    }
    else {
    $control_msg = "Switched off read button";
    }
  }

  //Control for IR Sensor Read
  if($_POST['mode'] == "5")
  {
    if(isset($_POST['read_ir'])){
      $ir_status = shell_exec("pigs r 23");
      if($ir_status == 0)
      {
        shell_exec("pigs w 9 1");
        $control_msg = "IR Sensor is 1";
      }
      else if ($ir_status == 1)
      {
        shell_exec("pigs w 9 0");
        $control_msg = "IR Sensor is 0";
      }
      else
      {
        $control_msg = "Invalid IR read";
      }
    }
    else {
      $control_msg = "Switched off read IR";
    }
  }

  if($_POST['mode'] == "9")
  {
    $logs = fopen("../resources/text/actions.txt", "w") or die("Unable to open file!");
    fwrite($logs, "");
    fclose($logs);
  }


  $logs = fopen("../resources/text/actions.txt", "c") or die("Unable to open file!");
  $msg =  shell_exec("echo %TIME%") ./* shell_exec("date +'%T '") .*/ shell_exec("echo %COMPUTERNAME%") ./*shell_exec("echo %HOSTNAME%") . */ " >> " . $control_msg . "<br>\n\n";
  fwrite($logs, $msg);
  fclose($logs);

  $return_message = file_get_contents("../resources/text/actions.txt");
  echo $return_message;
?>
