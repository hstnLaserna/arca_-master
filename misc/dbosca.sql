-- LAST UPDATE: 2020-08-22 05:20 AM

-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `db_osca`;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `address` (`id`, `address1`, `address2`, `city`, `province`, `is_active`, `last_update`) VALUES
(1,	'2129 Culdesac Rd Edison St',	'Brgy. Sun Valley',	'Paranaque City',	'Metro Manila',	1,	'2020-08-21 09:20:25'),
(2,	'L23 Villa Antonina Subd',	'Brgy. San Nicolas 2',	'Bacoor 1',	'Cavite',	1,	'2020-08-21 09:20:25'),
(3,	'Blk25 lot41 Milkwort St Ph3 Villa de Primarosa',	'Brgy. Mambog 3',	'Bacoor',	'Cavite',	1,	'2020-08-21 09:20:30'),
(4,	'3009, Ipil st.',	'Brgy Banaba',	'Silang',	'Cavite',	1,	'2020-08-21 10:44:05'),
(5,	'0235 Rafael St., Villa Modena',	'Villagio Ignatius Subd., Brgy. Buenavista III',	'General Trias',	'Cavite',	1,	'2020-08-21 09:29:06'),
(6,	'2099 Culdesac Rd Edison St',	'Brgy. Sun Valley',	'Paranaque City',	'Metro Manila',	1,	'2020-03-30 01:53:27'),
(7,	'5636 Rafael St.',	'Brgy. Manggahan',	'Gen. Tri',	'Cavite',	1,	'2020-08-21 21:14:18'),
(8,	'1001 Sant St.',	'Brgy Maybuhay',	'Manila',	'NCR',	1,	'2020-04-01 00:18:55'),
(9,	'0925 Remedios St.',	'Malate',	'Manila City',	'NCR',	1,	'2020-04-01 00:40:00'),
(10,	'1235 Phase 5 Pili St.',	'Brgy. Anahaw',	'Silang',	'Cavite',	0,	'2020-08-21 08:53:37'),
(11,	'Land of',	'Dawn',	'Abyss',	'Valhalla',	1,	'2020-08-13 01:26:28'),
(12,	'9287 Riverdale St.',	'Riverdale Subdivision, Brgy. Kasulukan',	'Paniqui',	'Tarlac',	1,	'2020-08-21 09:19:44'),
(13,	'0003 Grove St',	'Brgy. Los Santos',	'Batanggas',	'Batanggas',	0,	'2020-08-21 09:19:44'),
(14,	'Glass Manor',	'Brgy. Ibabaw Del Sur',	'Paete',	'Laguna',	1,	'2020-08-21 09:19:44'),
(15,	'2548, Nakpil St.',	'Brgy. Reezal, Tamagochi Village',	'Marilao',	'Bulacan',	1,	'2020-08-21 09:20:40'),
(16,	'3180 Zobel St.',	'San Andres Bukid',	'Manila',	'Metro Manila',	1,	'2020-08-21 10:48:07');

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
  CONSTRAINT `fk_address_jt_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_jt_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_jt_guardian` FOREIGN KEY (`guardian_id`) REFERENCES `guardian` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_address_jt_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `address_jt` (`id`, `address_id`, `member_id`, `company_id`, `guardian_id`) VALUES
(1,	1,	1,	NULL,	NULL),
(2,	2,	3,	NULL,	NULL),
(3,	3,	4,	NULL,	NULL),
(4,	4,	2,	NULL,	NULL),
(5,	5,	2,	NULL,	NULL),
(6,	6,	5,	NULL,	NULL),
(7,	7,	7,	NULL,	NULL),
(8,	8,	8,	NULL,	NULL),
(9,	9,	6,	NULL,	NULL),
(10,	10,	2,	NULL,	NULL),
(11,	11,	9,	NULL,	NULL),
(12,	12,	10,	NULL,	NULL),
(13,	13,	9,	NULL,	NULL),
(14,	14,	11,	NULL,	NULL),
(15,	15,	13,	NULL,	NULL),
(17,	16,	12,	NULL,	NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `admin` (`id`, `user_name`, `password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `position`, `contact_number`, `email`, `is_enabled`, `log_attempts`, `answer1`, `answer2`, `temporary_password`, `avatar`) VALUES
(1,	'ralf',	'09152217ad9582364c8d594fedc18ccc',	'Ralph Christian',	'Arbiol',	'Ortiz',	'1990-01-14',	'1',	'user',	NULL,	'ralph.ortiz@ymeal.com',	1,	0,	'ralp',	'orti',	'ralfralf',	'inuho1wjbk.png'),
(2,	'hstn',	'fc29f6ea32a347d55bd690c5d11ed8e3',	'Justine',	'Ildefonso',	'Laserna',	'1990-01-25',	'1',	'admin',	NULL,	'justine.laserna@ymeal.com',	1,	0,	'hustino',	'hustino',	'hstn',	'xnar04g9uo.png'),
(3,	'matt',	'ce86d7d02a229acfaca4b63f01a1171b',	'Matthew Franz',	'Castro',	'Vasquez',	'1990-01-15',	'1',	'admin',	NULL,	'matthew.vasquez@ymeal.com',	1,	0,	'matt',	'vasq',	'matt',	'1dngb3owoz.png'),
(4,	'fred',	'2697359d57024a8f41301b0332a8ba39',	'Frederick Allain',	'',	'Dela Cruz',	'1990-01-01',	'1',	'admin',	NULL,	'frederick.dela.cruz@ymeal.com',	1,	0,	'fred',	'lain',	'fredfred',	'izkue0sbn0.png'),
(5,	'cyrel',	'6230471bd10839658f414438bc33c88a',	'Cyrel',	'Odette',	'Lalikan',	'1990-01-03',	'2',	'user',	NULL,	'cyrel.lalikan@ymeal.com',	1,	0,	'swan',	'song',	'',	'k1ylycon4h.png'),
(6,	'shang',	'8379c86250c50c0537999a6576e18aa7',	'Jess',	'',	'Monty',	'1990-01-24',	'1',	'user2',	NULL,	'jess.monty@ymeal.com',	1,	2,	'shang',	'shang',	'4347da',	'py1c2qjcpq.png'),
(7,	'synth',	'4b418ed51830f54c3f9af6262b2201d2',	'synth',	'synth',	'synth',	'1980-08-19',	'2',	'admin1',	NULL,	'synth.synth@ymeal.com',	0,	0,	'synth',	'synth',	'synthsynth',	'null');

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `company_tin` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `company_name` varchar(250) COLLATE utf8mb4_bin NOT NULL,
  `branch` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `business_type` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_tin_UNIQUE` (`company_tin`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `company` (`id`, `company_tin`, `company_name`, `branch`, `business_type`) VALUES
(1,	'8801234499379',	'Jollibee',	'Gen. Luna, Ermita',	'food'),
(2,	'5519557197318',	'Diligence Cafe',	'Malate, Manila',	'food'),
(3,	'5514917468044',	'Mcdonalds',	'Tandang Sora, Commonwealth',	'food'),
(4,	'7752487534265',	'Dunkin Donuts',	'Sangandaan, Quezon City',	'food'),
(5,	'4007631221371',	'Greenwich',	'Farmer\'s Plaza',	'food'),
(6,	'1550792273984',	'Mang Inasal',	'Alphaland Southgate Towers',	'food'),
(7,	'6070323661079',	'Kenny Rogers Roasters',	'T3 - NAIA',	'food'),
(8,	'5200487589140',	'Chowking',	'Hi-Top Supermarket, Aurora Blvd.',	'food'),
(9,	'8801214499379',	'KFC',	'Mall of Asia',	'food'),
(10,	'3411927270293',	'Aristocrat',	'Malate, Manila',	'food'),
(11,	'6171762815599',	'Mercury Drug',	'GMA, Cavite',	'pharmacy'),
(12,	'9777439585523',	'Mercury Drug',	'Hidalgo St., Quiapo',	'pharmacy'),
(13,	'3591867572189',	'Mercury Drug',	'4th st., Taguig',	'pharmacy'),
(14,	'1037217215054',	'Watsons',	'Portal, GMA, Cavite',	'pharmacy'),
(15,	'8323848273530',	'Watsons',	'Montillano St., Alabang',	'pharmacy'),
(16,	'7965766163274',	'Watsons',	'Waltermart, Gen. Trias',	'pharmacy'),
(17,	'5821645777418',	'Watsons',	'SM Manila',	'pharmacy'),
(18,	'8690961165847',	'The Generics Pharmacy',	'Arnaiz Ave., Makati',	'pharmacy'),
(19,	'3983278667746',	'The Generics Pharmacy',	'Quirino Ave, Paranaque',	'pharmacy'),
(20,	'6344364475462',	'The Generics Pharmacy',	'Boni Ave., Mandaluyong',	'pharmacy'),
(21,	'8016618868870',	'LRT',	'Central Station',	'transportation'),
(22,	'9690147404702',	'LRT',	'Doroteo Jose Station',	'transportation'),
(23,	'2035775056071',	'LRT',	'United Nations Station',	'transportation'),
(24,	'8063147891117',	'LRT',	'Gil Puyat Station',	'transportation'),
(25,	'2019924979512',	'LRT',	'Pedro Gil Station',	'transportation'),
(26,	'2949532037078',	'MRT',	'Taft Station',	'transportation'),
(27,	'5100616960228',	'MRT',	'Guadalupe Station',	'transportation'),
(28,	'7282645530357',	'MRT',	'Magallanes Station',	'transportation'),
(29,	'3115335081083',	'MRT',	'Araneta - Cubao Station',	'transportation'),
(30,	'9271004158049',	'MRT',	'Ayala Station',	'transportation');

DROP TABLE IF EXISTS `complaint_report`;
CREATE TABLE `complaint_report` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `desc` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `report_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `company_id` int(20) NOT NULL,
  `member_id` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `complaint_report_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`),
  CONSTRAINT `complaint_report_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


DROP TABLE IF EXISTS `drug`;
CREATE TABLE `drug` (
  `id` int(20) NOT NULL,
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
(1,	'paracetamol',	'Biogesic',	500,	'mg',	1,	30000,	7000),
(2,	'paracetamol',	'Bioflu',	500,	'mg',	1,	30000,	7000),
(3,	'ibuprofen, paracetamol',	'Alaxan',	500,	'mg',	1,	30000,	7000),
(4,	'diphenhydramine',	'Benadryl',	500,	'mg',	1,	30000,	7000),
(5,	'loratidine',	'Claritin ',	500,	'mg',	1,	30000,	7000),
(6,	'famotidine, calcium carbonate, magnesium hydroxide',	'Kremil-S Advance',	500,	'mg',	1,	30000,	7000);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `food` (`id`, `transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1,	7,	'2',	100.00,	20.00,	80.00),
(2,	8,	'2',	100.00,	20.00,	80.00),
(3,	9,	'3',	100.00,	20.00,	80.00),
(4,	10,	'4',	100.00,	20.00,	80.00),
(5,	11,	'3',	100.00,	20.00,	80.00),
(6,	12,	'1',	100.00,	20.00,	80.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `guardian` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `relationship`, `contact_number`, `email`, `member_id`) VALUES
(1,	'Gary',	'Jenelle',	'Winton',	'1',	'Grandfather',	'0256890796',	'garywinton9@gmeal.com',	1),
(2,	'Nonie',	'Marlene',	'Irene',	'2',	'Grandmother',	'0289965829',	'nonieirene20@gmeal.com',	2),
(3,	'Kelsey',	'Eulalia',	'Diamond',	'1',	'Grandfather',	'0238471073',	'kelseydiamond9@gmeal.com',	3),
(4,	'Galen',	'Avah',	'Kirby',	'2',	'Grandmother',	'0211549317',	'galenkirby7@gmeal.com',	4),
(5,	'Kristine',	'Marcy',	'Charissa',	'1',	'Grandfather',	'0228048410',	'kristinecharissa17@gmeal.com',	5),
(6,	'Cheyanne',	'Paulette',	'Jaylee',	'1',	'Grandfather',	'0266301227',	'cheyannejaylee9@gmeal.com',	6),
(7,	'Avalon',	'Brynlee',	'Aspen',	'1',	'Grandfather',	'0249211504',	'avalonaspen19@gmeal.com',	7),
(8,	'Vianne',	'Kassidy',	'Ursula',	'2',	'Grandmother',	'0214578421',	'vianneursula9@gmeal.com',	8),
(9,	'Ray',	'Kent',	'Carran',	'2',	'Grandmother',	'0274763308',	'raycarran7@gmeal.com',	9),
(10,	'Pamella',	'Russel',	'Corey',	'2',	'Grandmother',	'0288296230',	'pamellacorey1@gmeal.com',	10),
(11,	'Lacy',	'Bekki',	'Marcy',	'1',	'Grandfather',	'0277137953',	'lacymarcy18@gmeal.com',	11),
(12,	'Love',	'Demelza',	'Paulette',	'1',	'Grandfather',	'0288759308',	'lovepaulette0@gmeal.com',	12),
(13,	'Goldie',	'Marilynn',	'Vianne',	'1',	'Grandfather',	'0261176212',	'goldievianne13@gmeal.com',	13);

DROP TABLE IF EXISTS `lost_report`;
CREATE TABLE `lost_report` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `report_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `member_id` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `lost_report_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `osca_id` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `nfc_serial` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `first_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `middle_name` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `birth_date` date NOT NULL,
  `sex` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `membership_date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `picture` varchar(250) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `osca_id_UNIQUE` (`osca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `member` (`id`, `osca_id`, `nfc_serial`, `password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `sex`, `contact_number`, `email`, `membership_date`, `picture`) VALUES
(1,	'20200000',	'WBVQRVY4DU5JALZI',	'621b0c9bbd565b7e39b9e7397cbec287',	'Lai',	'Arbiol',	'Girardi',	'1953-06-17',	'2',	'0912-456-7890',	'lai.girardi@ymeal.com',	'2020-08-21 19:56:46',	'ci1g9s0y35.png'),
(2,	'20200001',	'441C5D9C99080123',	'17e3fe30f46ca4df7c67db07b174475d',	'Ruby',	'Ildefonso',	'Glass',	'1960-01-25',	'2',	'046-538-5233',	'ruby.glass@ymeal.com',	'2020-08-21 19:56:46',	'ci1g9s0y35.png'),
(3,	'20200002',	'3U9K4TIVRUK7XO6G',	'62ea98c79d3e69f8cce7850cd0a3d4f0',	'Cordell',	'Castro',	'Broxton',	'1998-06-15',	'1',	'046-201-2011',	'cordell.broxton@ymeal.com',	'2020-08-21 19:56:46',	'j6bo6kqm07.png'),
(4,	'20200003',	'DAF5093412880400',	'ba241200ba9c49dcde1b7be816522125',	'Stephine',	'Gaco',	'Lamagna',	'1932-07-17',	'2',	'0917-325-5200',	'stephine.lamagna@ymeal.com',	'2020-08-21 19:56:46',	'ci1g9s0y35.png'),
(5,	'20200006',	'FBAD739105CBAE1D',	'e2b4ea1bfc499f085c0537209992ea84',	'Olimpia',	'',	'Ollis',	'1998-01-01',	'9',	NULL,	'olimpia.ollis@ymeal.com',	'2020-08-21 19:56:46',	'j6bo6kqm07.png'),
(6,	'20201010',	'9JIFHJVAHKE0G9AI',	'a48ada5f1fed03ae84a537eebb16f29b',	'Harriette',	'Flavell',	'Milbourn',	'1945-01-25',	'2',	'09-253-1028',	'harriette.milbourn@ymeal.com',	'2020-08-21 19:56:46',	'ci1g9s0y35.png'),
(7,	'19386729',	'H0B277JSE6SMGPY0',	'4ab183d6e338ded13b64a690d6ae7bde',	'Elise',	'Trump',	'Benjamin',	'1960-02-22',	'2',	'092819285719',	'elise.benjamin@ymeal.com',	'2020-08-21 16:00:00',	'j6bo6kqm07.png'),
(8,	'12341234',	'1234123443214321',	'9ad4504474c4563c50a4c8bda304d3f7',	'Hermine',	'Bridgman',	'Poirer',	'1990-01-01',	'1',	'0909-123-4567',	'hermine.poirer@ymeal.com',	'2020-08-21 19:56:47',	'j6bo6kqm07.png'),
(9,	'43214321',	'1234123412341234',	'ed2b1f468c5f915f3f1cf75d7068baae',	'Khaleed',	'',	'Dawson',	'1900-01-01',	'2',	'12341234',	'khaleed.dawson@ymeal.com',	'2020-08-21 19:56:47',	'ci1g9s0y35.png'),
(10,	'56785678',	'5678567856785678',	'c18b3eff03996f3a203f63733be03d15',	'Ernestine',	'Kyle',	'Ayers',	'1960-08-11',	'2',	'56785678',	'ernestine.ayers@ymeal.com',	'2020-08-21 19:56:47',	'ci1g9s0y35.png'),
(11,	'43217890',	'12341234asdfasdf',	'ed2b1f468c5f915f3f1cf75d7068baae',	'Noburu',	'Danya',	'Lea',	'1940-08-29',	'2',	'12341234',	'noburu.lea@ymeal.com',	'2020-08-21 19:56:47',	'ci1g9s0y35.png'),
(12,	'24192651',	'2A6B9CE2A46B1DE9',	'12acd97a4e9f587fc2f0de2108b158c3',	'Vasanti',	'Elpidio',	'Hippolyte',	'1948-12-08',	'2',	'0279281684',	'vasanti.hippolyte@ymeal.com',	'2020-08-21 19:56:47',	''),
(13,	'24292651',	'2A6B9CE2A4DB4DE9',	'12acd97a4e9f587fc2f0de2108b158c3',	'McKenzie ',	'Houston',	'Jessye',	'1948-12-08',	'2',	'0279281684',	'mckenzie.jessye@ymeal.com',	'2020-08-21 19:56:47',	'');

DROP TABLE IF EXISTS `pharmacy`;
CREATE TABLE `pharmacy` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(20) NOT NULL,
  `drug_id` int(20) NOT NULL,
  `quantity` int(20) NOT NULL,
  `unit_price` decimal(13,2) NOT NULL,
  `vat_exempt_price` decimal(13,2) NOT NULL,
  `discount_price` decimal(13,2) NOT NULL,
  `payable_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_id` (`drug_id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `pharmacy_ibfk_10` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`),
  CONSTRAINT `pharmacy_ibfk_9` FOREIGN KEY (`drug_id`) REFERENCES `drug` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `pharmacy` (`id`, `transaction_id`, `drug_id`, `quantity`, `unit_price`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1,	1,	2,	8,	1120.00,	1000.00,	200.00,	800.00),
(2,	2,	2,	10,	112.00,	100.00,	20.00,	80.00),
(3,	3,	3,	4,	896.00,	800.00,	160.00,	640.00),
(4,	4,	4,	3,	448.00,	400.00,	80.00,	320.00),
(5,	5,	3,	14,	1500.00,	1339.29,	267.86,	1071.43),
(6,	6,	1,	18,	2000.00,	1785.71,	357.14,	1428.57),
(7,	19,	3,	10,	5.00,	50.00,	10.00,	40.00),
(8,	21,	3,	14,	100.00,	1250.00,	250.00,	1000.00),
(9,	23,	3,	14,	100.00,	1250.00,	250.00,	1000.00),
(10,	24,	3,	14,	100.00,	1250.00,	250.00,	1000.00),
(17,	28,	3,	10,	201.60,	1800.00,	360.00,	1440.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `transaction` (`id`, `trans_date`, `company_id`, `member_id`, `clerk`) VALUES
(1,	'2020-08-03 17:36:53',	14,	4,	'Cy'),
(2,	'2020-07-27 20:45:34',	13,	2,	''),
(3,	'2020-06-11 22:53:53',	14,	3,	'Cy'),
(4,	'2020-07-24 21:39:00',	15,	3,	''),
(5,	'2020-08-11 17:36:53',	16,	4,	''),
(6,	'2020-07-23 17:36:53',	17,	2,	''),
(7,	'2020-02-14 17:36:55',	1,	4,	''),
(8,	'2020-05-15 17:36:55',	4,	4,	''),
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
(23,	'2020-07-27 22:58:32',	18,	2,	''),
(24,	'2020-08-16 00:58:15',	18,	4,	''),
(28,	'2020-02-14 17:36:55',	18,	2,	'');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `transportation` (`id`, `transaction_id`, `desc`, `vat_exempt_price`, `discount_price`, `payable_price`) VALUES
(1,	13,	'2',	100.00,	20.00,	80.00),
(2,	14,	'2',	100.00,	20.00,	80.00),
(3,	15,	'3',	100.00,	20.00,	80.00),
(4,	16,	'4',	100.00,	20.00,	80.00),
(5,	17,	'3',	100.00,	20.00,	80.00),
(6,	18,	'1',	100.00,	20.00,	80.00);

-- 2020-08-21 21:17:15
