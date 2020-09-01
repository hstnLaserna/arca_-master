<?php

if(isset($date_to_validate)  && (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date_to_validate)))
{
    if(!isset($is_senior_birthdate)){ $is_senior_birthdate = false;} else {}
    //if(!isset($is_birthdate)){ $is_birthdate = false;} else {}
    // Date validity
    // store the date to variable $date_to_validate
    // Set $is_birthdate = "true" ONLY IF validating birthdate. FALSE in default
    // Set $is_senior_birthdate = "true" ONLY IF validating birthdate of a Senior citizen. FALSE in default
    // $is_senior_birthdate is set to FALSE after validating a date.


    $test_arr  = explode('-', $date_to_validate);
    $timestamp = mktime(0, 0, 0, $test_arr[1], $test_arr[2], $test_arr[0]);



    //if($is_birthdate){
    //    $valid_date = strtotime(date("Y-m-d").' -18 year');
    //} else
    if($is_senior_birthdate){
        $valid_date = strtotime(date("Y-m-d").' -60 year');
    } else {
        $valid_date = strtotime(date("Y-m-d").' +1 day');
    }

    if (count($test_arr) == 3) {
        if (checkdate($test_arr[1], $test_arr[2], $test_arr[0]) && $timestamp <= $valid_date) { } else {
            array_push($errors, "Date must be valid $date_to_validate");
        }
    } else {
        array_push($errors, "Invalid date input.");
    }

    //$is_birthdate = false;
    $is_senior_birthdate = false;
    $date_to_validate = null;
} else {
    array_push($errors, "Invalid date");
}
?>