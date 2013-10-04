ALTER TABLE `user` ADD `config` text;

CREATE TABLE IF NOT EXISTS `string_translations` (
  `language` varchar(50) NOT NULL,
  `context` varchar(50) NOT NULL,
  `name` varchar(160) NOT NULL,
  `value` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY `context_name_language` (`context`,`name`,`language`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
