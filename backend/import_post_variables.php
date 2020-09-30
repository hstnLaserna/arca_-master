<?php
    include("../backend/php_functions.php");

    if(!isset($errors)) {$errors = array();}
    $validated = true;

    if(isset($personal_details) && $personal_details)
    {
        if(isset($_POST['first_name']) && 
        isset($_POST['middle_name']) && 
        isset($_POST['last_name']) && 
        isset($_POST['birthdate']) && 
        isset($_POST['gender']) && 
        isset($_POST['contact_number']) && 
        isset($_POST['email']))
        {
            $firstname = $mysqli->real_escape_string($_POST['first_name']);
            $middlename = $mysqli->real_escape_string($_POST['middle_name']);
            $lastname = $mysqli->real_escape_string($_POST['last_name']);
            $birthdate = $mysqli->real_escape_string($_POST['birthdate']);
            $sex = strtolower($mysqli->real_escape_string($_POST['gender']));
            $sex2 = determine_sex($sex, "post");
            $contact_number = $mysqli->real_escape_string($_POST['contact_number']);
            $email = $mysqli->real_escape_string($_POST['email']);
            // --------------
            // VALIDATION
            // --------------

            if(strlen($firstname) < 1 || strlen($firstname) > 120)
            {
                array_push($errors, "Firstname must be less than 120 characters");
            }
            if(strlen(strlen($middlename) > 120))
            {
                array_push($errors, "Middlename must be less than 120 characters");
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
            
            if(!(preg_match("/((^(\+)(\d){12}$)|(^\d{11}$))/",$contact_number)))
            {
                array_push($errors, "Contact number must be a valid phone number");
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                array_push($errors, "Email must be valid");
            }
            if(count($errors) > 0)
            {
                array_push($errors, "");
            }

        } else {
            if(!isset($_POST['first_name'])){array_push($errors, "Missing field: first_name");}
            if(!isset($_POST['last_name'])){array_push($errors, "Missing field: last_name");}
            if(!isset($_POST['birthdate'])){array_push($errors, "Missing field: birthdate");}
            if(!isset($_POST['gender'])){array_push($errors, "Missing field: gender");}
            if(!isset($_POST['contact_number'])){array_push($errors, "Missing field: contact_number");}
            if(!isset($_POST['email'])){array_push($errors, "Missing field: email");}
            $validated = false;
        }
    }

    
    
    
    if(isset($company_details) && $company_details)
    {
        if(isset($_POST['company_name']) && 
        isset($_POST['company_tin']) && 
        isset($_POST['company_branch']) && 
        isset($_POST['business_type']))
        {
            $company_name = $mysqli->real_escape_string($_POST['company_name']);
            $company_tin = $mysqli->real_escape_string($_POST['company_tin']);
            $branch = $mysqli->real_escape_string($_POST['company_branch']);
            $business_type = strtolower($mysqli->real_escape_string($_POST['business_type']));

            // --------------
            // VALIDATION
            // --------------
            if(strlen($company_name) < 1 || strlen($company_name) > 120)
            {
                array_push($errors, "Company Name must be less than 120 characters");
            }
            $company_tin = preg_replace('/[^0-9]/', '', $company_tin);
            if(strlen($company_tin) === 9 OR strlen($company_tin) === 10) {} else {
                array_push($errors, "TIN must either be 9 or 10 digits");
            }
            if(strlen($branch) < 1 || strlen($branch) > 120)
            {
                array_push($errors, "Branch must be less than 120 characters");
            }

            switch ($business_type) {
                case 'pharmacy':
                    break;
                case 'transportation':
                    break;
                case 'restaurant':
                    $business_type = "food";
                    break;
                default:
                    array_push($errors, "Business type is not valid.");
                    break;
            }
            
            if(count($errors) > 0)
            {
                array_push($errors, "");
            }
        } else {
            if(!isset($_POST['company_name'])){array_push($errors, "Missing field: company_name");}
            if(!isset($_POST['company_tin'])){array_push($errors, "Missing field: company_tin");}
            if(!isset($_POST['company_branch'])){array_push($errors, "Missing field: company_branch");}
            if(!isset($_POST['business_type'])){array_push($errors, "Missing field: business_type");}
            $validated = false;
        }
    }
    
    if(isset($with_address) && $with_address)
    {
        if(isset($_POST['address_line1']) && 
        isset($_POST['address_line2']) && 
        isset($_POST['address_city']) && 
        isset($_POST['address_province']))
        {
            $address_line1 = $mysqli->real_escape_string($_POST['address_line1']);
            $address_line2 = $mysqli->real_escape_string($_POST['address_line2']);
            $address_city = $mysqli->real_escape_string($_POST['address_city']);
            $address_province = $mysqli->real_escape_string($_POST['address_province']);

            // --------------
            // VALIDATION
            // --------------

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
            if (isset($_POST["address_is_active"])) {
                $address_is_active = "1";

            } else {
                $address_is_active = "0";
            }

            if(count($errors) > 0)
            {
                array_push($errors, "");
            }
        } else {
            if(!isset($_POST['address_line1'])){array_push($errors, "Missing field: address_line1");}
            if(!isset($_POST['address_line2'])){array_push($errors, "Missing field: address_line2");}
            if(!isset($_POST['address_city'])){array_push($errors, "Missing field: address_city");}
            if(!isset($_POST['address_province'])){array_push($errors, "Missing field: address_province");}
            $validated = false;
        }
        unset($with_address);
    }
    
    if(isset($with_guardian) && $with_guardian)
    {
        if(isset($_POST['g_first_name']) && 
        isset($_POST['g_middle_name']) && 
        isset($_POST['g_last_name']) && 
        isset($_POST['g_gender']) && 
        isset($_POST['g_relationship']) && 
        isset($_POST['g_contact_number']) && 
        isset($_POST['g_email']))
        {
            $g_firstname = $mysqli->real_escape_string($_POST['g_first_name']);
            $g_middlename = $mysqli->real_escape_string($_POST['g_middle_name']);
            $g_lastname = $mysqli->real_escape_string($_POST['g_last_name']);
            $g_sex = strtolower($mysqli->real_escape_string($_POST['g_gender']));
            $g_sex2 = determine_sex($g_sex, "post");
            $g_relationship = $mysqli->real_escape_string($_POST['g_relationship']);
            $g_contact_number = $mysqli->real_escape_string($_POST['g_contact_number']);
            $g_email = $mysqli->real_escape_string($_POST['g_email']);

            // --------------
            // VALIDATION
            // --------------
            if(strlen($g_firstname) < 1 || strlen($g_firstname) > 120)
            {
                array_push($errors, "Firstname must be less than 120 characters");
            }
            if(strlen(strlen($g_lastname) > 120))
            {
                array_push($errors, "Middlename must be less than 120 characters");
            }
            if(strlen($g_lastname) < 1 || strlen($g_lastname) > 120)
            {
                array_push($errors, "Lastname must be less than 120 characters");
            }
            if($g_sex != "male")
            {
                if($g_sex != "female")
                {
                    array_push($errors, "Gender must either be Male or Female.");
                }
            }
            if(strlen($g_relationship) < 1 || strlen($g_relationship) > 120)
            {
                array_push($errors, "Relationship must be less than 120 characters");
            }
            if(!(preg_match("/((^(\+)(\d){12}$)|(^\d{11}$))/",$g_contact_number)))
            {
                array_push($errors, "Phone number must be between 11 digits");
            }
            if(!filter_var($g_email, FILTER_VALIDATE_EMAIL))
            {
                array_push($errors, "Email must be valid");
            }
            if(count($errors) > 0)
            {
                array_push($errors, "");
            }
            $with_guardian = false;
        } else {
            if(!isset($_POST['g_first_name'])){array_push($errors, "Missing field: g_first_name");}
            if(!isset($_POST['g_last_name'])){array_push($errors, "Missing field: g_last_name");}
            if(!isset($_POST['g_gender'])){array_push($errors, "Missing field: g_gender");}
            if(!isset($_POST['g_relationship'])){array_push($errors, "Missing field: g_relationship");}
            if(!isset($_POST['g_contact_number'])){array_push($errors, "Missing field: g_contact_number");}
            if(!isset($_POST['g_email'])){array_push($errors, "Missing field: g_email");}
            $validated = false;
        }
    }
    


    if(isset($user_type)){
        if($user_type == "osca")
        {
            if(isset($_POST['user_name']) && 
            isset($_POST['password']) && 
            isset($_POST['position']) && 
            isset($_POST['birthdate']) && 
            isset($_POST['security_answer_1']) &&
            isset($_POST['security_answer_2']))
            {
                $username = $mysqli->real_escape_string($_POST['user_name']);
                $password = $mysqli->real_escape_string($_POST['password']);
                $birthdate = $mysqli->real_escape_string($_POST['birthdate']);
                $position = strtolower($mysqli->real_escape_string($_POST['position']));
                $answer1 = $mysqli->real_escape_string($_POST['security_answer_1']);
                $answer2 = $mysqli->real_escape_string($_POST['security_answer_2']);

                // --------------
                // VALIDATION
                // --------------
                
                if(!validate_date($birthdate, -1))
                {
                    array_push($errors, "Birthdate must be valid $birthdate");
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
            } else {
                if(!isset($_POST['user_name'])){array_push($errors, "Missing field: username");}
                if(!isset($_POST['position'])){array_push($errors, "Missing field: position");}
                if(!isset($_POST['birthdate'])){array_push($errors, "Missing field: birthdate");}
                if(!isset($_POST['security_answer_1'])){array_push($errors, "Missing field: security_answer_1");}
                if(!isset($_POST['security_answer_2'])){array_push($errors, "Missing field: security_answer_2");}
                $validated = false;
            }
        }
        if($user_type == "senior citizen")
        {
            if(isset($_POST['password']) && 
            isset($_POST['membership_date']) && 
            isset($_POST['nfc_serial']))
            {
                $password = $mysqli->real_escape_string($_POST['password']);
                $membership_date = $mysqli->real_escape_string($_POST['membership_date']);
                $birthdate = $mysqli->real_escape_string($_POST['birthdate']);
                $osca_id = (isset($_POST['osca_id']))? $mysqli->real_escape_string($_POST['osca_id']): "";
                $nfc_serial = $mysqli->real_escape_string($_POST['nfc_serial']);

                // --------------
                // VALIDATION
                // --------------

                if(!validate_date($birthdate, -60))
                {
                    array_push($errors, "Senior's birthdate must be valid $birthdate");
                }
                
                if(!validate_date($membership_date))
                {
                    array_push($errors, "Memership must be valid $membership_date");
                }
                
                if(strlen($password) != 0)
                {
                    if(strlen($password) < 8 || strlen($password) > 20)
                    {
                        array_push($errors, "Password length must be between 8 to 20 characters");
                    }
                }
            } else {
                if(!isset($_POST['password'])){array_push($errors, "Missing field: password");}
                if(!isset($_POST['membership_date'])){array_push($errors, "Missing field: membership_date");}
                if(!isset($_POST['birthdate'])){array_push($errors, "Missing field: birthdate");}
                if(!isset($_POST['nfc_serial'])){array_push($errors, "Missing field: nfc_serial");}
                $validated = false;
            }
        }
        
        if(count($errors) > 0) // TO leave 1 space below
        {
            array_push($errors, "");
        }
        unset($user_type);
    }

    

    if(isset($lost_report) && $lost_report)
    {
        if(isset($_POST['desc']) &&
        isset($_POST['lost_id']) && 
        isset($_POST['id']))
        {
            $desc = $mysqli->real_escape_string($_POST['desc']);
            $lost_id = $mysqli->real_escape_string($_POST['lost_id']);
            $id = $mysqli->real_escape_string($_POST['id']);
            $nfc_status = (isset($_POST['nfc_status'])) ? 1 : 0;
            $account_status = (isset($_POST['account_status'])) ? 1 : 0;
            
            // --------------
            // VALIDATION
            // --------------
            if(strlen($desc) < 1)
            {
                array_push($errors, "Action must not be empty");
            }
            if(strlen($lost_id) < 1)
            {
                array_push($errors, "Invalid Lost Report number");
            }
            if(strlen($id) < 1)
            {
                array_push($errors, "Invalid Member");
            }
        } else {
            if(!isset($_POST['desc'])){array_push($errors, "Missing field: desc");}
            if(!isset($_POST['lost_id'])){array_push($errors, "Missing field: lost_id");}
            if(!isset($_POST['id'])){array_push($errors, "Missing field: id");}
            $validated = false;
        }
    }


    
    if(isset($errors)) {
        $array_length=count($errors);
    }
    
    
?>