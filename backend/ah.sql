BEGIN

	IF ((SELECT COUNT(*) `user_count` FROM `address` WHERE `id` = `id_` AND `member_id` = `member_id_`) = 1) THEN
		(	IF (`is_active_` = 1) THEN
				UPDATE `address`
				SET `is_active` = `0`
				WHERE `member_id` = `member_id_`;
			ELSE 
			SET msg = "";
			END IF;
		)
		UPDATE `address` SET 
		`address1` = `add1_`,
		`address2` = `add2_`,
		`city` = `city_`,
		`province` = `province_`,
		`is_active` = `is_active_`,
		`last_update` = now()
		WHERE `id` = `id_`
		AND  `member_id` = `member_id_`;
		
		SET msg = "1";
	ELSE
		SET msg = "0";
	END IF;

END