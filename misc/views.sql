
DROP VIEW IF EXISTS view_pharma_transactions;
CREATE VIEW view_pharma_transactions AS 
(
SELECT  m.id `member_id`, m.first_name, m.last_name, t.id `trans_number`, t.trans_date, c.company_name, c.branch, c.business_type,
     -- query pharmacy
     d.generic_name, d.brand, d.dose, d.unit, p.quantity,  p.unit_price, p.vat_exempt_price, p.discount_price, p.payable_price	
FROM transaction t
LEFT JOIN pharmacy p ON p.transaction_id = t.id
LEFT JOIN member m ON t.member_id = m.id
LEFT JOIN company c ON t.company_id = c.id 
LEFT JOIN drug d ON p.drug_id = d.id 
WHERE p.transaction_id = t.id
);

DROP VIEW IF EXISTS view_food_transactions;
CREATE VIEW view_food_transactions AS 
(
SELECT  m.id `member_id`, m.first_name, m.last_name, t.id `trans_number`, t.trans_date, c.company_name, c.branch, c.business_type,
     -- query food
     f.`desc`, f.vat_exempt_price, f.discount_price, f.payable_price
FROM transaction t
LEFT JOIN food f ON f.transaction_id = t.id
LEFT JOIN member m ON t.member_id = m.id
LEFT JOIN company c ON t.company_id = c.id 
WHERE f.transaction_id = t.id
);

DROP VIEW IF EXISTS view_transportation_transactions;
CREATE VIEW view_transportation_transactions AS 
(
SELECT  m.id `member_id`, m.first_name, m.last_name, t.id `trans_number`, t.trans_date, c.company_name, c.branch, c.business_type,
     -- query pharmacy
     t1.`desc`,t1.vat_exempt_price, t1.discount_price, t1.payable_price
FROM transaction t
LEFT JOIN transportation t1 ON t1.transaction_id = t.id
LEFT JOIN member m ON t.member_id = m.id
LEFT JOIN company c ON t.company_id = c.id 
WHERE t1.transaction_id = t.id
);

DROP VIEW IF EXISTS view_all_transactions;
CREATE VIEW view_all_transactions AS 

SELECT * FROM
	(SELECT  m.id `member_id`, m.first_name, m.last_name, t.id `trans_number`, t.trans_date, c.company_name, c.branch, c.business_type,
		 -- query pharmacy
         
		 concat("[", UCASE(LEFT(d.generic_name, 1)), LCASE(SUBSTRING(d.generic_name, 2)), "], ", UCASE(LEFT(d.brand, 1)), LCASE(SUBSTRING(d.brand, 2)),  ", ", d.dose, d.unit,  ", ", p.quantity,  "pcs, P ",  p.unit_price, "/pc") AS `desc`, p.vat_exempt_price, p.discount_price, p.payable_price	
	FROM transaction t
	LEFT JOIN pharmacy p ON p.transaction_id = t.id
	LEFT JOIN member m ON t.member_id = m.id
	LEFT JOIN company c ON t.company_id = c.id 
	LEFT JOIN drug d ON p.drug_id = d.id 
	WHERE p.transaction_id = t.id) AS T1
UNION
SELECT * FROM
	(SELECT  m.id `member_id`, m.first_name, m.last_name, t.id `trans_number`, t.trans_date, c.company_name, c.branch, c.business_type,
		 -- query transpo
		 t1.`desc`,t1.vat_exempt_price, t1.discount_price, t1.payable_price
	FROM transaction t
	LEFT JOIN transportation t1 ON t1.transaction_id = t.id
	LEFT JOIN member m ON t.member_id = m.id
	LEFT JOIN company c ON t.company_id = c.id 
	WHERE t1.transaction_id = t.id) AS T2
UNION 
SELECT * FROM
	(SELECT  m.id `member_id`, m.first_name, m.last_name, t.id `trans_number`, t.trans_date, c.company_name, c.branch, c.business_type,
		 -- query food
		 f.`desc`,f.vat_exempt_price, f.discount_price, f.payable_price
	FROM transaction t
	LEFT JOIN food f ON f.transaction_id = t.id
	LEFT JOIN member m ON t.member_id = m.id
	LEFT JOIN company c ON t.company_id = c.id 
	WHERE f.transaction_id = t.id) AS T3
;

DROP VIEW IF EXISTS view_members_with_guardian;
CREATE VIEW view_members_with_guardian AS 
(
SELECT m.`id` `member_id`, m.`osca_id`, m.`nfc_serial`, m.`password`, m.`first_name`, m.`middle_name`, m.`last_name`, m.`sex`, 
concat(day(`birth_date`), ' ', monthname(`birth_date`), ' ', year(`birth_date`)) `bdate`, 
YEAR(CURDATE()) - 
YEAR(birth_date) -
IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(birth_date), '-', DAY(birth_date)) ,'%Y-%c-%e') > CURDATE(), 1, 0) age,
concat(day(`membership_date`), ' ', monthname(`membership_date`), ' ', year(`membership_date`)) `memship_date`, 
m.`contact_number`, m.`email`, m.`picture` `picture`, 
g.first_name `g_first_name`, g.middle_name `g_middle_name`, g.last_name `g_last_name`, g.sex `g_sex`, 
g.contact_number `g_contact_number`, g.email `g_email`, g.relationship `g_relationship` 
FROM `member` m INNER JOIN guardian g ON g.member_id = m.id
);


/*
SELECT * FROM view_pharma_transactions
-- WHERE member_id = 2
ORDER BY trans_date;

SELECT * FROM view_food_transactions
-- WHERE member_id = $selected_id
ORDER BY trans_date;

SELECT * FROM view_transportation_transactions
-- WHERE member_id = $selected_id
ORDER BY trans_date;

SELECT * FROM view_members_with_guardian
WHERE member_id = $selected_id

								*/