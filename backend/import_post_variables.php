<?php
include("../backend/php_functions.php");

    $firstname = $mysqli->escape_string($_POST['first_name']);
    $middlename = $mysqli->escape_string($_POST['middle_name']);
    $lastname = $mysqli->escape_string($_POST['last_name']);
    $birthdate = $mysqli->escape_string($_POST['birthdate']);
    $sex = strtolower($mysqli->escape_string($_POST['gender']));
    $sex2 = determine_sex($sex, "post");
    $contact_number = $mysqli->escape_string($_POST['contact_number']);
    $email = $mysqli->escape_string($_POST['email']);


    if(isset($user_type)) {
        if($user_type == "osca")
        {
            $username = $mysqli->escape_string($_POST['user_name']);
            $password = $mysqli->escape_string($_POST['password']);
            $position = strtolower($mysqli->escape_string($_POST['position']));
            $answer1 = $mysqli->escape_string($_POST['security_answer_1']);
            $answer2 = $mysqli->escape_string($_POST['security_answer_2']);
        }
        if($user_type == "senior citizen")
        {
            $password = $mysqli->escape_string($_POST['password']);
            $membership_date = $mysqli->escape_string($_POST['membership_date']);
            $osca_id = $mysqli->escape_string($_POST['osca_id']);
            $nfc_serial = $mysqli->escape_string($_POST['nfc_serial']);
        }
    }
    
    if($with_address)
    {
        $address_line1 = $mysqli->escape_string($_POST['address_line1']);
        $address_line2 = $mysqli->escape_string($_POST['address_line2']);
        $address_city = $mysqli->escape_string($_POST['address_city']);
        $address_province = $mysqli->escape_string($_POST['address_province']);
    }
    if($with_guardian)
    {
        $g_firstname = $mysqli->escape_string($_POST['g_first_name']);
        $g_middlename = $mysqli->escape_string($_POST['g_middle_name']);
        $g_lastname = $mysqli->escape_string($_POST['g_last_name']);
        $g_sex = strtolower($mysqli->escape_string($_POST['g_gender']));
        $g_sex2 = determine_sex($g_sex, "post");
        $g_relationship = $mysqli->escape_string($_POST['g_relationship']);
        $g_contact_number = $mysqli->escape_string($_POST['g_contact_number']);
        $g_email = $mysqli->escape_string($_POST['g_email']);
    }

?>