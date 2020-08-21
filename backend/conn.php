<?php
/* Database connection settings */
  $host = 'localhost';
  $user = 'dbosca';
  $pass = '@rc@m3d1c1n@';
  $schema = 'db_osca';
  $mysqli = new mysqli($host,$user,$pass,$schema) or die($mysqli->error);

  // Establishing Connection with Server by passing server_name, user_id and password as a parameter

  // Selecting Database
  $db = mysqli_connect($host,$user,$pass,$schema);


?>
