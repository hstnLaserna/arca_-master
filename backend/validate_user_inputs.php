<?php

    $errors = array();
    if(strlen($firstname) < 1 || strlen($firstname) > 120)
    {
        array_push($errors, "Firstname must be less than 120 characters");
    } else {}
    if(strlen($lastname) < 1 || strlen($lastname) > 120)
    {
        array_push($errors, "Lastname must be less than 120 characters");
    } else {}
    if($sex != "male")
    {
        if($sex != "female")
        {
        array_push($errors, "Gender must either be Male or Female. Selected: " . $_POST['edit_gender']);
        } else {}
    } else {}
    if(strlen($password) != 0)
    {
        if(strlen($password) < 8 || strlen($password) > 20)
        {
            array_push($errors, "Password length must be between 8 to 20 characters");
        } else {}
    }


    if($user_type == "osca")
    {
        
        { // validate senior citizen's birthdate
            $date_to_validate = $birthdate;
            include("../backend/validate_date.php");
        }
        if(strlen($username) < 4 || strlen($username) > 20)
        {
            array_push($errors, "Username length must be between 4 to 20 characters");
        } else {}
        if($position != "user")
        {
            if($position != "admin")
            {
            array_push($errors, "Position must either be User or Admin. Selected: " . $_POST['edit_position']);
            } else {}
        } else {}
        if(strlen($answer1) < 4 || strlen($answer1) > 20)
        {
            array_push($errors, "First Security answer must be between 4 - 20 characters");
        } else {}
        if(strlen($answer2) < 4 || strlen($answer2) > 20)
        {
            array_push($errors, "Second Security answer must be between 4 - 20 characters");
        } else {}
    }
    if($user_type == "senior citizen")
    {
        { // validate senior citizen's birthdate
            $date_to_validate = $birthdate;
            $is_senior_birthdate = true;
            include("../backend/validate_date.php");
        }

        { // validate membership date
            $date_to_validate = $membership_date;
            $is_senior_birthdate = false;
            include("../backend/validate_date.php");
        }
        
        if(strlen($osca_id) != 8)
        {
            array_push($errors, "OSCA ID must be between 8 characters");
        } else {}
        if(strlen($nfc_serial) != 16)
        {
            array_push($errors, "NFC must be between 16 characters");
        } else {}
        if(strlen($contact_number) < 4 || strlen($contact_number) > 30)
        {
            array_push($errors, "Contact numbermust be between 4 - 30 digits");
        } else {}
    }
    if($with_address)
    {
        if(strlen($address_line1) < 4 || strlen($address_line1) > 50)
        {
            array_push($errors, "Address Line 1 must be between 4 - 50 digits");
        } else {}
        if(strlen($address_line2) < 4 || strlen($address_line2) > 50)
        {
            array_push($errors, "Address Line 2 must be between 4 - 50 digits");
        } else {}
        if(strlen($address_city) < 4 || strlen($address_city) > 50)
        {
            array_push($errors, "City must be between 4 - 50 digits");
        } else {}
        if(strlen($address_province) < 4 || strlen($address_province) > 50)
        {
            array_push($errors, "Province must be between 4 - 50 digits");
        } else {}
        $with_address = false;
    }

    $array_length=count($errors);



?>