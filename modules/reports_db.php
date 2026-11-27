<?php
/* Reports ("Подай сигнал") – table is created automatically the first time it is needed. */
function reports_table(){
	mysql_query('CREATE TABLE IF NOT EXISTS `reports` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`item_type` varchar(10) NOT NULL,
		`item_id` int(11) NOT NULL,
		`reason` tinyint(4) NOT NULL,
		`message` text NOT NULL,
		`email` varchar(150) NOT NULL DEFAULT "",
		`customer_id` int(11) NOT NULL DEFAULT 0,
		`ip` varchar(45) NOT NULL,
		`added_date` datetime NOT NULL,
		`status` tinyint(4) NOT NULL DEFAULT 0,
		`admin_note` varchar(500) NOT NULL DEFAULT "",
		PRIMARY KEY (`id`), KEY `status` (`status`), KEY `ip` (`ip`, `added_date`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8');
}
function reports_reasons(){
	return array(1 => 'Опит за измама', 2 => 'Невярна / подвеждаща информация', 3 => 'Забранен или незаконен продукт',
		4 => 'Авторски права / чужди снимки', 5 => 'Обидно съдържание', 6 => 'Бизнесът не съществува', 7 => 'Спам / дублирана', 8 => 'Друго');
}
