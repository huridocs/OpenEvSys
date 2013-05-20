ALTER TABLE mt_vocab  MODIFY huri_code varchar(14) NULL ;
ALTER TABLE `mt_vocab` ADD `term_order` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `mt_vocab` ADD `parent_vocab_number` varchar(14) NOT NULL;
ALTER TABLE `mt_vocab` ADD `term_level` int(11) NOT NULL;

update data_dict set validation='number' where datatype='N';

ALTER TABLE config  MODIFY `value` text NULL ;

CREATE TABLE IF NOT EXISTS `data_dict_visibility` (
  `field_number` varchar(60) NOT NULL,
  `field_number2` varchar(60) NOT NULL,
  `value` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
