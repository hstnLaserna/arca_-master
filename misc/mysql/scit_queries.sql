use scit;

DROP VIEW IF EXISTS `view_companies`;
CREATE VIEW `view_companies` AS
(
SELECT * FROM `db_osca`.`view_companies`);

DROP VIEW IF EXISTS view_pharma_transactions;
CREATE VIEW view_pharma_transactions AS 
(
SELECT  * FROM `db_osca`.`view_pharma_transactions`
);

DROP VIEW IF EXISTS view_food_transactions;
CREATE VIEW view_food_transactions AS 
(
SELECT  * FROM `db_osca`.`view_food_transactions`
);

DROP VIEW IF EXISTS view_transportation_transactions;
CREATE VIEW view_transportation_transactions AS 
(
SELECT  * FROM `db_osca`.`view_transportation_transactions`
);

DROP VIEW IF EXISTS view_all_transactions;
CREATE VIEW view_all_transactions AS 
(
SELECT  * FROM `db_osca`.`view_all_transactions`
);


DROP VIEW IF EXISTS view_members_with_guardian;
CREATE VIEW view_members_with_guardian AS 
(
SELECT  * FROM `db_osca`.`view_members_with_guardian`
);

select * from view_pharma_transactions;
select * from view_food_transactions;
select * from view_transportation_transactions;
select * from view_all_transactions;
select * from view_members_with_guardian;
select * from view_companies;

select * from view_companies
where user_name = "lrt_pg" and password = MD5("lrt_pg");