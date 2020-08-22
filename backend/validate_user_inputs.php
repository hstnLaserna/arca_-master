<?php

    if(!isset($errors)) {$errors = array();}

    if(strlen($firstname) < 1 || strlen($firstname) > 120)
    {
        array_push($errors, "Firstname must be less than 120 characters");
    }
    if(strlen($lastname) < 1 || strlen($lastname) > 120)
    {
        array_push($errors, "Lastname must be less than 120 characters");
    }
    if($sex != "male")
    {
        if($sex != "female")
        {
            array_push($errors, "Gender must either be Male or Female.");
        }
    }
    if(strlen($contact_number) < 4 || strlen($contact_number) > 30)
    {
        array_push($errors, "Contact number must be between 4 - 30 digits");
    }
    if(strlen($email) < 4 || strlen($email) > 30)
    {
        array_push($errors, "Email must be valid");
    }
    
    if(isset($user_type)) {
        if($user_type == "osca")
        {
            
            { // validate senior citizen's birthdate
                $date_to_validate = $birthdate;
                include("../backend/validate_date.php");
            }
            if(strlen($username) < 4 || strlen($username) > 20)
            {
                array_push($errors, "Username length must be between 4 to 20 characters");
            }
            if(strlen($password) != 0)
            {
                if(strlen($password) < 8 || strlen($password) > 20)
                {
                    array_push($errors, "Password length must be between 8 to 20 characters");
                }
            }
            if($position != "user")
            {
                if($position != "admin")
                {
                array_push($errors, "Position must either be User or Admin.");
                }
            }
            if(strlen($answer1) < 4 || strlen($answer1) > 20)
            {
                array_push($errors, "First Security answer must be between 4 - 20 characters");
            }
            if(strlen($answer2) < 4 || strlen($answer2) > 20)
            {
                array_push($errors, "Second Security answer must be between 4 - 20 characters");
            }
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
            }
            if(strlen($nfc_serial) != 16)
            {
                array_push($errors, "NFC must be between 16 characters");
            }
            if(strlen($password) != 0)
            {
                if(strlen($password) < 8 || strlen($password) > 20)
                {
                    array_push($errors, "Password length must be between 8 to 20 characters");
                }
            }
        }
    }
    if($with_address)
    {
        if(strlen($address_line1) < 4 || strlen($address_line1) > 50)
        {
            array_push($errors, "Address Line 1 must be between 4 - 50 digits");
        }
        if(strlen($address_line2) < 4 || strlen($address_line2) > 50)
        {
            array_push($errors, "Address Line 2 must be between 4 - 50 digits");
        }
        if(strlen($address_city) < 4 || strlen($address_city) > 50)
        {
            array_push($errors, "City must be between 4 - 50 digits");
        }
        if(strlen($address_province) < 4 || strlen($address_province) > 50)
        {
            array_push($errors, "Province must be between 4 - 50 digits");
        }
        $with_address = false;
    }
    
    if($with_guardian)
    {
        if(strlen($g_firstname) < 1 || strlen($g_firstname) > 120)
        {
            array_push($errors, "Guardian's Firstname must be less than 120 characters");
        }
        if(strlen($g_lastname) < 1 || strlen($g_lastname) > 120)
        {
            array_push($errors, "Guardian's Lastname must be less than 120 characters");
        }
        if($g_sex != "male")
        {
            if($g_sex != "female")
            {
                array_push($errors, "Guardian's Gender must either be Male or Female.");
            }
        }
        if(strlen($g_relationship) < 1 || strlen($g_relationship) > 120)
        {
            array_push($errors, "Guardian's Relationship must be less than 120 characters");
        }
        if(strlen($g_contact_number) < 4 || strlen($g_contact_number) > 30)
        {
            array_push($errors, "Guardian's Contact number must be between 4 - 30 digits");
        }
        if(strlen($g_email) < 4 || strlen($g_email) > 30)
        {
            array_push($errors, "Guardian's Email must be valid");
        }
    }

    $array_length=count($errors);



?>