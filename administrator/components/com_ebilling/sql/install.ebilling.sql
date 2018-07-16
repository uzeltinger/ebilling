CREATE TABLE IF NOT EXISTS `#__ebilling_profiles` (
  `id` int(6) NOT NULL auto_increment,
  `mid` int(6) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `alias` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL default '',
  `type` int(1) NOT NULL default '0',
  `address1` varchar(50) NOT NULL default '',
  `address2` varchar(50) NOT NULL default '',
  `locality` varchar(50) NOT NULL default '',
  `pcode` varchar(10) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `phone` varchar(20) NOT NULL default '',
  `whatsapp` varchar(20) NOT NULL default '',
  `mobile` varchar(20) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `web` varchar(255) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(3) NOT NULL default '0',
  `checked_out` tinyint(1) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mid` (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ebilling_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_num` varchar(255) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `type` int(1) NOT NULL default '0',
  `status` varchar(255) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `profile_id` int(6) NOT NULL default '0',
  `to_name` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(3) NOT NULL default '0',
  `checked_out` tinyint(1) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `o2he0_ebilling_invoices_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `o2he0_ebilling_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
`CbteNum` varchar(10) NOT NULL,
`deRazonSocial` varchar(255) NOT NULL,
`deDireccion` text NOT NULL,
`deCondIva` varchar(255),
`deDocTipo` varchar(12),
`deDocNum` varchar(12),
`deIibbNumero` varchar(12),
`deInicioActividad` date NULL,
`CbteTipo` varchar(20),
`PtoVta` varchar(4),
`CbteFch` date NOT NULL,
`Concepto` varchar(20),
`FchServDesde` date NULL,
`FchServHasta` date NULL,
`FchVtoPago` date NOT NULL,
`aDocTipo` varchar(10),
`aDocNum` varchar(12),
`aCondIva` varchar(100),
`aCondVta` varchar(100),
`aRazonSocial` varchar(255) NOT NULL,
`aDireccion` text NOT NULL,
`aCodPostal` varchar(10) NOT NULL,
`aCiudad` varchar(255) NOT NULL,
`aProvincia` varchar(255) NOT NULL,
`aPais` varchar(255) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `profile_id` int(6) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(3) NOT NULL DEFAULT '0',
  `checked_out` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;






CREATE TABLE IF NOT EXISTS `#__ebilling_invoices_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `value` decimal(12,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL,
  `ordering` int(11) NOT NULL,
  `tax` decimal(12,2) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ebilling_products` (
  `id` int(6) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(3) NOT NULL default '0',
  `checked_out` tinyint(1) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
