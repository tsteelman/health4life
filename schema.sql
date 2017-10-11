

DROP TABLE IF EXISTS `users`;


CREATE TABLE `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`profile_picture` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`first_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`last_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`gender` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`date_of_birth` date DEFAULT NULL,
	`country` int(11) DEFAULT NULL,
	`zip` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`state` int(11) DEFAULT NULL,
	`timezone` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	`last_login_datetime` datetime DEFAULT NULL,
	`type` int(1) DEFAULT NULL COMMENT 'user type - patient, family, caregiver)',
	`is_admin` tinyint(1) DEFAULT NULL,
	`newsletter` tinyint(1) DEFAULT NULL,
	`status` int(1) DEFAULT NULL,
	`remember_me_code` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`forgot_password_code` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`height` int(3) DEFAULT NULL,
	`marital_status` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`phone_number` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;