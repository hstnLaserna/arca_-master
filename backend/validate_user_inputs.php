<?php

    $errors = array();
    if(strlen($username) < 4 || strlen($username) > 20)
    {
        array_push($errors, "Username length must be between 4 to 20 characters");
    } else {}
    if(strlen($firstname) < 1 || strlen($firstname) > 120)
    {
        array_push($errors, "Firstname must be less than 120 characters");
    } else {}
    if(strlen($lastname) < 1 || strlen($lastname) > 120)
    {
        array_push($errors, "Lastname must be less than 120 characters");
    } else {}
    if($birthdate == "")
    {
        array_push($errors, "Birthdate must be valid");
    } else {}
    if($sex != "male")
    {
        if($sex != "female")
        {
        array_push($errors, "Gender must either be Male or Female. Selected: " . $_POST['edit_gender']);
        } else {}
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


    if(strlen($password) != 0)
    {
        if(strlen($password) < 8 || strlen($password) > 20)
        {
            array_push($errors, "Password length must be between 8 to 20 characters");
        } else {}
    }


    
    $array_length=count($errors);



?>