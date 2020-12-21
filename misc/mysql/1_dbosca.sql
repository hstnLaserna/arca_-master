-- LAST UPDATE: 2020-09-30 06:46

-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `db_osca`;

DELIMITER ;;

DROP PROCEDURE IF EXISTS `activate_admin_account`;;
CREATE PROCEDURE `activate_admin_account`(IN `user_name_` varchar(60), OUT `msg` int(10))
BEGIN
  IF( (SELECT count(*) FROM `admin` WHERE `user_name` = `user_name_`) = 1) 
    THEN
    UPDATE `admin` SET
    is_enabled = 1,
    log_attempts = 0
    WHERE `user_name` = `user_name_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_address`;;
CREATE PROCEDURE `add_address`(IN `add1_` varchar(120), IN `add2_` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120), IN `is_active_` varchar(11), IN `member_id_` varchar(20), OUT `msg` varchar(1))
BEGIN
  IF ((SELECT COUNT(*) FROM `member` WHERE `id` = member_id_) = 1)
  THEN
    START TRANSACTION;
        
      IF (`is_active_` = 1) -- if is_active flag is set, clear other address of this entity
      THEN
        UPDATE `address` a
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
        SET a.`is_active` = 0
        WHERE ajt.`member_id` = (SELECT `id` FROM `member` WHERE `id` = `member_id_`);
      ELSE
        SET msg = 0; -- do nothing, 
      END IF;
            
      INSERT  INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
      VALUES (`add1_`, `add2_`, `city_`, `province_`, `is_active_`, now());
      
      SET @last_inserted_id = LAST_INSERT_ID();

      INSERT INTO address_jt (`address_id`, `member_id`) VALUES (@last_inserted_id, (SELECT `id` FROM `member` WHERE `id` = `member_id_`));
      SET msg = 1;  -- company exists
    COMMIT;
  ELSE 
    SET msg = 0; -- company doesnt exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_admin`;;
CREATE PROCEDURE `add_admin`(IN `username_` varchar(20), IN `password_` varchar(20), IN `firstname_` varchar(60), IN `middlename_` varchar(60), IN `lastname_` varchar(60), IN `birthdate_` date, IN `sex_` varchar(10), IN `contact_number_` varchar(20), IN `email_` varchar(120), IN `position_` varchar(60), IN `isEnabled_` tinyint, IN `answer1_` varchar(20), IN `answer2_` varchar(20), OUT `msg` varchar(60))
BEGIN

    IF
        ((SELECT COUNT(*) FROM `admin` WHERE `user_name` = `username_`) = 0)
    THEN
        INSERT INTO `admin` (`user_name`, `password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `position`, `is_enabled`, `log_attempts`, `answer1`, `answer2`, `temporary_password`, `avatar`)
                    VALUES (`username_`, MD5(`password_`), `firstname_`, `middlename_`, `lastname_`, `birthdate_`, `sex_`, `contact_number_`, `email_`, `position_`, `isEnabled_`, 0, `answer1_`, `answer2_`, `password_`, 'null');
        SET msg = "1";
    ELSE
        SET msg = "0";
    END IF;

END;;

DROP PROCEDURE IF EXISTS `add_company`;;
CREATE PROCEDURE `add_company`(IN `company_tin_` varchar(20), IN `company_name_` varchar(250), IN `branch_` varchar(120), IN `business_type_` varchar(120),
                                IN `address1_` varchar(120), IN `address2_` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120))
BEGIN
  START TRANSACTION;
  INSERT INTO `company` (`company_tin`, `company_name`, `branch`, `business_type`)
    VALUES  (`company_tin_`, `company_name_`, `branch_`, `business_type_`);
  SET @company_inserted_id = LAST_INSERT_ID();

  INSERT INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
    VALUES  (`address1_`, `address2_`, `city_`, `province_`, 1, now());

  SET @address_inserted_id = LAST_INSERT_ID();
  INSERT INTO address_jt (`address_id`, `company_id`) VALUES (@address_inserted_id, @company_inserted_id);
  COMMIT;
END;;

DROP PROCEDURE IF EXISTS `add_company_address`;;
CREATE PROCEDURE `add_company_address`(IN `add1_` varchar(120), IN `add2_` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120), IN `is_active_` varchar(11), IN `selected_id_` varchar(20), OUT `msg` varchar(1))
BEGIN
  IF ((SELECT COUNT(*) FROM `company` WHERE `id` = `selected_id_`) = 1)
  THEN
    START TRANSACTION;
        
      IF (`is_active_` = 1) -- if is_active flag is set, clear other address of this entity
      THEN
        UPDATE `address` a
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
        SET a.`is_active` = 0
        WHERE ajt.`company_id` = (SELECT `id` FROM `company` WHERE `id` = `selected_id_`);
      ELSE
        SET msg = 0; -- do nothing, 
      END IF;
      
      INSERT  INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
      VALUES (`add1_`, `add2_`, `city_`, `province_`, `is_active_`, now());
      SET @last_inserted_id = LAST_INSERT_ID();
      INSERT INTO address_jt (`address_id`, `company_id`) VALUES (@last_inserted_id, (SELECT `id` FROM `company` WHERE `id` = `selected_id_`));
            
      SET msg = 1;  -- company exists
    COMMIT;
  ELSE 
    SET msg = 0; -- company doesnt exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_complaint_report`;;
CREATE PROCEDURE `add_complaint_report`(IN `company_name_` VARCHAR(250), IN `branch_` VARCHAR(120), IN `osca_id_` VARCHAR(20), IN `desc_` VARCHAR(300), IN `report_date_` TIMESTAMP, OUT `msg` INT(1))
BEGIN
  IF ((SELECT COUNT(*)
    FROM company AS c
    INNER JOIN `member` AS m
    WHERE c.company_name = company_name_
    AND c.branch = branch_
    AND m.osca_id = osca_id_) = 1)
  THEN
    INSERT INTO complaint_report (company_id, member_id)
    SELECT c.id, m.id
    FROM company AS c
    INNER JOIN `member` AS m
    WHERE c.company_name = company_name_
    AND c.branch = branch_
    AND m.osca_id = osca_id_;
  
    SET @last_inserted_id = LAST_INSERT_ID();
    
    UPDATE complaint_report set
    `desc` = desc_,
    report_date = report_date_
    WHERE id = @last_inserted_id;
    
    SET msg = 1;
  ELSE 
    SET msg = 0;
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_drug`;;
CREATE PROCEDURE `add_drug`(IN `generic_name_` varchar(120), IN `brand_` varchar(120), IN `dose_` int(20), IN `unit_` varchar(120), IN `is_otc_` int(10), IN `max_monthly_` int(20), IN `max_weekly_` int(20))
BEGIN
    INSERT INTO `drug` (`generic_name`, `brand`, `dose`, `unit`, `is_otc`, `max_monthly`, `max_weekly`)
                VALUES (`generic_name_`, `brand_`, `dose_`, `unit_`, `is_otc_`, `max_monthly_`, `max_weekly_`);
END;;

DROP PROCEDURE IF EXISTS `add_guardian`;;
CREATE PROCEDURE `add_guardian`(IN `first_name_` varchar(120), IN `middle_name_` varchar(120), IN `last_name_` varchar(120), IN `sex_` varchar(10), IN `relationship_` varchar(120), IN `contact_number_` varchar(20), IN `email_` varchar(120), IN `member_id_` varchar(20), OUT `msg` int(1))
BEGIN
  IF ((SELECT COUNT(*) FROM `member` WHERE `id` = member_id_) = 1)
  THEN
    START TRANSACTION;
      
      INSERT  INTO `guardian` (`first_name`,  `middle_name`,  `last_name`,  `sex`,  `relationship`,  `contact_number`,  `email`,  `member_id`)
        VALUES (`first_name_`,  `middle_name_`,  `last_name_`,  `sex_`,  `relationship_`,  `contact_number_`,  `email_`,  `member_id_`);

      SET msg= 1; -- member exists
    COMMIT;
  ELSE 
    SET msg= 0; -- member does not exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_lost_report`;;
CREATE PROCEDURE `add_lost_report`(IN `osca_id_` varchar(20), IN `report_date_` timestamp, OUT `msg` int(1))
BEGIN
  IF ((SELECT COUNT(*)
    FROM `member` AS m
    WHERE m.osca_id = osca_id_) = 1)
  THEN
    INSERT INTO lost_report (member_id)
    SELECT m.id
    FROM `member` AS m
    WHERE m.osca_id = osca_id_;
  
    SET @last_inserted_id = LAST_INSERT_ID();
    
    UPDATE lost_report set
    report_date = report_date_
    WHERE id = @last_inserted_id;
    
    SET msg = 1;
  ELSE 
    SET msg = 0;
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_member`;;
CREATE PROCEDURE `add_member`(IN `fname` varchar(60), IN `mname` varchar(60), IN `lname` varchar(60), IN `bday` date, IN `sex_` varchar(10), IN `contact_no` varchar(20), IN `email_` varchar(120), IN `memship_date_` datetime, IN `add1` varchar(120), IN `add2` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120), IN `pword` varchar(120), IN `g_fname` varchar(120), IN `g_mname` varchar(120), IN `g_lname` varchar(120), IN `g_contact_no` varchar(120), IN `g_sex_` varchar(10), IN `g_relationship` varchar(120), IN `g_email_` varchar(120))
BEGIN
  INSERT INTO `member` (`first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `membership_date`, `password`)
    VALUES  (fname, mname, lname, bday, sex_, contact_no, email_, memship_date_, MD5(pword));
  SET @member_inserted_id = LAST_INSERT_ID();
  
    UPDATE `member` m
    SET member_count = @member_inserted_id
    WHERE m.id = @member_inserted_id;
    
  INSERT INTO `guardian` (`first_name`, `middle_name`, `last_name`, `sex`, `relationship`, `contact_number`, `email`, `member_id`)
    VALUES  (g_fname, g_mname, g_lname, g_sex_, g_relationship, g_contact_no, g_email_, @member_inserted_id);

  INSERT INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
    VALUES  (add1, add2, city_, province_, 1, now());

  SET @address_inserted_id = LAST_INSERT_ID();
  INSERT INTO address_jt (`address_id`, `member_id`) VALUES (@address_inserted_id, @member_inserted_id);


    UPDATE `member` m
    INNER JOIN address_jt ajt ON ajt.member_id = m.id
    INNER JOIN address a ON ajt.address_id = a.id
    INNER JOIN city_code cc ON LOWER(a.city) = LOWER(cc.citymunDesc)
      SET
      m.osca_id =  CONCAT(provCode, '-', substr(year(membership_date), 3,3), m.member_count),
      m.password = MD5(CONCAT(provCode, '-', substr(year(membership_date), 3,3), m.member_count))
    WHERE m.id = @member_inserted_id;

END;;

DROP PROCEDURE IF EXISTS `add_qr_request`;;
CREATE PROCEDURE `add_qr_request`(IN `osca_id_` varchar(120), IN `desc_` varchar(120), IN `token_` varchar(120), IN `request_date_` timestamp, OUT `msg` varchar(1))
BEGIN
  DECLARE member_id_ VARCHAR(120);
  IF ((SELECT count(*) FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_` LIMIT 1) = 1)
  THEN
    SET `member_id_` = (SELECT `member_id` FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_` LIMIT 1);
    INSERT INTO `qr_request`(`member_id`, `desc`, `token`, `trans_date`) VALUES
      (`member_id_`, `desc_`, `token_`, `request_date_`);
    SET msg = 1;
  ELSE
        SET msg = 0;
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_transaction`;;
CREATE PROCEDURE `add_transaction`(IN `trans_date_` timestamp, IN `company_tin_` varchar(120), IN `osca_id_` varchar(120), IN `clerk_` varchar(120), OUT `msg` varchar(120))
BEGIN
  DECLARE company_id_ INT(20);
  DECLARE member_id_ VARCHAR(120);
    SET `company_id_` = (SELECT `c_id` FROM `view_companies` WHERE `company_tin` = `company_tin_`);
    SET `member_id_` = (SELECT `member_id` FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_` LIMIT 1);
    INSERT INTO `transaction`(`trans_date`, `company_id`, `member_id`, `clerk`) VALUES
      (`trans_date_`, `company_id_`, `member_id_`, `clerk_`);
    SET msg = LAST_INSERT_ID();
END;;

DROP PROCEDURE IF EXISTS `add_transaction_food`;;
CREATE PROCEDURE `add_transaction_food`(IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `desc_` varchar(120), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
BEGIN
  IF (`trans_type` = 'food' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `food` (`transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `desc_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_transaction_pharmacy_drug`;;
CREATE PROCEDURE `add_transaction_pharmacy_drug`(IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `drug_id_` int(20), IN `quantity_` int(20), IN `unit_price_` decimal(13,2), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
BEGIN
  IF (`trans_type` = 'pharmacy' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `pharmacy` (`transaction_id`, `drug_id`, `quantity`, `unit_price`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `drug_id_`, `quantity_`, `unit_price_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_transaction_pharmacy_nondrug`;;
CREATE PROCEDURE `add_transaction_pharmacy_nondrug`(IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `desc_` varchar(120), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
BEGIN
  IF (`trans_type` = 'pharmacy' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `pharmacy` (`transaction_id`, `desc_nondrug`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `desc_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `add_transaction_transportation`;;
CREATE PROCEDURE `add_transaction_transportation`(IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `desc_` varchar(120), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
BEGIN
  IF (`trans_type` = 'transportation' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `transportation` (`transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `desc_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `deactivate_admin_account`;;
CREATE PROCEDURE `deactivate_admin_account`(IN `user_name_` varchar(60), OUT `msg` int(10))
BEGIN
  IF( (SELECT count(*) FROM `admin` WHERE `user_name` = `user_name_`) = 1) 
    THEN
    UPDATE `admin` SET
    is_enabled = 0,
    log_attempts = 0
    WHERE `user_name` = `user_name_`;
    SET `msg` = "0";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `delete_company_address`;;
CREATE PROCEDURE `delete_company_address`(IN `company_id_` int(20), IN `company_name_` varchar(250), IN `branch_` varchar(120), OUT `msg` varchar(20))
BEGIN
  
  IF(( SELECT count(*) FROM `company` c
      WHERE c.`id` = `company_id_` 
      AND c.`company_name` = `company_name_`
      AND c.`branch` = `branch_`) = 1)
  THEN
    DELETE FROM `company`
      WHERE `id` = `company_id_`;
    IF(( SELECT count(*) FROM `address` a 
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.`id`
        WHERE ajt.`company_id` = `company_id_`) > 0)
    THEN
      DELETE FROM `address_jt`
        WHERE `company_id` = `company_id_`;
      SET msg = 2; -- member AND address exists
    ELSE
      SET msg = 1; -- address doesnt exist
    END IF;
  ELSE
    SET msg = 0; -- the company doesnt exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `delete_guardian`;;
CREATE PROCEDURE `delete_guardian`(IN `member_osca_id_` varchar(20), IN `id_` int(20), OUT `msg` int(1))
BEGIN
  IF((
  SELECT count(*) FROM `guardian` g INNER JOIN `member` m ON g.`member_id` = m.`id` WHERE g.`id` = `id_` AND m.`osca_id` = `member_osca_id_`) = 1)
  THEN
    
    DELETE FROM `guardian`
    WHERE `id` = `id_`;

    IF(( SELECT count(*) FROM address a INNER JOIN address_jt ajt ON ajt.address_id = a.id WHERE ajt.`guardian_id` = `id_`) = 1)
    THEN            
      DELETE FROM `address_jt`
      WHERE `guardian_id` = `id_`;
      
      SET msg = 2; -- address for this guardian exists, guardian deleted
    ELSE

      SET msg = 1; -- address for this guardian does not exist, guardian deleted
    END IF;
  ELSE

    SET msg = 0; -- Guardian does not exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `delete_member_address`;;
CREATE PROCEDURE `delete_member_address`(IN `member_id_` int(20), IN `id_` int(20), OUT `msg` varchar(120))
BEGIN
  IF(( SELECT count(*) FROM `member` m
      INNER JOIN `address_jt` ajt ON ajt.`member_id` = m.`id`
      WHERE ajt.`member_id` = `member_id_`) > 0)
  THEN
    IF(( SELECT count(*) FROM `address` a 
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.`id`
                WHERE ajt.`address_id` = `address_id_`) > 0)
    THEN
      DELETE FROM `address_jt`
        WHERE `member_id` = `member_id_` AND `address_id` = `address_id_`;
      DELETE FROM `address`
        WHERE `id` = `address_id_`;
      SET msg = 2; -- member AND address exists
        ELSE
      SET msg = 1; -- member exists but not the address
        END IF;
  ELSE
    SET msg = 0; -- the member doesnt exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_address_company`;;
CREATE PROCEDURE `edit_address_company`(IN `add1_` varchar(120), IN `add2_` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120), IN `id_` int(11), IN `company_id_` varchar(120), OUT `msg` varchar(120))
BEGIN
  IF( (SELECT count(*) FROM `company` c
                    WHERE c.`id` = `company_id_`) = 1)
    THEN
    IF( (SELECT count(*) FROM `address` a
      INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
      WHERE ajt.`company_id` = `company_id_` AND ajt.`address_id` = `id_`) = 1 )
    THEN
      UPDATE `address` a
      INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
      SET `address1` = `add1_`,
        `address2` = `add2_`,
        `city` = `city_`,
        `province` = `province_`,
        `is_active` = `1`,
        `last_update` = now()
      WHERE a.`id` = `id_`
      AND  ajt.`company_id` = `company_id_`;
      SET msg = "2";  -- company exist AND address exist
    ELSE
      SET msg = "1"; -- company exist but address doesnt
    END IF;
  ELSE 
    SET msg = "0"; -- company doesnt exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_admin_no_pw`;;
CREATE PROCEDURE `edit_admin_no_pw`(IN `uname` varchar(120), IN `fname` varchar(120), IN `mname` varchar(120), IN `lname` varchar(120), IN `bday` date, IN `sex_` varchar(10), IN `contact_number_` varchar(20), IN `email_` varchar(120), IN `pos` varchar(120), IN `ans1` varchar(100), IN `ans2` varchar(100), IN `uid` varchar(120))
UPDATE `admin` SET 
user_name = uname,
first_name = fname,
middle_name = mname,
last_name = lname,
birth_date = bday,
sex = sex_,
contact_number = contact_number_,
email = email_,
position = pos,
answer1 = ans1,
answer2 = ans2
WHERE id = uid;;

DROP PROCEDURE IF EXISTS `edit_admin_picture`;;
CREATE PROCEDURE `edit_admin_picture`(IN `user_name_` varchar(60), IN `avatar_` varchar(60), OUT `msg` int(10))
BEGIN
  IF( (SELECT count(*) FROM `admin` WHERE `user_name` = `user_name_`) = 1) 
    THEN
    UPDATE `admin` SET
    `avatar` = `avatar_`
    WHERE `user_name` = `user_name_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_admin_with_pw`;;
CREATE PROCEDURE `edit_admin_with_pw`(IN `uname` varchar(120), IN `pword` varchar(120), IN `fname` varchar(120), IN `mname` varchar(120), IN `lname` varchar(120), IN `bday` date, IN `sex_` varchar(10), IN `contact_number_` varchar(20), IN `email_` varchar(120), IN `pos` varchar(120), IN `ans1` varchar(100), IN `ans2` varchar(100), IN `tempopw` varchar(120), IN `uid` varchar(120))
UPDATE `admin` SET 
user_name = uname,
password = MD5(pword),
first_name = fname,
middle_name = mname,
last_name = lname,
birth_date = bday,
sex = sex_,
contact_number = contact_number_,
email = email_,
position = pos,
answer1 = ans1,
answer2 = ans2,
temporary_password = pword
WHERE id = uid;;

DROP PROCEDURE IF EXISTS `edit_company`;;
CREATE PROCEDURE `edit_company`(IN `company_tin_` varchar(20), IN `company_name_` varchar(250), IN `branch_` varchar(120), IN `business_type_` varchar(120), IN `company_id_` int(20), OUT `msg` varchar(60))
BEGIN
  IF( (SELECT count(*) FROM `company` c WHERE c.`id` = `company_id_`) = 1)
  THEN
    UPDATE `company`
      SET 
      `company_tin` = `company_tin_`,
      `company_name` = `company_name_`,
      `branch` = `branch_`,
      `business_type` = `business_type_`
      WHERE `id` = `company_id_`;
    SET msg = "1"; -- company exists
  ELSE 
    SET msg = "0"; -- company exists
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_company_address`;;
CREATE PROCEDURE `edit_company_address`(IN `add1_` varchar(120), IN `add2_` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120), IN `is_active_` varchar(120), IN `id_` int(11), IN `company_id_` varchar(120), OUT `msg` varchar(120))
BEGIN
  IF( (SELECT count(*) FROM `company` c
                    WHERE c.`id` = `company_id_`) = 1)
    THEN
    IF( (SELECT count(*) FROM `address` a
      INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
      WHERE ajt.`company_id` = `company_id_` AND ajt.`address_id` = `id_`) = 1 )
    THEN
      UPDATE `address` a
      INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
      SET `address1` = `add1_`,
        `address2` = `add2_`,
        `city` = `city_`,
        `province` = `province_`,
        `is_active` = '1',
        `last_update` = now()
      WHERE a.`id` = `id_`
      AND  ajt.`company_id` = `company_id_`;
      SET msg = "2";  -- company exist AND address exist
    ELSE
      SET msg = "1"; -- company exist but address doesnt
    END IF;
  ELSE 
    SET msg = "0"; -- company doesnt exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_company_logo`;;
CREATE PROCEDURE `edit_company_logo`(IN `company_tin_` varchar(60), IN `logo_` varchar(60), OUT `msg` int(10))
BEGIN
  IF( (SELECT count(*) FROM `company` WHERE `company_tin` = `company_tin_`) = 1) 
    THEN
    UPDATE `company` SET
    `logo` = `logo_`
    WHERE `company_tin` = `company_tin_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_guardian`;;
CREATE PROCEDURE `edit_guardian`(IN `g_id_` int(20), IN `osca_id_` varchar(20), IN `first_name_` varchar(120), IN `middle_name_` varchar(120), IN `last_name_` varchar(120), IN `sex_` varchar(120), IN `relationship_` varchar(120), IN `contact_number_` varchar(120), IN `email_` varchar(120), OUT `msg` varchar(120))
BEGIN
  IF(
  (SELECT count(*) FROM `member` m
                    INNER JOIN `guardian` g ON g.member_id = m.id
                    WHERE m.`osca_id` = `osca_id_` AND g.`id` = `g_id_`) 
  = 1) THEN
      UPDATE `guardian` g
      INNER JOIN `member` m ON g.`member_id` = m.id
      SET 
      g.`first_name` = `first_name_`,
      g.`middle_name` = `middle_name_`,
      g.`last_name` = `last_name_`,
      g.`sex` = `sex_`,
      g.`relationship` = `relationship_`,
      g.`contact_number` = `contact_number_`,
      g.`email` = `email_`
      WHERE g.`id` = `g_id_`;
      SET msg = "1";
    ELSE 
      SET msg = "0";
    END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_lost_report`;;
CREATE PROCEDURE `edit_lost_report`(IN `lost_id_` varchar(20), IN `osca_id_` varchar(20), IN `desc_` varchar(120), IN `nfc_active_` INT(1), IN `account_enabled_` INT(1), OUT `msg` int(1))
BEGIN
  IF ((SELECT COUNT(*)
    FROM `member` AS m
    WHERE m.id = osca_id_) = 1)
  THEN
    UPDATE lost_report 
    SET `desc` = `desc_`
    WHERE `id` = `lost_id_`;
    
    UPDATE `member` SET
    `nfc_active` = `nfc_active_`,
    `account_enabled` = `account_enabled_`
    WHERE `id` = `osca_id_`;
    
    SET msg = 1;
  ELSE
  
    SET msg = 0;
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_member_address`;;
CREATE PROCEDURE `edit_member_address`(IN `add1_` varchar(120), IN `add2_` varchar(120), IN `city_` varchar(120), IN `province_` varchar(120), IN `is_active_` varchar(120), IN `id_` int(11), IN `member_id_` varchar(120), OUT `msg` varchar(120))
BEGIN
  IF( (SELECT count(*) FROM `member` m
                    WHERE `id` = `member_id_`) = 1)
  THEN 
    IF(( SELECT count(*) FROM `address` a
      INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
      WHERE ajt.`member_id` = `member_id_` AND ajt.`address_id` = `id_`) = 1)
    THEN
      IF (`is_active_` = 1)
      THEN
        UPDATE `address` a
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
        SET `is_active` = 0
        WHERE ajt.`member_id` = `member_id_`;
      ELSE 
        SET msg = "1";
      END IF;
      UPDATE `address` a
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
        SET 
        `address1` = `add1_`,
        `address2` = `add2_`,
        `city` = `city_`,
        `province` = `province_`,
        `is_active` = `is_active_`,
        `last_update` = now()
        WHERE a.`id` = `id_`
        AND  ajt.`member_id` = `member_id_`;
        SET msg = "2"; -- Address AND User exists. Successfully updated
    ELSE
      SET msg = "1"; -- Address for user does not exist
    END IF;
  ELSE
    SET msg = "0"; -- user des not exist
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_member_no_pw`;;
CREATE PROCEDURE `edit_member_no_pw`(IN `oid` varchar(20), IN `nserial` varchar(45), IN `fname` varchar(120), IN `mname` varchar(120), IN `lname` varchar(120), IN `bday` date, IN `cnumber` varchar(20), IN `email_` varchar(120), IN `sex_` varchar(10), IN `mdate` timestamp, IN `uid` varchar(120))
UPDATE `member` SET 
osca_id= oid,
nfc_serial= nserial,
first_name = fname,
middle_name = mname,
last_name = lname,
birth_date = bday,
sex = sex_,
contact_number = cnumber,
email = email_,
membership_date = mdate
WHERE id = uid;;

DROP PROCEDURE IF EXISTS `edit_member_picture`;;
CREATE PROCEDURE `edit_member_picture`(IN `osca_id_` varchar(60), IN `picture_` varchar(60), OUT `msg` int(10))
BEGIN
  IF( (SELECT count(*) FROM `member` WHERE `osca_id` = `osca_id_`) = 1) 
    THEN
    UPDATE `member` SET
    `picture` = `picture_`
    WHERE `osca_id` = `osca_id_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `edit_member_with_pw`;;
CREATE PROCEDURE `edit_member_with_pw`(IN `oid` varchar(20), IN `nserial` varchar(45), IN `pword` varchar(60), IN `fname` varchar(120), IN `mname` varchar(120), IN `lname` varchar(120), IN `bday` date, IN `cnumber` varchar(20), IN `email_` varchar(120), IN `sex_` varchar(10), IN `mdate` timestamp, IN `uid` varchar(120))
UPDATE `member` SET 
osca_id= oid,
nfc_serial= nserial,
password=MD5(MD5(MD5(pword))),
first_name = fname,
middle_name = mname,
last_name = lname,
birth_date = bday,
sex = sex_,
contact_number = cnumber,
email = email_,
membership_date = mdate
WHERE id = uid;;

DROP PROCEDURE IF EXISTS `fetch_food_transactions`;;
CREATE PROCEDURE `fetch_food_transactions`(IN `osca_id_` varchar(20))
BEGIN
  SELECT `trans_date`, `company_name`, `branch`, `business_type`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`
  FROM `view_food_transactions`
  WHERE `osca_id` = `osca_id_`;
END;;

DROP PROCEDURE IF EXISTS `fetch_password`;;
CREATE PROCEDURE `fetch_password`(IN `osca_id_` varchar(20))
BEGIN
  SELECT
    `osca_id`, `password`, `contact_number`
  FROM `view_members_with_guardian`
  WHERE `osca_id` = `osca_id_`
  AND  `a_is_active` = 1;
END;;

DROP PROCEDURE IF EXISTS `fetch_pharma_transactions`;;
CREATE PROCEDURE `fetch_pharma_transactions`(IN `osca_id` varchar(20))
BEGIN
  SELECT
      `view_pharma_transactions`.trans_date,
      `view_pharma_transactions`.company_name,
      `view_pharma_transactions`.branch,
      `view_pharma_transactions`.business_type,
            concat(`view_pharma_transactions`.generic_name, '\n' , `view_pharma_transactions`.brand, '\n',
                `view_pharma_transactions`.dose, '\n', `view_pharma_transactions`.unit, '\n',
          `view_pharma_transactions`.quantity, '\n', `view_pharma_transactions`.unit_price) AS `desc`,
      `view_pharma_transactions`.vat_exempt_price,
      `view_pharma_transactions`.discount_price,
      `view_pharma_transactions`.payable_price
    FROM
      `view_pharma_transactions`
    WHERE
      `view_pharma_transactions`.osca_id = osca_id;
END;;

DROP PROCEDURE IF EXISTS `fetch_transportation_transactions`;;
CREATE PROCEDURE `fetch_transportation_transactions`(IN `osca_id` varchar(20))
BEGIN
  SELECT
      `view_transportation_transactions`.trans_date,
      `view_transportation_transactions`.company_name,
      `view_transportation_transactions`.branch,
      `view_transportation_transactions`.business_type,
      `view_transportation_transactions`.`desc`,
      `view_transportation_transactions`.vat_exempt_price,
      `view_transportation_transactions`.discount_price,
      `view_transportation_transactions`.payable_price
  FROM
    `view_transportation_transactions`
  WHERE
    `view_transportation_transactions`.osca_id = osca_id;
END;;

DROP PROCEDURE IF EXISTS `forgot_pw_admin`;;
CREATE PROCEDURE `forgot_pw_admin`(IN `uname` varchar(120), IN `ans1` varchar(100), IN `ans2` varchar(100), OUT `tempopw` varchar(6), OUT `msg` int(10))
IF ((SELECT EXISTS(SELECT * FROM `admin` WHERE (user_name = uname AND answer1 = ans1) OR (user_name = uname AND answer2=ans2))) = 1)
THEN
  SET `tempopw`= (SELECT substring(MD5(RAND()), -6));
  UPDATE `admin` SET `password`=MD5(`tempopw`), `temporary_password`=(`tempopw`), is_enabled = 1, log_attempts=0 WHERE `user_name`=`uname`;
  SET msg = 1;
ELSE
  SET msg = 0;
END IF;;

DROP PROCEDURE IF EXISTS `invalid_login`;;
CREATE PROCEDURE `invalid_login`(IN `uname` varchar(20))
BEGIN
  DECLARE SELECTed_id INT(8);
  SET SELECTed_id = (SELECT id FROM `admin` WHERE `user_name`=uname);
  UPDATE `admin` SET log_attempts = log_attempts + 1 WHERE `id` = SELECTed_id;
  IF (SELECT log_attempts FROM admin WHERE `user_name`=uname) > 2
  THEN UPDATE admin SET is_enabled = 0 WHERE id = SELECTed_id;
  END IF;
END;;

DROP PROCEDURE IF EXISTS `login_member`;;
CREATE PROCEDURE `login_member`(IN `osca_id_` varchar(120), IN `password_` varchar(120))
BEGIN
  SELECT
    `osca_id`, `password`, `picture`,
    `bdate`, `sex`, `memship_date`, `contact_number`,
    CONCAT(`first_name`, ' ', `last_name`) AS `full_name`,
    CONCAT(`address_1`, ' ', `address_2`, ' ', `city`, ' ', `province`) AS address,
    `nfc_active`, `account_enabled`
  FROM
    `view_members_with_guardian`
  WHERE
    `osca_id` = `osca_id_`
    AND `password` = md5(`password_`)
    AND `a_is_active` = 1
    AND `account_enabled` = 1;
END;;

DROP PROCEDURE IF EXISTS `toggle_admin_acct`;;
CREATE PROCEDURE `toggle_admin_acct`(IN `user_name_` varchar(60), OUT `msg` int(1))
BEGIN
  IF( (SELECT count(*) FROM `admin` WHERE `user_name` = `user_name_`) = 1) 
  THEN
    IF((SELECT `is_enabled` FROM `admin` WHERE `user_name` = `user_name_`) = 1)
    THEN
      UPDATE `admin` SET
      `is_enabled` = 0,
      `log_attempts` = 0
      WHERE `user_name` = `user_name_`;
    ELSE
      UPDATE `admin` SET
      `is_enabled` = 1,
      `log_attempts` = 0
      WHERE `user_name` = `user_name_`;
    END IF;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `toggle_company_acct`;;
CREATE PROCEDURE `toggle_company_acct`(IN `company_id_` int(20), OUT `msg` int(1))
BEGIN
  IF( (SELECT count(*) FROM `company_accounts` WHERE `company_id` = `company_id_`) = 1) 
  THEN
    IF((SELECT `is_enabled` FROM `company_accounts` WHERE `company_id` = `company_id_`) = 1)
    THEN
      UPDATE `company_accounts` SET
      `is_enabled` = 0,
      `log_attempts` = 0
      WHERE `company_id` = `company_id_`;
    ELSE
      UPDATE `company_accounts` SET
      `is_enabled` = 1,
      `log_attempts` = 0
      WHERE `company_id` = `company_id_`;
    END IF;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `toggle_member_acct`;;
CREATE PROCEDURE `toggle_member_acct`(IN `id_` varchar(60), OUT `msg` int(1))
BEGIN
  IF( (SELECT count(*) FROM `member` WHERE `id` = `id_`) = 1) 
  THEN
    IF((SELECT `account_enabled` FROM `member` WHERE `id` = `id_`) = 1)
    THEN
      UPDATE `member` SET
      `account_enabled` = 0
      WHERE `id` = `id_`;
    ELSE
      UPDATE `member` SET
      `account_enabled` = 1
      WHERE `id` = `id_`;
    END IF;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `toggle_member_nfc`;;
CREATE PROCEDURE `toggle_member_nfc`(IN `id_` varchar(60), OUT `msg` int(1))
BEGIN
  IF( (SELECT count(*) FROM `member` WHERE `id` = `id_`) = 1) 
  THEN
    IF((SELECT `nfc_active` FROM `member` WHERE `id` = `id_`) = 1)
    THEN
      UPDATE `member` SET
      `nfc_active` = 0
      WHERE `id` = `id_`;
    ELSE
      UPDATE `member` SET
      `nfc_active` = 1
      WHERE `id` = `id_`;
    END IF;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END;;

DROP PROCEDURE IF EXISTS `validate_login`;;
CREATE PROCEDURE `validate_login`(IN `osca_id` varchar(20), IN `password` VARCHAR(120))
BEGIN
  SELECT user.osca_id, user.password, concat(first_name, " ", middle_name, " ", last_name) as full_name, user.birth_date, user.sex, user.membership_date, user.avatar, concat(address1, " ", address2, ", " , city, ", ", province) as address
  FROM user
  RIGHT JOIN address
  ON user.address_id = address.id
  WHERE user.osca_id = osca_id AND user.password = password;
END;;

DELIMITER ;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `address1` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `address2` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `province` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `is_active` int(11) NOT NULL,
  `last_update` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `address` (`id`, `address1`, `address2`, `city`, `province`, `is_active`, `last_update`) VALUES
(1,	'2129 Culdesac Rd Edison St',	'Brgy. Sun Valley',	'Paranaque',	'Ncr, Fourth District',	1,	'2020-09-25 07:47:50'),
(2,	'L23 Villa Antonina Subd',	'Brgy. San Nicolas 2',	'Bacoor City',	'Cavite',	1,	'2020-09-25 07:37:01'),
(3,	'Blk25 lot41 Milkwort St Ph3 Villa de Primarosa',	'Brgy. Mambog 3',	'Bacoor City',	'Cavite',	1,	'2020-09-25 07:36:49'),
(4,	'3009, Ipil st.',	'Brgy Banaba',	'Silang',	'Cavite',	0,	'2020-09-25 07:38:06'),
(5,	'0235 Rafael St., Villa Modena',	'Villagio Ignatius Subd., Brgy. Buenavista III',	'General Trias',	'Cavite',	0,	'2020-08-22 20:37:57'),
(6,	'2099 Culdesac Rd Edison St',	'Brgy. Sun Valley',	'Paranaque',	'Ncr, Fourth District',	1,	'2020-09-25 07:48:23'),
(7,	'5636 Rafael St.',	'Brgy. Manggahan',	'General Trias',	'Cavite',	1,	'2020-09-11 22:21:34'),
(8,	'1001 Sant St.',	'Brgy Maybuhay',	'Makati',	'Ncr, Fourth District',	1,	'2020-09-25 07:59:17'),
(9,	'0925 Remedios St.',	'Brgy 601',	'Malate',	'City Of Manila',	1,	'2020-09-25 07:45:21'),
(10,	'1235 Phase 5 Pili St.',	'Brgy. Anahaw',	'Silang',	'Cavite',	1,	'2020-09-11 22:23:51'),
(11,	'Land of',	'Dawn',	'Nasugbu',	'Batangas',	1,	'2020-09-11 22:39:33'),
(12,	'9287 Riverdale St.',	'Riverdale Subdivision, Brgy. Kasulukan',	'Paniqui',	'Tarlac',	1,	'2020-09-01 20:47:30'),
(14,	'Glass Manor',	'Brgy. Ibabaw Del Sur',	'Paete',	'Laguna',	1,	'2020-09-01 20:33:04'),
(15,	'2548, Nakpil St.',	'Brgy. Reezal, Tamagochi Village',	'Marilao',	'Bulacan',	1,	'2020-09-03 15:53:52'),
(16,	'3180 Zobel St.',	'Bukid',	'Malate',	'City Of Manila',	1,	'2020-09-25 07:47:03'),
(17,	'0028 Merger St.',	'Louiseville',	'Batangas',	'Batangas',	1,	'2020-08-24 11:32:03'),
(20,	'Walter Mart',	'Mc Arthur Highway ',	'Guiguinto',	'Bulacan',	1,	'2020-09-04 09:32:20'),
(23,	'4F Right Wing',	'Farmers Plaza Cubao',	'Quezon City',	'Ncr, Second District',	1,	'2020-09-11 22:03:35'),
(24,	'Grounds',	'Mall of Asia',	'Pasay City',	'Ncr, Fourth District',	1,	'2020-09-25 13:18:02'),
(25,	'2002 Kabihasnan St.',	'Gen. Luna',	'Ermita',	'Ncr, City Of Manila, First District',	1,	'2020-09-25 13:17:36'),
(26,	'Portal Mall GF',	'Brgy. San Gabriel II',	'General Mariano Alvarez',	'Cavite',	0,	'2020-09-02 18:31:38'),
(27,	'Taft Ave. cor. Quirino St',	'Brgy 6969',	'Malate',	'Ncr, City Of Manila, First District',	0,	'2020-09-11 21:42:18'),
(32,	'Somewhere',	'Out there',	'Quezon City',	'Ncr, Second District',	1,	'2020-09-27 11:14:51'),
(34,	'Hidalgo St., Quiapo',	'Raon',	'Quiapo',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:11:13'),
(35,	'0295 Mokmok St.',	'Sangandaan',	'Quezon City',	'Ncr, Second District',	0,	'2020-09-25 13:12:32'),
(36,	'Kamuning Rd',	'Hi-Top Supermarket',	'Quezon City',	'Ncr, Second District',	0,	'2020-09-25 13:14:08'),
(37,	'Manila Bay',	'Quirino Ave.',	'Malate',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:14:34'),
(38,	'Tandang Sora',	'Commonwealth Ave',	'Quezon City',	'Ncr, Second District',	1,	'2020-09-25 13:15:05'),
(39,	'Terminal 3',	'NAIA',	'Pasay City',	'Ncr, Fourth District',	0,	'2020-09-25 13:16:14'),
(40,	'Chino Roces Avenue corner',	'EDSA',	'Makati',	'Ncr, Fourth District',	0,	'2020-09-25 13:17:07'),
(41,	'Market Market',	'BGC Complex',	'Taguig City',	'Ncr, Second District',	0,	'2020-09-25 13:18:46'),
(42,	'San Marcelino St.',	'SM Manila',	'Ermita',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:19:25'),
(43,	'Montillano ST.',	'Festival Mall',	'Muntinlupa',	'Ncr, Fourth District',	0,	'2020-09-25 13:20:02'),
(44,	'1029 St',	'Quirino Ave',	'Paranaque',	'Ncr, Fourth District',	0,	'2020-09-25 13:20:32'),
(45,	'Alpa',	'Arnaiz Ave',	'Makati',	'Ncr, Fourth District',	0,	'2020-09-25 13:22:11'),
(46,	'2910 Ermita',	'Boni. Ave',	'Mandaluyong',	'Ncr, Second District',	0,	'2020-09-25 13:22:59'),
(47,	'Congressional rd. cor',	'Governor\'s Drive',	'Gen. Mariano Alvarez',	'Cavite',	0,	'2020-09-25 13:23:31'),
(48,	'Waltermart',	'Gov. Dr.',	'General Trias',	'Cavite',	0,	'2020-09-25 13:24:02'),
(49,	'Central Station',	'LRT A',	'Ermita',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:24:47'),
(50,	'Recto Ave cor.',	'Rizal Ave',	'Binondo',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:25:14'),
(51,	'Buendia Ave.',	'Taft. Ave',	'Pasay City',	'Ncr, Fourth District',	0,	'2020-09-25 13:25:44'),
(52,	'Pedro Gil Ave. cor',	'Taft Ave.',	'Malate',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:26:12'),
(53,	'United Nations Station',	'United Nations Station',	'Ermita',	'Ncr, City Of Manila, First District',	0,	'2020-09-25 13:27:53'),
(54,	'Araneta ',	'Cubao',	'Pasig',	'Ncr, Second District',	0,	'2020-09-25 13:28:32'),
(55,	'Station Dr',	'Ayala',	'Makati',	'Ncr, Fourth District',	0,	'2020-09-25 13:29:11'),
(56,	'Cembo Guadalupe 1212',	'Guijo',	'Makati',	'Ncr, Fourth District',	0,	'2020-09-25 13:30:02'),
(57,	'Magallanes',	'Epifanio de los Santos Ave',	'Makati',	'Ncr, Fourth District',	0,	'2020-09-25 13:30:33'),
(58,	'Taft Ave cor',	'Epifanio de los Santos Ave',	'Pasay City',	'Ncr, Fourth District',	0,	'2020-09-25 13:31:23');

DROP TABLE IF EXISTS `address_jt`;
CREATE TABLE `address_jt` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `address_id` int(20) NOT NULL,
  `member_id` int(20) DEFAULT NULL,
  `company_id` int(20) DEFAULT NULL,
  `guardian_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  KEY `member_id` (`member_id`),
  KEY `company_id` (`company_id`),
  KEY `guardian_id` (`guardian_id`),
  CONSTRAINT `address_jt_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_jt_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_jt_guardian` FOREIGN KEY (`guardian_id`) REFERENCES `guardian` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_jt_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `address_jt` (`id`, `address_id`, `member_id`, `company_id`, `guardian_id`) VALUES
(1,	1,	1,	NULL,	NULL),
(2,	2,	3,	NULL,	NULL),
(3,	3,	4,	NULL,	NULL),
(4,	4,	NULL,	NULL,	NULL),
(5,	5,	NULL,	NULL,	NULL),
(6,	6,	5,	NULL,	NULL),
(7,	7,	7,	NULL,	NULL),
(8,	8,	8,	NULL,	NULL),
(9,	9,	6,	NULL,	NULL),
(10,	10,	2,	NULL,	NULL),
(11,	11,	9,	NULL,	NULL),
(12,	12,	10,	NULL,	NULL),
(14,	14,	11,	NULL,	NULL),
(15,	15,	13,	NULL,	NULL),
(16,	16,	12,	NULL,	NULL),
(17,	17,	14,	NULL,	NULL),
(20,	20,	NULL,	31,	NULL),
(21,	23,	NULL,	5,	NULL),
(22,	24,	NULL,	9,	NULL),
(23,	25,	NULL,	1,	NULL),
(24,	26,	NULL,	14,	NULL),
(25,	27,	NULL,	2,	NULL),
(30,	32,	20,	NULL,	NULL),
(32,	34,	NULL,	12,	NULL),
(33,	35,	NULL,	4,	NULL),
(34,	36,	NULL,	8,	NULL),
(35,	37,	NULL,	10,	NULL),
(36,	38,	NULL,	3,	NULL),
(37,	39,	NULL,	7,	NULL),
(38,	40,	NULL,	6,	NULL),
(39,	41,	NULL,	13,	NULL),
(40,	42,	NULL,	17,	NULL),
(41,	43,	NULL,	15,	NULL),
(42,	44,	NULL,	19,	NULL),
(43,	45,	NULL,	18,	NULL),
(44,	46,	NULL,	20,	NULL),
(45,	47,	NULL,	11,	NULL),
(46,	48,	NULL,	16,	NULL),
(47,	49,	NULL,	21,	NULL),
(48,	50,	NULL,	22,	NULL),
(49,	51,	NULL,	24,	NULL),
(50,	52,	NULL,	25,	NULL),
(51,	53,	NULL,	23,	NULL),
(52,	54,	NULL,	29,	NULL),
(53,	55,	NULL,	30,	NULL),
(54,	56,	NULL,	27,	NULL),
(55,	57,	NULL,	28,	NULL),
(56,	58,	NULL,	26,	NULL);

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `first_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `middle_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `birth_date` date NOT NULL,
  `sex` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `position` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `is_enabled` int(10) NOT NULL,
  `log_attempts` int(10) NOT NULL,
  `answer1` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `answer2` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `temporary_password` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `avatar` varchar(250) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `admin` (`id`, `user_name`, `password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `position`, `contact_number`, `email`, `is_enabled`, `log_attempts`, `answer1`, `answer2`, `temporary_password`, `avatar`) VALUES
(1,	'ralf',	'3cca634013591eb51173fb6207572e37',	'Ralph Christian',	'Arbiol',	'Ortiz',	'1990-01-14',	'1',	'admin',	'07283754',	'ralph.ortiz@ymeal.com',	1,	1,	'ralp',	'orti',	'ralfralf',	'inuho1wjbk.png'),
(2,	'hstn',	'fc29f6ea32a347d55bd690c5d11ed8e3',	'Justine',	'Ildefonso',	'Laserna',	'1990-01-25',	'1',	'admin',	'86554553',	'justine.laserna@ymeal.com',	1,	0,	'hustino',	'hustino',	'b18340',	'c4ef6d230c396efc.png'),
(3,	'matt',	'ce86d7d02a229acfaca4b63f01a1171b',	'Matthew Franz',	'Castro',	'Vasquez',	'1990-01-15',	'1',	'admin',	'09123654123',	'matthew.vasquez@ymeal.com',	1,	0,	'matt',	'vasqa',	'matt',	'1dngb3owoz.png'),
(4,	'fred',	'2697359d57024a8f41301b0332a8ba39',	'Frederick Allain',	'',	'Dela Cruz',	'1990-01-01',	'1',	'admin',	'09123456789',	'frederick.dela.cruz@ymeal.com',	1,	0,	'fred',	'lain',	'fredfred',	'izkue0sbn0.png'),
(5,	'alycheese',	'6230471bd10839658f414438bc33c88a',	'Aly',	'x',	'Cheese',	'1990-11-11',	'2',	'user',	'09654123789',	'cyrel.lalikan@ymeal.com',	1,	0,	'swan',	'song',	'',	'k1ylycon4h.png'),
(6,	'shang',	'8379c86250c50c0537999a6576e18aa7',	'Jess',	'',	'Monty',	'1990-01-24',	'1',	'user',	'76567752',	'jess.monty@ymeal.com',	1,	2,	'shang',	'shang',	'4347da',	'py1c2qjcpq.png'),
(7,	'synth',	'4b418ed51830f54c3f9af6262b2201d2',	'synth',	'synth',	'synth',	'1980-08-19',	'2',	'user',	'96493119',	'synth.synth@ymeal.com',	1,	0,	'synth',	'synth',	'synthsynth',	'bde5a3192a556564.png'),
(8,	'dsfaasdgasdg',	'd15fd399edbb0b84811b7d18378692a3',	'asdg',	'asd',	'asdgasdgasdg',	'2019-08-26',	'2',	'admin',	'09123987456',	'6541234@asd.zxc',	0,	0,	'sdfgsdfgsdf',	'sdfgsdfg',	'dsfadgasdg',	'45a3f40a62f2cfef.png');

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `company_tin` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `company_name` varchar(250) COLLATE utf8mb4_bin NOT NULL,
  `branch` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `business_type` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `logo` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_tin_UNIQUE` (`company_tin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `company` (`id`, `company_tin`, `company_name`, `branch`, `business_type`, `logo`) VALUES
(1,	'654132150',	'Hustino Pansiteria',	'Gen. Luna, Ermita',	'food',	'pansiteria.png'),
(2,	'123654789',	'Affy Kapihan',	'Malate, Manila',	'food',	'kapihan.png'),
(3,	'5514917468',	'Dela Cruz Tea House',	'Tandang Sora, Commonwealth',	'food',	'tihaws.png'),
(4,	'7752487534',	'Dela Cruz Tea House',	'Sangandaan, Quezon City',	'food',	'tihaws.png'),
(5,	'1239874563',	'Vasquez Grill',	'Farmer\'s Plaza',	'food',	'grill.png'),
(6,	'1550792273',	'Affy Kapihan',	'Alphaland Southgate Towers',	'food',	'kapihan.png'),
(7,	'6070323661',	'Vasquez Grill',	'T3 - NAIA',	'food',	'grill.png'),
(8,	'5200487589',	'Hustino Pansiteria',	'Hi-Top Supermarket, Aurora Blvd.',	'food',	'pansiteria.png'),
(9,	'123498742',	'Affy Kapihan',	'Mall of Asia',	'food',	'kapihan.png'),
(10,	'3411927270',	'Dela Cruz Tea House',	'Malate, Manila',	'food',	'tihaws.png'),
(11,	'6171762815',	'Janus Botika',	'GMA, Cavite',	'pharmacy',	'janus.png'),
(12,	'4632180231',	'Janus Botika',	'Hidalgo St., Quiapo',	'pharmacy',	'janus.png'),
(13,	'3591867572',	'Janus Botika',	'BGC Market Market',	'pharmacy',	'janus.png'),
(14,	'9871234562',	'Kairos Pharmacy',	'Portal, GMA, Cavite',	'pharmacy',	'kairos.png'),
(15,	'8323848273',	'Kairos Pharmacy',	'Montillano St., Alabang',	'pharmacy',	'kairos.png'),
(16,	'7965766163',	'Kairos Pharmacy',	'Waltermart, Gen. Trias',	'pharmacy',	'kairos.png'),
(17,	'5821645777',	'Kairos Pharmacy',	'SM Manila',	'pharmacy',	'kairos.png'),
(18,	'8690961165',	'Belerick Pharmacy',	'Arnaiz Ave., Makati',	'pharmacy',	'belerick.png'),
(19,	'3983278667',	'Belerick Pharmacy',	'Quirino Ave, Paranaque',	'pharmacy',	'belerick.png'),
(20,	'6344364475',	'Belerick Pharmacy',	'Boni Ave., Mandaluyong',	'pharmacy',	'belerick.png'),
(21,	'8016618868',	'NRT',	'Central Station',	'transportation',	'nrta.jpg'),
(22,	'9690147404',	'NRT',	'Doroteo Jose Station',	'transportation',	'nrta.jpg'),
(23,	'2035775056',	'NRT',	'United Nations Station',	'transportation',	'nrta.jpg'),
(24,	'8063147891',	'NRT',	'Gil Puyat Station',	'transportation',	'nrta.jpg'),
(25,	'2019924979',	'NRT',	'Pedro Gil Station',	'transportation',	'nrta.jpg'),
(26,	'2949532037',	'NRT',	'Taft Station',	'transportation',	'nrta.jpg'),
(27,	'5100616960',	'NRT',	'Guadalupe Station',	'transportation',	'nrta.jpg'),
(28,	'7282645530',	'NRT',	'Magallanes Station',	'transportation',	'nrta.jpg'),
(29,	'3115335081',	'NRT',	'Araneta - Cubao Station',	'transportation',	'nrta.jpg'),
(30,	'9271004158',	'NRT',	'Ayala Station',	'transportation',	'nrta.jpg'),
(31,	'6545671280',	'Janus Botika',	'Guiguinto, Bulacan',	'pharmacy',	'janus.png');

DROP TABLE IF EXISTS `company_accounts`;
CREATE TABLE `company_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `company_id` int(20) NOT NULL,
  `is_enabled` int(10) NOT NULL,
  `log_attempts` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `company_accounts_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `company_accounts` (`id`, `user_name`, `password`, `company_id`, `is_enabled`, `log_attempts`) VALUES
(1,	'hp_genluna',	'5b13b321008e31191a77925d78f1692b',	1,	0,	7),
(2,	'ak_malate',	'44eba6203a4937cafc12e28916531072',	2,	1,	0),
(3,	'dt_tsora',	'fdf6bb562f2f2a0601f73b1ece263196',	3,	1,	0),
(4,	'dt_sangandaan',	'bec18c3338ea9cb96a1cbb336ddd46c4',	4,	1,	0),
(5,	'vg_farmers',	'3851716b76a0cdcaed1916abad2996fd',	5,	1,	0),
(6,	'ak_alphaland',	'daaa7c05892abddf97c9af500433148f',	6,	1,	0),
(7,	'vg_naia',	'1581e5e3cf1a284c4ec1dda71d658295',	7,	1,	0),
(8,	'hp_hitop',	'bcbdfa49185ff67639af0e6d2566c9a7',	8,	1,	0),
(9,	'ak_moa1',	'94d12759947a89a116b4ab1659946c8f',	9,	1,	0),
(10,	'dt_malate',	'ed2253c10959382224c86d1431dcec1d',	10,	1,	0),
(11,	'janus_gma',	'f88b8cd008184e16838cbf734e7db092',	11,	1,	0),
(12,	'janus_hidalgo',	'f55050f9062e343f57c9767d0f8f186d',	12,	1,	0),
(13,	'janus_bgc',	'fbf08970e2303693874c87e46bc95f01',	13,	1,	0),
(14,	'kairos_portal',	'9ba846602b829847a7fd98e2fdfa0c8d',	14,	1,	0),
(15,	'kairos_montillano',	'98483147e9bbd3043d12284160de1e3e',	15,	1,	0),
(16,	'kairos_wmgentri',	'58ac0164a8ac96c6787b7b5a9ba70eb2',	16,	1,	0),
(17,	'kairos_smmanila',	'9a62118115a1d0f5d4adf5ed34c90529',	17,	1,	0),
(18,	'bel_arnaiz',	'49fb4883b0e83d7f6bbd93e66b4137a2',	18,	1,	0),
(19,	'bel_quirino',	'86ff35a5eedc434eabc792b8ec4e4b5b',	19,	1,	0),
(20,	'bel_boni',	'e84169605d796596dfd60c17459f1834',	20,	1,	0),
(21,	'nrt_central',	'0e2cf5c06ba97b0ec78f567904610685',	21,	1,	0),
(22,	'nrt_djose',	'4986918f89ac5594afb37e197f5197c8',	22,	1,	0),
(23,	'nrt_un',	'1d1dfb01a0e8dc34fdb0e0fda11a6288',	23,	1,	0),
(24,	'nrt_gp',	'1d29cbee55eb925cffa0c310d9f25214',	24,	1,	0),
(25,	'nrt_pg',	'e203007a1eaad2334a01e90a2bff28c7',	25,	1,	0),
(26,	'nrt_taft',	'784ef201fbf7688685f2a191dde000bd',	26,	1,	0),
(27,	'nrt_guada',	'ee5cb63b42572d2112af6afaba286884',	27,	1,	0),
(28,	'nrt_maga',	'ac736b6d6d947f995e8b6c879dcdf188',	28,	1,	0),
(29,	'nrt_araneta',	'25638ddb0af4ab1bab06a35a5de6ad38',	29,	1,	0),
(30,	'nrt_ayala',	'3fdfdc3a36cfaef950b79d917efbd03b',	30,	1,	0),
(31,	'janus_guiguinto',	'9f76f9e1a8aecd1b6540f461ab39c97f',	31,	1,	0);

DROP TABLE IF EXISTS `complaint_report`;
CREATE TABLE `complaint_report` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `desc` varchar(300) COLLATE utf8mb4_bin DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `company_id` int(20) DEFAULT NULL,
  `member_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `complaint_report_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`),
  CONSTRAINT `complaint_report_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `complaint_report` (`id`, `desc`, `report_date`, `company_id`, `member_id`) VALUES
(2,	'Epal nung bantay',	'2020-08-27 06:46:30',	1,	2),
(3,	'No discount given',	'2020-08-27 06:46:30',	11,	3);

DROP TABLE IF EXISTS `drug`;
CREATE TABLE `drug` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `generic_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `brand` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `dose` int(20) NOT NULL,
  `unit` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `is_otc` int(10) NOT NULL,
  `max_monthly` int(20) DEFAULT NULL,
  `max_weekly` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `drug` (`id`, `generic_name`, `brand`, `dose`, `unit`, `is_otc`, `max_monthly`, `max_weekly`) VALUES
(1,	'paracetamol',	'biogesic',	500,	'mg',	1,	45000,	12000),
(2,	'paracetamol',	'bioflu',	500,	'mg',	1,	45000,	13500),
(3,	'ibuprofen,paracetamol',	'alaxan',	500,	'mg',	1,	45000,	14000),
(4,	'diphenhydramine',	'benadryl',	500,	'mg',	1,	45000,	21000),
(5,	'loratidine',	'claritin ',	500,	'mg',	1,	45000,	21000),
(6,	'calcium carbonate,famotidine,magnesium hydroxide',	'kremil-s advance',	500,	'mg',	0,	45000,	21000),
(7,	'cetirizine',	'watsons',	10,	'mg',	1,	70,	300),
(8,	'cetirizine',	'virlix',	10,	'mg',	1,	70,	300),
(9,	'carbocisteine,zinc',	'solmux',	500,	'mg',	1,	7000,	30000),
(10,	'sodium ascorbate,zinc',	'immunpro',	500,	'mg',	1,	7000,	30000),
(11,	'aa,sodium ascorbate',	'immunpro',	500,	'mg',	1,	30000,	7000),
(12,	'sodium ascorbate,zincx',	'immunpro',	500,	'mg',	1,	7000,	30000);

DROP TABLE IF EXISTS `food`;
CREATE TABLE `food` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(20) NOT NULL,
  `desc` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `vat_exempt_price` decimal(13,2) NOT NULL,
  `discount_price` decimal(13,2) NOT NULL,
  `payable_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `food_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `food` (`id`, `transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1,	7,	'meals for 2',	100.00,	20.00,	80.00),
(2,	8,	'meals',	100.00,	20.00,	80.00),
(3,	9,	'meals for 2',	100.00,	20.00,	80.00),
(4,	10,	'meals',	100.00,	20.00,	80.00),
(5,	11,	'meals',	100.00,	20.00,	80.00),
(6,	12,	'meals',	100.00,	20.00,	80.00),
(7,	38,	'Deiri melk 3s',	200.00,	40.00,	160.00),
(8,	38,	'Gardenko 6s',	1000.00,	200.00,	800.00),
(9,	38,	'Neskopi 11-in-1',	500.00,	100.00,	400.00),
(10,	66,	'Dine in meals for 2',	1227.68,	245.54,	982.14),
(11,	73,	'Meals for 3',	892.86,	178.57,	714.29),
(12,	78,	'1 King Lasagna 2 Pizza',	799.11,	159.82,	639.29);

DROP TABLE IF EXISTS `guardian`;
CREATE TABLE `guardian` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `middle_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `sex` varchar(10) COLLATE utf8mb4_bin NOT NULL DEFAULT '0',
  `relationship` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `member_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `guardian_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `guardian` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `relationship`, `contact_number`, `email`, `member_id`) VALUES
(1,	'Gary',	'Jenelle',	'Winton',	'1',	'Grandfather',	'0256890796',	'garywinton9@gmeal.com',	1),
(2,	'Nonie',	'',	'Irene',	'2',	'Grandmother',	'09123654789',	'nonieirene20@gmeal.com',	2),
(3,	'Kelsey',	'Eulalia',	'Diamond',	'1',	'Grandfather',	'0238471073',	'kelseydiamond9@gmeal.com',	3),
(4,	'Galen',	'Avah',	'Kirby',	'2',	'Grandmother',	'0211549317',	'galenkirby7@gmeal.com',	4),
(5,	'Kristine',	'Marcy',	'Charissa',	'1',	'Grandfather',	'0228048410',	'kristinecharissa17@gmeal.com',	5),
(6,	'Cheyanne',	'Paulette',	'Jaylee',	'1',	'Grandfather',	'0266301227',	'cheyannejaylee9@gmeal.com',	6),
(7,	'Avalon',	'Brynlee',	'Aspen',	'1',	'Grandfather',	'09123654654',	'avalonaspen19@gmeal.com',	7),
(8,	'Vianne',	'Kassidy',	'Ursula',	'2',	'Grandmother',	'0214578421',	'vianneursula9@gmeal.com',	8),
(9,	'Rayzer',	'Kentt',	'Carran',	'2',	'Grandmother',	'0274763308',	'raycarran7@gmeal.com',	9),
(10,	'Pamella',	'Russel',	'Corey',	'2',	'Grandmother',	'0288296230',	'pamellacorey1@gmeal.com',	10),
(11,	'Lacy',	'Bekki',	'Marcy',	'1',	'Grandfather',	'0277137953',	'lacymarcy18@gmeal.com',	11),
(12,	'Love',	'Demelzaxx',	'Paulette',	'1',	'Grandfather',	'0288759308',	'lovepaulette0@gmeal.com',	12),
(13,	'Goldie',	'Marilynn',	'Vianne',	'1',	'Grandfather',	'0261176212',	'goldievianne13@gmeal.com',	13),
(14,	'Kimson',	'',	'Kingston',	'1',	'Grandfather',	'029384723',	'sdlkfj@meal.ccc',	14),
(23,	'Live',	'News',	'Portal',	'1',	'grandpapa',	'09654666999',	'livenews@yicjpb.csx',	20);

DROP TABLE IF EXISTS `lost_report`;
CREATE TABLE `lost_report` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `desc` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `report_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `member_id` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `lost_report_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `lost_report` (`id`, `desc`, `report_date`, `member_id`) VALUES
(1,	'',	'2020-08-27 19:20:36',	2),
(2,	'',	'2020-08-27 19:28:56',	2),
(3,	'',	'2020-08-27 19:40:41',	2),
(4,	'',	'2020-08-27 19:41:16',	2),
(5,	'',	'2020-08-27 19:43:30',	2),
(6,	'On 09/30/2020 5:40 AM; NFC Activated; Account Activated; ',	'2020-09-29 21:41:00',	5),
(7,	'',	'2020-09-29 21:26:35',	5),
(8,	'',	'2020-09-23 22:10:08',	2),
(9,	'',	'2020-09-24 02:36:35',	2);

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `member_count` int(5) unsigned zerofill DEFAULT NULL,
  `osca_id` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `nfc_serial` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `nfc_active` int(10) DEFAULT '1',
  `password` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `account_enabled` int(10) DEFAULT '1',
  `first_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `middle_name` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `birth_date` date NOT NULL,
  `sex` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `membership_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `picture` varchar(250) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `osca_id_UNIQUE` (`osca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `member` (`id`, `member_count`, `osca_id`, `nfc_serial`, `nfc_active`, `password`, `account_enabled`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `membership_date`, `picture`) VALUES
(1,	00001,	'1376-2000001',	'0415916a',	0,	'757efdfdd2d522485fc7d2abca265f5a',	0,	'Lai',	'Arbiol',	'Girardi',	'1953-06-17',	'2',	'0912-456-7890',	'lai.girardi@ymeal.com',	'2020-09-29 14:01:08',	'6283f9c3b7aefb1f.png'),
(2,	00002,	'0421-2000002',	'040af172',	0,	'5315626f5051ccf7ae91bb13e54df81f',	1,	'Ruby',	'Ildefonso',	'Glass',	'1960-01-25',	'2',	'09123321456',	'ruby.glass@ymeal.com',	'2020-09-29 12:37:14',	'3dfffc5385f89a93.png'),
(3,	00003,	'0421-2000003',	'04e29172',	1,	'bd3fb7aeedec139792338edf6b9e5d77',	1,	'Cordell',	'Castro',	'Broxton',	'1940-06-15',	'1',	'09654123789',	'cordell.broxton@ymeal.com',	'2020-09-29 22:44:20',	'52448de14aa059fb.png'),
(4,	00004,	'0421-2000004',	'046c6d6a',	1,	'b1383705b102fb7e7f09bd3419f15ae8',	1,	'Stephine',	'Gaco',	'Lamagna',	'1932-07-17',	'2',	'0917-325-5200',	'stephine.lamagna@ymeal.com',	'2020-09-29 14:01:20',	'c44a971857566659.png'),
(5,	00005,	'1376-2000005',	'043af50a',	1,	'c105429a85eb404596dea1812efe4f3f',	1,	'Olimpia',	'',	'Ollis',	'1940-01-01',	'2',	'09123654289',	'olimpia.ollis@ymeal.com',	'2020-09-29 21:41:00',	'30286704964f2216.png'),
(6,	00006,	'1339-2000006',	'04d84c72',	1,	'c422a05eb4e88b81e1edce1bdcb1b10d',	1,	'Harriette',	'Flavell',	'Milbourn',	'1945-01-25',	'2',	'09-253-1028',	'harriette.milbourn@ymeal.com',	'2020-09-29 14:02:11',	'db790b1dd0875bf8.png'),
(7,	00007,	'0421-2000007',	'04cc3672',	1,	'bcf19899b934b970cf38180f435ac92b',	1,	'Elise',	'Trump',	'Benjamin',	'1960-02-22',	'1',	'09123456987',	'elise.benjamin@ymeal.com',	'2020-09-29 22:19:35',	'a5e20bf9e82bcbcd.png'),
(8,	00008,	'1376-2000008',	'04df6e72 ',	1,	'08b18de87a0ec3bfda4b71f8cfcf96bd',	1,	'Hermine',	'Bridgman',	'Poirer',	'1990-01-01',	'1',	'0909-123-4567',	'hermine.poirer@ymeal.com',	'2020-09-29 14:02:33',	'724a1268e8e3e80e.png'),
(9,	00009,	'0410-2000009',	'04499172',	1,	'6c51ba20aa60e52ef80ce1cd7ecdec65',	1,	'Khaleed',	'',	'Dawson',	'1900-01-01',	'2',	'12341234',	'khaleed.dawson@ymeal.com',	'2020-09-29 08:56:36',	'599cc2fdde9ba53f.png'),
(10,	00010,	'0369-2000010',	'5678567856785678',	1,	'9ece80d16df210a3565dd6bb8087b635',	1,	'Ernestine',	'Kyle',	'Ayers',	'1960-08-11',	'2',	'56785678',	'ernestine.ayers@ymeal.com',	'2020-09-29 14:00:32',	'488d72933f722163.png'),
(11,	00011,	'0434-2000011',	'12341234asdfasdf',	1,	'a5f2e92c99938d340841bc8ae88fd3e8',	1,	'Noburu',	'Danya',	'Lea',	'1940-08-29',	'2',	'12341234',	'noburu.lea@ymeal.com',	'2020-09-29 14:01:34',	'64705d03e4205626.png'),
(12,	00012,	'1339-2000012',	'2A6B9CE2A46B1DE9',	1,	'03ebc74ab3befe4f8c01ead8c1675c8a',	1,	'Vasanti',	'Elpidio',	'Hippolyte',	'1800-12-25',	'2',	'0279281684',	'vasanti.hippolyte@ymeal.com',	'2020-09-29 08:57:56',	'7c7cc230591ba5dc.png'),
(13,	00013,	'0314-2000013',	'2A6B9CE2A4DB4DE9',	1,	'0433d5db98e86fd7686339b27ace91fe',	1,	'McKenzie ',	'Houston',	'Jessye',	'1948-12-08',	'2',	'0279281684',	'mckenzie.jessye@ymeal.com',	'2020-09-29 08:55:25',	'c74391e2ef0cbfd5.png'),
(14,	00014,	'0410-2000014',	'7890acde7890acde',	1,	'4893a53f8f7e6d89938f539c7a910f12',	1,	'Christian',	'',	'Murphy',	'1958-10-25',	'1',	'0948654123',	'asdsa@aa.afx',	'2020-09-29 08:54:17',	'b423e08f7a5206c6.png'),
(20,	00020,	'1374-2000020',	'9as8d7asg654',	1,	'2228c67e7304709d3405461b427ee018',	1,	'Akosie',	'',	'Dogiedog',	'1948-03-01',	'1',	'09654123987',	'livenews@youchub.com',	'2020-09-29 05:46:47',	'ff6f87deb1182bff.png');

DROP TABLE IF EXISTS `pharmacy`;
CREATE TABLE `pharmacy` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(20) NOT NULL,
  `desc_nondrug` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `drug_id` int(20) DEFAULT NULL,
  `quantity` int(20) DEFAULT NULL,
  `unit_price` decimal(13,2) DEFAULT NULL,
  `vat_exempt_price` decimal(13,2) NOT NULL,
  `discount_price` decimal(13,2) NOT NULL,
  `payable_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_id` (`drug_id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `pharmacy_ibfk_10` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`),
  CONSTRAINT `pharmacy_ibfk_12` FOREIGN KEY (`drug_id`) REFERENCES `drug` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `pharmacy` (`id`, `transaction_id`, `desc_nondrug`, `drug_id`, `quantity`, `unit_price`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1,	1,	'',	2,	8,	1120.00,	1000.00,	200.00,	800.00),
(2,	2,	'',	6,	10,	112.00,	100.00,	20.00,	80.00),
(3,	3,	'',	3,	4,	896.00,	800.00,	160.00,	640.00),
(4,	4,	'',	4,	3,	448.00,	400.00,	80.00,	320.00),
(5,	5,	'',	3,	14,	1500.00,	1339.29,	267.86,	1071.43),
(6,	6,	'',	1,	18,	2000.00,	1785.71,	357.14,	1428.57),
(7,	19,	'',	3,	10,	5.00,	50.00,	10.00,	40.00),
(8,	21,	'',	1,	14,	100.00,	1250.00,	250.00,	1000.00),
(9,	23,	'',	1,	18,	100.00,	1250.00,	250.00,	1000.00),
(10,	24,	'',	1,	14,	100.00,	1250.00,	250.00,	1000.00),
(17,	23,	'',	2,	10,	201.60,	1800.00,	360.00,	1440.00),
(18,	29,	'',	6,	10,	4.00,	35.71,	7.14,	28.56),
(19,	33,	'Dairy Milk',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(20,	41,	'Deiri melk',	NULL,	NULL,	NULL,	200.00,	40.00,	160.00),
(21,	42,	'Kopinya 10s',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(22,	42,	'Nice Kopi 11in1 - 10s',	NULL,	NULL,	NULL,	200.00,	40.00,	160.00),
(23,	44,	'Kopinya 10s',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(24,	45,	'Grate Caste White 30s',	NULL,	NULL,	NULL,	150.00,	30.00,	120.00),
(25,	46,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(26,	46,	'Starbox Prop-puchino 330ml',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(27,	48,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(28,	48,	'Starbox Prop-puchino 330ml',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(29,	50,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(30,	50,	'Pudgey Vars 12s',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(31,	50,	NULL,	3,	10,	5.00,	100.00,	20.00,	80.00),
(32,	50,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(33,	50,	'Pudgey Vars 12s',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(34,	55,	NULL,	6,	10,	5.00,	100.00,	20.00,	80.00),
(35,	55,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(36,	55,	'Pudgey Vars 12s',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(37,	58,	NULL,	6,	10,	5.00,	100.00,	20.00,	80.00),
(38,	58,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(39,	58,	'Pudgey Vars 12s',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(40,	61,	NULL,	6,	10,	5.00,	100.00,	20.00,	80.00),
(41,	61,	'Quai-ker Oathmill',	NULL,	NULL,	NULL,	100.00,	20.00,	80.00),
(42,	61,	'Pudgey Vars 12s',	NULL,	NULL,	NULL,	97.32,	19.46,	77.86),
(43,	67,	NULL,	6,	14,	8.15,	101.88,	20.38,	81.50),
(44,	68,	NULL,	8,	7,	6.25,	39.06,	7.81,	31.25),
(45,	68,	NULL,	9,	7,	8.00,	50.00,	10.00,	40.00),
(46,	68,	NULL,	1,	1,	5.20,	65.00,	13.00,	52.00),
(47,	68,	NULL,	10,	1,	5.20,	65.00,	13.00,	52.00),
(48,	72,	NULL,	9,	7,	8.00,	50.00,	10.00,	40.00),
(49,	76,	'Kitkat 11\'s',	NULL,	NULL,	NULL,	892.86,	178.57,	714.29),
(50,	79,	NULL,	7,	7,	6.25,	39.06,	7.81,	31.25),
(51,	79,	NULL,	9,	7,	8.00,	50.00,	10.00,	40.00),
(52,	79,	NULL,	1,	1,	5.20,	65.00,	13.00,	52.00),
(53,	79,	NULL,	10,	1,	5.20,	65.00,	13.00,	52.00),
(54,	80,	'Frozen Siomai Pack 25s Pack',	NULL,	NULL,	NULL,	249.75,	44.60,	205.15),
(57,	83,	'Frozen Siomai Pack 25s Pack',	NULL,	NULL,	NULL,	249.75,	44.60,	205.15),
(58,	84,	NULL,	7,	7,	6.25,	39.06,	7.81,	31.25),
(59,	84,	NULL,	9,	10,	8.00,	50.00,	10.00,	40.00),
(60,	84,	NULL,	1,	1,	5.20,	65.00,	13.00,	52.00),
(61,	84,	NULL,	12,	1,	5.20,	65.00,	13.00,	52.00);

DROP TABLE IF EXISTS `qr_request`;
CREATE TABLE `qr_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(20) NOT NULL,
  `desc` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `token` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `trans_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `transaction_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `fk_qr_request_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `qr_request_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `qr_request` (`id`, `member_id`, `desc`, `token`, `trans_date`, `transaction_id`) VALUES
(1,	2,	'Product: Biogesic Quantity: 7 Notes: kahit ano',	'9jifhjvahke0g9ai',	'2020-08-08 20:02:22',	NULL),
(2,	2,	'Product: Alaxan 500mg; Qty: 10pcs; Notes: Sakit katawan',	'47bce5c74f589f4',	'2020-09-28 08:41:40',	19);

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `trans_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `company_id` int(20) NOT NULL,
  `member_id` int(20) NOT NULL,
  `clerk` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `fk_transaction_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_transaction_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `transaction` (`id`, `trans_date`, `company_id`, `member_id`, `clerk`) VALUES
(1,	'2020-09-06 13:12:45',	14,	4,	'M Reyes'),
(2,	'2020-07-27 20:45:34',	13,	2,	''),
(3,	'2020-09-07 15:15:25',	14,	3,	'Cy'),
(4,	'2020-07-24 21:39:00',	15,	3,	''),
(5,	'2020-08-11 17:36:53',	16,	4,	''),
(6,	'2020-07-04 17:36:53',	17,	2,	''),
(7,	'2020-09-12 09:27:18',	4,	4,	''),
(8,	'2020-08-15 17:36:55',	4,	4,	''),
(9,	'2020-06-17 17:36:55',	6,	5,	''),
(10,	'2020-01-11 17:36:55',	5,	2,	''),
(11,	'2020-02-18 17:36:55',	4,	3,	''),
(12,	'2020-08-11 17:36:55',	9,	4,	''),
(13,	'2020-03-12 17:36:55',	21,	2,	''),
(14,	'2020-07-11 17:36:55',	28,	4,	''),
(15,	'2020-04-13 17:36:55',	24,	3,	''),
(16,	'2020-05-15 17:36:55',	27,	3,	''),
(17,	'2020-02-11 17:36:55',	27,	1,	''),
(18,	'2020-03-11 17:36:55',	26,	2,	''),
(19,	'2020-08-15 23:15:28',	14,	2,	NULL),
(21,	'2020-08-16 00:57:09',	18,	2,	''),
(23,	'2020-09-02 06:10:45',	14,	2,	''),
(24,	'2020-09-02 06:07:17',	14,	4,	''),
(28,	'2020-09-02 06:07:17',	14,	2,	''),
(29,	'2020-09-08 16:03:50',	11,	2,	'Cy'),
(33,	'2020-08-28 05:25:34',	13,	2,	'AB Dela Rosa'),
(38,	'2020-09-10 13:12:45',	4,	2,	'AB Garcia'),
(41,	'2020-09-10 13:12:45',	14,	2,	'AB Garcia'),
(42,	'2020-09-11 05:11:11',	14,	2,	'CD Efren'),
(43,	'2020-09-11 05:10:18',	14,	2,	'CD Efren'),
(44,	'2020-09-11 08:43:49',	14,	2,	'CD Efren'),
(45,	'2020-09-13 01:25:34',	14,	2,	'CD Efren'),
(46,	'2020-09-14 04:18:10',	14,	2,	'CD Efren'),
(48,	'2020-09-14 04:35:48',	14,	2,	'CD Efren'),
(50,	'2020-09-14 07:48:09',	14,	2,	'GH Igol'),
(55,	'2020-09-15 03:14:31',	14,	2,	'GH Igol'),
(58,	'2020-09-13 07:48:09',	14,	3,	'GH Igol'),
(61,	'2020-09-16 01:56:37',	14,	2,	'JB Meneses'),
(65,	'2020-09-18 11:30:13',	26,	2,	'jeppie boi'),
(66,	'2020-09-11 12:38:08',	10,	2,	'AR Magnayo'),
(67,	'2020-09-14 10:45:37',	19,	3,	'AR Magnayo'),
(68,	'2020-09-17 10:40:11',	19,	3,	'AL Manalon'),
(72,	'2020-09-17 13:11:11',	14,	2,	'JK MARLU'),
(73,	'2020-09-15 03:11:11',	5,	2,	'XY Zinger'),
(74,	'2020-09-16 03:11:11',	14,	2,	'XY Zinger'),
(75,	'2020-09-17 15:11:11',	14,	2,	'XY Zinger'),
(76,	'2020-09-17 15:11:11',	14,	2,	'XY Zinger'),
(77,	'2020-09-16 03:11:11',	24,	3,	'AL Wanwan'),
(78,	'2020-09-16 03:11:11',	5,	1,	'AL Wanwan'),
(79,	'2020-09-17 13:11:11',	11,	6,	'AL Manalon'),
(80,	'2020-09-17 15:23:23',	13,	2,	'Ling Maker'),
(83,	'2020-09-21 22:59:59',	13,	6,	'Baxia Master'),
(84,	'2020-09-17 13:11:11',	14,	1,	'AL Manalon'),
(85,	'2020-09-20 03:11:11',	26,	2,	'AB Ignacio');

DROP TABLE IF EXISTS `transportation`;
CREATE TABLE `transportation` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(20) NOT NULL,
  `desc` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `vat_exempt_price` decimal(13,2) NOT NULL,
  `discount_price` decimal(13,2) NOT NULL,
  `payable_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `transportation_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `transportation` (`id`, `transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1,	13,	'Bound to Pasay',	100.00,	20.00,	80.00),
(2,	14,	'Bound to Pasay',	100.00,	20.00,	80.00),
(3,	15,	'Bound to DJose',	100.00,	20.00,	80.00),
(4,	16,	'Bound to Pasay',	100.00,	20.00,	80.00),
(5,	17,	'Bound to Cubao',	100.00,	20.00,	80.00),
(6,	18,	'Bound to EDSA',	100.00,	20.00,	80.00),
(7,	65,	'Pasay to Guadalupe | Senior - SJT',	26.79,	5.36,	21.43),
(8,	77,	'LRT Gil Puyat to LRT United Nations',	267.86,	53.57,	214.29),
(9,	85,	'Pasay to Guadalupe | Senior - SJT',	22.32,	4.46,	17.86);

-- 2020-09-29 22:46:01
