<?php
/* Database connection settings */
  $host = 'localhost';
  $user = 'dbosca';
  $pass = '@rc@m3d1c1n@';
  $schema = 'db_osca';
  $mysqli = new mysqli($host,$user,$pass,$schema) or die($mysqli->error);
  
  $db = mysqli_connect($host,$user,$pass,$schema);


?>
