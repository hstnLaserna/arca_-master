<?php
  /* Database connection settings */
  $host = 'localhost';
  //$host = 'https://my_server-ralphchri1.pitunnel.com';
  $user = 'dbosca';
  $pass = '@rc@m3d1c1n@';
  $schema = 'db_osca';
  $mysqli = new mysqli($host,$user,$pass,$schema) or die($mysqli->error);
?>
