<?php

    $username = $mysqli->escape_string($_POST['user_name']);
    $password = $mysqli->escape_string($_POST['password']);
    $firstname = $mysqli->escape_string($_POST['first_name']);
    $middlename = $mysqli->escape_string($_POST['middle_name']);
    $lastname = $mysqli->escape_string($_POST['last_name']);
    $birthdate = $mysqli->escape_string($_POST['birthdate']);
    $sex = strtolower($mysqli->escape_string($_POST['gender']));
    $sex2 = ($mysqli->escape_string(strtolower($_POST['gender'])) == 'female') ? "f" : "m";
    $position = strtolower($mysqli->escape_string($_POST['position']));
    $answer1 = $mysqli->escape_string($_POST['security_answer_1']);
    $answer2 = $mysqli->escape_string($_POST['security_answer_2']);


?>