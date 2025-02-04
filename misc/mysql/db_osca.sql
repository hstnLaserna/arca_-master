-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 10, 2020 at 05:57 PM
-- Server version: 10.3.23-MariaDB-0+deb10u1
-- PHP Version: 7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_osca`
--
CREATE DATABASE IF NOT EXISTS `db_osca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_osca`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `activate_admin_account`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `activate_admin_account` (IN `user_name_` VARCHAR(60), OUT `msg` INT(10))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `add_address`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_address` (IN `add1_` VARCHAR(120), IN `add2_` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120), IN `is_active_` VARCHAR(11), IN `member_id_` VARCHAR(20), OUT `msg` VARCHAR(1))  BEGIN
  IF ((SELECT COUNT(*) FROM `member` WHERE `id` = member_id_) = 1)
  THEN
    START TRANSACTION;
        
      IF (`is_active_` = 1)       THEN
        UPDATE `address` a
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
        SET a.`is_active` = 0
        WHERE ajt.`member_id` = (SELECT `id` FROM `member` WHERE `id` = `member_id_`);
      ELSE
        SET msg = 0;       END IF;
            
      INSERT  INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
      VALUES (`add1_`, `add2_`, `city_`, `province_`, `is_active_`, now());
      
      SET @last_inserted_id = LAST_INSERT_ID();

      INSERT INTO address_jt (`address_id`, `member_id`) VALUES (@last_inserted_id, (SELECT `id` FROM `member` WHERE `id` = `member_id_`));
      SET msg = 1;      COMMIT;
  ELSE 
    SET msg = 0;   END IF;
END$$

DROP PROCEDURE IF EXISTS `add_admin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_admin` (IN `username_` VARCHAR(20), IN `password_` VARCHAR(20), IN `firstname_` VARCHAR(60), IN `middlename_` VARCHAR(60), IN `lastname_` VARCHAR(60), IN `birthdate_` DATE, IN `sex_` VARCHAR(10), IN `contact_number_` VARCHAR(20), IN `email_` VARCHAR(120), IN `position_` VARCHAR(60), IN `isEnabled_` TINYINT, IN `answer1_` VARCHAR(20), IN `answer2_` VARCHAR(20), OUT `msg` VARCHAR(60))  BEGIN

    IF
        ((SELECT COUNT(*) FROM `admin` WHERE `user_name` = `username_`) = 0)
    THEN
        INSERT INTO `admin` (`user_name`, `password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `position`, `is_enabled`, `log_attempts`, `answer1`, `answer2`, `temporary_password`, `avatar`)
                    VALUES (`username_`, MD5(`password_`), `firstname_`, `middlename_`, `lastname_`, `birthdate_`, `sex_`, `contact_number_`, `email_`, `position_`, `isEnabled_`, 0, `answer1_`, `answer2_`, `password_`, 'null');
        SET msg = "1";
    ELSE
        SET msg = "0";
    END IF;

END$$

DROP PROCEDURE IF EXISTS `add_company`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_company` (IN `company_tin_` VARCHAR(20), IN `company_name_` VARCHAR(250), IN `branch_` VARCHAR(120), IN `business_type_` VARCHAR(120), IN `address1_` VARCHAR(120), IN `address2_` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120))  BEGIN
  START TRANSACTION;
  INSERT INTO `company` (`company_tin`, `company_name`, `branch`, `business_type`)
    VALUES  (`company_tin_`, `company_name_`, `branch_`, `business_type_`);
  SET @company_inserted_id = LAST_INSERT_ID();

  INSERT INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
    VALUES  (`address1_`, `address2_`, `city_`, `province_`, 1, now());

  SET @address_inserted_id = LAST_INSERT_ID();
  INSERT INTO address_jt (`address_id`, `company_id`) VALUES (@address_inserted_id, @company_inserted_id);
  COMMIT;
END$$

DROP PROCEDURE IF EXISTS `add_company_address`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_company_address` (IN `add1_` VARCHAR(120), IN `add2_` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120), IN `is_active_` VARCHAR(11), IN `selected_id_` VARCHAR(20), OUT `msg` VARCHAR(1))  BEGIN
  IF ((SELECT COUNT(*) FROM `company` WHERE `id` = `selected_id_`) = 1)
  THEN
    START TRANSACTION;
        
      IF (`is_active_` = 1)       THEN
        UPDATE `address` a
        INNER JOIN `address_jt` ajt ON ajt.`address_id` = a.id
        SET a.`is_active` = 0
        WHERE ajt.`company_id` = (SELECT `id` FROM `company` WHERE `id` = `selected_id_`);
      ELSE
        SET msg = 0;       END IF;
      
      INSERT  INTO `address` (`address1`, `address2`, `city`, `province`, `is_active`, `last_update`)
      VALUES (`add1_`, `add2_`, `city_`, `province_`, `is_active_`, now());
      SET @last_inserted_id = LAST_INSERT_ID();
      INSERT INTO address_jt (`address_id`, `company_id`) VALUES (@last_inserted_id, (SELECT `id` FROM `company` WHERE `id` = `selected_id_`));
            
      SET msg = 1;      COMMIT;
  ELSE 
    SET msg = 0;   END IF;
END$$

DROP PROCEDURE IF EXISTS `add_complaint_report`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_complaint_report` (IN `company_name_` VARCHAR(250), IN `branch_` VARCHAR(120), IN `osca_id_` VARCHAR(20), IN `desc_` VARCHAR(300), IN `report_date_` TIMESTAMP, OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `add_drug`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_drug` (IN `generic_name_` VARCHAR(120), IN `brand_` VARCHAR(120), IN `dose_` INT(20), IN `unit_` VARCHAR(120), IN `is_otc_` INT(10), IN `max_monthly_` INT(20), IN `max_weekly_` INT(20))  BEGIN
    INSERT INTO `drug` (`generic_name`, `brand`, `dose`, `unit`, `is_otc`, `max_monthly`, `max_weekly`)
                VALUES (`generic_name_`, `brand_`, `dose_`, `unit_`, `is_otc_`, `max_monthly_`, `max_weekly_`);
END$$

DROP PROCEDURE IF EXISTS `add_guardian`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_guardian` (IN `first_name_` VARCHAR(120), IN `middle_name_` VARCHAR(120), IN `last_name_` VARCHAR(120), IN `sex_` VARCHAR(10), IN `relationship_` VARCHAR(120), IN `contact_number_` VARCHAR(20), IN `email_` VARCHAR(120), IN `member_id_` VARCHAR(20), OUT `msg` INT(1))  BEGIN
  IF ((SELECT COUNT(*) FROM `member` WHERE `id` = member_id_) = 1)
  THEN
    START TRANSACTION;
      
      INSERT  INTO `guardian` (`first_name`,  `middle_name`,  `last_name`,  `sex`,  `relationship`,  `contact_number`,  `email`,  `member_id`)
        VALUES (`first_name_`,  `middle_name_`,  `last_name_`,  `sex_`,  `relationship_`,  `contact_number_`,  `email_`,  `member_id_`);

      SET msg= 1;     COMMIT;
  ELSE 
    SET msg= 0;   END IF;
END$$

DROP PROCEDURE IF EXISTS `add_lost_report`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_lost_report` (IN `osca_id_` VARCHAR(20), IN `report_date_` TIMESTAMP, OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `add_member`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_member` (IN `fname` VARCHAR(60), IN `mname` VARCHAR(60), IN `lname` VARCHAR(60), IN `bday` DATE, IN `sex_` VARCHAR(10), IN `contact_no` VARCHAR(20), IN `email_` VARCHAR(120), IN `memship_date_` DATETIME, IN `add1` VARCHAR(120), IN `add2` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120), IN `pword` VARCHAR(120), IN `g_fname` VARCHAR(120), IN `g_mname` VARCHAR(120), IN `g_lname` VARCHAR(120), IN `g_contact_no` VARCHAR(120), IN `g_sex_` VARCHAR(10), IN `g_relationship` VARCHAR(120), IN `g_email_` VARCHAR(120))  BEGIN
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
      m.osca_id =  CONCAT(provCode, '-', substr(year(membership_date), 3,3), m.member_count)
    WHERE m.id = @member_inserted_id;

END$$

DROP PROCEDURE IF EXISTS `add_qr_request`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_qr_request` (IN `osca_id_` VARCHAR(120), IN `desc_` VARCHAR(120), IN `token_` VARCHAR(120), IN `request_date_` TIMESTAMP, OUT `msg` VARCHAR(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `add_transaction`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_transaction` (IN `trans_date_` TIMESTAMP, IN `company_tin_` VARCHAR(120), IN `osca_id_` VARCHAR(120), IN `clerk_` VARCHAR(120), OUT `msg` VARCHAR(120))  BEGIN
  DECLARE company_id_ INT(20);
  DECLARE member_id_ VARCHAR(120);
    SET `company_id_` = (SELECT `c_id` FROM `view_companies` WHERE `company_tin` = `company_tin_`);
    SET `member_id_` = (SELECT `member_id` FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_` LIMIT 1);
    INSERT INTO `transaction`(`trans_date`, `company_id`, `member_id`, `clerk`) VALUES
      (`trans_date_`, `company_id_`, `member_id_`, `clerk_`);
    SET msg = LAST_INSERT_ID();
END$$

DROP PROCEDURE IF EXISTS `add_transaction_food`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_transaction_food` (IN `trans_type` VARCHAR(120), IN `transaction_id_` INT(20), IN `company_tin_` VARCHAR(120), IN `desc_` VARCHAR(120), IN `vat_exempt_price_` DECIMAL(13,2), IN `discount_price_` DECIMAL(13,2), IN `payable_price_` DECIMAL(13,2), OUT `msg` VARCHAR(120))  BEGIN
  IF (`trans_type` = 'food' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `food` (`transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `desc_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `add_transaction_pharmacy_drug`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_transaction_pharmacy_drug` (IN `trans_type` VARCHAR(120), IN `transaction_id_` INT(20), IN `company_tin_` VARCHAR(120), IN `drug_id_` INT(20), IN `quantity_` INT(20), IN `unit_price_` DECIMAL(13,2), IN `vat_exempt_price_` DECIMAL(13,2), IN `discount_price_` DECIMAL(13,2), IN `payable_price_` DECIMAL(13,2), OUT `msg` VARCHAR(120))  BEGIN
  IF (`trans_type` = 'pharmacy' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `pharmacy` (`transaction_id`, `drug_id`, `quantity`, `unit_price`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `drug_id_`, `quantity_`, `unit_price_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `add_transaction_pharmacy_nondrug`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_transaction_pharmacy_nondrug` (IN `trans_type` VARCHAR(120), IN `transaction_id_` INT(20), IN `company_tin_` VARCHAR(120), IN `desc_` VARCHAR(120), IN `vat_exempt_price_` DECIMAL(13,2), IN `discount_price_` DECIMAL(13,2), IN `payable_price_` DECIMAL(13,2), OUT `msg` VARCHAR(120))  BEGIN
  IF (`trans_type` = 'pharmacy' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `pharmacy` (`transaction_id`, `desc_nondrug`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `desc_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `add_transaction_transportation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_transaction_transportation` (IN `trans_type` VARCHAR(120), IN `transaction_id_` INT(20), IN `company_tin_` VARCHAR(120), IN `desc_` VARCHAR(120), IN `vat_exempt_price_` DECIMAL(13,2), IN `discount_price_` DECIMAL(13,2), IN `payable_price_` DECIMAL(13,2), OUT `msg` VARCHAR(120))  BEGIN
  IF (`trans_type` = 'transportation' AND (SELECT COUNT(*) FROM `view_companies` WHERE `company_tin` = `company_tin_`) = 1)
  THEN
    INSERT INTO `transportation` (`transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
      (`transaction_id_`, `desc_`, `vat_exempt_price_`, `discount_price_`, `payable_price_`);
    SET msg = "1";
  ELSE 
    SET msg = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `deactivate_admin_account`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deactivate_admin_account` (IN `user_name_` VARCHAR(60), OUT `msg` INT(10))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `delete_company_address`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_company_address` (IN `company_id_` INT(20), IN `company_name_` VARCHAR(250), IN `branch_` VARCHAR(120), OUT `msg` VARCHAR(20))  BEGIN
  
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
      SET msg = 2;     ELSE
      SET msg = 1;     END IF;
  ELSE
    SET msg = 0;   END IF;
END$$

DROP PROCEDURE IF EXISTS `delete_guardian`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_guardian` (IN `member_osca_id_` VARCHAR(20), IN `id_` INT(20), OUT `msg` INT(1))  BEGIN
  IF((
  SELECT count(*) FROM `guardian` g INNER JOIN `member` m ON g.`member_id` = m.`id` WHERE g.`id` = `id_` AND m.`osca_id` = `member_osca_id_`) = 1)
  THEN
    
    DELETE FROM `guardian`
    WHERE `id` = `id_`;

    IF(( SELECT count(*) FROM address a INNER JOIN address_jt ajt ON ajt.address_id = a.id WHERE ajt.`guardian_id` = `id_`) = 1)
    THEN            
      DELETE FROM `address_jt`
      WHERE `guardian_id` = `id_`;
      
      SET msg = 2;     ELSE

      SET msg = 1;     END IF;
  ELSE

    SET msg = 0;   END IF;
END$$

DROP PROCEDURE IF EXISTS `delete_member_address`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_member_address` (IN `member_id_` INT(20), IN `id_` INT(20), OUT `msg` VARCHAR(120))  BEGIN
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
      SET msg = 2;         ELSE
      SET msg = 1;         END IF;
  ELSE
    SET msg = 0;   END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_address_company`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_address_company` (IN `add1_` VARCHAR(120), IN `add2_` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120), IN `id_` INT(11), IN `company_id_` VARCHAR(120), OUT `msg` VARCHAR(120))  BEGIN
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
      SET msg = "2";      ELSE
      SET msg = "1";     END IF;
  ELSE 
    SET msg = "0";   END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_admin_no_pw`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_admin_no_pw` (IN `uname` VARCHAR(120), IN `fname` VARCHAR(120), IN `mname` VARCHAR(120), IN `lname` VARCHAR(120), IN `bday` DATE, IN `sex_` VARCHAR(10), IN `contact_number_` VARCHAR(20), IN `email_` VARCHAR(120), IN `pos` VARCHAR(120), IN `ans1` VARCHAR(100), IN `ans2` VARCHAR(100), IN `uid` VARCHAR(120))  UPDATE `admin` SET 
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
WHERE id = uid$$

DROP PROCEDURE IF EXISTS `edit_admin_picture`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_admin_picture` (IN `user_name_` VARCHAR(60), IN `avatar_` VARCHAR(60), OUT `msg` INT(10))  BEGIN
  IF( (SELECT count(*) FROM `admin` WHERE `user_name` = `user_name_`) = 1) 
    THEN
    UPDATE `admin` SET
    `avatar` = `avatar_`
    WHERE `user_name` = `user_name_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_admin_with_pw`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_admin_with_pw` (IN `uname` VARCHAR(120), IN `pword` VARCHAR(120), IN `fname` VARCHAR(120), IN `mname` VARCHAR(120), IN `lname` VARCHAR(120), IN `bday` DATE, IN `sex_` VARCHAR(10), IN `contact_number_` VARCHAR(20), IN `email_` VARCHAR(120), IN `pos` VARCHAR(120), IN `ans1` VARCHAR(100), IN `ans2` VARCHAR(100), IN `tempopw` VARCHAR(120), IN `uid` VARCHAR(120))  UPDATE `admin` SET 
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
WHERE id = uid$$

DROP PROCEDURE IF EXISTS `edit_company`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_company` (IN `company_tin_` VARCHAR(20), IN `company_name_` VARCHAR(250), IN `branch_` VARCHAR(120), IN `business_type_` VARCHAR(120), IN `company_id_` INT(20), OUT `msg` VARCHAR(60))  BEGIN
  IF( (SELECT count(*) FROM `company` c WHERE c.`id` = `company_id_`) = 1)
  THEN
    UPDATE `company`
      SET 
      `company_tin` = `company_tin_`,
      `company_name` = `company_name_`,
      `branch` = `branch_`,
      `business_type` = `business_type_`
      WHERE `id` = `company_id_`;
    SET msg = "1";   ELSE 
    SET msg = "0";   END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_company_address`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_company_address` (IN `add1_` VARCHAR(120), IN `add2_` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120), IN `is_active_` VARCHAR(120), IN `id_` INT(11), IN `company_id_` VARCHAR(120), OUT `msg` VARCHAR(120))  BEGIN
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
      SET msg = "2";      ELSE
      SET msg = "1";     END IF;
  ELSE 
    SET msg = "0";   END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_company_logo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_company_logo` (IN `company_tin_` VARCHAR(60), IN `logo_` VARCHAR(60), OUT `msg` INT(10))  BEGIN
  IF( (SELECT count(*) FROM `company` WHERE `company_tin` = `company_tin_`) = 1) 
    THEN
    UPDATE `company` SET
    `logo` = `logo_`
    WHERE `company_tin` = `company_tin_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_guardian`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_guardian` (IN `g_id_` INT(20), IN `osca_id_` VARCHAR(20), IN `first_name_` VARCHAR(120), IN `middle_name_` VARCHAR(120), IN `last_name_` VARCHAR(120), IN `sex_` VARCHAR(120), IN `relationship_` VARCHAR(120), IN `contact_number_` VARCHAR(120), IN `email_` VARCHAR(120), OUT `msg` VARCHAR(120))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `edit_lost_report`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_lost_report` (IN `lost_id_` VARCHAR(20), IN `osca_id_` VARCHAR(20), IN `desc_` VARCHAR(120), IN `nfc_active_` INT(1), IN `account_enabled_` INT(1), OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `edit_member_address`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_member_address` (IN `add1_` VARCHAR(120), IN `add2_` VARCHAR(120), IN `city_` VARCHAR(120), IN `province_` VARCHAR(120), IN `is_active_` VARCHAR(120), IN `id_` INT(11), IN `member_id_` VARCHAR(120), OUT `msg` VARCHAR(120))  BEGIN
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
        SET msg = "2";     ELSE
      SET msg = "1";     END IF;
  ELSE
    SET msg = "0";   END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_member_no_pw`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_member_no_pw` (IN `oid` VARCHAR(20), IN `nserial` VARCHAR(45), IN `fname` VARCHAR(120), IN `mname` VARCHAR(120), IN `lname` VARCHAR(120), IN `bday` DATE, IN `cnumber` VARCHAR(20), IN `email_` VARCHAR(120), IN `sex_` VARCHAR(10), IN `mdate` TIMESTAMP, IN `uid` VARCHAR(120))  UPDATE `member` SET 
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
WHERE id = uid$$

DROP PROCEDURE IF EXISTS `edit_member_picture`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_member_picture` (IN `osca_id_` VARCHAR(60), IN `picture_` VARCHAR(60), OUT `msg` INT(10))  BEGIN
  IF( (SELECT count(*) FROM `member` WHERE `osca_id` = `osca_id_`) = 1) 
    THEN
    UPDATE `member` SET
    `picture` = `picture_`
    WHERE `osca_id` = `osca_id_`;
    SET `msg` = "1";
  ELSE 
    SET `msg` = "0";
  END IF;
END$$

DROP PROCEDURE IF EXISTS `edit_member_with_pw`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_member_with_pw` (IN `oid` VARCHAR(20), IN `nserial` VARCHAR(45), IN `pword` VARCHAR(60), IN `fname` VARCHAR(120), IN `mname` VARCHAR(120), IN `lname` VARCHAR(120), IN `bday` DATE, IN `cnumber` VARCHAR(20), IN `email_` VARCHAR(120), IN `sex_` VARCHAR(10), IN `mdate` TIMESTAMP, IN `uid` VARCHAR(120))  UPDATE `member` SET 
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
WHERE id = uid$$

DROP PROCEDURE IF EXISTS `fetch_food_transactions`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `fetch_food_transactions` (IN `osca_id_` VARCHAR(20))  BEGIN
  SELECT `trans_date`, `company_name`, `branch`, `business_type`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`
  FROM `view_food_transactions`
  WHERE `osca_id` = `osca_id_`;
END$$

DROP PROCEDURE IF EXISTS `fetch_password`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `fetch_password` (IN `osca_id_` VARCHAR(20))  BEGIN
  SELECT
    `osca_id`, `password`, `contact_number`
  FROM `view_members_with_guardian`
  WHERE `osca_id` = `osca_id_`
  AND  `a_is_active` = 1;
END$$

DROP PROCEDURE IF EXISTS `fetch_pharma_transactions`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `fetch_pharma_transactions` (IN `osca_id` VARCHAR(20))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `fetch_transportation_transactions`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `fetch_transportation_transactions` (IN `osca_id` VARCHAR(20))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `forgot_pw_admin`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `forgot_pw_admin` (IN `uname` VARCHAR(120), IN `ans1` VARCHAR(100), IN `ans2` VARCHAR(100), OUT `tempopw` VARCHAR(6), OUT `msg` INT(10))  IF ((SELECT EXISTS(SELECT * FROM `admin` WHERE (user_name = uname AND answer1 = ans1) OR (user_name = uname AND answer2=ans2))) = 1)
THEN
  SET `tempopw`= (SELECT substring(MD5(RAND()), -6));
  UPDATE `admin` SET `password`=MD5(`tempopw`), `temporary_password`=(`tempopw`), is_enabled = 1, log_attempts=0 WHERE `user_name`=`uname`;
  SET msg = 1;
ELSE
  SET msg = 0;
END IF$$

DROP PROCEDURE IF EXISTS `invalid_login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `invalid_login` (IN `uname` VARCHAR(20))  BEGIN
  DECLARE SELECTed_id INT(8);
  SET SELECTed_id = (SELECT id FROM `admin` WHERE `user_name`=uname);
  UPDATE `admin` SET log_attempts = log_attempts + 1 WHERE `id` = SELECTed_id;
  IF (SELECT log_attempts FROM admin WHERE `user_name`=uname) > 2
  THEN UPDATE admin SET is_enabled = 0 WHERE id = SELECTed_id;
  END IF;
END$$

DROP PROCEDURE IF EXISTS `login_member`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `login_member` (IN `osca_id_` VARCHAR(120), IN `password_` VARCHAR(120))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `toggle_admin_acct`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `toggle_admin_acct` (IN `user_name_` VARCHAR(60), OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `toggle_company_acct`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `toggle_company_acct` (IN `company_id_` INT(20), OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `toggle_member_acct`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `toggle_member_acct` (IN `id_` VARCHAR(60), OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `toggle_member_nfc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `toggle_member_nfc` (IN `id_` VARCHAR(60), OUT `msg` INT(1))  BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `validate_login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validate_login` (IN `osca_id` VARCHAR(20), IN `password` VARCHAR(120))  BEGIN
  SELECT user.osca_id, user.password, concat(first_name, " ", middle_name, " ", last_name) as full_name, user.birth_date, user.sex, user.membership_date, user.avatar, concat(address1, " ", address2, ", " , city, ", ", province) as address
  FROM user
  RIGHT JOIN address
  ON user.address_id = address.id
  WHERE user.osca_id = osca_id AND user.password = password;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `address1` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `address2` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `province` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `is_active` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `address1`, `address2`, `city`, `province`, `is_active`, `last_update`) VALUES
(1, '2129 Culdesac Rd Edison St', 'Brgy. Sun Valley', 'Paranaque', 'Ncr, Fourth District', 1, '2020-09-25 07:47:50'),
(2, 'L23 Villa Antonina Subd', 'Brgy. San Nicolas 2', 'Bacoor City', 'Cavite', 1, '2020-09-25 07:37:01'),
(3, 'Blk25 lot41 Milkwort St Ph3 Villa de Primarosa', 'Brgy. Mambog 3', 'Bacoor City', 'Cavite', 1, '2020-09-25 07:36:49'),
(4, '3009, Ipil st.', 'Brgy Banaba', 'Silang', 'Cavite', 0, '2020-09-25 07:38:06'),
(5, '0235 Rafael St., Villa Modena', 'Villagio Ignatius Subd., Brgy. Buenavista III', 'General Trias', 'Cavite', 0, '2020-08-22 20:37:57'),
(6, '2099 Culdesac Rd Edison St', 'Brgy. Sun Valley', 'Paranaque', 'Ncr, Fourth District', 1, '2020-09-25 07:48:23'),
(7, '5636 Rafael St.', 'Brgy. Manggahan', 'General Trias', 'Cavite', 1, '2020-09-11 22:21:34'),
(8, '1001 Sant St.', 'Brgy Maybuhay', 'Makati', 'Ncr, Fourth District', 1, '2020-09-25 07:59:17'),
(9, '0925 Remedios St.', 'Brgy 601', 'Malate', 'City Of Manila', 1, '2020-09-25 07:45:21'),
(10, '1235 Phase 5 Pili St.', 'Brgy. Anahaw', 'Silang', 'Cavite', 1, '2020-09-11 22:23:51'),
(11, 'Land of', 'Dawn', 'Nasugbu', 'Batangas', 1, '2020-09-11 22:39:33'),
(12, '9287 Riverdale St.', 'Riverdale Subdivision, Brgy. Kasulukan', 'Paniqui', 'Tarlac', 1, '2020-09-01 20:47:30'),
(14, 'Glass Manor', 'Brgy. Ibabaw Del Sur', 'Paete', 'Laguna', 1, '2020-09-01 20:33:04'),
(15, '2548, Nakpil St.', 'Brgy. Reezal, Tamagochi Village', 'Marilao', 'Bulacan', 1, '2020-09-03 15:53:52'),
(16, '3180 Zobel St.', 'Bukid', 'Malate', 'City Of Manila', 1, '2020-09-25 07:47:03'),
(17, '0028 Merger St.', 'Louiseville', 'Batangas', 'Batangas', 1, '2020-08-24 11:32:03'),
(20, 'Walter Mart', 'Mc Arthur Highway ', 'Guiguinto', 'Bulacan', 1, '2020-09-04 09:32:20'),
(23, '4F Right Wing', 'Farmers Plaza Cubao', 'Quezon City', 'Ncr, Second District', 1, '2020-09-11 22:03:35'),
(24, 'Grounds', 'Mall of Asia', 'Pasay City', 'Ncr, Fourth District', 1, '2020-09-25 13:18:02'),
(25, '2002 Kabihasnan St.', 'Gen. Luna', 'Ermita', 'Ncr, City Of Manila, First District', 1, '2020-09-25 13:17:36'),
(26, 'Portal Mall GF', 'Brgy. San Gabriel II', 'General Mariano Alvarez', 'Cavite', 0, '2020-09-02 18:31:38'),
(27, 'Taft Ave. cor. Quirino St', 'Brgy 6969', 'Malate', 'Ncr, City Of Manila, First District', 0, '2020-09-11 21:42:18'),
(32, 'Somewhere', 'Out there', 'Quezon City', 'Ncr, Second District', 1, '2020-09-27 11:14:51'),
(34, 'Hidalgo St., Quiapo', 'Raon', 'Quiapo', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:11:13'),
(35, '0295 Mokmok St.', 'Sangandaan', 'Quezon City', 'Ncr, Second District', 0, '2020-09-25 13:12:32'),
(36, 'Kamuning Rd', 'Hi-Top Supermarket', 'Quezon City', 'Ncr, Second District', 0, '2020-09-25 13:14:08'),
(37, 'Manila Bay', 'Quirino Ave.', 'Malate', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:14:34'),
(38, 'Tandang Sora', 'Commonwealth Ave', 'Quezon City', 'Ncr, Second District', 1, '2020-09-25 13:15:05'),
(39, 'Terminal 3', 'NAIA', 'Pasay City', 'Ncr, Fourth District', 0, '2020-09-25 13:16:14'),
(40, 'Chino Roces Avenue corner', 'EDSA', 'Makati', 'Ncr, Fourth District', 0, '2020-09-25 13:17:07'),
(41, 'Market Market', 'BGC Complex', 'Taguig City', 'Ncr, Second District', 0, '2020-09-25 13:18:46'),
(42, 'San Marcelino St.', 'SM Manila', 'Ermita', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:19:25'),
(43, 'Montillano ST.', 'Festival Mall', 'Muntinlupa', 'Ncr, Fourth District', 0, '2020-09-25 13:20:02'),
(44, '1029 St', 'Quirino Ave', 'Paranaque', 'Ncr, Fourth District', 0, '2020-09-25 13:20:32'),
(45, 'Alpa', 'Arnaiz Ave', 'Makati', 'Ncr, Fourth District', 0, '2020-09-25 13:22:11'),
(46, '2910 Ermita', 'Boni. Ave', 'Mandaluyong', 'Ncr, Second District', 0, '2020-09-25 13:22:59'),
(47, 'Congressional rd. cor', 'Governor\'s Drive', 'Gen. Mariano Alvarez', 'Cavite', 0, '2020-09-25 13:23:31'),
(48, 'Waltermart', 'Gov. Dr.', 'General Trias', 'Cavite', 0, '2020-09-25 13:24:02'),
(49, 'Central Station', 'LRT A', 'Ermita', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:24:47'),
(50, 'Recto Ave cor.', 'Rizal Ave', 'Binondo', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:25:14'),
(51, 'Buendia Ave.', 'Taft. Ave', 'Pasay City', 'Ncr, Fourth District', 0, '2020-09-25 13:25:44'),
(52, 'Pedro Gil Ave. cor', 'Taft Ave.', 'Malate', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:26:12'),
(53, 'United Nations Station', 'United Nations Station', 'Ermita', 'Ncr, City Of Manila, First District', 0, '2020-09-25 13:27:53'),
(54, 'Araneta ', 'Cubao', 'Pasig', 'Ncr, Second District', 0, '2020-09-25 13:28:32'),
(55, 'Station Dr', 'Ayala', 'Makati', 'Ncr, Fourth District', 0, '2020-09-25 13:29:11'),
(56, 'Cembo Guadalupe 1212', 'Guijo', 'Makati', 'Ncr, Fourth District', 0, '2020-09-25 13:30:02'),
(57, 'Magallanes', 'Epifanio de los Santos Ave', 'Makati', 'Ncr, Fourth District', 0, '2020-09-25 13:30:33'),
(58, 'Taft Ave cor', 'Epifanio de los Santos Ave', 'Pasay City', 'Ncr, Fourth District', 0, '2020-09-25 13:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `address_jt`
--

DROP TABLE IF EXISTS `address_jt`;
CREATE TABLE IF NOT EXISTS `address_jt` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `address_id` int(20) NOT NULL,
  `member_id` int(20) DEFAULT NULL,
  `company_id` int(20) DEFAULT NULL,
  `guardian_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  KEY `member_id` (`member_id`),
  KEY `company_id` (`company_id`),
  KEY `guardian_id` (`guardian_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `address_jt`
--

INSERT INTO `address_jt` (`id`, `address_id`, `member_id`, `company_id`, `guardian_id`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 3, NULL, NULL),
(3, 3, 4, NULL, NULL),
(4, 4, NULL, NULL, NULL),
(5, 5, NULL, NULL, NULL),
(6, 6, 5, NULL, NULL),
(7, 7, 7, NULL, NULL),
(8, 8, 8, NULL, NULL),
(9, 9, 6, NULL, NULL),
(10, 10, 2, NULL, NULL),
(11, 11, 9, NULL, NULL),
(12, 12, 10, NULL, NULL),
(14, 14, 11, NULL, NULL),
(15, 15, 13, NULL, NULL),
(16, 16, 12, NULL, NULL),
(17, 17, 14, NULL, NULL),
(20, 20, NULL, 31, NULL),
(21, 23, NULL, 5, NULL),
(22, 24, NULL, 9, NULL),
(23, 25, NULL, 1, NULL),
(24, 26, NULL, 14, NULL),
(25, 27, NULL, 2, NULL),
(30, 32, 20, NULL, NULL),
(32, 34, NULL, 12, NULL),
(33, 35, NULL, 4, NULL),
(34, 36, NULL, 8, NULL),
(35, 37, NULL, 10, NULL),
(36, 38, NULL, 3, NULL),
(37, 39, NULL, 7, NULL),
(38, 40, NULL, 6, NULL),
(39, 41, NULL, 13, NULL),
(40, 42, NULL, 17, NULL),
(41, 43, NULL, 15, NULL),
(42, 44, NULL, 19, NULL),
(43, 45, NULL, 18, NULL),
(44, 46, NULL, 20, NULL),
(45, 47, NULL, 11, NULL),
(46, 48, NULL, 16, NULL),
(47, 49, NULL, 21, NULL),
(48, 50, NULL, 22, NULL),
(49, 51, NULL, 24, NULL),
(50, 52, NULL, 25, NULL),
(51, 53, NULL, 23, NULL),
(52, 54, NULL, 29, NULL),
(53, 55, NULL, 30, NULL),
(54, 56, NULL, 27, NULL),
(55, 57, NULL, 28, NULL),
(56, 58, NULL, 26, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_name`, `password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `position`, `contact_number`, `email`, `is_enabled`, `log_attempts`, `answer1`, `answer2`, `temporary_password`, `avatar`) VALUES
(1, 'ralf', '3cca634013591eb51173fb6207572e37', 'Ralph Christian', 'Arbiol', 'Ortiz', '1990-01-14', '1', 'admin', '07283754', 'ralph.ortiz@ymeal.com', 1, 1, 'ralp', 'orti', 'ralfralf', 'inuho1wjbk.png'),
(2, 'hstn', 'fc29f6ea32a347d55bd690c5d11ed8e3', 'Justine', 'Ildefonso', 'Laserna', '1990-01-25', '1', 'admin', '86554553', 'justine.laserna@ymeal.com', 1, 0, 'hustino', 'hustino', 'b18340', 'c4ef6d230c396efc.png'),
(3, 'matt', 'ce86d7d02a229acfaca4b63f01a1171b', 'Matthew Franz', 'Castro', 'Vasquez', '1990-01-15', '1', 'admin', '09123654123', 'matthew.vasquez@ymeal.com', 1, 0, 'matt', 'vasqa', 'matt', '1dngb3owoz.png'),
(4, 'fred', '2697359d57024a8f41301b0332a8ba39', 'Frederick Allain', '', 'Dela Cruz', '1990-01-01', '1', 'admin', '09123456789', 'frederick.dela.cruz@ymeal.com', 1, 0, 'fred', 'lain', 'fredfred', 'izkue0sbn0.png'),
(5, 'alycheese', 'f1a8007fd4dfef79ee8122b51fa30922', 'Aly', 'x', 'Cheese', '1990-10-10', '2', 'admin', '09654123789', 'cyrel.lalikan@ymeal.com', 1, 1, 'swan', 'song', 'alycheese', 'k1ylycon4h.png'),
(6, 'shang', '8379c86250c50c0537999a6576e18aa7', 'Jess', '', 'Monty', '1990-01-24', '1', 'user', '76567752', 'jess.monty@ymeal.com', 1, 2, 'shang', 'shang', '4347da', 'py1c2qjcpq.png'),
(7, 'synth', '4b418ed51830f54c3f9af6262b2201d2', 'synth', 'synth', 'synth', '1980-08-19', '2', 'user', '96493119', 'synth.synth@ymeal.com', 1, 0, 'synth', 'synth', 'synthsynth', 'bde5a3192a556564.png'),
(8, 'dsfaasdgasdg', 'd15fd399edbb0b84811b7d18378692a3', 'asdg', 'asd', 'asdgasdgasdg', '2019-08-26', '2', 'admin', '09123987456', '6541234@asd.zxc', 0, 0, 'sdfgsdfgsdf', 'sdfgsdfg', 'dsfadgasdg', '45a3f40a62f2cfef.png');

-- --------------------------------------------------------

--
-- Table structure for table `city_code`
--

DROP TABLE IF EXISTS `city_code`;
CREATE TABLE IF NOT EXISTS `city_code` (
  `id` int(255) NOT NULL DEFAULT 0,
  `psgcCode` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `citymunDesc` text CHARACTER SET utf8 DEFAULT NULL,
  `regDesc` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `provCode` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `citymunCode` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `city_code`
--

INSERT INTO `city_code` (`id`, `psgcCode`, `citymunDesc`, `regDesc`, `provCode`, `citymunCode`) VALUES
(1, '012801000', 'ADAMS', '01', '0128', '012801'),
(2, '012802000', 'BACARRA', '01', '0128', '012802'),
(3, '012803000', 'BADOC', '01', '0128', '012803'),
(4, '012804000', 'BANGUI', '01', '0128', '012804'),
(5, '012805000', 'BATAC', '01', '0128', '012805'),
(6, '012806000', 'BURGOS', '01', '0128', '012806'),
(7, '012807000', 'CARASI', '01', '0128', '012807'),
(8, '012808000', 'CURRIMAO', '01', '0128', '012808'),
(9, '012809000', 'DINGRAS', '01', '0128', '012809'),
(10, '012810000', 'DUMALNEG', '01', '0128', '012810'),
(11, '012811000', 'BANNA (ESPIRITU)', '01', '0128', '012811'),
(12, '012812000', 'LAOAG', '01', '0128', '012812'),
(13, '012813000', 'MARCOS', '01', '0128', '012813'),
(14, '012814000', 'NUEVA ERA', '01', '0128', '012814'),
(15, '012815000', 'PAGUDPUD', '01', '0128', '012815'),
(16, '012816000', 'PAOAY', '01', '0128', '012816'),
(17, '012817000', 'PASUQUIN', '01', '0128', '012817'),
(18, '012818000', 'PIDDIG', '01', '0128', '012818'),
(19, '012819000', 'PINILI', '01', '0128', '012819'),
(20, '012820000', 'SAN NICOLAS', '01', '0128', '012820'),
(21, '012821000', 'SARRAT', '01', '0128', '012821'),
(22, '012822000', 'SOLSONA', '01', '0128', '012822'),
(23, '012823000', 'VINTAR', '01', '0128', '012823'),
(24, '012901000', 'ALILEM', '01', '0129', '012901'),
(25, '012902000', 'BANAYOYO', '01', '0129', '012902'),
(26, '012903000', 'BANTAY', '01', '0129', '012903'),
(27, '012904000', 'BURGOS', '01', '0129', '012904'),
(28, '012905000', 'CABUGAO', '01', '0129', '012905'),
(29, '012906000', 'CANDON', '01', '0129', '012906'),
(30, '012907000', 'CAOAYAN', '01', '0129', '012907'),
(31, '012908000', 'CERVANTES', '01', '0129', '012908'),
(32, '012909000', 'GALIMUYOD', '01', '0129', '012909'),
(33, '012910000', 'GREGORIO DEL PILAR (CONCEPCION)', '01', '0129', '012910'),
(34, '012911000', 'LIDLIDDA', '01', '0129', '012911'),
(35, '012912000', 'MAGSINGAL', '01', '0129', '012912'),
(36, '012913000', 'NAGBUKEL', '01', '0129', '012913'),
(37, '012914000', 'NARVACAN', '01', '0129', '012914'),
(38, '012915000', 'QUIRINO (ANGKAKI)', '01', '0129', '012915'),
(39, '012916000', 'SALCEDO (BAUGEN)', '01', '0129', '012916'),
(40, '012917000', 'SAN EMILIO', '01', '0129', '012917'),
(41, '012918000', 'SAN ESTEBAN', '01', '0129', '012918'),
(42, '012919000', 'SAN ILDEFONSO', '01', '0129', '012919'),
(43, '012920000', 'SAN JUAN (LAPOG)', '01', '0129', '012920'),
(44, '012921000', 'SAN VICENTE', '01', '0129', '012921'),
(45, '012922000', 'SANTA', '01', '0129', '012922'),
(46, '012923000', 'SANTA CATALINA', '01', '0129', '012923'),
(47, '012924000', 'SANTA CRUZ', '01', '0129', '012924'),
(48, '012925000', 'SANTA LUCIA', '01', '0129', '012925'),
(49, '012926000', 'SANTA MARIA', '01', '0129', '012926'),
(50, '012927000', 'SANTIAGO', '01', '0129', '012927'),
(51, '012928000', 'SANTO DOMINGO', '01', '0129', '012928'),
(52, '012929000', 'SIGAY', '01', '0129', '012929'),
(53, '012930000', 'SINAIT', '01', '0129', '012930'),
(54, '012931000', 'SUGPON', '01', '0129', '012931'),
(55, '012932000', 'SUYO', '01', '0129', '012932'),
(56, '012933000', 'TAGUDIN', '01', '0129', '012933'),
(57, '012934000', 'VIGAN', '01', '0129', '012934'),
(58, '013301000', 'AGOO', '01', '0133', '013301'),
(59, '013302000', 'ARINGAY', '01', '0133', '013302'),
(60, '013303000', 'BACNOTAN', '01', '0133', '013303'),
(61, '013304000', 'BAGULIN', '01', '0133', '013304'),
(62, '013305000', 'BALAOAN', '01', '0133', '013305'),
(63, '013306000', 'BANGAR', '01', '0133', '013306'),
(64, '013307000', 'BAUANG', '01', '0133', '013307'),
(65, '013308000', 'BURGOS', '01', '0133', '013308'),
(66, '013309000', 'CABA', '01', '0133', '013309'),
(67, '013310000', 'LUNA', '01', '0133', '013310'),
(68, '013311000', 'NAGUILIAN', '01', '0133', '013311'),
(69, '013312000', 'PUGO', '01', '0133', '013312'),
(70, '013313000', 'ROSARIO', '01', '0133', '013313'),
(71, '013314000', 'SAN FERNANDO', '01', '0133', '013314'),
(72, '013315000', 'SAN GABRIEL', '01', '0133', '013315'),
(73, '013316000', 'SAN JUAN', '01', '0133', '013316'),
(74, '013317000', 'SANTO TOMAS', '01', '0133', '013317'),
(75, '013318000', 'SANTOL', '01', '0133', '013318'),
(76, '013319000', 'SUDIPEN', '01', '0133', '013319'),
(77, '013320000', 'TUBAO', '01', '0133', '013320'),
(78, '015501000', 'AGNO', '01', '0155', '015501'),
(79, '015502000', 'AGUILAR', '01', '0155', '015502'),
(80, '015503000', 'ALAMINOS', '01', '0155', '015503'),
(81, '015504000', 'ALCALA', '01', '0155', '015504'),
(82, '015505000', 'ANDA', '01', '0155', '015505'),
(83, '015506000', 'ASINGAN', '01', '0155', '015506'),
(84, '015507000', 'BALUNGAO', '01', '0155', '015507'),
(85, '015508000', 'BANI', '01', '0155', '015508'),
(86, '015509000', 'BASISTA', '01', '0155', '015509'),
(87, '015510000', 'BAUTISTA', '01', '0155', '015510'),
(88, '015511000', 'BAYAMBANG', '01', '0155', '015511'),
(89, '015512000', 'BINALONAN', '01', '0155', '015512'),
(90, '015513000', 'BINMALEY', '01', '0155', '015513'),
(91, '015514000', 'BOLINAO', '01', '0155', '015514'),
(92, '015515000', 'BUGALLON', '01', '0155', '015515'),
(93, '015516000', 'BURGOS', '01', '0155', '015516'),
(94, '015517000', 'CALASIAO', '01', '0155', '015517'),
(95, '015518000', 'DAGUPAN CITY', '01', '0155', '015518'),
(96, '015519000', 'DASOL', '01', '0155', '015519'),
(97, '015520000', 'INFANTA', '01', '0155', '015520'),
(98, '015521000', 'LABRADOR', '01', '0155', '015521'),
(99, '015522000', 'LINGAYEN', '01', '0155', '015522'),
(100, '015523000', 'MABINI', '01', '0155', '015523'),
(101, '015524000', 'MALASIQUI', '01', '0155', '015524'),
(102, '015525000', 'MANAOAG', '01', '0155', '015525'),
(103, '015526000', 'MANGALDAN', '01', '0155', '015526'),
(104, '015527000', 'MANGATAREM', '01', '0155', '015527'),
(105, '015528000', 'MAPANDAN', '01', '0155', '015528'),
(106, '015529000', 'NATIVIDAD', '01', '0155', '015529'),
(107, '015530000', 'POZORRUBIO', '01', '0155', '015530'),
(108, '015531000', 'ROSALES', '01', '0155', '015531'),
(109, '015532000', 'SAN CARLOS CITY', '01', '0155', '015532'),
(110, '015533000', 'SAN FABIAN', '01', '0155', '015533'),
(111, '015534000', 'SAN JACINTO', '01', '0155', '015534'),
(112, '015535000', 'SAN MANUEL', '01', '0155', '015535'),
(113, '015536000', 'SAN NICOLAS', '01', '0155', '015536'),
(114, '015537000', 'SAN QUINTIN', '01', '0155', '015537'),
(115, '015538000', 'SANTA BARBARA', '01', '0155', '015538'),
(116, '015539000', 'SANTA MARIA', '01', '0155', '015539'),
(117, '015540000', 'SANTO TOMAS', '01', '0155', '015540'),
(118, '015541000', 'SISON', '01', '0155', '015541'),
(119, '015542000', 'SUAL', '01', '0155', '015542'),
(120, '015543000', 'TAYUG', '01', '0155', '015543'),
(121, '015544000', 'UMINGAN', '01', '0155', '015544'),
(122, '015545000', 'URBIZTONDO', '01', '0155', '015545'),
(123, '015546000', 'URDANETA', '01', '0155', '015546'),
(124, '015547000', 'VILLASIS', '01', '0155', '015547'),
(125, '015548000', 'LAOAC', '01', '0155', '015548'),
(126, '020901000', 'BASCO', '02', '0209', '020901'),
(127, '020902000', 'ITBAYAT', '02', '0209', '020902'),
(128, '020903000', 'IVANA', '02', '0209', '020903'),
(129, '020904000', 'MAHATAO', '02', '0209', '020904'),
(130, '020905000', 'SABTANG', '02', '0209', '020905'),
(131, '020906000', 'UYUGAN', '02', '0209', '020906'),
(132, '021501000', 'ABULUG', '02', '0215', '021501'),
(133, '021502000', 'ALCALA', '02', '0215', '021502'),
(134, '021503000', 'ALLACAPAN', '02', '0215', '021503'),
(135, '021504000', 'AMULUNG', '02', '0215', '021504'),
(136, '021505000', 'APARRI', '02', '0215', '021505'),
(137, '021506000', 'BAGGAO', '02', '0215', '021506'),
(138, '021507000', 'BALLESTEROS', '02', '0215', '021507'),
(139, '021508000', 'BUGUEY', '02', '0215', '021508'),
(140, '021509000', 'CALAYAN', '02', '0215', '021509'),
(141, '021510000', 'CAMALANIUGAN', '02', '0215', '021510'),
(142, '021511000', 'CLAVERIA', '02', '0215', '021511'),
(143, '021512000', 'ENRILE', '02', '0215', '021512'),
(144, '021513000', 'GATTARAN', '02', '0215', '021513'),
(145, '021514000', 'GONZAGA', '02', '0215', '021514'),
(146, '021515000', 'IGUIG', '02', '0215', '021515'),
(147, '021516000', 'LAL-LO', '02', '0215', '021516'),
(148, '021517000', 'LASAM', '02', '0215', '021517'),
(149, '021518000', 'PAMPLONA', '02', '0215', '021518'),
(150, '021519000', 'PENABLANCA', '02', '0215', '021519'),
(151, '021520000', 'PIAT', '02', '0215', '021520'),
(152, '021521000', 'RIZAL', '02', '0215', '021521'),
(153, '021522000', 'SANCHEZ-MIRA', '02', '0215', '021522'),
(154, '021523000', 'SANTA ANA', '02', '0215', '021523'),
(155, '021524000', 'SANTA PRAXEDES', '02', '0215', '021524'),
(156, '021525000', 'SANTA TERESITA', '02', '0215', '021525'),
(157, '021526000', 'SANTO NINO (FAIRE)', '02', '0215', '021526'),
(158, '021527000', 'SOLANA', '02', '0215', '021527'),
(159, '021528000', 'TUAO', '02', '0215', '021528'),
(160, '021529000', 'TUGUEGARAO', '02', '0215', '021529'),
(161, '023101000', 'ALICIA', '02', '0231', '023101'),
(162, '023102000', 'ANGADANAN', '02', '0231', '023102'),
(163, '023103000', 'AURORA', '02', '0231', '023103'),
(164, '023104000', 'BENITO SOLIVEN', '02', '0231', '023104'),
(165, '023105000', 'BURGOS', '02', '0231', '023105'),
(166, '023106000', 'CABAGAN', '02', '0231', '023106'),
(167, '023107000', 'CABATUAN', '02', '0231', '023107'),
(168, '023108000', 'CAUAYAN', '02', '0231', '023108'),
(169, '023109000', 'CORDON', '02', '0231', '023109'),
(170, '023110000', 'DINAPIGUE', '02', '0231', '023110'),
(171, '023111000', 'DIVILACAN', '02', '0231', '023111'),
(172, '023112000', 'ECHAGUE', '02', '0231', '023112'),
(173, '023113000', 'GAMU', '02', '0231', '023113'),
(174, '023114000', 'ILAGAN', '02', '0231', '023114'),
(175, '023115000', 'JONES', '02', '0231', '023115'),
(176, '023116000', 'LUNA', '02', '0231', '023116'),
(177, '023117000', 'MACONACON', '02', '0231', '023117'),
(178, '023118000', 'DELFIN ALBANO (MAGSAYSAY)', '02', '0231', '023118'),
(179, '023119000', 'MALLIG', '02', '0231', '023119'),
(180, '023120000', 'NAGUILIAN', '02', '0231', '023120'),
(181, '023121000', 'PALANAN', '02', '0231', '023121'),
(182, '023122000', 'QUEZON', '02', '0231', '023122'),
(183, '023123000', 'QUIRINO', '02', '0231', '023123'),
(184, '023124000', 'RAMON', '02', '0231', '023124'),
(185, '023125000', 'REINA MERCEDES', '02', '0231', '023125'),
(186, '023126000', 'ROXAS', '02', '0231', '023126'),
(187, '023127000', 'SAN AGUSTIN', '02', '0231', '023127'),
(188, '023128000', 'SAN GUILLERMO', '02', '0231', '023128'),
(189, '023129000', 'SAN ISIDRO', '02', '0231', '023129'),
(190, '023130000', 'SAN MANUEL', '02', '0231', '023130'),
(191, '023131000', 'SAN MARIANO', '02', '0231', '023131'),
(192, '023132000', 'SAN MATEO', '02', '0231', '023132'),
(193, '023133000', 'SAN PABLO', '02', '0231', '023133'),
(194, '023134000', 'SANTA MARIA', '02', '0231', '023134'),
(195, '023135000', 'SANTIAGO', '02', '0231', '023135'),
(196, '023136000', 'SANTO TOMAS', '02', '0231', '023136'),
(197, '023137000', 'TUMAUINI', '02', '0231', '023137'),
(198, '025001000', 'AMBAGUIO', '02', '0250', '025001'),
(199, '025002000', 'ARITAO', '02', '0250', '025002'),
(200, '025003000', 'BAGABAG', '02', '0250', '025003'),
(201, '025004000', 'BAMBANG', '02', '0250', '025004'),
(202, '025005000', 'BAYOMBONG', '02', '0250', '025005'),
(203, '025006000', 'DIADI', '02', '0250', '025006'),
(204, '025007000', 'DUPAX DEL NORTE', '02', '0250', '025007'),
(205, '025008000', 'DUPAX DEL SUR', '02', '0250', '025008'),
(206, '025009000', 'KASIBU', '02', '0250', '025009'),
(207, '025010000', 'KAYAPA', '02', '0250', '025010'),
(208, '025011000', 'QUEZON', '02', '0250', '025011'),
(209, '025012000', 'SANTA FE', '02', '0250', '025012'),
(210, '025013000', 'SOLANO', '02', '0250', '025013'),
(211, '025014000', 'VILLAVERDE', '02', '0250', '025014'),
(212, '025015000', 'ALFONSO CASTANEDA', '02', '0250', '025015'),
(213, '025701000', 'AGLIPAY', '02', '0257', '025701'),
(214, '025702000', 'CABARROGUIS', '02', '0257', '025702'),
(215, '025703000', 'DIFFUN', '02', '0257', '025703'),
(216, '025704000', 'MADDELA', '02', '0257', '025704'),
(217, '025705000', 'SAGUDAY', '02', '0257', '025705'),
(218, '025706000', 'NAGTIPUNAN', '02', '0257', '025706'),
(219, '030801000', 'ABUCAY', '03', '0308', '030801'),
(220, '030802000', 'BAGAC', '03', '0308', '030802'),
(221, '030803000', 'BALANGA', '03', '0308', '030803'),
(222, '030804000', 'DINALUPIHAN', '03', '0308', '030804'),
(223, '030805000', 'HERMOSA', '03', '0308', '030805'),
(224, '030806000', 'LIMAY', '03', '0308', '030806'),
(225, '030807000', 'MARIVELES', '03', '0308', '030807'),
(226, '030808000', 'MORONG', '03', '0308', '030808'),
(227, '030809000', 'ORANI', '03', '0308', '030809'),
(228, '030810000', 'ORION', '03', '0308', '030810'),
(229, '030811000', 'PILAR', '03', '0308', '030811'),
(230, '030812000', 'SAMAL', '03', '0308', '030812'),
(231, '031401000', 'ANGAT', '03', '0314', '031401'),
(232, '031402000', 'BALAGTAS (BIGAA)', '03', '0314', '031402'),
(233, '031403000', 'BALIUAG', '03', '0314', '031403'),
(234, '031404000', 'BOCAUE', '03', '0314', '031404'),
(235, '031405000', 'BULACAN', '03', '0314', '031405'),
(236, '031406000', 'BUSTOS', '03', '0314', '031406'),
(237, '031407000', 'CALUMPIT', '03', '0314', '031407'),
(238, '031408000', 'GUIGUINTO', '03', '0314', '031408'),
(239, '031409000', 'HAGONOY', '03', '0314', '031409'),
(240, '031410000', 'MALOLOS', '03', '0314', '031410'),
(241, '031411000', 'MARILAO', '03', '0314', '031411'),
(242, '031412000', 'MEYCAUAYAN', '03', '0314', '031412'),
(243, '031413000', 'NORZAGARAY', '03', '0314', '031413'),
(244, '031414000', 'OBANDO', '03', '0314', '031414'),
(245, '031415000', 'PANDI', '03', '0314', '031415'),
(246, '031416000', 'PAOMBONG', '03', '0314', '031416'),
(247, '031417000', 'PLARIDEL', '03', '0314', '031417'),
(248, '031418000', 'PULILAN', '03', '0314', '031418'),
(249, '031419000', 'SAN ILDEFONSO', '03', '0314', '031419'),
(250, '031420000', 'SAN JOSE DEL MONTE', '03', '0314', '031420'),
(251, '031421000', 'SAN MIGUEL', '03', '0314', '031421'),
(252, '031422000', 'SAN RAFAEL', '03', '0314', '031422'),
(253, '031423000', 'SANTA MARIA', '03', '0314', '031423'),
(254, '031424000', 'DONA REMEDIOS TRINIDAD', '03', '0314', '031424'),
(255, '034901000', 'ALIAGA', '03', '0349', '034901'),
(256, '034902000', 'BONGABON', '03', '0349', '034902'),
(257, '034903000', 'CABANATUAN CITY', '03', '0349', '034903'),
(258, '034904000', 'CABIAO', '03', '0349', '034904'),
(259, '034905000', 'CARRANGLAN', '03', '0349', '034905'),
(260, '034906000', 'CUYAPO', '03', '0349', '034906'),
(261, '034907000', 'GABALDON (BITULOK & SABANI)', '03', '0349', '034907'),
(262, '034908000', 'GAPAN', '03', '0349', '034908'),
(263, '034909000', 'GENERAL MAMERTO NATIVIDAD', '03', '0349', '034909'),
(264, '034910000', 'GENERAL TINIO (PAPAYA)', '03', '0349', '034910'),
(265, '034911000', 'GUIMBA', '03', '0349', '034911'),
(266, '034912000', 'JAEN', '03', '0349', '034912'),
(267, '034913000', 'LAUR', '03', '0349', '034913'),
(268, '034914000', 'LICAB', '03', '0349', '034914'),
(269, '034915000', 'LLANERA', '03', '0349', '034915'),
(270, '034916000', 'LUPAO', '03', '0349', '034916'),
(271, '034917000', 'SCIENCE MUNOZ', '03', '0349', '034917'),
(272, '034918000', 'NAMPICUAN', '03', '0349', '034918'),
(273, '034919000', 'PALAYAN', '03', '0349', '034919'),
(274, '034920000', 'PANTABANGAN', '03', '0349', '034920'),
(275, '034921000', 'PENARANDA', '03', '0349', '034921'),
(276, '034922000', 'QUEZON', '03', '0349', '034922'),
(277, '034923000', 'RIZAL', '03', '0349', '034923'),
(278, '034924000', 'SAN ANTONIO', '03', '0349', '034924'),
(279, '034925000', 'SAN ISIDRO', '03', '0349', '034925'),
(280, '034926000', 'SAN JOSE CITY', '03', '0349', '034926'),
(281, '034927000', 'SAN LEONARDO', '03', '0349', '034927'),
(282, '034928000', 'SANTA ROSA', '03', '0349', '034928'),
(283, '034929000', 'SANTO DOMINGO', '03', '0349', '034929'),
(284, '034930000', 'TALAVERA', '03', '0349', '034930'),
(285, '034931000', 'TALUGTUG', '03', '0349', '034931'),
(286, '034932000', 'ZARAGOZA', '03', '0349', '034932'),
(287, '035401000', 'ANGELES CITY', '03', '0354', '035401'),
(288, '035402000', 'APALIT', '03', '0354', '035402'),
(289, '035403000', 'ARAYAT', '03', '0354', '035403'),
(290, '035404000', 'BACOLOR', '03', '0354', '035404'),
(291, '035405000', 'CANDABA', '03', '0354', '035405'),
(292, '035406000', 'FLORIDABLANCA', '03', '0354', '035406'),
(293, '035407000', 'GUAGUA', '03', '0354', '035407'),
(294, '035408000', 'LUBAO', '03', '0354', '035408'),
(295, '035409000', 'MABALACAT CITY', '03', '0354', '035409'),
(296, '035410000', 'MACABEBE', '03', '0354', '035410'),
(297, '035411000', 'MAGALANG', '03', '0354', '035411'),
(298, '035412000', 'MASANTOL', '03', '0354', '035412'),
(299, '035413000', 'MEXICO', '03', '0354', '035413'),
(300, '035414000', 'MINALIN', '03', '0354', '035414'),
(301, '035415000', 'PORAC', '03', '0354', '035415'),
(302, '035416000', 'SAN FERNANDO', '03', '0354', '035416'),
(303, '035417000', 'SAN LUIS', '03', '0354', '035417'),
(304, '035418000', 'SAN SIMON', '03', '0354', '035418'),
(305, '035419000', 'SANTA ANA', '03', '0354', '035419'),
(306, '035420000', 'SANTA RITA', '03', '0354', '035420'),
(307, '035421000', 'SANTO TOMAS', '03', '0354', '035421'),
(308, '035422000', 'SASMUAN (Sexmoan)', '03', '0354', '035422'),
(309, '036901000', 'ANAO', '03', '0369', '036901'),
(310, '036902000', 'BAMBAN', '03', '0369', '036902'),
(311, '036903000', 'CAMILING', '03', '0369', '036903'),
(312, '036904000', 'CAPAS', '03', '0369', '036904'),
(313, '036905000', 'CONCEPCION', '03', '0369', '036905'),
(314, '036906000', 'GERONA', '03', '0369', '036906'),
(315, '036907000', 'LA PAZ', '03', '0369', '036907'),
(316, '036908000', 'MAYANTOC', '03', '0369', '036908'),
(317, '036909000', 'MONCADA', '03', '0369', '036909'),
(318, '036910000', 'PANIQUI', '03', '0369', '036910'),
(319, '036911000', 'PURA', '03', '0369', '036911'),
(320, '036912000', 'RAMOS', '03', '0369', '036912'),
(321, '036913000', 'SAN CLEMENTE', '03', '0369', '036913'),
(322, '036914000', 'SAN MANUEL', '03', '0369', '036914'),
(323, '036915000', 'SANTA IGNACIA', '03', '0369', '036915'),
(324, '036916000', 'TARLAC', '03', '0369', '036916'),
(325, '036917000', 'VICTORIA', '03', '0369', '036917'),
(326, '036918000', 'SAN JOSE', '03', '0369', '036918'),
(327, '037101000', 'BOTOLAN', '03', '0371', '037101'),
(328, '037102000', 'CABANGAN', '03', '0371', '037102'),
(329, '037103000', 'CANDELARIA', '03', '0371', '037103'),
(330, '037104000', 'CASTILLEJOS', '03', '0371', '037104'),
(331, '037105000', 'IBA', '03', '0371', '037105'),
(332, '037106000', 'MASINLOC', '03', '0371', '037106'),
(333, '037107000', 'OLONGAPO CITY', '03', '0371', '037107'),
(334, '037108000', 'PALAUIG', '03', '0371', '037108'),
(335, '037109000', 'SAN ANTONIO', '03', '0371', '037109'),
(336, '037110000', 'SAN FELIPE', '03', '0371', '037110'),
(337, '037111000', 'SAN MARCELINO', '03', '0371', '037111'),
(338, '037112000', 'SAN NARCISO', '03', '0371', '037112'),
(339, '037113000', 'SANTA CRUZ', '03', '0371', '037113'),
(340, '037114000', 'SUBIC', '03', '0371', '037114'),
(341, '037701000', 'BALER', '03', '0377', '037701'),
(342, '037702000', 'CASIGURAN', '03', '0377', '037702'),
(343, '037703000', 'DILASAG', '03', '0377', '037703'),
(344, '037704000', 'DINALUNGAN', '03', '0377', '037704'),
(345, '037705000', 'DINGALAN', '03', '0377', '037705'),
(346, '037706000', 'DIPACULAO', '03', '0377', '037706'),
(347, '037707000', 'MARIA AURORA', '03', '0377', '037707'),
(348, '037708000', 'SAN LUIS', '03', '0377', '037708'),
(349, '041001000', 'AGONCILLO', '04', '0410', '041001'),
(350, '041002000', 'ALITAGTAG', '04', '0410', '041002'),
(351, '041003000', 'BALAYAN', '04', '0410', '041003'),
(352, '041004000', 'BALETE', '04', '0410', '041004'),
(353, '041005000', 'BATANGAS', '04', '0410', '041005'),
(354, '041006000', 'BAUAN', '04', '0410', '041006'),
(355, '041007000', 'CALACA', '04', '0410', '041007'),
(356, '041008000', 'CALATAGAN', '04', '0410', '041008'),
(357, '041009000', 'CUENCA', '04', '0410', '041009'),
(358, '041010000', 'IBAAN', '04', '0410', '041010'),
(359, '041011000', 'LAUREL', '04', '0410', '041011'),
(360, '041012000', 'LEMERY', '04', '0410', '041012'),
(361, '041013000', 'LIAN', '04', '0410', '041013'),
(362, '041014000', 'LIPA CITY', '04', '0410', '041014'),
(363, '041015000', 'LOBO', '04', '0410', '041015'),
(364, '041016000', 'MABINI', '04', '0410', '041016'),
(365, '041017000', 'MALVAR', '04', '0410', '041017'),
(366, '041018000', 'MATAASNAKAHOY', '04', '0410', '041018'),
(367, '041019000', 'NASUGBU', '04', '0410', '041019'),
(368, '041020000', 'PADRE GARCIA', '04', '0410', '041020'),
(369, '041021000', 'ROSARIO', '04', '0410', '041021'),
(370, '041022000', 'SAN JOSE', '04', '0410', '041022'),
(371, '041023000', 'SAN JUAN', '04', '0410', '041023'),
(372, '041024000', 'SAN LUIS', '04', '0410', '041024'),
(373, '041025000', 'SAN NICOLAS', '04', '0410', '041025'),
(374, '041026000', 'SAN PASCUAL', '04', '0410', '041026'),
(375, '041027000', 'SANTA TERESITA', '04', '0410', '041027'),
(376, '041028000', 'SANTO TOMAS', '04', '0410', '041028'),
(377, '041029000', 'TAAL', '04', '0410', '041029'),
(378, '041030000', 'TALISAY', '04', '0410', '041030'),
(379, '041031000', 'TANAUAN', '04', '0410', '041031'),
(380, '041032000', 'TAYSAN', '04', '0410', '041032'),
(381, '041033000', 'TINGLOY', '04', '0410', '041033'),
(382, '041034000', 'TUY', '04', '0410', '041034'),
(383, '042101000', 'ALFONSO', '04', '0421', '042101'),
(384, '042102000', 'AMADEO', '04', '0421', '042102'),
(385, '042103000', 'BACOOR CITY', '04', '0421', '042103'),
(386, '042104000', 'CARMONA', '04', '0421', '042104'),
(387, '042105000', 'CAVITE CITY', '04', '0421', '042105'),
(388, '042106000', 'DASMARINAS', '04', '0421', '042106'),
(389, '042107000', 'GENERAL EMILIO AGUINALDO', '04', '0421', '042107'),
(390, '042108000', 'GENERAL TRIAS', '04', '0421', '042108'),
(391, '042109000', 'IMUS CITY', '04', '0421', '042109'),
(392, '042110000', 'INDANG', '04', '0421', '042110'),
(393, '042111000', 'KAWIT', '04', '0421', '042111'),
(394, '042112000', 'MAGALLANES', '04', '0421', '042112'),
(395, '042113000', 'MARAGONDON', '04', '0421', '042113'),
(396, '042114000', 'MENDEZ (MENDEZ-NUNEZ)', '04', '0421', '042114'),
(397, '042115000', 'NAIC', '04', '0421', '042115'),
(398, '042116000', 'NOVELETA', '04', '0421', '042116'),
(399, '042117000', 'ROSARIO', '04', '0421', '042117'),
(400, '042118000', 'SILANG', '04', '0421', '042118'),
(401, '042119000', 'TAGAYTAY CITY', '04', '0421', '042119'),
(402, '042120000', 'TANZA', '04', '0421', '042120'),
(403, '042121000', 'TERNATE', '04', '0421', '042121'),
(404, '042122000', 'TRECE MARTIRES', '04', '0421', '042122'),
(405, '042123000', 'GEN. MARIANO ALVAREZ', '04', '0421', '042123'),
(406, '043401000', 'ALAMINOS', '04', '0434', '043401'),
(407, '043402000', 'BAY', '04', '0434', '043402'),
(408, '043403000', 'BINAN', '04', '0434', '043403'),
(409, '043404000', 'CABUYAO CITY', '04', '0434', '043404'),
(410, '043405000', 'CALAMBA', '04', '0434', '043405'),
(411, '043406000', 'CALAUAN', '04', '0434', '043406'),
(412, '043407000', 'CAVINTI', '04', '0434', '043407'),
(413, '043408000', 'FAMY', '04', '0434', '043408'),
(414, '043409000', 'KALAYAAN', '04', '0434', '043409'),
(415, '043410000', 'LILIW', '04', '0434', '043410'),
(416, '043411000', 'LOS BANOS', '04', '0434', '043411'),
(417, '043412000', 'LUISIANA', '04', '0434', '043412'),
(418, '043413000', 'LUMBAN', '04', '0434', '043413'),
(419, '043414000', 'MABITAC', '04', '0434', '043414'),
(420, '043415000', 'MAGDALENA', '04', '0434', '043415'),
(421, '043416000', 'MAJAYJAY', '04', '0434', '043416'),
(422, '043417000', 'NAGCARLAN', '04', '0434', '043417'),
(423, '043418000', 'PAETE', '04', '0434', '043418'),
(424, '043419000', 'PAGSANJAN', '04', '0434', '043419'),
(425, '043420000', 'PAKIL', '04', '0434', '043420'),
(426, '043421000', 'PANGIL', '04', '0434', '043421'),
(427, '043422000', 'PILA', '04', '0434', '043422'),
(428, '043423000', 'RIZAL', '04', '0434', '043423'),
(429, '043424000', 'SAN PABLO CITY', '04', '0434', '043424'),
(430, '043425000', 'SAN PEDRO', '04', '0434', '043425'),
(431, '043426000', 'SANTA CRUZ', '04', '0434', '043426'),
(432, '043427000', 'SANTA MARIA', '04', '0434', '043427'),
(433, '043428000', 'SANTA ROSA', '04', '0434', '043428'),
(434, '043429000', 'SINILOAN', '04', '0434', '043429'),
(435, '043430000', 'VICTORIA', '04', '0434', '043430'),
(436, '045601000', 'AGDANGAN', '04', '0456', '045601'),
(437, '045602000', 'ALABAT', '04', '0456', '045602'),
(438, '045603000', 'ATIMONAN', '04', '0456', '045603'),
(439, '045605000', 'BUENAVISTA', '04', '0456', '045605'),
(440, '045606000', 'BURDEOS', '04', '0456', '045606'),
(441, '045607000', 'CALAUAG', '04', '0456', '045607'),
(442, '045608000', 'CANDELARIA', '04', '0456', '045608'),
(443, '045610000', 'CATANAUAN', '04', '0456', '045610'),
(444, '045615000', 'DOLORES', '04', '0456', '045615'),
(445, '045616000', 'GENERAL LUNA', '04', '0456', '045616'),
(446, '045617000', 'GENERAL NAKAR', '04', '0456', '045617'),
(447, '045618000', 'GUINAYANGAN', '04', '0456', '045618'),
(448, '045619000', 'GUMACA', '04', '0456', '045619'),
(449, '045620000', 'INFANTA', '04', '0456', '045620'),
(450, '045621000', 'JOMALIG', '04', '0456', '045621'),
(451, '045622000', 'LOPEZ', '04', '0456', '045622'),
(452, '045623000', 'LUCBAN', '04', '0456', '045623'),
(453, '045624000', 'LUCENA', '04', '0456', '045624'),
(454, '045625000', 'MACALELON', '04', '0456', '045625'),
(455, '045627000', 'MAUBAN', '04', '0456', '045627'),
(456, '045628000', 'MULANAY', '04', '0456', '045628'),
(457, '045629000', 'PADRE BURGOS', '04', '0456', '045629'),
(458, '045630000', 'PAGBILAO', '04', '0456', '045630'),
(459, '045631000', 'PANUKULAN', '04', '0456', '045631'),
(460, '045632000', 'PATNANUNGAN', '04', '0456', '045632'),
(461, '045633000', 'PEREZ', '04', '0456', '045633'),
(462, '045634000', 'PITOGO', '04', '0456', '045634'),
(463, '045635000', 'PLARIDEL', '04', '0456', '045635'),
(464, '045636000', 'POLILLO', '04', '0456', '045636'),
(465, '045637000', 'QUEZON', '04', '0456', '045637'),
(466, '045638000', 'REAL', '04', '0456', '045638'),
(467, '045639000', 'SAMPALOC', '04', '0456', '045639'),
(468, '045640000', 'SAN ANDRES', '04', '0456', '045640'),
(469, '045641000', 'SAN ANTONIO', '04', '0456', '045641'),
(470, '045642000', 'SAN FRANCISCO (AURORA)', '04', '0456', '045642'),
(471, '045644000', 'SAN NARCISO', '04', '0456', '045644'),
(472, '045645000', 'SARIAYA', '04', '0456', '045645'),
(473, '045646000', 'TAGKAWAYAN', '04', '0456', '045646'),
(474, '045647000', 'TAYABAS', '04', '0456', '045647'),
(475, '045648000', 'TIAONG', '04', '0456', '045648'),
(476, '045649000', 'UNISAN', '04', '0456', '045649'),
(477, '045801000', 'ANGONO', '04', '0458', '045801'),
(478, '045802000', 'ANTIPOLO', '04', '0458', '045802'),
(479, '045803000', 'BARAS', '04', '0458', '045803'),
(480, '045804000', 'BINANGONAN', '04', '0458', '045804'),
(481, '045805000', 'CAINTA', '04', '0458', '045805'),
(482, '045806000', 'CARDONA', '04', '0458', '045806'),
(483, '045807000', 'JALA-JALA', '04', '0458', '045807'),
(484, '045808000', 'RODRIGUEZ (MONTALBAN)', '04', '0458', '045808'),
(485, '045809000', 'MORONG', '04', '0458', '045809'),
(486, '045810000', 'PILILLA', '04', '0458', '045810'),
(487, '045811000', 'SAN MATEO', '04', '0458', '045811'),
(488, '045812000', 'TANAY', '04', '0458', '045812'),
(489, '045813000', 'TAYTAY', '04', '0458', '045813'),
(490, '045814000', 'TERESA', '04', '0458', '045814'),
(491, '174001000', 'BOAC', '17', '1740', '174001'),
(492, '174002000', 'BUENAVISTA', '17', '1740', '174002'),
(493, '174003000', 'GASAN', '17', '1740', '174003'),
(494, '174004000', 'MOGPOG', '17', '1740', '174004'),
(495, '174005000', 'SANTA CRUZ', '17', '1740', '174005'),
(496, '174006000', 'TORRIJOS', '17', '1740', '174006'),
(497, '175101000', 'ABRA DE ILOG', '17', '1751', '175101'),
(498, '175102000', 'CALINTAAN', '17', '1751', '175102'),
(499, '175103000', 'LOOC', '17', '1751', '175103'),
(500, '175104000', 'LUBANG', '17', '1751', '175104'),
(501, '175105000', 'MAGSAYSAY', '17', '1751', '175105'),
(502, '175106000', 'MAMBURAO', '17', '1751', '175106'),
(503, '175107000', 'PALUAN', '17', '1751', '175107'),
(504, '175108000', 'RIZAL', '17', '1751', '175108'),
(505, '175109000', 'SABLAYAN', '17', '1751', '175109'),
(506, '175110000', 'SAN JOSE', '17', '1751', '175110'),
(507, '175111000', 'SANTA CRUZ', '17', '1751', '175111'),
(508, '175201000', 'BACO', '17', '1752', '175201'),
(509, '175202000', 'BANSUD', '17', '1752', '175202'),
(510, '175203000', 'BONGABONG', '17', '1752', '175203'),
(511, '175204000', 'BULALACAO (SAN PEDRO)', '17', '1752', '175204'),
(512, '175205000', 'CALAPAN', '17', '1752', '175205'),
(513, '175206000', 'GLORIA', '17', '1752', '175206'),
(514, '175207000', 'MANSALAY', '17', '1752', '175207'),
(515, '175208000', 'NAUJAN', '17', '1752', '175208'),
(516, '175209000', 'PINAMALAYAN', '17', '1752', '175209'),
(517, '175210000', 'POLA', '17', '1752', '175210'),
(518, '175211000', 'PUERTO GALERA', '17', '1752', '175211'),
(519, '175212000', 'ROXAS', '17', '1752', '175212'),
(520, '175213000', 'SAN TEODORO', '17', '1752', '175213'),
(521, '175214000', 'SOCORRO', '17', '1752', '175214'),
(522, '175215000', 'VICTORIA', '17', '1752', '175215'),
(523, '175301000', 'ABORLAN', '17', '1753', '175301'),
(524, '175302000', 'AGUTAYA', '17', '1753', '175302'),
(525, '175303000', 'ARACELI', '17', '1753', '175303'),
(526, '175304000', 'BALABAC', '17', '1753', '175304'),
(527, '175305000', 'BATARAZA', '17', '1753', '175305'),
(528, '175306000', 'BROOKES POINT', '17', '1753', '175306'),
(529, '175307000', 'BUSUANGA', '17', '1753', '175307'),
(530, '175308000', 'CAGAYANCILLO', '17', '1753', '175308'),
(531, '175309000', 'CORON', '17', '1753', '175309'),
(532, '175310000', 'CUYO', '17', '1753', '175310'),
(533, '175311000', 'DUMARAN', '17', '1753', '175311'),
(534, '175312000', 'EL NIDO (BACUIT)', '17', '1753', '175312'),
(535, '175313000', 'LINAPACAN', '17', '1753', '175313'),
(536, '175314000', 'MAGSAYSAY', '17', '1753', '175314'),
(537, '175315000', 'NARRA', '17', '1753', '175315'),
(538, '175316000', 'PUERTO PRINCESA', '17', '1753', '175316'),
(539, '175317000', 'QUEZON', '17', '1753', '175317'),
(540, '175318000', 'ROXAS', '17', '1753', '175318'),
(541, '175319000', 'SAN VICENTE', '17', '1753', '175319'),
(542, '175320000', 'TAYTAY', '17', '1753', '175320'),
(543, '175321000', 'KALAYAAN', '17', '1753', '175321'),
(544, '175322000', 'CULION', '17', '1753', '175322'),
(545, '175323000', 'RIZAL (MARCOS)', '17', '1753', '175323'),
(546, '175324000', 'SOFRONIO ESPANOLA', '17', '1753', '175324'),
(547, '175901000', 'ALCANTARA', '17', '1759', '175901'),
(548, '175902000', 'BANTON', '17', '1759', '175902'),
(549, '175903000', 'CAJIDIOCAN', '17', '1759', '175903'),
(550, '175904000', 'CALATRAVA', '17', '1759', '175904'),
(551, '175905000', 'CONCEPCION', '17', '1759', '175905'),
(552, '175906000', 'CORCUERA', '17', '1759', '175906'),
(553, '175907000', 'LOOC', '17', '1759', '175907'),
(554, '175908000', 'MAGDIWANG', '17', '1759', '175908'),
(555, '175909000', 'ODIONGAN', '17', '1759', '175909'),
(556, '175910000', 'ROMBLON', '17', '1759', '175910'),
(557, '175911000', 'SAN AGUSTIN', '17', '1759', '175911'),
(558, '175912000', 'SAN ANDRES', '17', '1759', '175912'),
(559, '175913000', 'SAN FERNANDO', '17', '1759', '175913'),
(560, '175914000', 'SAN JOSE', '17', '1759', '175914'),
(561, '175915000', 'SANTA FE', '17', '1759', '175915'),
(562, '175916000', 'FERROL', '17', '1759', '175916'),
(563, '175917000', 'SANTA MARIA (IMELDA)', '17', '1759', '175917'),
(564, '050501000', 'BACACAY', '05', '0505', '050501'),
(565, '050502000', 'CAMALIG', '05', '0505', '050502'),
(566, '050503000', 'DARAGA (LOCSIN)', '05', '0505', '050503'),
(567, '050504000', 'GUINOBATAN', '05', '0505', '050504'),
(568, '050505000', 'JOVELLAR', '05', '0505', '050505'),
(569, '050506000', 'LEGAZPI', '05', '0505', '050506'),
(570, '050507000', 'LIBON', '05', '0505', '050507'),
(571, '050508000', 'LIGAO', '05', '0505', '050508'),
(572, '050509000', 'MALILIPOT', '05', '0505', '050509'),
(573, '050510000', 'MALINAO', '05', '0505', '050510'),
(574, '050511000', 'MANITO', '05', '0505', '050511'),
(575, '050512000', 'OAS', '05', '0505', '050512'),
(576, '050513000', 'PIO DURAN', '05', '0505', '050513'),
(577, '050514000', 'POLANGUI', '05', '0505', '050514'),
(578, '050515000', 'RAPU-RAPU', '05', '0505', '050515'),
(579, '050516000', 'SANTO DOMINGO (LIBOG)', '05', '0505', '050516'),
(580, '050517000', 'TABACO', '05', '0505', '050517'),
(581, '050518000', 'TIWI', '05', '0505', '050518'),
(582, '051601000', 'BASUD', '05', '0516', '051601'),
(583, '051602000', 'CAPALONGA', '05', '0516', '051602'),
(584, '051603000', 'DAET', '05', '0516', '051603'),
(585, '051604000', 'SAN LORENZO RUIZ (IMELDA)', '05', '0516', '051604'),
(586, '051605000', 'JOSE PANGANIBAN', '05', '0516', '051605'),
(587, '051606000', 'LABO', '05', '0516', '051606'),
(588, '051607000', 'MERCEDES', '05', '0516', '051607'),
(589, '051608000', 'PARACALE', '05', '0516', '051608'),
(590, '051609000', 'SAN VICENTE', '05', '0516', '051609'),
(591, '051610000', 'SANTA ELENA', '05', '0516', '051610'),
(592, '051611000', 'TALISAY', '05', '0516', '051611'),
(593, '051612000', 'VINZONS', '05', '0516', '051612'),
(594, '051701000', 'BAAO', '05', '0517', '051701'),
(595, '051702000', 'BALATAN', '05', '0517', '051702'),
(596, '051703000', 'BATO', '05', '0517', '051703'),
(597, '051704000', 'BOMBON', '05', '0517', '051704'),
(598, '051705000', 'BUHI', '05', '0517', '051705'),
(599, '051706000', 'BULA', '05', '0517', '051706'),
(600, '051707000', 'CABUSAO', '05', '0517', '051707'),
(601, '051708000', 'CALABANGA', '05', '0517', '051708'),
(602, '051709000', 'CAMALIGAN', '05', '0517', '051709'),
(603, '051710000', 'CANAMAN', '05', '0517', '051710'),
(604, '051711000', 'CARAMOAN', '05', '0517', '051711'),
(605, '051712000', 'DEL GALLEGO', '05', '0517', '051712'),
(606, '051713000', 'GAINZA', '05', '0517', '051713'),
(607, '051714000', 'GARCHITORENA', '05', '0517', '051714'),
(608, '051715000', 'GOA', '05', '0517', '051715'),
(609, '051716000', 'IRIGA CITY', '05', '0517', '051716'),
(610, '051717000', 'LAGONOY', '05', '0517', '051717'),
(611, '051718000', 'LIBMANAN', '05', '0517', '051718'),
(612, '051719000', 'LUPI', '05', '0517', '051719'),
(613, '051720000', 'MAGARAO', '05', '0517', '051720'),
(614, '051721000', 'MILAOR', '05', '0517', '051721'),
(615, '051722000', 'MINALABAC', '05', '0517', '051722'),
(616, '051723000', 'NABUA', '05', '0517', '051723'),
(617, '051724000', 'NAGA CITY', '05', '0517', '051724'),
(618, '051725000', 'OCAMPO', '05', '0517', '051725'),
(619, '051726000', 'PAMPLONA', '05', '0517', '051726'),
(620, '051727000', 'PASACAO', '05', '0517', '051727'),
(621, '051728000', 'PILI', '05', '0517', '051728'),
(622, '051729000', 'PRESENTACION (PARUBCAN)', '05', '0517', '051729'),
(623, '051730000', 'RAGAY', '05', '0517', '051730'),
(624, '051731000', 'SAGNAY', '05', '0517', '051731'),
(625, '051732000', 'SAN FERNANDO', '05', '0517', '051732'),
(626, '051733000', 'SAN JOSE', '05', '0517', '051733'),
(627, '051734000', 'SIPOCOT', '05', '0517', '051734'),
(628, '051735000', 'SIRUMA', '05', '0517', '051735'),
(629, '051736000', 'TIGAON', '05', '0517', '051736'),
(630, '051737000', 'TINAMBAC', '05', '0517', '051737'),
(631, '052001000', 'BAGAMANOC', '05', '0520', '052001'),
(632, '052002000', 'BARAS', '05', '0520', '052002'),
(633, '052003000', 'BATO', '05', '0520', '052003'),
(634, '052004000', 'CARAMORAN', '05', '0520', '052004'),
(635, '052005000', 'GIGMOTO', '05', '0520', '052005'),
(636, '052006000', 'PANDAN', '05', '0520', '052006'),
(637, '052007000', 'PANGANIBAN (PAYO)', '05', '0520', '052007'),
(638, '052008000', 'SAN ANDRES (CALOLBON)', '05', '0520', '052008'),
(639, '052009000', 'SAN MIGUEL', '05', '0520', '052009'),
(640, '052010000', 'VIGA', '05', '0520', '052010'),
(641, '052011000', 'VIRAC', '05', '0520', '052011'),
(642, '054101000', 'AROROY', '05', '0541', '054101'),
(643, '054102000', 'BALENO', '05', '0541', '054102'),
(644, '054103000', 'BALUD', '05', '0541', '054103'),
(645, '054104000', 'BATUAN', '05', '0541', '054104'),
(646, '054105000', 'CATAINGAN', '05', '0541', '054105'),
(647, '054106000', 'CAWAYAN', '05', '0541', '054106'),
(648, '054107000', 'CLAVERIA', '05', '0541', '054107'),
(649, '054108000', 'DIMASALANG', '05', '0541', '054108'),
(650, '054109000', 'ESPERANZA', '05', '0541', '054109'),
(651, '054110000', 'MANDAON', '05', '0541', '054110'),
(652, '054111000', 'MASBATE', '05', '0541', '054111'),
(653, '054112000', 'MILAGROS', '05', '0541', '054112'),
(654, '054113000', 'MOBO', '05', '0541', '054113'),
(655, '054114000', 'MONREAL', '05', '0541', '054114'),
(656, '054115000', 'PALANAS', '05', '0541', '054115'),
(657, '054116000', 'PIO V. CORPUZ (LIMBUHAN)', '05', '0541', '054116'),
(658, '054117000', 'PLACER', '05', '0541', '054117'),
(659, '054118000', 'SAN FERNANDO', '05', '0541', '054118'),
(660, '054119000', 'SAN JACINTO', '05', '0541', '054119'),
(661, '054120000', 'SAN PASCUAL', '05', '0541', '054120'),
(662, '054121000', 'USON', '05', '0541', '054121'),
(663, '056202000', 'BARCELONA', '05', '0562', '056202'),
(664, '056203000', 'BULAN', '05', '0562', '056203'),
(665, '056204000', 'BULUSAN', '05', '0562', '056204'),
(666, '056205000', 'CASIGURAN', '05', '0562', '056205'),
(667, '056206000', 'CASTILLA', '05', '0562', '056206'),
(668, '056207000', 'DONSOL', '05', '0562', '056207'),
(669, '056208000', 'GUBAT', '05', '0562', '056208'),
(670, '056209000', 'IROSIN', '05', '0562', '056209'),
(671, '056210000', 'JUBAN', '05', '0562', '056210'),
(672, '056211000', 'MAGALLANES', '05', '0562', '056211'),
(673, '056212000', 'MATNOG', '05', '0562', '056212'),
(674, '056213000', 'PILAR', '05', '0562', '056213'),
(675, '056214000', 'PRIETO DIAZ', '05', '0562', '056214'),
(676, '056215000', 'SANTA MAGDALENA', '05', '0562', '056215'),
(677, '056216000', 'SORSOGON', '05', '0562', '056216'),
(678, '060401000', 'ALTAVAS', '06', '0604', '060401'),
(679, '060402000', 'BALETE', '06', '0604', '060402'),
(680, '060403000', 'BANGA', '06', '0604', '060403'),
(681, '060404000', 'BATAN', '06', '0604', '060404'),
(682, '060405000', 'BURUANGA', '06', '0604', '060405'),
(683, '060406000', 'IBAJAY', '06', '0604', '060406'),
(684, '060407000', 'KALIBO', '06', '0604', '060407'),
(685, '060408000', 'LEZO', '06', '0604', '060408'),
(686, '060409000', 'LIBACAO', '06', '0604', '060409'),
(687, '060410000', 'MADALAG', '06', '0604', '060410'),
(688, '060411000', 'MAKATO', '06', '0604', '060411'),
(689, '060412000', 'MALAY', '06', '0604', '060412'),
(690, '060413000', 'MALINAO', '06', '0604', '060413'),
(691, '060414000', 'NABAS', '06', '0604', '060414'),
(692, '060415000', 'NEW WASHINGTON', '06', '0604', '060415'),
(693, '060416000', 'NUMANCIA', '06', '0604', '060416'),
(694, '060417000', 'TANGALAN', '06', '0604', '060417'),
(695, '060601000', 'ANINI-Y', '06', '0606', '060601'),
(696, '060602000', 'BARBAZA', '06', '0606', '060602'),
(697, '060603000', 'BELISON', '06', '0606', '060603'),
(698, '060604000', 'BUGASONG', '06', '0606', '060604'),
(699, '060605000', 'CALUYA', '06', '0606', '060605'),
(700, '060606000', 'CULASI', '06', '0606', '060606'),
(701, '060607000', 'TOBIAS FORNIER (DAO)', '06', '0606', '060607'),
(702, '060608000', 'HAMTIC', '06', '0606', '060608'),
(703, '060609000', 'LAUA-AN', '06', '0606', '060609'),
(704, '060610000', 'LIBERTAD', '06', '0606', '060610'),
(705, '060611000', 'PANDAN', '06', '0606', '060611'),
(706, '060612000', 'PATNONGON', '06', '0606', '060612'),
(707, '060613000', 'SAN JOSE', '06', '0606', '060613'),
(708, '060614000', 'SAN REMIGIO', '06', '0606', '060614'),
(709, '060615000', 'SEBASTE', '06', '0606', '060615'),
(710, '060616000', 'SIBALOM', '06', '0606', '060616'),
(711, '060617000', 'TIBIAO', '06', '0606', '060617'),
(712, '060618000', 'VALDERRAMA', '06', '0606', '060618'),
(713, '061901000', 'CUARTERO', '06', '0619', '061901'),
(714, '061902000', 'DAO', '06', '0619', '061902'),
(715, '061903000', 'DUMALAG', '06', '0619', '061903'),
(716, '061904000', 'DUMARAO', '06', '0619', '061904'),
(717, '061905000', 'IVISAN', '06', '0619', '061905'),
(718, '061906000', 'JAMINDAN', '06', '0619', '061906'),
(719, '061907000', 'MA-AYON', '06', '0619', '061907'),
(720, '061908000', 'MAMBUSAO', '06', '0619', '061908'),
(721, '061909000', 'PANAY', '06', '0619', '061909'),
(722, '061910000', 'PANITAN', '06', '0619', '061910'),
(723, '061911000', 'PILAR', '06', '0619', '061911'),
(724, '061912000', 'PONTEVEDRA', '06', '0619', '061912'),
(725, '061913000', 'PRESIDENT ROXAS', '06', '0619', '061913'),
(726, '061914000', 'ROXAS', '06', '0619', '061914'),
(727, '061915000', 'SAPI-AN', '06', '0619', '061915'),
(728, '061916000', 'SIGMA', '06', '0619', '061916'),
(729, '061917000', 'TAPAZ', '06', '0619', '061917'),
(730, '063001000', 'AJUY', '06', '0630', '063001'),
(731, '063002000', 'ALIMODIAN', '06', '0630', '063002'),
(732, '063003000', 'ANILAO', '06', '0630', '063003'),
(733, '063004000', 'BADIANGAN', '06', '0630', '063004'),
(734, '063005000', 'BALASAN', '06', '0630', '063005'),
(735, '063006000', 'BANATE', '06', '0630', '063006'),
(736, '063007000', 'BAROTAC NUEVO', '06', '0630', '063007'),
(737, '063008000', 'BAROTAC VIEJO', '06', '0630', '063008'),
(738, '063009000', 'BATAD', '06', '0630', '063009'),
(739, '063010000', 'BINGAWAN', '06', '0630', '063010'),
(740, '063012000', 'CABATUAN', '06', '0630', '063012'),
(741, '063013000', 'CALINOG', '06', '0630', '063013'),
(742, '063014000', 'CARLES', '06', '0630', '063014'),
(743, '063015000', 'CONCEPCION', '06', '0630', '063015'),
(744, '063016000', 'DINGLE', '06', '0630', '063016'),
(745, '063017000', 'DUENAS', '06', '0630', '063017'),
(746, '063018000', 'DUMANGAS', '06', '0630', '063018'),
(747, '063019000', 'ESTANCIA', '06', '0630', '063019'),
(748, '063020000', 'GUIMBAL', '06', '0630', '063020'),
(749, '063021000', 'IGBARAS', '06', '0630', '063021'),
(750, '063022000', 'ILOILO', '06', '0630', '063022'),
(751, '063023000', 'JANIUAY', '06', '0630', '063023'),
(752, '063025000', 'LAMBUNAO', '06', '0630', '063025'),
(753, '063026000', 'LEGANES', '06', '0630', '063026'),
(754, '063027000', 'LEMERY', '06', '0630', '063027'),
(755, '063028000', 'LEON', '06', '0630', '063028'),
(756, '063029000', 'MAASIN', '06', '0630', '063029'),
(757, '063030000', 'MIAGAO', '06', '0630', '063030'),
(758, '063031000', 'MINA', '06', '0630', '063031'),
(759, '063032000', 'NEW LUCENA', '06', '0630', '063032'),
(760, '063034000', 'OTON', '06', '0630', '063034'),
(761, '063035000', 'PASSI', '06', '0630', '063035'),
(762, '063036000', 'PAVIA', '06', '0630', '063036'),
(763, '063037000', 'POTOTAN', '06', '0630', '063037'),
(764, '063038000', 'SAN DIONISIO', '06', '0630', '063038'),
(765, '063039000', 'SAN ENRIQUE', '06', '0630', '063039'),
(766, '063040000', 'SAN JOAQUIN', '06', '0630', '063040'),
(767, '063041000', 'SAN MIGUEL', '06', '0630', '063041'),
(768, '063042000', 'SAN RAFAEL', '06', '0630', '063042'),
(769, '063043000', 'SANTA BARBARA', '06', '0630', '063043'),
(770, '063044000', 'SARA', '06', '0630', '063044'),
(771, '063045000', 'TIGBAUAN', '06', '0630', '063045'),
(772, '063046000', 'TUBUNGAN', '06', '0630', '063046'),
(773, '063047000', 'ZARRAGA', '06', '0630', '063047'),
(774, '064501000', 'BACOLOD', '06', '0645', '064501'),
(775, '064502000', 'BAGO CITY', '06', '0645', '064502'),
(776, '064503000', 'BINALBAGAN', '06', '0645', '064503'),
(777, '064504000', 'CADIZ CITY', '06', '0645', '064504'),
(778, '064505000', 'CALATRAVA', '06', '0645', '064505'),
(779, '064506000', 'CANDONI', '06', '0645', '064506'),
(780, '064507000', 'CAUAYAN', '06', '0645', '064507'),
(781, '064508000', 'ENRIQUE B. MAGALONA (SARAVIA)', '06', '0645', '064508'),
(782, '064509000', 'ESCALANTE', '06', '0645', '064509'),
(783, '064510000', 'HIMAMAYLAN', '06', '0645', '064510'),
(784, '064511000', 'HINIGARAN', '06', '0645', '064511'),
(785, '064512000', 'HINOBA-AN (ASIA)', '06', '0645', '064512'),
(786, '064513000', 'ILOG', '06', '0645', '064513'),
(787, '064514000', 'ISABELA', '06', '0645', '064514'),
(788, '064515000', 'KABANKALAN', '06', '0645', '064515'),
(789, '064516000', 'LA CARLOTA CITY', '06', '0645', '064516'),
(790, '064517000', 'LA CASTELLANA', '06', '0645', '064517'),
(791, '064518000', 'MANAPLA', '06', '0645', '064518'),
(792, '064519000', 'MOISES PADILLA (MAGALLON)', '06', '0645', '064519'),
(793, '064520000', 'MURCIA', '06', '0645', '064520'),
(794, '064521000', 'PONTEVEDRA', '06', '0645', '064521'),
(795, '064522000', 'PULUPANDAN', '06', '0645', '064522'),
(796, '064523000', 'SAGAY CITY', '06', '0645', '064523'),
(797, '064524000', 'SAN CARLOS CITY', '06', '0645', '064524'),
(798, '064525000', 'SAN ENRIQUE', '06', '0645', '064525'),
(799, '064526000', 'SILAY CITY', '06', '0645', '064526'),
(800, '064527000', 'SIPALAY', '06', '0645', '064527'),
(801, '064528000', 'TALISAY', '06', '0645', '064528'),
(802, '064529000', 'TOBOSO', '06', '0645', '064529'),
(803, '064530000', 'VALLADOLID', '06', '0645', '064530'),
(804, '064531000', 'VICTORIAS', '06', '0645', '064531'),
(805, '064532000', 'SALVADOR BENEDICTO', '06', '0645', '064532'),
(806, '067901000', 'BUENAVISTA', '06', '0679', '067901'),
(807, '067902000', 'JORDAN', '06', '0679', '067902'),
(808, '067903000', 'NUEVA VALENCIA', '06', '0679', '067903'),
(809, '067904000', 'SAN LORENZO', '06', '0679', '067904'),
(810, '067905000', 'SIBUNAG', '06', '0679', '067905'),
(811, '071201000', 'ALBURQUERQUE', '07', '0712', '071201'),
(812, '071202000', 'ALICIA', '07', '0712', '071202'),
(813, '071203000', 'ANDA', '07', '0712', '071203'),
(814, '071204000', 'ANTEQUERA', '07', '0712', '071204'),
(815, '071205000', 'BACLAYON', '07', '0712', '071205'),
(816, '071206000', 'BALILIHAN', '07', '0712', '071206'),
(817, '071207000', 'BATUAN', '07', '0712', '071207'),
(818, '071208000', 'BILAR', '07', '0712', '071208'),
(819, '071209000', 'BUENAVISTA', '07', '0712', '071209'),
(820, '071210000', 'CALAPE', '07', '0712', '071210'),
(821, '071211000', 'CANDIJAY', '07', '0712', '071211'),
(822, '071212000', 'CARMEN', '07', '0712', '071212'),
(823, '071213000', 'CATIGBIAN', '07', '0712', '071213'),
(824, '071214000', 'CLARIN', '07', '0712', '071214'),
(825, '071215000', 'CORELLA', '07', '0712', '071215'),
(826, '071216000', 'CORTES', '07', '0712', '071216'),
(827, '071217000', 'DAGOHOY', '07', '0712', '071217'),
(828, '071218000', 'DANAO', '07', '0712', '071218'),
(829, '071219000', 'DAUIS', '07', '0712', '071219'),
(830, '071220000', 'DIMIAO', '07', '0712', '071220'),
(831, '071221000', 'DUERO', '07', '0712', '071221'),
(832, '071222000', 'GARCIA HERNANDEZ', '07', '0712', '071222'),
(833, '071223000', 'GUINDULMAN', '07', '0712', '071223'),
(834, '071224000', 'INABANGA', '07', '0712', '071224'),
(835, '071225000', 'JAGNA', '07', '0712', '071225'),
(836, '071226000', 'JETAFE', '07', '0712', '071226'),
(837, '071227000', 'LILA', '07', '0712', '071227'),
(838, '071228000', 'LOAY', '07', '0712', '071228'),
(839, '071229000', 'LOBOC', '07', '0712', '071229'),
(840, '071230000', 'LOON', '07', '0712', '071230'),
(841, '071231000', 'MABINI', '07', '0712', '071231'),
(842, '071232000', 'MARIBOJOC', '07', '0712', '071232'),
(843, '071233000', 'PANGLAO', '07', '0712', '071233'),
(844, '071234000', 'PILAR', '07', '0712', '071234'),
(845, '071235000', 'PRES. CARLOS P. GARCIA (PITOGO)', '07', '0712', '071235'),
(846, '071236000', 'SAGBAYAN (BORJA)', '07', '0712', '071236'),
(847, '071237000', 'SAN ISIDRO', '07', '0712', '071237'),
(848, '071238000', 'SAN MIGUEL', '07', '0712', '071238'),
(849, '071239000', 'SEVILLA', '07', '0712', '071239'),
(850, '071240000', 'SIERRA BULLONES', '07', '0712', '071240'),
(851, '071241000', 'SIKATUNA', '07', '0712', '071241'),
(852, '071242000', 'TAGBILARAN', '07', '0712', '071242'),
(853, '071243000', 'TALIBON', '07', '0712', '071243'),
(854, '071244000', 'TRINIDAD', '07', '0712', '071244'),
(855, '071245000', 'TUBIGON', '07', '0712', '071245'),
(856, '071246000', 'UBAY', '07', '0712', '071246'),
(857, '071247000', 'VALENCIA', '07', '0712', '071247'),
(858, '071248000', 'BIEN UNIDO', '07', '0712', '071248'),
(859, '072201000', 'ALCANTARA', '07', '0722', '072201'),
(860, '072202000', 'ALCOY', '07', '0722', '072202'),
(861, '072203000', 'ALEGRIA', '07', '0722', '072203'),
(862, '072204000', 'ALOGUINSAN', '07', '0722', '072204'),
(863, '072205000', 'ARGAO', '07', '0722', '072205'),
(864, '072206000', 'ASTURIAS', '07', '0722', '072206'),
(865, '072207000', 'BADIAN', '07', '0722', '072207'),
(866, '072208000', 'BALAMBAN', '07', '0722', '072208'),
(867, '072209000', 'BANTAYAN', '07', '0722', '072209'),
(868, '072210000', 'BARILI', '07', '0722', '072210'),
(869, '072211000', 'BOGO', '07', '0722', '072211'),
(870, '072212000', 'BOLJOON', '07', '0722', '072212'),
(871, '072213000', 'BORBON', '07', '0722', '072213'),
(872, '072214000', 'CARCAR', '07', '0722', '072214'),
(873, '072215000', 'CARMEN', '07', '0722', '072215'),
(874, '072216000', 'CATMON', '07', '0722', '072216'),
(875, '072217000', 'CEBU', '07', '0722', '072217'),
(876, '072218000', 'COMPOSTELA', '07', '0722', '072218'),
(877, '072219000', 'CONSOLACION', '07', '0722', '072219'),
(878, '072220000', 'CORDOVA', '07', '0722', '072220'),
(879, '072221000', 'DAANBANTAYAN', '07', '0722', '072221'),
(880, '072222000', 'DALAGUETE', '07', '0722', '072222'),
(881, '072223000', 'DANAO CITY', '07', '0722', '072223'),
(882, '072224000', 'DUMANJUG', '07', '0722', '072224'),
(883, '072225000', 'GINATILAN', '07', '0722', '072225'),
(884, '072226000', 'LAPU-LAPU CITY (OPON)', '07', '0722', '072226'),
(885, '072227000', 'LILOAN', '07', '0722', '072227'),
(886, '072228000', 'MADRIDEJOS', '07', '0722', '072228'),
(887, '072229000', 'MALABUYOC', '07', '0722', '072229'),
(888, '072230000', 'MANDAUE CITY', '07', '0722', '072230'),
(889, '072231000', 'MEDELLIN', '07', '0722', '072231'),
(890, '072232000', 'MINGLANILLA', '07', '0722', '072232'),
(891, '072233000', 'MOALBOAL', '07', '0722', '072233'),
(892, '072234000', 'NAGA', '07', '0722', '072234'),
(893, '072235000', 'OSLOB', '07', '0722', '072235'),
(894, '072236000', 'PILAR', '07', '0722', '072236'),
(895, '072237000', 'PINAMUNGAHAN', '07', '0722', '072237'),
(896, '072238000', 'PORO', '07', '0722', '072238'),
(897, '072239000', 'RONDA', '07', '0722', '072239'),
(898, '072240000', 'SAMBOAN', '07', '0722', '072240'),
(899, '072241000', 'SAN FERNANDO', '07', '0722', '072241'),
(900, '072242000', 'SAN FRANCISCO', '07', '0722', '072242'),
(901, '072243000', 'SAN REMIGIO', '07', '0722', '072243'),
(902, '072244000', 'SANTA FE', '07', '0722', '072244'),
(903, '072245000', 'SANTANDER', '07', '0722', '072245'),
(904, '072246000', 'SIBONGA', '07', '0722', '072246'),
(905, '072247000', 'SOGOD', '07', '0722', '072247'),
(906, '072248000', 'TABOGON', '07', '0722', '072248'),
(907, '072249000', 'TABUELAN', '07', '0722', '072249'),
(908, '072250000', 'TALISAY', '07', '0722', '072250'),
(909, '072251000', 'TOLEDO CITY', '07', '0722', '072251'),
(910, '072252000', 'TUBURAN', '07', '0722', '072252'),
(911, '072253000', 'TUDELA', '07', '0722', '072253'),
(912, '074601000', 'AMLAN (AYUQUITAN)', '07', '0746', '074601'),
(913, '074602000', 'AYUNGON', '07', '0746', '074602'),
(914, '074603000', 'BACONG', '07', '0746', '074603'),
(915, '074604000', 'BAIS CITY', '07', '0746', '074604'),
(916, '074605000', 'BASAY', '07', '0746', '074605'),
(917, '074606000', 'BAYAWAN (TULONG)', '07', '0746', '074606'),
(918, '074607000', 'BINDOY (PAYABON)', '07', '0746', '074607'),
(919, '074608000', 'CANLAON CITY', '07', '0746', '074608'),
(920, '074609000', 'DAUIN', '07', '0746', '074609'),
(921, '074610000', 'DUMAGUETE', '07', '0746', '074610');
INSERT INTO `city_code` (`id`, `psgcCode`, `citymunDesc`, `regDesc`, `provCode`, `citymunCode`) VALUES
(922, '074611000', 'GUIHULNGAN', '07', '0746', '074611'),
(923, '074612000', 'JIMALALUD', '07', '0746', '074612'),
(924, '074613000', 'LA LIBERTAD', '07', '0746', '074613'),
(925, '074614000', 'MABINAY', '07', '0746', '074614'),
(926, '074615000', 'MANJUYOD', '07', '0746', '074615'),
(927, '074616000', 'PAMPLONA', '07', '0746', '074616'),
(928, '074617000', 'SAN JOSE', '07', '0746', '074617'),
(929, '074618000', 'SANTA CATALINA', '07', '0746', '074618'),
(930, '074619000', 'SIATON', '07', '0746', '074619'),
(931, '074620000', 'SIBULAN', '07', '0746', '074620'),
(932, '074621000', 'TANJAY', '07', '0746', '074621'),
(933, '074622000', 'TAYASAN', '07', '0746', '074622'),
(934, '074623000', 'VALENCIA (LUZURRIAGA)', '07', '0746', '074623'),
(935, '074624000', 'VALLEHERMOSO', '07', '0746', '074624'),
(936, '074625000', 'ZAMBOANGUITA', '07', '0746', '074625'),
(937, '076101000', 'ENRIQUE VILLANUEVA', '07', '0761', '076101'),
(938, '076102000', 'LARENA', '07', '0761', '076102'),
(939, '076103000', 'LAZI', '07', '0761', '076103'),
(940, '076104000', 'MARIA', '07', '0761', '076104'),
(941, '076105000', 'SAN JUAN', '07', '0761', '076105'),
(942, '076106000', 'SIQUIJOR', '07', '0761', '076106'),
(943, '082601000', 'ARTECHE', '08', '0826', '082601'),
(944, '082602000', 'BALANGIGA', '08', '0826', '082602'),
(945, '082603000', 'BALANGKAYAN', '08', '0826', '082603'),
(946, '082604000', 'BORONGAN', '08', '0826', '082604'),
(947, '082605000', 'CAN-AVID', '08', '0826', '082605'),
(948, '082606000', 'DOLORES', '08', '0826', '082606'),
(949, '082607000', 'GENERAL MACARTHUR', '08', '0826', '082607'),
(950, '082608000', 'GIPORLOS', '08', '0826', '082608'),
(951, '082609000', 'GUIUAN', '08', '0826', '082609'),
(952, '082610000', 'HERNANI', '08', '0826', '082610'),
(953, '082611000', 'JIPAPAD', '08', '0826', '082611'),
(954, '082612000', 'LAWAAN', '08', '0826', '082612'),
(955, '082613000', 'LLORENTE', '08', '0826', '082613'),
(956, '082614000', 'MASLOG', '08', '0826', '082614'),
(957, '082615000', 'MAYDOLONG', '08', '0826', '082615'),
(958, '082616000', 'MERCEDES', '08', '0826', '082616'),
(959, '082617000', 'ORAS', '08', '0826', '082617'),
(960, '082618000', 'QUINAPONDAN', '08', '0826', '082618'),
(961, '082619000', 'SALCEDO', '08', '0826', '082619'),
(962, '082620000', 'SAN JULIAN', '08', '0826', '082620'),
(963, '082621000', 'SAN POLICARPO', '08', '0826', '082621'),
(964, '082622000', 'SULAT', '08', '0826', '082622'),
(965, '082623000', 'TAFT', '08', '0826', '082623'),
(966, '083701000', 'ABUYOG', '08', '0837', '083701'),
(967, '083702000', 'ALANGALANG', '08', '0837', '083702'),
(968, '083703000', 'ALBUERA', '08', '0837', '083703'),
(969, '083705000', 'BABATNGON', '08', '0837', '083705'),
(970, '083706000', 'BARUGO', '08', '0837', '083706'),
(971, '083707000', 'BATO', '08', '0837', '083707'),
(972, '083708000', 'BAYBAY', '08', '0837', '083708'),
(973, '083710000', 'BURAUEN', '08', '0837', '083710'),
(974, '083713000', 'CALUBIAN', '08', '0837', '083713'),
(975, '083714000', 'CAPOOCAN', '08', '0837', '083714'),
(976, '083715000', 'CARIGARA', '08', '0837', '083715'),
(977, '083717000', 'DAGAMI', '08', '0837', '083717'),
(978, '083718000', 'DULAG', '08', '0837', '083718'),
(979, '083719000', 'HILONGOS', '08', '0837', '083719'),
(980, '083720000', 'HINDANG', '08', '0837', '083720'),
(981, '083721000', 'INOPACAN', '08', '0837', '083721'),
(982, '083722000', 'ISABEL', '08', '0837', '083722'),
(983, '083723000', 'JARO', '08', '0837', '083723'),
(984, '083724000', 'JAVIER (BUGHO)', '08', '0837', '083724'),
(985, '083725000', 'JULITA', '08', '0837', '083725'),
(986, '083726000', 'KANANGA', '08', '0837', '083726'),
(987, '083728000', 'LA PAZ', '08', '0837', '083728'),
(988, '083729000', 'LEYTE', '08', '0837', '083729'),
(989, '083730000', 'MACARTHUR', '08', '0837', '083730'),
(990, '083731000', 'MAHAPLAG', '08', '0837', '083731'),
(991, '083733000', 'MATAG-OB', '08', '0837', '083733'),
(992, '083734000', 'MATALOM', '08', '0837', '083734'),
(993, '083735000', 'MAYORGA', '08', '0837', '083735'),
(994, '083736000', 'MERIDA', '08', '0837', '083736'),
(995, '083738000', 'ORMOC CITY', '08', '0837', '083738'),
(996, '083739000', 'PALO', '08', '0837', '083739'),
(997, '083740000', 'PALOMPON', '08', '0837', '083740'),
(998, '083741000', 'PASTRANA', '08', '0837', '083741'),
(999, '083742000', 'SAN ISIDRO', '08', '0837', '083742'),
(1000, '083743000', 'SAN MIGUEL', '08', '0837', '083743'),
(1001, '083744000', 'SANTA FE', '08', '0837', '083744'),
(1002, '083745000', 'TABANGO', '08', '0837', '083745'),
(1003, '083746000', 'TABONTABON', '08', '0837', '083746'),
(1004, '083747000', 'TACLOBAN', '08', '0837', '083747'),
(1005, '083748000', 'TANAUAN', '08', '0837', '083748'),
(1006, '083749000', 'TOLOSA', '08', '0837', '083749'),
(1007, '083750000', 'TUNGA', '08', '0837', '083750'),
(1008, '083751000', 'VILLABA', '08', '0837', '083751'),
(1009, '084801000', 'ALLEN', '08', '0848', '084801'),
(1010, '084802000', 'BIRI', '08', '0848', '084802'),
(1011, '084803000', 'BOBON', '08', '0848', '084803'),
(1012, '084804000', 'CAPUL', '08', '0848', '084804'),
(1013, '084805000', 'CATARMAN', '08', '0848', '084805'),
(1014, '084806000', 'CATUBIG', '08', '0848', '084806'),
(1015, '084807000', 'GAMAY', '08', '0848', '084807'),
(1016, '084808000', 'LAOANG', '08', '0848', '084808'),
(1017, '084809000', 'LAPINIG', '08', '0848', '084809'),
(1018, '084810000', 'LAS NAVAS', '08', '0848', '084810'),
(1019, '084811000', 'LAVEZARES', '08', '0848', '084811'),
(1020, '084812000', 'MAPANAS', '08', '0848', '084812'),
(1021, '084813000', 'MONDRAGON', '08', '0848', '084813'),
(1022, '084814000', 'PALAPAG', '08', '0848', '084814'),
(1023, '084815000', 'PAMBUJAN', '08', '0848', '084815'),
(1024, '084816000', 'ROSARIO', '08', '0848', '084816'),
(1025, '084817000', 'SAN ANTONIO', '08', '0848', '084817'),
(1026, '084818000', 'SAN ISIDRO', '08', '0848', '084818'),
(1027, '084819000', 'SAN JOSE', '08', '0848', '084819'),
(1028, '084820000', 'SAN ROQUE', '08', '0848', '084820'),
(1029, '084821000', 'SAN VICENTE', '08', '0848', '084821'),
(1030, '084822000', 'SILVINO LOBOS', '08', '0848', '084822'),
(1031, '084823000', 'VICTORIA', '08', '0848', '084823'),
(1032, '084824000', 'LOPE DE VEGA', '08', '0848', '084824'),
(1033, '086001000', 'ALMAGRO', '08', '0860', '086001'),
(1034, '086002000', 'BASEY', '08', '0860', '086002'),
(1035, '086003000', 'CALBAYOG CITY', '08', '0860', '086003'),
(1036, '086004000', 'CALBIGA', '08', '0860', '086004'),
(1037, '086005000', 'CATBALOGAN', '08', '0860', '086005'),
(1038, '086006000', 'DARAM', '08', '0860', '086006'),
(1039, '086007000', 'GANDARA', '08', '0860', '086007'),
(1040, '086008000', 'HINABANGAN', '08', '0860', '086008'),
(1041, '086009000', 'JIABONG', '08', '0860', '086009'),
(1042, '086010000', 'MARABUT', '08', '0860', '086010'),
(1043, '086011000', 'MATUGUINAO', '08', '0860', '086011'),
(1044, '086012000', 'MOTIONG', '08', '0860', '086012'),
(1045, '086013000', 'PINABACDAO', '08', '0860', '086013'),
(1046, '086014000', 'SAN JOSE DE BUAN', '08', '0860', '086014'),
(1047, '086015000', 'SAN SEBASTIAN', '08', '0860', '086015'),
(1048, '086016000', 'SANTA MARGARITA', '08', '0860', '086016'),
(1049, '086017000', 'SANTA RITA', '08', '0860', '086017'),
(1050, '086018000', 'SANTO NINO', '08', '0860', '086018'),
(1051, '086019000', 'TALALORA', '08', '0860', '086019'),
(1052, '086020000', 'TARANGNAN', '08', '0860', '086020'),
(1053, '086021000', 'VILLAREAL', '08', '0860', '086021'),
(1054, '086022000', 'PARANAS (WRIGHT)', '08', '0860', '086022'),
(1055, '086023000', 'ZUMARRAGA', '08', '0860', '086023'),
(1056, '086024000', 'TAGAPUL-AN', '08', '0860', '086024'),
(1057, '086025000', 'SAN JORGE', '08', '0860', '086025'),
(1058, '086026000', 'PAGSANGHAN', '08', '0860', '086026'),
(1059, '086401000', 'ANAHAWAN', '08', '0864', '086401'),
(1060, '086402000', 'BONTOC', '08', '0864', '086402'),
(1061, '086403000', 'HINUNANGAN', '08', '0864', '086403'),
(1062, '086404000', 'HINUNDAYAN', '08', '0864', '086404'),
(1063, '086405000', 'LIBAGON', '08', '0864', '086405'),
(1064, '086406000', 'LILOAN', '08', '0864', '086406'),
(1065, '086407000', 'MAASIN', '08', '0864', '086407'),
(1066, '086408000', 'MACROHON', '08', '0864', '086408'),
(1067, '086409000', 'MALITBOG', '08', '0864', '086409'),
(1068, '086410000', 'PADRE BURGOS', '08', '0864', '086410'),
(1069, '086411000', 'PINTUYAN', '08', '0864', '086411'),
(1070, '086412000', 'SAINT BERNARD', '08', '0864', '086412'),
(1071, '086413000', 'SAN FRANCISCO', '08', '0864', '086413'),
(1072, '086414000', 'SAN JUAN (CABALIAN)', '08', '0864', '086414'),
(1073, '086415000', 'SAN RICARDO', '08', '0864', '086415'),
(1074, '086416000', 'SILAGO', '08', '0864', '086416'),
(1075, '086417000', 'SOGOD', '08', '0864', '086417'),
(1076, '086418000', 'TOMAS OPPUS', '08', '0864', '086418'),
(1077, '086419000', 'LIMASAWA', '08', '0864', '086419'),
(1078, '087801000', 'ALMERIA', '08', '0878', '087801'),
(1079, '087802000', 'BILIRAN', '08', '0878', '087802'),
(1080, '087803000', 'CABUCGAYAN', '08', '0878', '087803'),
(1081, '087804000', 'CAIBIRAN', '08', '0878', '087804'),
(1082, '087805000', 'CULABA', '08', '0878', '087805'),
(1083, '087806000', 'KAWAYAN', '08', '0878', '087806'),
(1084, '087807000', 'MARIPIPI', '08', '0878', '087807'),
(1085, '087808000', 'NAVAL', '08', '0878', '087808'),
(1086, '097201000', 'DAPITAN CITY', '09', '0972', '097201'),
(1087, '097202000', 'DIPOLOG', '09', '0972', '097202'),
(1088, '097203000', 'KATIPUNAN', '09', '0972', '097203'),
(1089, '097204000', 'LA LIBERTAD', '09', '0972', '097204'),
(1090, '097205000', 'LABASON', '09', '0972', '097205'),
(1091, '097206000', 'LILOY', '09', '0972', '097206'),
(1092, '097207000', 'MANUKAN', '09', '0972', '097207'),
(1093, '097208000', 'MUTIA', '09', '0972', '097208'),
(1094, '097209000', 'PINAN (NEW PINAN)', '09', '0972', '097209'),
(1095, '097210000', 'POLANCO', '09', '0972', '097210'),
(1096, '097211000', 'PRES. MANUEL A. ROXAS', '09', '0972', '097211'),
(1097, '097212000', 'RIZAL', '09', '0972', '097212'),
(1098, '097213000', 'SALUG', '09', '0972', '097213'),
(1099, '097214000', 'SERGIO OSMENA SR.', '09', '0972', '097214'),
(1100, '097215000', 'SIAYAN', '09', '0972', '097215'),
(1101, '097216000', 'SIBUCO', '09', '0972', '097216'),
(1102, '097217000', 'SIBUTAD', '09', '0972', '097217'),
(1103, '097218000', 'SINDANGAN', '09', '0972', '097218'),
(1104, '097219000', 'SIOCON', '09', '0972', '097219'),
(1105, '097220000', 'SIRAWAI', '09', '0972', '097220'),
(1106, '097221000', 'TAMPILISAN', '09', '0972', '097221'),
(1107, '097222000', 'JOSE DALMAN (PONOT)', '09', '0972', '097222'),
(1108, '097223000', 'GUTALAC', '09', '0972', '097223'),
(1109, '097224000', 'BALIGUIAN', '09', '0972', '097224'),
(1110, '097225000', 'GODOD', '09', '0972', '097225'),
(1111, '097226000', 'BACUNGAN (Leon T. Postigo)', '09', '0972', '097226'),
(1112, '097227000', 'KALAWIT', '09', '0972', '097227'),
(1113, '097302000', 'AURORA', '09', '0973', '097302'),
(1114, '097303000', 'BAYOG', '09', '0973', '097303'),
(1115, '097305000', 'DIMATALING', '09', '0973', '097305'),
(1116, '097306000', 'DINAS', '09', '0973', '097306'),
(1117, '097307000', 'DUMALINAO', '09', '0973', '097307'),
(1118, '097308000', 'DUMINGAG', '09', '0973', '097308'),
(1119, '097311000', 'KUMALARANG', '09', '0973', '097311'),
(1120, '097312000', 'LABANGAN', '09', '0973', '097312'),
(1121, '097313000', 'LAPUYAN', '09', '0973', '097313'),
(1122, '097315000', 'MAHAYAG', '09', '0973', '097315'),
(1123, '097317000', 'MARGOSATUBIG', '09', '0973', '097317'),
(1124, '097318000', 'MIDSALIP', '09', '0973', '097318'),
(1125, '097319000', 'MOLAVE', '09', '0973', '097319'),
(1126, '097322000', 'PAGADIAN', '09', '0973', '097322'),
(1127, '097323000', 'RAMON MAGSAYSAY (LIARGO)', '09', '0973', '097323'),
(1128, '097324000', 'SAN MIGUEL', '09', '0973', '097324'),
(1129, '097325000', 'SAN PABLO', '09', '0973', '097325'),
(1130, '097327000', 'TABINA', '09', '0973', '097327'),
(1131, '097328000', 'TAMBULIG', '09', '0973', '097328'),
(1132, '097330000', 'TUKURAN', '09', '0973', '097330'),
(1133, '097332000', 'ZAMBOANGA CITY', '09', '0973', '097332'),
(1134, '097333000', 'LAKEWOOD', '09', '0973', '097333'),
(1135, '097337000', 'JOSEFINA', '09', '0973', '097337'),
(1136, '097338000', 'PITOGO', '09', '0973', '097338'),
(1137, '097340000', 'SOMINOT (DON MARIANO MARCOS)', '09', '0973', '097340'),
(1138, '097341000', 'VINCENZO A. SAGUN', '09', '0973', '097341'),
(1139, '097343000', 'GUIPOS', '09', '0973', '097343'),
(1140, '097344000', 'TIGBAO', '09', '0973', '097344'),
(1141, '098301000', 'ALICIA', '09', '0983', '098301'),
(1142, '098302000', 'BUUG', '09', '0983', '098302'),
(1143, '098303000', 'DIPLAHAN', '09', '0983', '098303'),
(1144, '098304000', 'IMELDA', '09', '0983', '098304'),
(1145, '098305000', 'IPIL', '09', '0983', '098305'),
(1146, '098306000', 'KABASALAN', '09', '0983', '098306'),
(1147, '098307000', 'MABUHAY', '09', '0983', '098307'),
(1148, '098308000', 'MALANGAS', '09', '0983', '098308'),
(1149, '098309000', 'NAGA', '09', '0983', '098309'),
(1150, '098310000', 'OLUTANGA', '09', '0983', '098310'),
(1151, '098311000', 'PAYAO', '09', '0983', '098311'),
(1152, '098312000', 'ROSELLER LIM', '09', '0983', '098312'),
(1153, '098313000', 'SIAY', '09', '0983', '098313'),
(1154, '098314000', 'TALUSAN', '09', '0983', '098314'),
(1155, '098315000', 'TITAY', '09', '0983', '098315'),
(1156, '098316000', 'TUNGAWAN', '09', '0983', '098316'),
(1157, '099701000', 'ISABELA', '09', '0997', '099701'),
(1158, '101301000', 'BAUNGON', '10', '1013', '101301'),
(1159, '101302000', 'DAMULOG', '10', '1013', '101302'),
(1160, '101303000', 'DANGCAGAN', '10', '1013', '101303'),
(1161, '101304000', 'DON CARLOS', '10', '1013', '101304'),
(1162, '101305000', 'IMPASUG-ONG', '10', '1013', '101305'),
(1163, '101306000', 'KADINGILAN', '10', '1013', '101306'),
(1164, '101307000', 'KALILANGAN', '10', '1013', '101307'),
(1165, '101308000', 'KIBAWE', '10', '1013', '101308'),
(1166, '101309000', 'KITAOTAO', '10', '1013', '101309'),
(1167, '101310000', 'LANTAPAN', '10', '1013', '101310'),
(1168, '101311000', 'LIBONA', '10', '1013', '101311'),
(1169, '101312000', 'MALAYBALAY', '10', '1013', '101312'),
(1170, '101313000', 'MALITBOG', '10', '1013', '101313'),
(1171, '101314000', 'MANOLO FORTICH', '10', '1013', '101314'),
(1172, '101315000', 'MARAMAG', '10', '1013', '101315'),
(1173, '101316000', 'PANGANTUCAN', '10', '1013', '101316'),
(1174, '101317000', 'QUEZON', '10', '1013', '101317'),
(1175, '101318000', 'SAN FERNANDO', '10', '1013', '101318'),
(1176, '101319000', 'SUMILAO', '10', '1013', '101319'),
(1177, '101320000', 'TALAKAG', '10', '1013', '101320'),
(1178, '101321000', 'VALENCIA', '10', '1013', '101321'),
(1179, '101322000', 'CABANGLASAN', '10', '1013', '101322'),
(1180, '101801000', 'CATARMAN', '10', '1018', '101801'),
(1181, '101802000', 'GUINSILIBAN', '10', '1018', '101802'),
(1182, '101803000', 'MAHINOG', '10', '1018', '101803'),
(1183, '101804000', 'MAMBAJAO', '10', '1018', '101804'),
(1184, '101805000', 'SAGAY', '10', '1018', '101805'),
(1185, '103501000', 'BACOLOD', '10', '1035', '103501'),
(1186, '103502000', 'BALOI', '10', '1035', '103502'),
(1187, '103503000', 'BAROY', '10', '1035', '103503'),
(1188, '103504000', 'ILIGAN CITY', '10', '1035', '103504'),
(1189, '103505000', 'KAPATAGAN', '10', '1035', '103505'),
(1190, '103506000', 'SULTAN NAGA DIMAPORO (KAROMATAN)', '10', '1035', '103506'),
(1191, '103507000', 'KAUSWAGAN', '10', '1035', '103507'),
(1192, '103508000', 'KOLAMBUGAN', '10', '1035', '103508'),
(1193, '103509000', 'LALA', '10', '1035', '103509'),
(1194, '103510000', 'LINAMON', '10', '1035', '103510'),
(1195, '103511000', 'MAGSAYSAY', '10', '1035', '103511'),
(1196, '103512000', 'MAIGO', '10', '1035', '103512'),
(1197, '103513000', 'MATUNGAO', '10', '1035', '103513'),
(1198, '103514000', 'MUNAI', '10', '1035', '103514'),
(1199, '103515000', 'NUNUNGAN', '10', '1035', '103515'),
(1200, '103516000', 'PANTAO RAGAT', '10', '1035', '103516'),
(1201, '103517000', 'POONA PIAGAPO', '10', '1035', '103517'),
(1202, '103518000', 'SALVADOR', '10', '1035', '103518'),
(1203, '103519000', 'SAPAD', '10', '1035', '103519'),
(1204, '103520000', 'TAGOLOAN', '10', '1035', '103520'),
(1205, '103521000', 'TANGCAL', '10', '1035', '103521'),
(1206, '103522000', 'TUBOD', '10', '1035', '103522'),
(1207, '103523000', 'PANTAR', '10', '1035', '103523'),
(1208, '104201000', 'ALORAN', '10', '1042', '104201'),
(1209, '104202000', 'BALIANGAO', '10', '1042', '104202'),
(1210, '104203000', 'BONIFACIO', '10', '1042', '104203'),
(1211, '104204000', 'CALAMBA', '10', '1042', '104204'),
(1212, '104205000', 'CLARIN', '10', '1042', '104205'),
(1213, '104206000', 'CONCEPCION', '10', '1042', '104206'),
(1214, '104207000', 'JIMENEZ', '10', '1042', '104207'),
(1215, '104208000', 'LOPEZ JAENA', '10', '1042', '104208'),
(1216, '104209000', 'OROQUIETA', '10', '1042', '104209'),
(1217, '104210000', 'OZAMIS CITY', '10', '1042', '104210'),
(1218, '104211000', 'PANAON', '10', '1042', '104211'),
(1219, '104212000', 'PLARIDEL', '10', '1042', '104212'),
(1220, '104213000', 'SAPANG DALAGA', '10', '1042', '104213'),
(1221, '104214000', 'SINACABAN', '10', '1042', '104214'),
(1222, '104215000', 'TANGUB CITY', '10', '1042', '104215'),
(1223, '104216000', 'TUDELA', '10', '1042', '104216'),
(1224, '104217000', 'DON VICTORIANO CHIONGBIAN  (DON MARIANO MARCOS)', '10', '1042', '104217'),
(1225, '104301000', 'ALUBIJID', '10', '1043', '104301'),
(1226, '104302000', 'BALINGASAG', '10', '1043', '104302'),
(1227, '104303000', 'BALINGOAN', '10', '1043', '104303'),
(1228, '104304000', 'BINUANGAN', '10', '1043', '104304'),
(1229, '104305000', 'CAGAYAN DE ORO', '10', '1043', '104305'),
(1230, '104306000', 'CLAVERIA', '10', '1043', '104306'),
(1231, '104307000', 'EL SALVADOR', '10', '1043', '104307'),
(1232, '104308000', 'GINGOOG CITY', '10', '1043', '104308'),
(1233, '104309000', 'GITAGUM', '10', '1043', '104309'),
(1234, '104310000', 'INITAO', '10', '1043', '104310'),
(1235, '104311000', 'JASAAN', '10', '1043', '104311'),
(1236, '104312000', 'KINOGUITAN', '10', '1043', '104312'),
(1237, '104313000', 'LAGONGLONG', '10', '1043', '104313'),
(1238, '104314000', 'LAGUINDINGAN', '10', '1043', '104314'),
(1239, '104315000', 'LIBERTAD', '10', '1043', '104315'),
(1240, '104316000', 'LUGAIT', '10', '1043', '104316'),
(1241, '104317000', 'MAGSAYSAY (LINUGOS)', '10', '1043', '104317'),
(1242, '104318000', 'MANTICAO', '10', '1043', '104318'),
(1243, '104319000', 'MEDINA', '10', '1043', '104319'),
(1244, '104320000', 'NAAWAN', '10', '1043', '104320'),
(1245, '104321000', 'OPOL', '10', '1043', '104321'),
(1246, '104322000', 'SALAY', '10', '1043', '104322'),
(1247, '104323000', 'SUGBONGCOGON', '10', '1043', '104323'),
(1248, '104324000', 'TAGOLOAN', '10', '1043', '104324'),
(1249, '104325000', 'TALISAYAN', '10', '1043', '104325'),
(1250, '104326000', 'VILLANUEVA', '10', '1043', '104326'),
(1251, '112301000', 'ASUNCION (SAUG)', '11', '1123', '112301'),
(1252, '112303000', 'CARMEN', '11', '1123', '112303'),
(1253, '112305000', 'KAPALONG', '11', '1123', '112305'),
(1254, '112314000', 'NEW CORELLA', '11', '1123', '112314'),
(1255, '112315000', 'PANABO', '11', '1123', '112315'),
(1256, '112317000', 'ISLAND GARDEN SAMAL', '11', '1123', '112317'),
(1257, '112318000', 'SANTO TOMAS', '11', '1123', '112318'),
(1258, '112319000', 'TAGUM', '11', '1123', '112319'),
(1259, '112322000', 'TALAINGOD', '11', '1123', '112322'),
(1260, '112323000', 'BRAULIO E. DUJALI', '11', '1123', '112323'),
(1261, '112324000', 'SAN ISIDRO', '11', '1123', '112324'),
(1262, '112401000', 'BANSALAN', '11', '1124', '112401'),
(1263, '112402000', 'DAVAO CITY', '11', '1124', '112402'),
(1264, '112403000', 'DIGOS', '11', '1124', '112403'),
(1265, '112404000', 'HAGONOY', '11', '1124', '112404'),
(1266, '112406000', 'KIBLAWAN', '11', '1124', '112406'),
(1267, '112407000', 'MAGSAYSAY', '11', '1124', '112407'),
(1268, '112408000', 'MALALAG', '11', '1124', '112408'),
(1269, '112410000', 'MATANAO', '11', '1124', '112410'),
(1270, '112411000', 'PADADA', '11', '1124', '112411'),
(1271, '112412000', 'SANTA CRUZ', '11', '1124', '112412'),
(1272, '112414000', 'SULOP', '11', '1124', '112414'),
(1273, '112501000', 'BAGANGA', '11', '1125', '112501'),
(1274, '112502000', 'BANAYBANAY', '11', '1125', '112502'),
(1275, '112503000', 'BOSTON', '11', '1125', '112503'),
(1276, '112504000', 'CARAGA', '11', '1125', '112504'),
(1277, '112505000', 'CATEEL', '11', '1125', '112505'),
(1278, '112506000', 'GOVERNOR GENEROSO', '11', '1125', '112506'),
(1279, '112507000', 'LUPON', '11', '1125', '112507'),
(1280, '112508000', 'MANAY', '11', '1125', '112508'),
(1281, '112509000', 'MATI', '11', '1125', '112509'),
(1282, '112510000', 'SAN ISIDRO', '11', '1125', '112510'),
(1283, '112511000', 'TARRAGONA', '11', '1125', '112511'),
(1284, '118201000', 'COMPOSTELA', '11', '1182', '118201'),
(1285, '118202000', 'LAAK (SAN VICENTE)', '11', '1182', '118202'),
(1286, '118203000', 'MABINI (DONA ALICIA)', '11', '1182', '118203'),
(1287, '118204000', 'MACO', '11', '1182', '118204'),
(1288, '118205000', 'MARAGUSAN (SAN MARIANO)', '11', '1182', '118205'),
(1289, '118206000', 'MAWAB', '11', '1182', '118206'),
(1290, '118207000', 'MONKAYO', '11', '1182', '118207'),
(1291, '118208000', 'MONTEVISTA', '11', '1182', '118208'),
(1292, '118209000', 'NABUNTURAN', '11', '1182', '118209'),
(1293, '118210000', 'NEW BATAAN', '11', '1182', '118210'),
(1294, '118211000', 'PANTUKAN', '11', '1182', '118211'),
(1295, '118601000', 'DON MARCELINO', '11', '1186', '118601'),
(1296, '118602000', 'JOSE ABAD SANTOS (TRINIDAD)', '11', '1186', '118602'),
(1297, '118603000', 'MALITA', '11', '1186', '118603'),
(1298, '118604000', 'SANTA MARIA', '11', '1186', '118604'),
(1299, '118605000', 'SARANGANI', '11', '1186', '118605'),
(1300, '124701000', 'ALAMADA', '12', '1247', '124701'),
(1301, '124702000', 'CARMEN', '12', '1247', '124702'),
(1302, '124703000', 'KABACAN', '12', '1247', '124703'),
(1303, '124704000', 'KIDAPAWAN', '12', '1247', '124704'),
(1304, '124705000', 'LIBUNGAN', '12', '1247', '124705'),
(1305, '124706000', 'MAGPET', '12', '1247', '124706'),
(1306, '124707000', 'MAKILALA', '12', '1247', '124707'),
(1307, '124708000', 'MATALAM', '12', '1247', '124708'),
(1308, '124709000', 'MIDSAYAP', '12', '1247', '124709'),
(1309, '124710000', 'MLANG', '12', '1247', '124710'),
(1310, '124711000', 'PIGKAWAYAN', '12', '1247', '124711'),
(1311, '124712000', 'PIKIT', '12', '1247', '124712'),
(1312, '124713000', 'PRESIDENT ROXAS', '12', '1247', '124713'),
(1313, '124714000', 'TULUNAN', '12', '1247', '124714'),
(1314, '124715000', 'ANTIPAS', '12', '1247', '124715'),
(1315, '124716000', 'BANISILAN', '12', '1247', '124716'),
(1316, '124717000', 'ALEOSAN', '12', '1247', '124717'),
(1317, '124718000', 'ARAKAN', '12', '1247', '124718'),
(1318, '126302000', 'BANGA', '12', '1263', '126302'),
(1319, '126303000', 'GENERAL SANTOS CITY (DADIANGAS)', '12', '1263', '126303'),
(1320, '126306000', 'KORONADAL', '12', '1263', '126306'),
(1321, '126311000', 'NORALA', '12', '1263', '126311'),
(1322, '126312000', 'POLOMOLOK', '12', '1263', '126312'),
(1323, '126313000', 'SURALLAH', '12', '1263', '126313'),
(1324, '126314000', 'TAMPAKAN', '12', '1263', '126314'),
(1325, '126315000', 'TANTANGAN', '12', '1263', '126315'),
(1326, '126316000', 'TBOLI', '12', '1263', '126316'),
(1327, '126317000', 'TUPI', '12', '1263', '126317'),
(1328, '126318000', 'SANTO NINO', '12', '1263', '126318'),
(1329, '126319000', 'LAKE SEBU', '12', '1263', '126319'),
(1330, '126501000', 'BAGUMBAYAN', '12', '1265', '126501'),
(1331, '126502000', 'COLUMBIO', '12', '1265', '126502'),
(1332, '126503000', 'ESPERANZA', '12', '1265', '126503'),
(1333, '126504000', 'ISULAN', '12', '1265', '126504'),
(1334, '126505000', 'KALAMANSIG', '12', '1265', '126505'),
(1335, '126506000', 'LEBAK', '12', '1265', '126506'),
(1336, '126507000', 'LUTAYAN', '12', '1265', '126507'),
(1337, '126508000', 'LAMBAYONG (MARIANO MARCOS)', '12', '1265', '126508'),
(1338, '126509000', 'PALIMBANG', '12', '1265', '126509'),
(1339, '126510000', 'PRESIDENT QUIRINO', '12', '1265', '126510'),
(1340, '126511000', 'TACURONG', '12', '1265', '126511'),
(1341, '126512000', 'SEN. NINOY AQUINO', '12', '1265', '126512'),
(1342, '128001000', 'ALABEL', '12', '1280', '128001'),
(1343, '128002000', 'GLAN', '12', '1280', '128002'),
(1344, '128003000', 'KIAMBA', '12', '1280', '128003'),
(1345, '128004000', 'MAASIM', '12', '1280', '128004'),
(1346, '128005000', 'MAITUM', '12', '1280', '128005'),
(1347, '128006000', 'MALAPATAN', '12', '1280', '128006'),
(1348, '128007000', 'MALUNGON', '12', '1280', '128007'),
(1349, '129804000', 'COTABATO CITY', '12', '1298', '129804'),
(1350, '133901000', 'TONDO I / II', '13', '1339', '133901'),
(1351, '133902000', 'BINONDO', '13', '1339', '133902'),
(1352, '133903000', 'QUIAPO', '13', '1339', '133903'),
(1353, '133904000', 'SAN NICOLAS', '13', '1339', '133904'),
(1354, '133905000', 'SANTA CRUZ', '13', '1339', '133905'),
(1355, '133906000', 'SAMPALOC', '13', '1339', '133906'),
(1356, '133907000', 'SAN MIGUEL', '13', '1339', '133907'),
(1357, '133908000', 'ERMITA', '13', '1339', '133908'),
(1358, '133909000', 'INTRAMUROS', '13', '1339', '133909'),
(1359, '133910000', 'MALATE', '13', '1339', '133910'),
(1360, '133911000', 'PACO', '13', '1339', '133911'),
(1361, '133912000', 'PANDACAN', '13', '1339', '133912'),
(1362, '133913000', 'PORT AREA', '13', '1339', '133913'),
(1363, '133914000', 'SANTA ANA', '13', '1339', '133914'),
(1364, '137401000', 'MANDALUYONG', '13', '1374', '137401'),
(1365, '137402000', 'MARIKINA', '13', '1374', '137402'),
(1366, '137403000', 'PASIG', '13', '1374', '137403'),
(1367, '137404000', 'QUEZON CITY', '13', '1374', '137404'),
(1368, '137405000', 'SAN JUAN', '13', '1374', '137405'),
(1369, '137501000', 'CALOOCAN CITY', '13', '1375', '137501'),
(1370, '137502000', 'MALABON', '13', '1375', '137502'),
(1371, '137503000', 'NAVOTAS', '13', '1375', '137503'),
(1372, '137504000', 'VALENZUELA', '13', '1375', '137504'),
(1373, '137601000', 'LAS PINAS', '13', '1376', '137601'),
(1374, '137602000', 'MAKATI', '13', '1376', '137602'),
(1375, '137603000', 'MUNTINLUPA', '13', '1376', '137603'),
(1376, '137604000', 'PARANAQUE', '13', '1376', '137604'),
(1377, '137605000', 'PASAY CITY', '13', '1376', '137605'),
(1378, '137606000', 'PATEROS', '13', '1376', '137606'),
(1379, '137607000', 'TAGUIG CITY', '13', '1376', '137607'),
(1380, '140101000', 'BANGUED', '14', '1401', '140101'),
(1381, '140102000', 'BOLINEY', '14', '1401', '140102'),
(1382, '140103000', 'BUCAY', '14', '1401', '140103'),
(1383, '140104000', 'BUCLOC', '14', '1401', '140104'),
(1384, '140105000', 'DAGUIOMAN', '14', '1401', '140105'),
(1385, '140106000', 'DANGLAS', '14', '1401', '140106'),
(1386, '140107000', 'DOLORES', '14', '1401', '140107'),
(1387, '140108000', 'LA PAZ', '14', '1401', '140108'),
(1388, '140109000', 'LACUB', '14', '1401', '140109'),
(1389, '140110000', 'LAGANGILANG', '14', '1401', '140110'),
(1390, '140111000', 'LAGAYAN', '14', '1401', '140111'),
(1391, '140112000', 'LANGIDEN', '14', '1401', '140112'),
(1392, '140113000', 'LICUAN-BAAY (LICUAN)', '14', '1401', '140113'),
(1393, '140114000', 'LUBA', '14', '1401', '140114'),
(1394, '140115000', 'MALIBCONG', '14', '1401', '140115'),
(1395, '140116000', 'MANABO', '14', '1401', '140116'),
(1396, '140117000', 'PENARRUBIA', '14', '1401', '140117'),
(1397, '140118000', 'PIDIGAN', '14', '1401', '140118'),
(1398, '140119000', 'PILAR', '14', '1401', '140119'),
(1399, '140120000', 'SALLAPADAN', '14', '1401', '140120'),
(1400, '140121000', 'SAN ISIDRO', '14', '1401', '140121'),
(1401, '140122000', 'SAN JUAN', '14', '1401', '140122'),
(1402, '140123000', 'SAN QUINTIN', '14', '1401', '140123'),
(1403, '140124000', 'TAYUM', '14', '1401', '140124'),
(1404, '140125000', 'TINEG', '14', '1401', '140125'),
(1405, '140126000', 'TUBO', '14', '1401', '140126'),
(1406, '140127000', 'VILLAVICIOSA', '14', '1401', '140127'),
(1407, '141101000', 'ATOK', '14', '1411', '141101'),
(1408, '141102000', 'BAGUIO CITY', '14', '1411', '141102'),
(1409, '141103000', 'BAKUN', '14', '1411', '141103'),
(1410, '141104000', 'BOKOD', '14', '1411', '141104'),
(1411, '141105000', 'BUGUIAS', '14', '1411', '141105'),
(1412, '141106000', 'ITOGON', '14', '1411', '141106'),
(1413, '141107000', 'KABAYAN', '14', '1411', '141107'),
(1414, '141108000', 'KAPANGAN', '14', '1411', '141108'),
(1415, '141109000', 'KIBUNGAN', '14', '1411', '141109'),
(1416, '141110000', 'LA TRINIDAD', '14', '1411', '141110'),
(1417, '141111000', 'MANKAYAN', '14', '1411', '141111'),
(1418, '141112000', 'SABLAN', '14', '1411', '141112'),
(1419, '141113000', 'TUBA', '14', '1411', '141113'),
(1420, '141114000', 'TUBLAY', '14', '1411', '141114'),
(1421, '142701000', 'BANAUE', '14', '1427', '142701'),
(1422, '142702000', 'HUNGDUAN', '14', '1427', '142702'),
(1423, '142703000', 'KIANGAN', '14', '1427', '142703'),
(1424, '142704000', 'LAGAWE', '14', '1427', '142704'),
(1425, '142705000', 'LAMUT', '14', '1427', '142705'),
(1426, '142706000', 'MAYOYAO', '14', '1427', '142706'),
(1427, '142707000', 'ALFONSO LISTA (POTIA)', '14', '1427', '142707'),
(1428, '142708000', 'AGUINALDO', '14', '1427', '142708'),
(1429, '142709000', 'HINGYON', '14', '1427', '142709'),
(1430, '142710000', 'TINOC', '14', '1427', '142710'),
(1431, '142711000', 'ASIPULO', '14', '1427', '142711'),
(1432, '143201000', 'BALBALAN', '14', '1432', '143201'),
(1433, '143206000', 'LUBUAGAN', '14', '1432', '143206'),
(1434, '143208000', 'PASIL', '14', '1432', '143208'),
(1435, '143209000', 'PINUKPUK', '14', '1432', '143209'),
(1436, '143211000', 'RIZAL (LIWAN)', '14', '1432', '143211'),
(1437, '143213000', 'TABUK', '14', '1432', '143213'),
(1438, '143214000', 'TANUDAN', '14', '1432', '143214'),
(1439, '143215000', 'TINGLAYAN', '14', '1432', '143215'),
(1440, '144401000', 'BARLIG', '14', '1444', '144401'),
(1441, '144402000', 'BAUKO', '14', '1444', '144402'),
(1442, '144403000', 'BESAO', '14', '1444', '144403'),
(1443, '144404000', 'BONTOC', '14', '1444', '144404'),
(1444, '144405000', 'NATONIN', '14', '1444', '144405'),
(1445, '144406000', 'PARACELIS', '14', '1444', '144406'),
(1446, '144407000', 'SABANGAN', '14', '1444', '144407'),
(1447, '144408000', 'SADANGA', '14', '1444', '144408'),
(1448, '144409000', 'SAGADA', '14', '1444', '144409'),
(1449, '144410000', 'TADIAN', '14', '1444', '144410'),
(1450, '148101000', 'CALANASAN (BAYAG)', '14', '1481', '148101'),
(1451, '148102000', 'CONNER', '14', '1481', '148102'),
(1452, '148103000', 'FLORA', '14', '1481', '148103'),
(1453, '148104000', 'KABUGAO', '14', '1481', '148104'),
(1454, '148105000', 'LUNA', '14', '1481', '148105'),
(1455, '148106000', 'PUDTOL', '14', '1481', '148106'),
(1456, '148107000', 'SANTA MARCELA', '14', '1481', '148107'),
(1457, '150702000', 'LAMITAN', '15', '1507', '150702'),
(1458, '150703000', 'LANTAWAN', '15', '1507', '150703'),
(1459, '150704000', 'MALUSO', '15', '1507', '150704'),
(1460, '150705000', 'SUMISIP', '15', '1507', '150705'),
(1461, '150706000', 'TIPO-TIPO', '15', '1507', '150706'),
(1462, '150707000', 'TUBURAN', '15', '1507', '150707'),
(1463, '150708000', 'AKBAR', '15', '1507', '150708'),
(1464, '150709000', 'AL-BARKA', '15', '1507', '150709'),
(1465, '150710000', 'HADJI MOHAMMAD AJUL', '15', '1507', '150710'),
(1466, '150711000', 'UNGKAYA PUKAN', '15', '1507', '150711'),
(1467, '150712000', 'HADJI MUHTAMAD', '15', '1507', '150712'),
(1468, '150713000', 'TABUAN-LASA', '15', '1507', '150713'),
(1469, '153601000', 'BACOLOD-KALAWI (BACOLOD GRANDE)', '15', '1536', '153601'),
(1470, '153602000', 'BALABAGAN', '15', '1536', '153602'),
(1471, '153603000', 'BALINDONG (WATU)', '15', '1536', '153603'),
(1472, '153604000', 'BAYANG', '15', '1536', '153604'),
(1473, '153605000', 'BINIDAYAN', '15', '1536', '153605'),
(1474, '153606000', 'BUBONG', '15', '1536', '153606'),
(1475, '153607000', 'BUTIG', '15', '1536', '153607'),
(1476, '153609000', 'GANASSI', '15', '1536', '153609'),
(1477, '153610000', 'KAPAI', '15', '1536', '153610'),
(1478, '153611000', 'LUMBA-BAYABAO (MAGUING)', '15', '1536', '153611'),
(1479, '153612000', 'LUMBATAN', '15', '1536', '153612'),
(1480, '153613000', 'MADALUM', '15', '1536', '153613'),
(1481, '153614000', 'MADAMBA', '15', '1536', '153614'),
(1482, '153615000', 'MALABANG', '15', '1536', '153615'),
(1483, '153616000', 'MARANTAO', '15', '1536', '153616'),
(1484, '153617000', 'MARAWI', '15', '1536', '153617'),
(1485, '153618000', 'MASIU', '15', '1536', '153618'),
(1486, '153619000', 'MULONDO', '15', '1536', '153619'),
(1487, '153620000', 'PAGAYAWAN (TATARIKAN)', '15', '1536', '153620'),
(1488, '153621000', 'PIAGAPO', '15', '1536', '153621'),
(1489, '153622000', 'POONA BAYABAO (GATA)', '15', '1536', '153622'),
(1490, '153623000', 'PUALAS', '15', '1536', '153623'),
(1491, '153624000', 'DITSAAN-RAMAIN', '15', '1536', '153624'),
(1492, '153625000', 'SAGUIARAN', '15', '1536', '153625'),
(1493, '153626000', 'TAMPARAN', '15', '1536', '153626'),
(1494, '153627000', 'TARAKA', '15', '1536', '153627'),
(1495, '153628000', 'TUBARAN', '15', '1536', '153628'),
(1496, '153629000', 'TUGAYA', '15', '1536', '153629'),
(1497, '153630000', 'WAO', '15', '1536', '153630'),
(1498, '153631000', 'MAROGONG', '15', '1536', '153631'),
(1499, '153632000', 'CALANOGAS', '15', '1536', '153632'),
(1500, '153633000', 'BUADIPOSO-BUNTONG', '15', '1536', '153633'),
(1501, '153634000', 'MAGUING', '15', '1536', '153634'),
(1502, '153635000', 'PICONG (SULTAN GUMANDER)', '15', '1536', '153635'),
(1503, '153636000', 'LUMBAYANAGUE', '15', '1536', '153636'),
(1504, '153637000', 'BUMBARAN', '15', '1536', '153637'),
(1505, '153638000', 'TAGOLOAN II', '15', '1536', '153638'),
(1506, '153639000', 'KAPATAGAN', '15', '1536', '153639'),
(1507, '153640000', 'SULTAN DUMALONDONG', '15', '1536', '153640'),
(1508, '153641000', 'LUMBACA-UNAYAN', '15', '1536', '153641'),
(1509, '153801000', 'AMPATUAN', '15', '1538', '153801'),
(1510, '153802000', 'BULDON', '15', '1538', '153802'),
(1511, '153803000', 'BULUAN', '15', '1538', '153803'),
(1512, '153805000', 'DATU PAGLAS', '15', '1538', '153805'),
(1513, '153806000', 'DATU PIANG', '15', '1538', '153806'),
(1514, '153807000', 'DATU ODIN SINSUAT (DINAIG)', '15', '1538', '153807'),
(1515, '153808000', 'SHARIFF AGUAK (MAGANOY)', '15', '1538', '153808'),
(1516, '153809000', 'MATANOG', '15', '1538', '153809'),
(1517, '153810000', 'PAGALUNGAN', '15', '1538', '153810'),
(1518, '153811000', 'PARANG', '15', '1538', '153811'),
(1519, '153812000', 'SULTAN KUDARAT (NULING)', '15', '1538', '153812'),
(1520, '153813000', 'SULTAN SA BARONGIS (LAMBAYONG)', '15', '1538', '153813'),
(1521, '153814000', 'KABUNTALAN (TUMBAO)', '15', '1538', '153814'),
(1522, '153815000', 'UPI', '15', '1538', '153815'),
(1523, '153816000', 'TALAYAN', '15', '1538', '153816'),
(1524, '153817000', 'SOUTH UPI', '15', '1538', '153817'),
(1525, '153818000', 'BARIRA', '15', '1538', '153818'),
(1526, '153819000', 'GEN. S. K. PENDATUN', '15', '1538', '153819'),
(1527, '153820000', 'MAMASAPANO', '15', '1538', '153820'),
(1528, '153821000', 'TALITAY', '15', '1538', '153821'),
(1529, '153822000', 'PAGAGAWAN', '15', '1538', '153822'),
(1530, '153823000', 'PAGLAT', '15', '1538', '153823'),
(1531, '153824000', 'SULTAN MASTURA', '15', '1538', '153824'),
(1532, '153825000', 'GUINDULUNGAN', '15', '1538', '153825'),
(1533, '153826000', 'DATU SAUDI-AMPATUAN', '15', '1538', '153826'),
(1534, '153827000', 'DATU UNSAY', '15', '1538', '153827'),
(1535, '153828000', 'DATU ABDULLAH SANGKI', '15', '1538', '153828'),
(1536, '153829000', 'RAJAH BUAYAN', '15', '1538', '153829'),
(1537, '153830000', 'DATU BLAH T. SINSUAT', '15', '1538', '153830'),
(1538, '153831000', 'DATU ANGGAL MIDTIMBANG', '15', '1538', '153831'),
(1539, '153832000', 'MANGUDADATU', '15', '1538', '153832'),
(1540, '153833000', 'PANDAG', '15', '1538', '153833'),
(1541, '153834000', 'NORTHERN KABUNTALAN', '15', '1538', '153834'),
(1542, '153835000', 'DATU HOFFER AMPATUAN', '15', '1538', '153835'),
(1543, '153836000', 'DATU SALIBO', '15', '1538', '153836'),
(1544, '153837000', 'SHARIFF SAYDONA MUSTAPHA', '15', '1538', '153837'),
(1545, '156601000', 'INDANAN', '15', '1566', '156601'),
(1546, '156602000', 'JOLO', '15', '1566', '156602'),
(1547, '156603000', 'KALINGALAN CALUANG', '15', '1566', '156603'),
(1548, '156604000', 'LUUK', '15', '1566', '156604'),
(1549, '156605000', 'MAIMBUNG', '15', '1566', '156605'),
(1550, '156606000', 'HADJI PANGLIMA TAHIL (MARUNGGAS)', '15', '1566', '156606'),
(1551, '156607000', 'OLD PANAMAO', '15', '1566', '156607'),
(1552, '156608000', 'PANGUTARAN', '15', '1566', '156608'),
(1553, '156609000', 'PARANG', '15', '1566', '156609'),
(1554, '156610000', 'PATA', '15', '1566', '156610'),
(1555, '156611000', 'PATIKUL', '15', '1566', '156611'),
(1556, '156612000', 'SIASI', '15', '1566', '156612'),
(1557, '156613000', 'TALIPAO', '15', '1566', '156613'),
(1558, '156614000', 'TAPUL', '15', '1566', '156614'),
(1559, '156615000', 'TONGKIL', '15', '1566', '156615'),
(1560, '156616000', 'PANGLIMA ESTINO (NEW PANAMAO)', '15', '1566', '156616'),
(1561, '156617000', 'LUGUS', '15', '1566', '156617'),
(1562, '156618000', 'PANDAMI', '15', '1566', '156618'),
(1563, '156619000', 'OMAR', '15', '1566', '156619'),
(1564, '157001000', 'PANGLIMA SUGALA (BALIMBING)', '15', '1570', '157001'),
(1565, '157002000', 'BONGAO', '15', '1570', '157002'),
(1566, '157003000', 'MAPUN (CAGAYAN DE TAWI-TAWI)', '15', '1570', '157003'),
(1567, '157004000', 'SIMUNUL', '15', '1570', '157004'),
(1568, '157005000', 'SITANGKAI', '15', '1570', '157005'),
(1569, '157006000', 'SOUTH UBIAN', '15', '1570', '157006'),
(1570, '157007000', 'TANDUBAS', '15', '1570', '157007'),
(1571, '157008000', 'TURTLE ISLANDS', '15', '1570', '157008'),
(1572, '157009000', 'LANGUYAN', '15', '1570', '157009'),
(1573, '157010000', 'SAPA-SAPA', '15', '1570', '157010'),
(1574, '157011000', 'SIBUTU', '15', '1570', '157011'),
(1575, '160201000', 'BUENAVISTA', '16', '1602', '160201'),
(1576, '160202000', 'BUTUAN', '16', '1602', '160202'),
(1577, '160203000', 'CABADBARAN', '16', '1602', '160203'),
(1578, '160204000', 'CARMEN', '16', '1602', '160204'),
(1579, '160205000', 'JABONGA', '16', '1602', '160205'),
(1580, '160206000', 'KITCHARAO', '16', '1602', '160206'),
(1581, '160207000', 'LAS NIEVES', '16', '1602', '160207'),
(1582, '160208000', 'MAGALLANES', '16', '1602', '160208'),
(1583, '160209000', 'NASIPIT', '16', '1602', '160209'),
(1584, '160210000', 'SANTIAGO', '16', '1602', '160210'),
(1585, '160211000', 'TUBAY', '16', '1602', '160211'),
(1586, '160212000', 'REMEDIOS T. ROMUALDEZ', '16', '1602', '160212'),
(1587, '160301000', 'BAYUGAN', '16', '1603', '160301'),
(1588, '160302000', 'BUNAWAN', '16', '1603', '160302'),
(1589, '160303000', 'ESPERANZA', '16', '1603', '160303'),
(1590, '160304000', 'LA PAZ', '16', '1603', '160304'),
(1591, '160305000', 'LORETO', '16', '1603', '160305'),
(1592, '160306000', 'PROSPERIDAD', '16', '1603', '160306'),
(1593, '160307000', 'ROSARIO', '16', '1603', '160307'),
(1594, '160308000', 'SAN FRANCISCO', '16', '1603', '160308'),
(1595, '160309000', 'SAN LUIS', '16', '1603', '160309'),
(1596, '160310000', 'SANTA JOSEFA', '16', '1603', '160310'),
(1597, '160311000', 'TALACOGON', '16', '1603', '160311'),
(1598, '160312000', 'TRENTO', '16', '1603', '160312'),
(1599, '160313000', 'VERUELA', '16', '1603', '160313'),
(1600, '160314000', 'SIBAGAT', '16', '1603', '160314'),
(1601, '166701000', 'ALEGRIA', '16', '1667', '166701'),
(1602, '166702000', 'BACUAG', '16', '1667', '166702'),
(1603, '166704000', 'BURGOS', '16', '1667', '166704'),
(1604, '166706000', 'CLAVER', '16', '1667', '166706'),
(1605, '166707000', 'DAPA', '16', '1667', '166707'),
(1606, '166708000', 'DEL CARMEN', '16', '1667', '166708'),
(1607, '166710000', 'GENERAL LUNA', '16', '1667', '166710'),
(1608, '166711000', 'GIGAQUIT', '16', '1667', '166711'),
(1609, '166714000', 'MAINIT', '16', '1667', '166714'),
(1610, '166715000', 'MALIMONO', '16', '1667', '166715'),
(1611, '166716000', 'PILAR', '16', '1667', '166716'),
(1612, '166717000', 'PLACER', '16', '1667', '166717'),
(1613, '166718000', 'SAN BENITO', '16', '1667', '166718'),
(1614, '166719000', 'SAN FRANCISCO (ANAO-AON)', '16', '1667', '166719'),
(1615, '166720000', 'SAN ISIDRO', '16', '1667', '166720'),
(1616, '166721000', 'SANTA MONICA (SAPAO)', '16', '1667', '166721'),
(1617, '166722000', 'SISON', '16', '1667', '166722'),
(1618, '166723000', 'SOCORRO', '16', '1667', '166723'),
(1619, '166724000', 'SURIGAO', '16', '1667', '166724'),
(1620, '166725000', 'TAGANA-AN', '16', '1667', '166725'),
(1621, '166727000', 'TUBOD', '16', '1667', '166727'),
(1622, '166801000', 'BAROBO', '16', '1668', '166801'),
(1623, '166802000', 'BAYABAS', '16', '1668', '166802'),
(1624, '166803000', 'BISLIG', '16', '1668', '166803'),
(1625, '166804000', 'CAGWAIT', '16', '1668', '166804'),
(1626, '166805000', 'CANTILAN', '16', '1668', '166805'),
(1627, '166806000', 'CARMEN', '16', '1668', '166806'),
(1628, '166807000', 'CARRASCAL', '16', '1668', '166807'),
(1629, '166808000', 'CORTES', '16', '1668', '166808'),
(1630, '166809000', 'HINATUAN', '16', '1668', '166809'),
(1631, '166810000', 'LANUZA', '16', '1668', '166810'),
(1632, '166811000', 'LIANGA', '16', '1668', '166811'),
(1633, '166812000', 'LINGIG', '16', '1668', '166812'),
(1634, '166813000', 'MADRID', '16', '1668', '166813'),
(1635, '166814000', 'MARIHATAG', '16', '1668', '166814'),
(1636, '166815000', 'SAN AGUSTIN', '16', '1668', '166815'),
(1637, '166816000', 'SAN MIGUEL', '16', '1668', '166816'),
(1638, '166817000', 'TAGBINA', '16', '1668', '166817'),
(1639, '166818000', 'TAGO', '16', '1668', '166818'),
(1640, '166819000', 'TANDAG', '16', '1668', '166819'),
(1641, '168501000', 'BASILISA (RIZAL)', '16', '1685', '168501'),
(1642, '168502000', 'CAGDIANAO', '16', '1685', '168502'),
(1643, '168503000', 'DINAGAT', '16', '1685', '168503'),
(1644, '168504000', 'LIBJO (ALBOR)', '16', '1685', '168504'),
(1645, '168505000', 'LORETO', '16', '1685', '168505'),
(1646, '168506000', 'SAN JOSE', '16', '1685', '168506'),
(1647, '168507000', 'TUBAJON', '16', '1685', '168507');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `company_tin` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `company_name` varchar(250) COLLATE utf8mb4_bin NOT NULL,
  `branch` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `business_type` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `logo` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_tin_UNIQUE` (`company_tin`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_tin`, `company_name`, `branch`, `business_type`, `logo`) VALUES
(1, '654132150', 'Jollibee', 'Gen. Luna, Ermita', 'food', 'jollibee.png'),
(2, '123654789', 'Diligence Cafe', 'Malate, Manila', 'food', 'diligence.jpg'),
(3, '5514917468', 'Jollibee', 'Tandang Sora, Commonwealth', 'food', 'jollibee.png'),
(4, '7752487534', 'Dunkin Donuts', 'Sangandaan, Quezon City', 'food', 'dunkin.png'),
(5, '1239874563', 'Greenwich', 'Farmer\'s Plaza', 'food', 'greenwich.png'),
(6, '1550792273', 'Mang Inasal', 'Alphaland Southgate Towers', 'food', 'mang inasal.png'),
(7, '6070323661', 'Kenny Rogers Roasters', 'T3 - NAIA', 'food', 'kennyrogers.png'),
(8, '5200487589', 'Chowking', 'Hi-Top Supermarket, Aurora Blvd.', 'food', 'chowking.png'),
(9, '123498742', 'KFC', 'Mall of Asia', 'food', 'kfc.png'),
(10, '3411927270', 'Aristocrat', 'Malate, Manila', 'food', '7f0s8h7d0s0c.png'),
(11, '6171762815', 'Mercury Drug', 'GMA, Cavite', 'pharmacy', 'mercury.png'),
(12, '4632180231', 'Mercury Drug', 'Hidalgo St., Quiapo', 'pharmacy', 'mercury.png'),
(13, '3591867572', 'Mercury Drug', 'BGC Market Market', 'pharmacy', 'mercury.png'),
(14, '9871234562', 'Watsons', 'Portal, GMA, Cavite', 'pharmacy', 'watsons.png'),
(15, '8323848273', 'Watsons', 'Montillano St., Alabang', 'pharmacy', 'watsons.png'),
(16, '7965766163', 'Watsons', 'Waltermart, Gen. Trias', 'pharmacy', 'watsons.png'),
(17, '5821645777', 'Watsons', 'SM Manila', 'pharmacy', 'watsons.png'),
(18, '8690961165', 'The Generics Pharmacy', 'Arnaiz Ave., Makati', 'pharmacy', 'tgp.png'),
(19, '3983278667', 'The Generics Pharmacy', 'Quirino Ave, Paranaque', 'pharmacy', 'tgp.png'),
(20, '6344364475', 'The Generics Pharmacy', 'Boni Ave., Mandaluyong', 'pharmacy', 'tgp.png'),
(21, '8016618868', 'LRT', 'Central Station', 'transportation', 'lrta.png'),
(22, '9690147404', 'LRT', 'Doroteo Jose Station', 'transportation', 'lrta.png'),
(23, '2035775056', 'LRT', 'United Nations Station', 'transportation', 'lrta.png'),
(24, '8063147891', 'LRT', 'Gil Puyat Station', 'transportation', 'lrta.png'),
(25, '2019924979', 'LRT', 'Pedro Gil Station', 'transportation', 'lrta.png'),
(26, '2949532037', 'MRT', 'Taft Station', 'transportation', 'lrta.png'),
(27, '5100616960', 'MRT', 'Guadalupe Station', 'transportation', 'lrta.png'),
(28, '7282645530', 'MRT', 'Magallanes Station', 'transportation', 'lrta.png'),
(29, '3115335081', 'MRT', 'Araneta - Cubao Station', 'transportation', 'lrta.png'),
(30, '9271004158', 'MRT', 'Ayala Station', 'transportation', 'lrta.png'),
(31, '6545671280', 'Mercury Drug', 'Guiguinto, Bulacan', 'pharmacy', 'mercury.png');

-- --------------------------------------------------------

--
-- Table structure for table `company_accounts`
--

DROP TABLE IF EXISTS `company_accounts`;
CREATE TABLE IF NOT EXISTS `company_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `company_id` int(20) NOT NULL,
  `is_enabled` int(10) NOT NULL,
  `log_attempts` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `company_accounts`
--

INSERT INTO `company_accounts` (`id`, `user_name`, `password`, `company_id`, `is_enabled`, `log_attempts`) VALUES
(1, 'jb_genluna', '497be5f0198c1013006e9d996989025f', 1, 0, 7),
(2, 'diligence_malate', 'f9aa9714846b01a8e53e9e1fbf48b645', 2, 1, 0),
(3, 'jb_tsora', 'c5447f2456099ffe25b72d33a4306b98', 3, 1, 0),
(4, 'dd_sangandaan', 'd6cc7d23565cb3a613845f3aa5494785', 4, 1, 0),
(5, 'gw_farmers', '57cd02849719667bcda9907cf7d06367', 5, 1, 0),
(6, 'mi_alphaland', '85c1a1370fbd5445bd04a7b9baeb3142', 6, 1, 0),
(7, 'krr_naiat3', '88bdd553895ad4e8cc9f423bf5ce6d30', 7, 1, 0),
(8, 'ck_hitop', 'e8ac986e7fc025944fd255d79e11cbd9', 8, 1, 0),
(9, 'kfc_moa1', '11f0dc647cc8d1dc45be423e76bf57a1', 9, 1, 0),
(10, 'ars_malate', 'd09e2d16db0d34ea17b22b6ea797a81c', 10, 1, 0),
(11, 'md_gmacav', '7dea03553e42d350f4d495b471e1e93c', 11, 1, 0),
(12, 'md_hidalgo', 'dbfcae8c1e9d8c11b4e10c7cd6b017dc', 12, 1, 0),
(13, 'md_bgcmarket', '325686ffdbf76c771a7442acc32de0ed', 13, 1, 0),
(14, 'wats_portal', 'dc6f58dd01e606eff0879d3a4b49105b', 14, 1, 0),
(15, 'wats_montillano', '820d92d47034ef55e5ec73cfae112071', 15, 1, 0),
(16, 'wats_wmgentri', '2bcdd43285f1e6918ed141f94c211a5b', 16, 1, 0),
(17, 'wats_smmanila', 'c4aca91ed91cdb8f20c455f358ef82e7', 17, 1, 0),
(18, 'tgp_arnaiz', '7b8ad0271d4c27fbb644cbb8bf38af84', 18, 1, 0),
(19, 'tgp_quirino', '6ebef3a0dc58f31f29fc9fb62e1a9787', 19, 1, 0),
(20, 'tgp_boni', '883a5d99edd0d7ff659f408425f6f35f', 20, 1, 0),
(21, 'lrt_central', '213a685e9492411de7648789f3388e0a', 21, 1, 0),
(22, 'lrt_djose', 'e290b31b851111f7ff96908d309c172b', 22, 1, 0),
(23, 'lrt_un', '803aa5a49b417aab8543eb610ca09c8e', 23, 1, 0),
(24, 'lrt_gp', '844bad0f042113287b99138680cd2f48', 24, 1, 0),
(25, 'lrt_pg', 'be9fcad3a6db5f5fc323838867d3d286', 25, 1, 0),
(26, 'mrt_taft', '295eb00c7e0bd88685dd5be2b59fda97', 26, 1, 0),
(27, 'mrt_guada', '10760d0affc120b7fa4b7390ace0d9cc', 27, 1, 0),
(28, 'mrt_maga', 'f1031948b5f83cc72411cf5d5e5ea534', 28, 1, 0),
(29, 'mrt_araneta', '35603a4ee8e6fa6b59f8062c961322f7', 29, 1, 0),
(30, 'mrt_ayala', '6226dd56a733a525c6aaa76b7e7a6702', 30, 1, 0),
(31, 'md_wmguiguinto', '85f0dcc21eb3ca8173867aae85baee97', 31, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `complaint_report`
--

DROP TABLE IF EXISTS `complaint_report`;
CREATE TABLE IF NOT EXISTS `complaint_report` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `desc` varchar(300) COLLATE utf8mb4_bin DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `company_id` int(20) DEFAULT NULL,
  `member_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `complaint_report`
--

INSERT INTO `complaint_report` (`id`, `desc`, `report_date`, `company_id`, `member_id`) VALUES
(2, 'Epal nung bantay', '2020-08-27 06:46:30', 1, 2),
(3, 'No discount given', '2020-08-27 06:46:30', 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `drug`
--

DROP TABLE IF EXISTS `drug`;
CREATE TABLE IF NOT EXISTS `drug` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `generic_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `brand` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `dose` int(20) NOT NULL,
  `unit` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `is_otc` int(10) NOT NULL,
  `max_monthly` int(20) DEFAULT NULL,
  `max_weekly` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `drug`
--

INSERT INTO `drug` (`id`, `generic_name`, `brand`, `dose`, `unit`, `is_otc`, `max_monthly`, `max_weekly`) VALUES
(1, 'paracetamol', 'biogesic', 500, 'mg', 1, 45000, 12000),
(2, 'paracetamol', 'bioflu', 500, 'mg', 1, 45000, 13500),
(3, 'ibuprofen,paracetamol', 'alaxan', 500, 'mg', 1, 45000, 14000),
(4, 'diphenhydramine', 'benadryl', 500, 'mg', 1, 45000, 21000),
(5, 'loratidine', 'claritin ', 500, 'mg', 1, 45000, 21000),
(6, 'calcium carbonate,famotidine,magnesium hydroxide', 'kremil-s advance', 500, 'mg', 0, 45000, 21000),
(7, 'cetirizine', 'watsons', 10, 'mg', 1, 70, 300),
(8, 'cetirizine', 'virlix', 10, 'mg', 1, 70, 300),
(9, 'carbocisteine,zinc', 'solmux', 500, 'mg', 1, 7000, 30000),
(10, 'sodium ascorbate,zinc', 'immunpro', 500, 'mg', 1, 7000, 30000),
(11, 'aa,sodium ascorbate', 'immunpro', 500, 'mg', 1, 30000, 7000),
(12, 'sodium ascorbate,zincx', 'immunpro', 500, 'mg', 1, 7000, 30000),
(13, 'naproxen', 'flanax', 100, 'mg', 1, 6600, 1540),
(14, 'paracetamol', 'watsons', 500, 'mg', 1, 30000, 7000),
(15, 'mefenamic acid', 'ritemed', 100, 'mg', 1, 7500, 1750),
(16, 'bisacodyl', 'dulcolax', 250, 'mg', 1, 7500, 1750),
(17, 'bisacodyl', 'dulcolax', 100, 'mg', 1, 7500, 1750),
(18, 'loratidine', 'claritin', 50, 'mg', 1, 300, 70);

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
CREATE TABLE IF NOT EXISTS `food` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(20) NOT NULL,
  `desc` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `vat_exempt_price` decimal(13,2) NOT NULL,
  `discount_price` decimal(13,2) NOT NULL,
  `payable_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1, 7, 'meals for 2', '100.00', '20.00', '80.00'),
(2, 8, 'meals', '100.00', '20.00', '80.00'),
(3, 9, 'meals for 2', '100.00', '20.00', '80.00'),
(4, 10, 'meals', '100.00', '20.00', '80.00'),
(5, 11, 'meals', '100.00', '20.00', '80.00'),
(6, 12, 'meals', '100.00', '20.00', '80.00'),
(7, 38, 'Deiri melk 3s', '200.00', '40.00', '160.00'),
(8, 38, 'Gardenko 6s', '1000.00', '200.00', '800.00'),
(9, 38, 'Neskopi 11-in-1', '500.00', '100.00', '400.00'),
(10, 66, 'Dine in meals for 2', '1227.68', '245.54', '982.14'),
(11, 73, 'Meals for 3', '892.86', '178.57', '714.29'),
(12, 78, '1 King Lasagna 2 Pizza', '799.11', '159.82', '639.29'),
(13, 86, 'Hamburger', '26.79', '5.36', '21.43'),
(14, 87, 'Palabok', '107.14', '21.43', '85.71'),
(15, 88, 'Chicken Burger', '89.29', '17.86', '71.42'),
(16, 97, 'Palabok', '35.71', '7.14', '28.57'),
(17, 98, 'Palabok', '178.57', '35.71', '142.85'),
(18, 99, 'Chicken Spaghetti', '160.71', '32.14', '128.58'),
(19, 100, 'Chicken Burger', '446.43', '89.29', '357.10'),
(20, 101, 'Hamburger', '26.79', '5.36', '21.43'),
(21, 102, 'Chicken Burger', '44.64', '8.93', '35.71'),
(22, 103, 'Chicken Burger', '44.64', '8.93', '35.71'),
(23, 121, 'Chicken Burger', '1339.29', '267.86', '1071.30');

-- --------------------------------------------------------

--
-- Table structure for table `guardian`
--

DROP TABLE IF EXISTS `guardian`;
CREATE TABLE IF NOT EXISTS `guardian` (
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
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `guardian`
--

INSERT INTO `guardian` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `relationship`, `contact_number`, `email`, `member_id`) VALUES
(1, 'Gary', 'Jenelle', 'Winton', '1', 'Grandfather', '0256890796', 'garywinton9@gmeal.com', 1),
(2, 'Nonie', '', 'Irene', '2', 'Grandmother', '09123654789', 'nonieirene20@gmeal.com', 2),
(3, 'Kelsey', 'Eulalia', 'Diamond', '1', 'Grandfather', '0238471073', 'kelseydiamond9@gmeal.com', 3),
(4, 'Galen', 'Avah', 'Kirby', '2', 'Grandmother', '0211549317', 'galenkirby7@gmeal.com', 4),
(5, 'Kristine', 'Marcy', 'Charissa', '1', 'Grandfather', '0228048410', 'kristinecharissa17@gmeal.com', 5),
(6, 'Cheyanne', 'Paulette', 'Jaylee', '1', 'Grandfather', '0266301227', 'cheyannejaylee9@gmeal.com', 6),
(7, 'Avalon', 'Brynlee', 'Aspen', '1', 'Grandfather', '09123654654', 'avalonaspen19@gmeal.com', 7),
(8, 'Vianne', 'Kassidy', 'Ursula', '2', 'Grandmother', '0214578421', 'vianneursula9@gmeal.com', 8),
(9, 'Rayzer', 'Kentt', 'Carran', '2', 'Grandmother', '0274763308', 'raycarran7@gmeal.com', 9),
(10, 'Pamella', 'Russel', 'Corey', '2', 'Grandmother', '0288296230', 'pamellacorey1@gmeal.com', 10),
(11, 'Lacy', 'Bekki', 'Marcy', '1', 'Grandfather', '0277137953', 'lacymarcy18@gmeal.com', 11),
(12, 'Love', 'Demelzaxx', 'Paulette', '1', 'Grandfather', '0288759308', 'lovepaulette0@gmeal.com', 12),
(13, 'Goldie', 'Marilynn', 'Vianne', '1', 'Grandfather', '0261176212', 'goldievianne13@gmeal.com', 13),
(14, 'Kimson', '', 'Kingston', '1', 'Grandfather', '029384723', 'sdlkfj@meal.ccc', 14),
(23, 'Live', 'News', 'Portal', '1', 'grandpapa', '09654666999', 'livenews@yicjpb.csx', 20);

-- --------------------------------------------------------

--
-- Table structure for table `lost_report`
--

DROP TABLE IF EXISTS `lost_report`;
CREATE TABLE IF NOT EXISTS `lost_report` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `desc` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `member_id` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `lost_report`
--

INSERT INTO `lost_report` (`id`, `desc`, `report_date`, `member_id`) VALUES
(1, '', '2020-08-27 19:20:36', 2),
(2, '', '2020-08-27 19:28:56', 2),
(3, '', '2020-08-27 19:40:41', 2),
(4, '', '2020-08-27 19:41:16', 2),
(5, '', '2020-08-27 19:43:30', 2),
(6, 'On 09/30/2020 5:40 AM; NFC Activated; Account Activated; ', '2020-09-29 21:41:00', 5),
(7, '', '2020-09-29 21:26:35', 5),
(8, '', '2020-09-23 22:10:08', 2),
(9, 'On 10/10/2020 5:40 PM; NFC Activated; Account Deactivated; ', '2020-10-10 09:40:21', 2);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `member_count` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `osca_id` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `nfc_serial` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL,
  `nfc_active` int(10) DEFAULT 1,
  `password` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `account_enabled` int(10) DEFAULT 1,
  `first_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `middle_name` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `birth_date` date NOT NULL,
  `sex` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `membership_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `picture` varchar(250) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `osca_id_UNIQUE` (`osca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `member_count`, `osca_id`, `nfc_serial`, `nfc_active`, `password`, `account_enabled`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `membership_date`, `picture`) VALUES
(1, 00001, '1376-2000001', '0415916a', 1, '757efdfdd2d522485fc7d2abca265f5a', 1, 'Lai', 'Arbiol', 'Girardi', '1953-06-17', '2', '0912-456-7890', 'lai.girardi@ymeal.com', '2020-10-10 09:38:34', '99f194053295edb4.png'),
(2, 00002, '0421-2000002', '040af172', 1, '5315626f5051ccf7ae91bb13e54df81f', 0, 'Rubyx', 'Ildefonso', 'Glass', '1960-01-25', '2', '09123321456', 'ruby.glass@ymeal.com', '2020-10-10 09:40:21', '321bf8c6b2f541ca.png'),
(3, 00003, '0421-2000003', '04e29172', 1, 'bd3fb7aeedec139792338edf6b9e5d77', 1, 'Cordell', 'Castro', 'Broxton', '1940-06-15', '1', '09654123789', 'cordell.broxton@ymeal.com', '2020-09-29 22:44:20', '52448de14aa059fb.png'),
(4, 00004, '0421-2000004', '046c6d6a', 1, 'b1383705b102fb7e7f09bd3419f15ae8', 1, 'Stephine', 'Gaco', 'Lamagna', '1932-07-17', '2', '0917-325-5200', 'stephine.lamagna@ymeal.com', '2020-10-03 14:12:10', 'c44a971857566659.png'),
(5, 00005, '1376-2000005', '043af50a', 1, 'c105429a85eb404596dea1812efe4f3f', 1, 'Olimpia', '', 'Ollis', '1940-01-01', '2', '09123654289', 'olimpia.ollis@ymeal.com', '2020-09-29 21:41:00', '30286704964f2216.png'),
(6, 00006, '1339-2000006', '04d84c72', 1, 'c422a05eb4e88b81e1edce1bdcb1b10d', 1, 'Harriette', 'Flavell', 'Milbourn', '1945-01-25', '2', '09-253-1028', 'harriette.milbourn@ymeal.com', '2020-09-29 14:02:11', 'db790b1dd0875bf8.png'),
(7, 00007, '0421-2000007', '04cc3672', 0, 'bcf19899b934b970cf38180f435ac92b', 1, 'Elise', 'Trump', 'Benjamin', '1960-02-22', '1', '09123456987', 'elise.benjamin@ymeal.com', '2020-10-10 05:36:21', 'a5e20bf9e82bcbcd.png'),
(8, 00008, '1376-2000008', '04df6e72 ', 1, '08b18de87a0ec3bfda4b71f8cfcf96bd', 1, 'Hermine', 'Bridgman', 'Poirer', '1990-01-01', '1', '0909-123-4567', 'hermine.poirer@ymeal.com', '2020-09-29 14:02:33', '724a1268e8e3e80e.png'),
(9, 00009, '0410-2000009', '04499172', 1, '6c51ba20aa60e52ef80ce1cd7ecdec65', 1, 'Khaleed', '', 'Dawson', '1900-01-01', '2', '12341234', 'khaleed.dawson@ymeal.com', '2020-09-29 08:56:36', '599cc2fdde9ba53f.png'),
(10, 00010, '0369-2000010', '5678567856785678', 1, '9ece80d16df210a3565dd6bb8087b635', 1, 'Ernestine', 'Kyle', 'Ayers', '1960-08-11', '2', '56785678', 'ernestine.ayers@ymeal.com', '2020-09-29 14:00:32', '488d72933f722163.png'),
(11, 00011, '0434-2000011', '12341234asdfasdf', 1, 'a5f2e92c99938d340841bc8ae88fd3e8', 1, 'Noburu', 'Danya', 'Lea', '1940-08-29', '2', '12341234', 'noburu.lea@ymeal.com', '2020-09-29 14:01:34', '64705d03e4205626.png'),
(12, 00012, '1339-2000012', '2A6B9CE2A46B1DE9', 1, '03ebc74ab3befe4f8c01ead8c1675c8a', 1, 'Vasanti', 'Elpidio', 'Hippolyte', '1800-12-25', '2', '0279281684', 'vasanti.hippolyte@ymeal.com', '2020-09-29 08:57:56', '7c7cc230591ba5dc.png'),
(13, 00013, '0314-2000013', '2A6B9CE2A4DB4DE9', 1, '0433d5db98e86fd7686339b27ace91fe', 1, 'McKenzie ', 'Houston', 'Jessye', '1948-12-08', '2', '0279281684', 'mckenzie.jessye@ymeal.com', '2020-09-29 08:55:25', 'c74391e2ef0cbfd5.png'),
(14, 00014, '0410-2000014', '7890acde7890acde', 1, '4893a53f8f7e6d89938f539c7a910f12', 1, 'Christian', '', 'Murphy', '1958-10-25', '1', '0948654123', 'asdsa@aa.afx', '2020-09-29 08:54:17', 'b423e08f7a5206c6.png'),
(20, 00020, '1374-2000020', '9as8d7asg654', 1, '2228c67e7304709d3405461b427ee018', 1, 'Akosie', '', 'Dogiedog', '1948-03-01', '1', '09654123987', 'livenews@youchub.com', '2020-09-29 05:46:47', 'ff6f87deb1182bff.png');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

DROP TABLE IF EXISTS `pharmacy`;
CREATE TABLE IF NOT EXISTS `pharmacy` (
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
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `pharmacy`
--

INSERT INTO `pharmacy` (`id`, `transaction_id`, `desc_nondrug`, `drug_id`, `quantity`, `unit_price`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1, 1, '', 2, 8, '1120.00', '1000.00', '200.00', '800.00'),
(2, 2, '', 6, 10, '112.00', '100.00', '20.00', '80.00'),
(3, 3, '', 3, 4, '896.00', '800.00', '160.00', '640.00'),
(4, 4, '', 4, 3, '448.00', '400.00', '80.00', '320.00'),
(5, 5, '', 3, 14, '1500.00', '1339.29', '267.86', '1071.43'),
(6, 6, '', 1, 18, '2000.00', '1785.71', '357.14', '1428.57'),
(7, 19, '', 3, 10, '5.00', '50.00', '10.00', '40.00'),
(8, 21, '', 1, 14, '100.00', '1250.00', '250.00', '1000.00'),
(9, 23, '', 1, 18, '100.00', '1250.00', '250.00', '1000.00'),
(10, 24, '', 1, 14, '100.00', '1250.00', '250.00', '1000.00'),
(17, 23, '', 2, 10, '201.60', '1800.00', '360.00', '1440.00'),
(18, 29, '', 6, 10, '4.00', '35.71', '7.14', '28.56'),
(19, 33, 'Dairy Milk', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(20, 41, 'Deiri melk', NULL, NULL, NULL, '200.00', '40.00', '160.00'),
(21, 42, 'Kopinya 10s', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(22, 42, 'Nice Kopi 11in1 - 10s', NULL, NULL, NULL, '200.00', '40.00', '160.00'),
(23, 44, 'Kopinya 10s', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(24, 45, 'Grate Caste White 30s', NULL, NULL, NULL, '150.00', '30.00', '120.00'),
(25, 46, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(26, 46, 'Starbox Prop-puchino 330ml', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(27, 48, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(28, 48, 'Starbox Prop-puchino 330ml', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(29, 50, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(30, 50, 'Pudgey Vars 12s', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(31, 50, NULL, 3, 10, '5.00', '100.00', '20.00', '80.00'),
(32, 50, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(33, 50, 'Pudgey Vars 12s', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(34, 55, NULL, 6, 10, '5.00', '100.00', '20.00', '80.00'),
(35, 55, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(36, 55, 'Pudgey Vars 12s', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(37, 58, NULL, 6, 10, '5.00', '100.00', '20.00', '80.00'),
(38, 58, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(39, 58, 'Pudgey Vars 12s', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(40, 61, NULL, 6, 10, '5.00', '100.00', '20.00', '80.00'),
(41, 61, 'Quai-ker Oathmill', NULL, NULL, NULL, '100.00', '20.00', '80.00'),
(42, 61, 'Pudgey Vars 12s', NULL, NULL, NULL, '97.32', '19.46', '77.86'),
(43, 67, NULL, 6, 14, '8.15', '101.88', '20.38', '81.50'),
(44, 68, NULL, 8, 7, '6.25', '39.06', '7.81', '31.25'),
(45, 68, NULL, 9, 7, '8.00', '50.00', '10.00', '40.00'),
(46, 68, NULL, 1, 1, '5.20', '65.00', '13.00', '52.00'),
(47, 68, NULL, 10, 1, '5.20', '65.00', '13.00', '52.00'),
(48, 72, NULL, 9, 7, '8.00', '50.00', '10.00', '40.00'),
(49, 76, 'Kitkat 11\'s', NULL, NULL, NULL, '892.86', '178.57', '714.29'),
(50, 79, NULL, 7, 7, '6.25', '39.06', '7.81', '31.25'),
(51, 79, NULL, 9, 7, '8.00', '50.00', '10.00', '40.00'),
(52, 79, NULL, 1, 1, '5.20', '65.00', '13.00', '52.00'),
(53, 79, NULL, 10, 1, '5.20', '65.00', '13.00', '52.00'),
(54, 80, 'Frozen Siomai Pack 25s Pack', NULL, NULL, NULL, '249.75', '44.60', '205.15'),
(57, 83, 'Frozen Siomai Pack 25s Pack', NULL, NULL, NULL, '249.75', '44.60', '205.15'),
(58, 84, NULL, 7, 7, '6.25', '39.06', '7.81', '31.25'),
(59, 84, NULL, 9, 10, '8.00', '50.00', '10.00', '40.00'),
(60, 84, NULL, 1, 1, '5.20', '65.00', '13.00', '52.00'),
(61, 84, NULL, 12, 1, '5.20', '65.00', '13.00', '52.00'),
(62, 109, NULL, 1, 1, '30.00', '26.79', '5.36', '21.43'),
(63, 110, NULL, 1, 1, '30.00', '26.79', '5.36', '21.43'),
(64, 111, NULL, 1, 5, '30.00', '133.93', '26.79', '107.14'),
(65, 112, NULL, 1, 1, '30.00', '26.79', '5.36', '21.43'),
(66, 113, NULL, 13, 1, '20.00', '17.86', '3.57', '14.29'),
(67, 114, NULL, 14, 1, '30.00', '26.79', '5.36', '21.43'),
(68, 116, NULL, 15, 1, '20.00', '17.86', '3.57', '14.29'),
(69, 117, NULL, 17, 1, '20.00', '17.86', '3.57', '14.29'),
(70, 118, NULL, 1, 5, '30.00', '133.93', '26.79', '107.14'),
(71, 119, NULL, 18, 1, '10.00', '8.93', '1.79', '7.14'),
(72, 122, NULL, 1, 16, '30.00', '428.57', '85.71', '342.86'),
(73, 123, NULL, 1, 17, '30.00', '455.36', '91.07', '364.29');

-- --------------------------------------------------------

--
-- Table structure for table `qr_request`
--

DROP TABLE IF EXISTS `qr_request`;
CREATE TABLE IF NOT EXISTS `qr_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(20) NOT NULL,
  `desc` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `token` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `trans_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `transaction_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `qr_request`
--

INSERT INTO `qr_request` (`id`, `member_id`, `desc`, `token`, `trans_date`, `transaction_id`) VALUES
(1, 2, 'Product: Biogesic Quantity: 7 Notes: kahit ano', '9jifhjvahke0g9ai', '2020-10-05 20:02:22', NULL),
(2, 4, 'Product: Alaxan 500mg; Qty: 10pcs; Notes: Sakit katawan', '47bce5c74f589f4', '2020-10-04 12:52:57', 19);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `trans_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `company_id` int(20) NOT NULL,
  `member_id` int(20) NOT NULL,
  `clerk` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `trans_date`, `company_id`, `member_id`, `clerk`) VALUES
(1, '2020-09-06 13:12:45', 14, 4, 'M Reyes'),
(2, '2020-07-27 20:45:34', 13, 2, ''),
(3, '2020-09-07 15:15:25', 14, 3, 'Cy'),
(4, '2020-07-24 21:39:00', 15, 3, ''),
(5, '2020-08-11 17:36:53', 16, 4, ''),
(6, '2020-07-04 17:36:53', 17, 2, ''),
(7, '2020-09-12 09:27:18', 4, 4, ''),
(8, '2020-08-15 17:36:55', 4, 4, ''),
(9, '2020-06-17 17:36:55', 6, 5, ''),
(10, '2020-01-11 17:36:55', 5, 2, ''),
(11, '2020-02-18 17:36:55', 4, 3, ''),
(12, '2020-08-11 17:36:55', 9, 4, ''),
(13, '2020-03-12 17:36:55', 21, 2, ''),
(14, '2020-07-11 17:36:55', 28, 4, ''),
(15, '2020-04-13 17:36:55', 24, 3, ''),
(16, '2020-05-15 17:36:55', 27, 3, ''),
(17, '2020-02-11 17:36:55', 27, 1, ''),
(18, '2020-03-11 17:36:55', 26, 2, ''),
(19, '2020-08-15 23:15:28', 14, 2, NULL),
(21, '2020-08-16 00:57:09', 18, 2, ''),
(23, '2020-09-02 06:10:45', 14, 2, ''),
(24, '2020-09-02 06:07:17', 14, 4, ''),
(28, '2020-09-02 06:07:17', 14, 2, ''),
(29, '2020-09-08 16:03:50', 11, 2, 'Cy'),
(33, '2020-08-28 05:25:34', 13, 2, 'AB Dela Rosa'),
(38, '2020-09-10 13:12:45', 4, 2, 'AB Garcia'),
(41, '2020-09-10 13:12:45', 14, 2, 'AB Garcia'),
(42, '2020-09-11 05:11:11', 14, 2, 'CD Efren'),
(43, '2020-09-11 05:10:18', 14, 2, 'CD Efren'),
(44, '2020-09-11 08:43:49', 14, 2, 'CD Efren'),
(45, '2020-09-13 01:25:34', 14, 2, 'CD Efren'),
(46, '2020-09-14 04:18:10', 14, 2, 'CD Efren'),
(48, '2020-09-14 04:35:48', 14, 2, 'CD Efren'),
(50, '2020-09-14 07:48:09', 14, 2, 'GH Igol'),
(55, '2020-09-15 03:14:31', 14, 2, 'GH Igol'),
(58, '2020-09-13 07:48:09', 14, 3, 'GH Igol'),
(61, '2020-09-16 01:56:37', 14, 2, 'JB Meneses'),
(65, '2020-09-18 11:30:13', 26, 2, 'jeppie boi'),
(66, '2020-09-11 12:38:08', 10, 2, 'AR Magnayo'),
(67, '2020-09-14 10:45:37', 19, 3, 'AR Magnayo'),
(68, '2020-09-17 10:40:11', 19, 3, 'AL Manalon'),
(72, '2020-09-17 13:11:11', 14, 2, 'JK MARLU'),
(73, '2020-09-15 03:11:11', 5, 2, 'XY Zinger'),
(74, '2020-09-16 03:11:11', 14, 2, 'XY Zinger'),
(75, '2020-09-17 15:11:11', 14, 2, 'XY Zinger'),
(76, '2020-09-17 15:11:11', 14, 2, 'XY Zinger'),
(77, '2020-09-16 03:11:11', 24, 3, 'AL Wanwan'),
(78, '2020-09-16 03:11:11', 5, 1, 'AL Wanwan'),
(79, '2020-09-17 13:11:11', 11, 6, 'AL Manalon'),
(80, '2020-09-17 15:23:23', 13, 2, 'Ling Maker'),
(83, '2020-09-21 22:59:59', 13, 6, 'Baxia Master'),
(84, '2020-09-17 13:11:11', 14, 1, 'AL Manalon'),
(85, '2020-09-20 03:11:11', 26, 2, 'AB Ignacio'),
(86, '2020-10-02 18:51:11', 4, 2, ' machu'),
(87, '2020-10-02 19:14:11', 5, 2, 'machu'),
(88, '2020-10-02 19:59:31', 5, 1, 'sss'),
(89, '2020-10-03 13:50:05', 26, 2, 'boowrath'),
(90, '2020-10-03 13:55:56', 26, 2, 'pangalan'),
(91, '2020-10-03 13:59:52', 26, 2, 'cat'),
(92, '2020-10-03 14:05:54', 26, 2, 'cat'),
(93, '2020-10-03 14:06:29', 26, 2, 'cat'),
(94, '2020-10-03 14:07:02', 26, 2, 'cat'),
(95, '2020-10-03 14:09:21', 26, 4, 'matchu'),
(96, '2020-10-03 14:10:14', 26, 3, 'matchu'),
(97, '2020-10-03 14:14:04', 5, 4, ' machu'),
(98, '2020-10-03 14:21:49', 5, 4, 'chua'),
(99, '2020-10-03 14:28:11', 3, 2, 'JohnCena'),
(100, '2020-10-03 14:30:17', 3, 3, 'emjhaydogs'),
(101, '2020-10-03 15:34:40', 5, 4, 'asdasd'),
(102, '2020-10-03 18:00:55', 5, 2, 'ad'),
(103, '2020-10-04 12:03:29', 5, 2, ' a'),
(104, '2020-10-05 14:45:02', 26, 8, 'lain'),
(105, '2020-10-05 15:24:05', 26, 6, 'Type Name'),
(106, '2020-10-05 15:27:39', 26, 2, 'Type Name'),
(107, '2020-10-05 16:38:04', 14, 8, 'a'),
(108, '2020-10-06 13:34:34', 14, 5, 'a'),
(109, '2020-10-06 14:00:15', 14, 5, 'a'),
(110, '2020-10-06 15:49:39', 14, 9, 'd'),
(111, '2020-10-07 03:35:50', 14, 9, 'lain'),
(112, '2020-10-08 11:50:50', 14, 9, 'a'),
(113, '2020-10-08 12:40:09', 14, 9, 'a'),
(114, '2020-10-08 12:58:09', 14, 9, 'a'),
(115, '2020-10-09 05:10:08', 26, 9, 'Llain'),
(116, '2020-10-09 06:29:29', 14, 9, 'a'),
(117, '2020-10-09 07:02:00', 14, 9, 'a'),
(118, '2020-10-09 12:16:55', 14, 4, 'a'),
(119, '2020-10-09 14:00:11', 14, 4, 'a'),
(120, '2020-10-09 19:21:47', 26, 8, 'LeBron'),
(121, '2020-10-10 08:01:26', 5, 8, 'Ralph Betlog'),
(122, '2020-10-10 08:20:27', 14, 5, 'matthew'),
(123, '2020-10-10 08:25:41', 14, 5, 'ralph'),
(124, '2020-10-10 08:48:51', 26, 5, 'matthew');

-- --------------------------------------------------------

--
-- Table structure for table `transportation`
--

DROP TABLE IF EXISTS `transportation`;
CREATE TABLE IF NOT EXISTS `transportation` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(20) NOT NULL,
  `desc` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `vat_exempt_price` decimal(13,2) NOT NULL,
  `discount_price` decimal(13,2) NOT NULL,
  `payable_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `transportation`
--

INSERT INTO `transportation` (`id`, `transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1, 13, 'Bound to Pasay', '100.00', '20.00', '80.00'),
(2, 14, 'Bound to Pasay', '100.00', '20.00', '80.00'),
(3, 15, 'Bound to DJose', '100.00', '20.00', '80.00'),
(4, 16, 'Bound to Pasay', '100.00', '20.00', '80.00'),
(5, 17, 'Bound to Cubao', '100.00', '20.00', '80.00'),
(6, 18, 'Bound to EDSA', '100.00', '20.00', '80.00'),
(7, 65, 'Pasay to Guadalupe | Senior - SJT', '26.79', '5.36', '21.43'),
(8, 77, 'LRT Gil Puyat to LRT United Nations', '267.86', '53.57', '214.29'),
(9, 85, 'Pasay to Guadalupe | Senior - SJT', '22.32', '4.46', '17.86'),
(10, 89, 'United Nations to Roosevelt | Senior - SJT', '26.79', '5.36', '21.43'),
(11, 90, 'United Nations to R. Papa | Senior - SJT', '17.86', '3.57', '14.29'),
(12, 91, 'United Nations to Monumento | Senior - SJT', '17.86', '3.57', '14.29'),
(13, 92, 'United Nations to Roosevelt | Senior - SJT', '26.79', '5.36', '21.43'),
(14, 93, 'United Nations to Central | Senior - SJT', '13.39', '2.68', '10.71'),
(15, 94, 'United Nations to Monumento | Senior - SJT', '17.86', '3.57', '14.29'),
(16, 95, 'United Nations to Blumentritt | Senior - SJT', '17.86', '3.57', '14.29'),
(17, 96, 'United Nations to Central | Senior - SJT', '13.39', '2.68', '10.71'),
(18, 104, 'United Nations to EDSA | Senior - SJT', '17.86', '3.57', '14.29'),
(19, 105, 'United Nations to Balintawak | Senior - SJT', '26.79', '5.36', '21.43'),
(20, 106, 'United Nations to Roosevelt | Senior - SJT', '26.79', '5.36', '21.43'),
(21, 115, 'United Nations to EDSA | Senior - SJT', '17.86', '3.57', '14.29'),
(22, 120, 'United Nations to Baclaran | Senior - SJT', '17.86', '3.57', '14.29'),
(23, 124, 'United Nations to Tayuman | Senior - SJT', '13.39', '2.68', '10.71');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_all_transactions`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_all_transactions`;
CREATE TABLE IF NOT EXISTS `view_all_transactions` (
`member_id` int(20)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
,`trans_number` int(20)
,`trans_date` timestamp
,`clerk` varchar(120)
,`company_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`desc` varchar(1153)
,`vat_exempt_price` decimal(13,2)
,`discount_price` decimal(13,2)
,`payable_price` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_companies`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_companies`;
CREATE TABLE IF NOT EXISTS `view_companies` (
`c_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`logo` varchar(120)
,`ca_id` int(11)
,`user_name` varchar(120)
,`password` varchar(120)
,`is_enabled` int(10)
,`log_attempts` int(10)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_complaints`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_complaints`;
CREATE TABLE IF NOT EXISTS `view_complaints` (
`desc` varchar(300)
,`report_date` timestamp
,`company_id` int(20)
,`member_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_drugs`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_drugs`;
CREATE TABLE IF NOT EXISTS `view_drugs` (
`id` int(20)
,`generic_name` varchar(120)
,`brand` varchar(120)
,`dose` int(20)
,`unit` varchar(120)
,`is_otc` int(10)
,`max_monthly` int(20)
,`max_weekly` int(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_food_transactions`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_food_transactions`;
CREATE TABLE IF NOT EXISTS `view_food_transactions` (
`member_id` int(20)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
,`trans_number` int(20)
,`trans_date` timestamp
,`clerk` varchar(120)
,`company_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`desc` varchar(120)
,`vat_exempt_price` decimal(13,2)
,`discount_price` decimal(13,2)
,`payable_price` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_lost_report`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_lost_report`;
CREATE TABLE IF NOT EXISTS `view_lost_report` (
`member_id` int(20)
,`lost_id` int(20)
,`desc` varchar(120)
,`osca_id` varchar(20)
,`report_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_members_with_guardian`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_members_with_guardian`;
CREATE TABLE IF NOT EXISTS `view_members_with_guardian` (
`member_id` int(20)
,`osca_id` varchar(20)
,`nfc_serial` varchar(45)
,`nfc_active` int(10)
,`password` varchar(120)
,`account_enabled` int(10)
,`first_name` varchar(120)
,`middle_name` varchar(120)
,`last_name` varchar(120)
,`sex` varchar(10)
,`bdate` varchar(17)
,`age` int(6)
,`memship_date` varchar(17)
,`contact_number` varchar(20)
,`email` varchar(120)
,`picture` varchar(250)
,`g_id` int(20)
,`g_first_name` varchar(120)
,`g_middle_name` varchar(120)
,`g_last_name` varchar(120)
,`g_sex` varchar(10)
,`g_contact_number` varchar(20)
,`g_email` varchar(120)
,`g_relationship` varchar(120)
,`address_1` varchar(120)
,`address_2` varchar(120)
,`city` varchar(120)
,`province` varchar(120)
,`a_is_active` int(11)
,`a_last_update` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pharma_transactions`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_pharma_transactions`;
CREATE TABLE IF NOT EXISTS `view_pharma_transactions` (
`member_id` int(20)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
,`trans_number` int(20)
,`trans_date` timestamp
,`clerk` varchar(120)
,`company_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`generic_name` varchar(120)
,`brand` varchar(120)
,`dose` int(20)
,`unit` varchar(120)
,`is_otc` int(10)
,`max_monthly` int(20)
,`max_weekly` int(20)
,`quantity` int(20)
,`unit_price` decimal(13,2)
,`vat_exempt_price` decimal(13,2)
,`discount_price` decimal(13,2)
,`payable_price` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pharma_transactions_all`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_pharma_transactions_all`;
CREATE TABLE IF NOT EXISTS `view_pharma_transactions_all` (
`member_id` int(20)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
,`trans_number` int(20)
,`trans_date` timestamp
,`clerk` varchar(120)
,`company_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`desc_nondrug` varchar(120)
,`generic_name` varchar(120)
,`brand` varchar(120)
,`dose` int(20)
,`unit` varchar(120)
,`is_otc` int(10)
,`max_monthly` int(20)
,`max_weekly` int(20)
,`quantity` int(20)
,`unit_price` decimal(13,2)
,`vat_exempt_price` decimal(13,2)
,`discount_price` decimal(13,2)
,`payable_price` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pharma_transactions_nondrug`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_pharma_transactions_nondrug`;
CREATE TABLE IF NOT EXISTS `view_pharma_transactions_nondrug` (
`member_id` int(20)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
,`trans_number` int(20)
,`trans_date` timestamp
,`clerk` varchar(120)
,`company_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`desc_nondrug` varchar(120)
,`vat_exempt_price` decimal(13,2)
,`discount_price` decimal(13,2)
,`payable_price` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_qr_request`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_qr_request`;
CREATE TABLE IF NOT EXISTS `view_qr_request` (
`id` int(11)
,`member_id` int(20)
,`desc` varchar(120)
,`token` varchar(120)
,`trans_date` timestamp
,`transaction_id` int(20)
,`is_expired` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_qr_request_transactions`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_qr_request_transactions`;
CREATE TABLE IF NOT EXISTS `view_qr_request_transactions` (
`member_id` int(20)
,`osca_id` varchar(20)
,`desc` varchar(120)
,`request_date` timestamp
,`is_expired` int(11)
,`transaction_id` int(20)
,`transaction_date` timestamp
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_transportation_transactions`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_transportation_transactions`;
CREATE TABLE IF NOT EXISTS `view_transportation_transactions` (
`member_id` int(20)
,`osca_id` varchar(20)
,`first_name` varchar(120)
,`last_name` varchar(120)
,`trans_number` int(20)
,`trans_date` timestamp
,`clerk` varchar(120)
,`company_id` int(20)
,`company_tin` varchar(20)
,`company_name` varchar(250)
,`branch` varchar(120)
,`business_type` varchar(120)
,`desc` varchar(120)
,`vat_exempt_price` decimal(13,2)
,`discount_price` decimal(13,2)
,`payable_price` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Structure for view `view_all_transactions`
--
DROP TABLE IF EXISTS `view_all_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_all_transactions`  AS  select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,concat('[',ucase(left(`d`.`generic_name`,1)),lcase(substr(`d`.`generic_name`,2)),'], ',ucase(left(`d`.`brand`,1)),lcase(substr(`d`.`brand`,2)),', ',`d`.`dose`,`d`.`unit`,', ',`p`.`quantity`,'pcs, P ',`p`.`unit_price`,'/pc') AS `desc`,`p`.`vat_exempt_price` AS `vat_exempt_price`,`p`.`discount_price` AS `discount_price`,`p`.`payable_price` AS `payable_price` from ((((`transaction` `t` left join `pharmacy` `p` on(`p`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) left join `drug` `d` on(`p`.`drug_id` = `d`.`id`)) where `p`.`transaction_id` = `t`.`id` and (`p`.`desc_nondrug` is null or `p`.`desc_nondrug` = '') union select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`p`.`desc_nondrug` AS `desc`,`p`.`vat_exempt_price` AS `vat_exempt_price`,`p`.`discount_price` AS `discount_price`,`p`.`payable_price` AS `payable_price` from ((((`transaction` `t` left join `pharmacy` `p` on(`p`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) left join `drug` `d` on(`p`.`drug_id` = `d`.`id`)) where `p`.`transaction_id` = `t`.`id` and !(`p`.`id` in (select `p2`.`id` from `pharmacy` `p2` where `p2`.`desc_nondrug` is null or `p2`.`desc_nondrug` = '')) union select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`t1`.`desc` AS `desc`,`t1`.`vat_exempt_price` AS `vat_exempt_price`,`t1`.`discount_price` AS `discount_price`,`t1`.`payable_price` AS `payable_price` from (((`transaction` `t` left join `transportation` `t1` on(`t1`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) where `t1`.`transaction_id` = `t`.`id` union select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`f`.`desc` AS `desc`,`f`.`vat_exempt_price` AS `vat_exempt_price`,`f`.`discount_price` AS `discount_price`,`f`.`payable_price` AS `payable_price` from (((`transaction` `t` left join `food` `f` on(`f`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) where `f`.`transaction_id` = `t`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `view_companies`
--
DROP TABLE IF EXISTS `view_companies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_companies`  AS  (select `c`.`id` AS `c_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`c`.`logo` AS `logo`,`ca`.`id` AS `ca_id`,`ca`.`user_name` AS `user_name`,`ca`.`password` AS `password`,`ca`.`is_enabled` AS `is_enabled`,`ca`.`log_attempts` AS `log_attempts` from (`company` `c` join `company_accounts` `ca` on(`ca`.`company_id` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_complaints`
--
DROP TABLE IF EXISTS `view_complaints`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_complaints`  AS  (select `cr`.`desc` AS `desc`,`cr`.`report_date` AS `report_date`,`cr`.`company_id` AS `company_id`,`cr`.`member_id` AS `member_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name` from ((`complaint_report` `cr` left join `member` `m` on(`cr`.`member_id` = `m`.`id`)) left join `company` `c` on(`cr`.`company_id` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_drugs`
--
DROP TABLE IF EXISTS `view_drugs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_drugs`  AS  select `drug`.`id` AS `id`,`drug`.`generic_name` AS `generic_name`,`drug`.`brand` AS `brand`,`drug`.`dose` AS `dose`,`drug`.`unit` AS `unit`,`drug`.`is_otc` AS `is_otc`,`drug`.`max_monthly` AS `max_monthly`,`drug`.`max_weekly` AS `max_weekly` from `drug` ;

-- --------------------------------------------------------

--
-- Structure for view `view_food_transactions`
--
DROP TABLE IF EXISTS `view_food_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_food_transactions`  AS  (select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`f`.`desc` AS `desc`,`f`.`vat_exempt_price` AS `vat_exempt_price`,`f`.`discount_price` AS `discount_price`,`f`.`payable_price` AS `payable_price` from (((`transaction` `t` left join `food` `f` on(`f`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) where `f`.`transaction_id` = `t`.`id`) ;

-- --------------------------------------------------------

--
-- Structure for view `view_lost_report`
--
DROP TABLE IF EXISTS `view_lost_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_lost_report`  AS  select `m`.`id` AS `member_id`,`l`.`id` AS `lost_id`,`l`.`desc` AS `desc`,`m`.`osca_id` AS `osca_id`,`l`.`report_date` AS `report_date` from (`lost_report` `l` join `member` `m` on(`l`.`member_id` = `m`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_members_with_guardian`
--
DROP TABLE IF EXISTS `view_members_with_guardian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_members_with_guardian`  AS  (select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`nfc_serial` AS `nfc_serial`,`m`.`nfc_active` AS `nfc_active`,`m`.`password` AS `password`,`m`.`account_enabled` AS `account_enabled`,`m`.`first_name` AS `first_name`,`m`.`middle_name` AS `middle_name`,`m`.`last_name` AS `last_name`,`m`.`sex` AS `sex`,concat(dayofmonth(`m`.`birth_date`),' ',monthname(`m`.`birth_date`),' ',year(`m`.`birth_date`)) AS `bdate`,year(curdate()) - year(`m`.`birth_date`) - if(str_to_date(concat(year(curdate()),'-',month(`m`.`birth_date`),'-',dayofmonth(`m`.`birth_date`)),'%Y-%c-%e') > curdate(),1,0) AS `age`,concat(dayofmonth(`m`.`membership_date`),' ',monthname(`m`.`membership_date`),' ',year(`m`.`membership_date`)) AS `memship_date`,`m`.`contact_number` AS `contact_number`,`m`.`email` AS `email`,`m`.`picture` AS `picture`,`g`.`id` AS `g_id`,`g`.`first_name` AS `g_first_name`,`g`.`middle_name` AS `g_middle_name`,`g`.`last_name` AS `g_last_name`,`g`.`sex` AS `g_sex`,`g`.`contact_number` AS `g_contact_number`,`g`.`email` AS `g_email`,`g`.`relationship` AS `g_relationship`,`a`.`address1` AS `address_1`,`a`.`address2` AS `address_2`,`a`.`city` AS `city`,`a`.`province` AS `province`,`a`.`is_active` AS `a_is_active`,`a`.`last_update` AS `a_last_update` from (((`member` `m` join `guardian` `g` on(`g`.`member_id` = `m`.`id`)) join `address_jt` `ajt` on(`ajt`.`member_id` = `m`.`id`)) join `address` `a` on(`ajt`.`address_id` = `a`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_pharma_transactions`
--
DROP TABLE IF EXISTS `view_pharma_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pharma_transactions`  AS  (select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`d`.`generic_name` AS `generic_name`,`d`.`brand` AS `brand`,`d`.`dose` AS `dose`,`d`.`unit` AS `unit`,`d`.`is_otc` AS `is_otc`,`d`.`max_monthly` AS `max_monthly`,`d`.`max_weekly` AS `max_weekly`,`p`.`quantity` AS `quantity`,`p`.`unit_price` AS `unit_price`,`p`.`vat_exempt_price` AS `vat_exempt_price`,`p`.`discount_price` AS `discount_price`,`p`.`payable_price` AS `payable_price` from ((((`transaction` `t` left join `pharmacy` `p` on(`p`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) left join `drug` `d` on(`p`.`drug_id` = `d`.`id`)) where `p`.`transaction_id` = `t`.`id` and (`p`.`desc_nondrug` is null or `p`.`desc_nondrug` = '')) ;

-- --------------------------------------------------------

--
-- Structure for view `view_pharma_transactions_all`
--
DROP TABLE IF EXISTS `view_pharma_transactions_all`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pharma_transactions_all`  AS  select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`p`.`desc_nondrug` AS `desc_nondrug`,`d`.`generic_name` AS `generic_name`,`d`.`brand` AS `brand`,`d`.`dose` AS `dose`,`d`.`unit` AS `unit`,`d`.`is_otc` AS `is_otc`,`d`.`max_monthly` AS `max_monthly`,`d`.`max_weekly` AS `max_weekly`,`p`.`quantity` AS `quantity`,`p`.`unit_price` AS `unit_price`,`p`.`vat_exempt_price` AS `vat_exempt_price`,`p`.`discount_price` AS `discount_price`,`p`.`payable_price` AS `payable_price` from ((((`transaction` `t` left join `pharmacy` `p` on(`p`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) left join `drug` `d` on(`p`.`drug_id` = `d`.`id`)) where `p`.`transaction_id` = `t`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `view_pharma_transactions_nondrug`
--
DROP TABLE IF EXISTS `view_pharma_transactions_nondrug`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pharma_transactions_nondrug`  AS  (select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`p`.`desc_nondrug` AS `desc_nondrug`,`p`.`vat_exempt_price` AS `vat_exempt_price`,`p`.`discount_price` AS `discount_price`,`p`.`payable_price` AS `payable_price` from (((`transaction` `t` left join `pharmacy` `p` on(`p`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) where `p`.`transaction_id` = `t`.`id` and !(`p`.`id` in (select `p2`.`id` from `pharmacy` `p2` where `p`.`desc_nondrug` is null or `p`.`desc_nondrug` = ''))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_qr_request`
--
DROP TABLE IF EXISTS `view_qr_request`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_qr_request`  AS  select `qr_request`.`id` AS `id`,`qr_request`.`member_id` AS `member_id`,`qr_request`.`desc` AS `desc`,`qr_request`.`token` AS `token`,`qr_request`.`trans_date` AS `trans_date`,`qr_request`.`transaction_id` AS `transaction_id`,if(`qr_request`.`trans_date` > current_timestamp() - interval 1 day,0,1) AS `is_expired` from `qr_request` ;

-- --------------------------------------------------------

--
-- Structure for view `view_qr_request_transactions`
--
DROP TABLE IF EXISTS `view_qr_request_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_qr_request_transactions`  AS  select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`v`.`desc` AS `desc`,`v`.`trans_date` AS `request_date`,`v`.`is_expired` AS `is_expired`,`t`.`id` AS `transaction_id`,`t`.`trans_date` AS `transaction_date`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch` from (((`view_qr_request` `v` join `member` `m` on(`v`.`member_id` = `m`.`id`)) join `transaction` `t` on(`v`.`transaction_id` = `t`.`id`)) join `company` `c` on(`t`.`company_id` = `c`.`id`)) where !(`v`.`id` in (select `v2`.`id` from `view_qr_request` `v2` where `v2`.`transaction_id` is null or `v2`.`transaction_id` = '')) union select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`v`.`desc` AS `desc`,`v`.`trans_date` AS `request_date`,`v`.`is_expired` AS `is_expired`,NULL AS `transaction_id`,NULL AS `transaction_date`,NULL AS `company_tin`,NULL AS `company_name`,NULL AS `branch` from (`view_qr_request` `v` join `member` `m` on(`v`.`member_id` = `m`.`id`)) where `v`.`transaction_id` is null or `v`.`transaction_id` = '' ;

-- --------------------------------------------------------

--
-- Structure for view `view_transportation_transactions`
--
DROP TABLE IF EXISTS `view_transportation_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_transportation_transactions`  AS  (select `m`.`id` AS `member_id`,`m`.`osca_id` AS `osca_id`,`m`.`first_name` AS `first_name`,`m`.`last_name` AS `last_name`,`t`.`id` AS `trans_number`,`t`.`trans_date` AS `trans_date`,`t`.`clerk` AS `clerk`,`c`.`id` AS `company_id`,`c`.`company_tin` AS `company_tin`,`c`.`company_name` AS `company_name`,`c`.`branch` AS `branch`,`c`.`business_type` AS `business_type`,`t1`.`desc` AS `desc`,`t1`.`vat_exempt_price` AS `vat_exempt_price`,`t1`.`discount_price` AS `discount_price`,`t1`.`payable_price` AS `payable_price` from (((`transaction` `t` left join `transportation` `t1` on(`t1`.`transaction_id` = `t`.`id`)) left join `member` `m` on(`t`.`member_id` = `m`.`id`)) left join `company` `c` on(`t`.`company_id` = `c`.`id`)) where `t1`.`transaction_id` = `t`.`id`) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address_jt`
--
ALTER TABLE `address_jt`
  ADD CONSTRAINT `address_jt_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_address_jt_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_address_jt_guardian` FOREIGN KEY (`guardian_id`) REFERENCES `guardian` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_address_jt_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company_accounts`
--
ALTER TABLE `company_accounts`
  ADD CONSTRAINT `company_accounts_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Constraints for table `complaint_report`
--
ALTER TABLE `complaint_report`
  ADD CONSTRAINT `complaint_report_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `complaint_report_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`);

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`);

--
-- Constraints for table `guardian`
--
ALTER TABLE `guardian`
  ADD CONSTRAINT `guardian_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`);

--
-- Constraints for table `lost_report`
--
ALTER TABLE `lost_report`
  ADD CONSTRAINT `lost_report_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`);

--
-- Constraints for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD CONSTRAINT `pharmacy_ibfk_10` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`),
  ADD CONSTRAINT `pharmacy_ibfk_12` FOREIGN KEY (`drug_id`) REFERENCES `drug` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `qr_request`
--
ALTER TABLE `qr_request`
  ADD CONSTRAINT `fk_qr_request_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `qr_request_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_transaction_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transportation`
--
ALTER TABLE `transportation`
  ADD CONSTRAINT `transportation_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
