USE db_osca;

DROP PROCEDURE IF EXISTS `add_transaction`;
DELIMITER ;;
CREATE PROCEDURE `add_transaction` (IN `trans_date_` TIMESTAMP, IN `company_tin_` varchar(120), IN `osca_id_` varchar(120), IN `clerk_` varchar(120), OUT `msg` varchar(120))
BEGIN
  DECLARE company_id_ INT(20);
  DECLARE member_id_ VARCHAR(120);
    SET `company_id_` = (SELECT `c_id` FROM `view_companies` WHERE `company_tin` = `company_tin_`);
    SET `member_id_` = (SELECT `member_id` FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_` LIMIT 1);
    INSERT INTO `transaction`(`trans_date`, `company_id`, `member_id`, `clerk`) VALUES
      (`trans_date_`, `company_id_`, `member_id_`, `clerk_`);
    SET msg = LAST_INSERT_ID();
END;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `add_transaction_food`;
DELIMITER ;;
CREATE PROCEDURE `add_transaction_food` (IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `desc_` varchar(120), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS `add_transaction_pharmacy_drug`;
DELIMITER ;;
CREATE PROCEDURE `add_transaction_pharmacy_drug` (IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `drug_id_` int(20), IN `quantity_` int(20), IN `unit_price_` decimal(13,2), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS `add_transaction_pharmacy_nondrug`;
DELIMITER ;;
CREATE PROCEDURE `add_transaction_pharmacy_nondrug` (IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `desc_` varchar(120), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS `add_transaction_transportation`;
DELIMITER ;;
CREATE PROCEDURE `add_transaction_transportation` (IN `trans_type` varchar(120), IN `transaction_id_` int(20), IN `company_tin_` varchar(120), IN `desc_` varchar(120), IN `vat_exempt_price_` decimal(13,2), IN `discount_price_` decimal(13,2), IN `payable_price_` decimal(13,2), OUT `msg` varchar(120))
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
DELIMITER ;


DROP PROCEDURE IF EXISTS `add_qr_request`;
DELIMITER ;;
CREATE PROCEDURE `add_qr_request` (IN `osca_id_` varchar(120), IN `desc_` varchar(120), IN `token_` varchar(120), OUT `msg` varchar(120))
BEGIN
  DECLARE member_id_ VARCHAR(120);
    IF ((SELECT count(*) FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_`) = 1)
    THEN
      SET `member_id_` = (SELECT `member_id` FROM `view_members_with_guardian` WHERE `osca_id` = `osca_id_` LIMIT 1);
      INSERT INTO `qr_request`(`member_id`, `desc`, `token`) VALUES
        (`member_id_`, `desc_`, `token_`);
      SET msg = 1;
    ELSE 
      SET msg = 0;
    END IF;
END;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `edit_lost_report`;
DELIMITER ;;
CREATE PROCEDURE `edit_lost_report` (IN `lost_id_` varchar(20), IN `osca_id_` varchar(20), IN `desc_` varchar(120), IN `nfc_active_` INT(1), IN `account_enabled_` INT(1), OUT `msg` int(1))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS`toggle_member_nfc`;
DELIMITER ;;
CREATE PROCEDURE `toggle_member_nfc` (IN `id_` varchar(60), OUT `msg` int(1))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS`toggle_member_acct`;
DELIMITER ;;
CREATE PROCEDURE `toggle_member_acct` (IN `id_` varchar(60), OUT `msg` int(1))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS`toggle_admin_acct`;
DELIMITER ;;
CREATE PROCEDURE `toggle_admin_acct` (IN `user_name_` varchar(60), OUT `msg` int(1))
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
DELIMITER ;

DROP PROCEDURE IF EXISTS`toggle_company_acct`;
DELIMITER ;;
CREATE PROCEDURE `toggle_company_acct` (IN `company_id_` int(20), OUT `msg` int(1))
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
DELIMITER ;