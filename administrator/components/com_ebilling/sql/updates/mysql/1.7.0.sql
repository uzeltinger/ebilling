CREATE TABLE IF NOT EXISTS `#__ebilling_payments` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`invoice_id` int(11) NOT NULL,
	`codigo` varchar(50) NOT NULL,
	`detalle` varchar(255) NOT NULL DEFAULT '',
	`valor` decimal(12,2) NOT NULL,	
	`total` tinyint(1) NOT NULL DEFAULT '0',
	`published` tinyint(1) NOT NULL DEFAULT '0',
	`ordering` int(3) NOT NULL DEFAULT '0',
	`checked_out` tinyint(1) NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime DEFAULT '0000-00-00 00:00:00',
	`created_by` int(10) NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;