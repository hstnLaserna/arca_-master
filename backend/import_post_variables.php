<?php

    $firstname = $mysqli->escape_string($_POST['first_name']);
    $middlename = $mysqli->escape_string($_POST['middle_name']);
    $lastname = $mysqli->escape_string($_POST['last_name']);
    $birthdate = $mysqli->escape_string($_POST['birthdate']);
    $sex = strtolower($mysqli->escape_string($_POST['gender']));
    $sex2 = ($mysqli->escape_string(strtolower($_POST['gender'])) == 'female') ? "f" : "m";
    $password = $mysqli->escape_string($_POST['password']);


    if($user_type == "osca")
    {
        $username = $mysqli->escape_string($_POST['user_name']);
        $position = strtolower($mysqli->escape_string($_POST['position']));
        $answer1 = $mysqli->escape_string($_POST['security_answer_1']);
        $answer2 = $mysqli->escape_string($_POST['security_answer_2']);
    }
    if($user_type == "senior citizen")
    {
        $membership_date = $mysqli->escape_string($_POST['membership_date']);
        $osca_id = $mysqli->escape_string($_POST['osca_id']);
        $nfc_serial = $mysqli->escape_string($_POST['nfc_serial']);
        $contact_number = $mysqli->escape_string($_POST['contact_number']);
    }
    if($with_address)
    {
        $address_line1 = $mysqli->escape_string($_POST['address_line1']);
        $address_line2 = $mysqli->escape_string($_POST['address_line2']);
        $address_city = $mysqli->escape_string($_POST['address_city']);
        $address_province = $mysqli->escape_string($_POST['address_province']);
    }


    ////////////// UPDATE MEMBER ON NEW MODULE: MEMBER PROFILE
?>