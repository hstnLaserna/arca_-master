use `philippines`;

-- ----------------------------
-- Table structure for province
-- ----------------------------
DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `psgcCode` varchar(255) DEFAULT NULL,
  `provDesc` text,
  `regCode` varchar(255) DEFAULT NULL,
  `provCode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of province
-- ----------------------------
INSERT INTO `province` VALUES ('1', '012800000', 'ILOCOS NORTE', '01', '0128'),
        ('2', '012900000', 'ILOCOS SUR', '01', '0129'),
        ('3', '013300000', 'LA UNION', '01', '0133'),
        ('4', '015500000', 'PANGASINAN', '01', '0155'),
        ('5', '020900000', 'BATANES', '02', '0209'),
        ('6', '021500000', 'CAGAYAN', '02', '0215'),
        ('7', '023100000', 'ISABELA', '02', '0231'),
        ('8', '025000000', 'NUEVA VIZCAYA', '02', '0250'),
        ('9', '025700000', 'QUIRINO', '02', '0257'),
        ('10', '030800000', 'BATAAN', '03', '0308'),
        ('11', '031400000', 'BULACAN', '03', '0314'),
        ('12', '034900000', 'NUEVA ECIJA', '03', '0349'),
        ('13', '035400000', 'PAMPANGA', '03', '0354'),
        ('14', '036900000', 'TARLAC', '03', '0369'),
        ('15', '037100000', 'ZAMBALES', '03', '0371'),
        ('16', '037700000', 'AURORA', '03', '0377'),
        ('17', '041000000', 'BATANGAS', '04', '0410'),
        ('18', '042100000', 'CAVITE', '04', '0421'),
        ('19', '043400000', 'LAGUNA', '04', '0434'),
        ('20', '045600000', 'QUEZON', '04', '0456'),
        ('21', '045800000', 'RIZAL', '04', '0458'),
        ('22', '174000000', 'MARINDUQUE', '17', '1740'),
        ('23', '175100000', 'OCCIDENTAL MINDORO', '17', '1751'),
        ('24', '175200000', 'ORIENTAL MINDORO', '17', '1752'),
        ('25', '175300000', 'PALAWAN', '17', '1753'),
        ('26', '175900000', 'ROMBLON', '17', '1759'),
        ('27', '050500000', 'ALBAY', '05', '0505'),
        ('28', '051600000', 'CAMARINES NORTE', '05', '0516'),
        ('29', '051700000', 'CAMARINES SUR', '05', '0517'),
        ('30', '052000000', 'CATANDUANES', '05', '0520'),
        ('31', '054100000', 'MASBATE', '05', '0541'),
        ('32', '056200000', 'SORSOGON', '05', '0562'),
        ('33', '060400000', 'AKLAN', '06', '0604'),
        ('34', '060600000', 'ANTIQUE', '06', '0606'),
        ('35', '061900000', 'CAPIZ', '06', '0619'),
        ('36', '063000000', 'ILOILO', '06', '0630'),
        ('37', '064500000', 'NEGROS OCCIDENTAL', '06', '0645'),
        ('38', '067900000', 'GUIMARAS', '06', '0679'),
        ('39', '071200000', 'BOHOL', '07', '0712'),
        ('40', '072200000', 'CEBU', '07', '0722'),
        ('41', '074600000', 'NEGROS ORIENTAL', '07', '0746'),
        ('42', '076100000', 'SIQUIJOR', '07', '0761'),
        ('43', '082600000', 'EASTERN SAMAR', '08', '0826'),
        ('44', '083700000', 'LEYTE', '08', '0837'),
        ('45', '084800000', 'NORTHERN SAMAR', '08', '0848'),
        ('46', '086000000', 'WESTERN SAMAR', '08', '0860'),
        ('47', '086400000', 'SOUTHERN LEYTE', '08', '0864'),
        ('48', '087800000', 'BILIRAN', '08', '0878'),
        ('49', '097200000', 'ZAMBOANGA DEL NORTE', '09', '0972'),
        ('50', '097300000', 'ZAMBOANGA DEL SUR', '09', '0973'),
        ('51', '098300000', 'ZAMBOANGA SIBUGAY', '09', '0983'),
        ('52', '099700000', 'ISABELA CITY', '09', '0997'),
        ('53', '101300000', 'BUKIDNON', '10', '1013'),
        ('54', '101800000', 'CAMIGUIN', '10', '1018'),
        ('55', '103500000', 'LANAO DEL NORTE', '10', '1035'),
        ('56', '104200000', 'MISAMIS OCCIDENTAL', '10', '1042'),
        ('57', '104300000', 'MISAMIS ORIENTAL', '10', '1043'),
        ('58', '112300000', 'DAVAO DEL NORTE', '11', '1123'),
        ('59', '112400000', 'DAVAO DEL SUR', '11', '1124'),
        ('60', '112500000', 'DAVAO ORIENTAL', '11', '1125'),
        ('61', '118200000', 'COMPOSTELA VALLEY', '11', '1182'),
        ('62', '118600000', 'DAVAO OCCIDENTAL', '11', '1186'),
        ('63', '124700000', 'NORTH COTABATO', '12', '1247'),
        ('64', '126300000', 'SOUTH COTABATO', '12', '1263'),
        ('65', '126500000', 'SULTAN KUDARAT', '12', '1265'),
        ('66', '128000000', 'SARANGANI', '12', '1280'),
        ('67', '129800000', 'COTABATO CITY', '12', '1298'),
        ('68', '133900000', 'NCR, CITY OF MANILA, FIRST DISTRICT', '13', '1339'),
        ('69', '133900000', 'CITY OF MANILA', '13', '1339'),
        ('70', '137400000', 'NCR, SECOND DISTRICT', '13', '1374'),
        ('71', '137500000', 'NCR, THIRD DISTRICT', '13', '1375'),
        ('72', '137600000', 'NCR, FOURTH DISTRICT', '13', '1376'),
        ('73', '140100000', 'ABRA', '14', '1401'),
        ('74', '141100000', 'BENGUET', '14', '1411'),
        ('75', '142700000', 'IFUGAO', '14', '1427'),
        ('76', '143200000', 'KALINGA', '14', '1432'),
        ('77', '144400000', 'MOUNTAIN PROVINCE', '14', '1444'),
        ('78', '148100000', 'APAYAO', '14', '1481'),
        ('79', '150700000', 'BASILAN', '15', '1507'),
        ('80', '153600000', 'LANAO DEL SUR', '15', '1536'),
        ('81', '153800000', 'MAGUINDANAO', '15', '1538'),
        ('82', '156600000', 'SULU', '15', '1566'),
        ('83', '157000000', 'TAWI-TAWI', '15', '1570'),
        ('84', '160200000', 'AGUSAN DEL NORTE', '16', '1602'),
        ('85', '160300000', 'AGUSAN DEL SUR', '16', '1603'),
        ('86', '166700000', 'SURIGAO DEL NORTE', '16', '1667'),
        ('87', '166800000', 'SURIGAO DEL SUR', '16', '1668'),
        ('88', '168500000', 'DINAGAT ISLANDS', '16', '1685');
