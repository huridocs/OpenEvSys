
CREATE TABLE IF NOT EXISTS `geometry` (
  `geometry_record_number` varchar(45) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` varchar(45) NOT NULL,
  `geometry` geometry NOT NULL,
  PRIMARY KEY (`geometry_record_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `geometry_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `data_dict` (`field_number`, `field_label`, `field_type`, `datatype`, `label_number`, `list_code`, `is_repeat`, `clar_note`, `field_name`, `link_table`, `link_field`, `remove`, `chk_remove`, `num_levels`, `lite`, `bool_def`, `numb_def`, `entity`, `find`, `essential`, `enabled`, `visible_new`, `visible_edit`, `visible_view`, `visible_search`, `visible_search_display`, `visible_browse`, `visible_browse_editable`, `validation`, `required`, `visible_adv_search`, `visible_adv_search_display`) VALUES
('0173', 'Location', 'location', 'L', '0173', '0', 'N', 'n', 'event_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'event', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n'),
('0972', 'Location', 'location', 'L', '0972', '0', 'N', 'n', 'person_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'Person', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n'),
('2172', 'Location', 'location', 'L', '2172', '0', 'N', 'n', 'act_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'act', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n'),
('2672', 'Location', 'location', 'L', '2672', '0', 'N', 'n', 'intervention_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'intervention', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n');


INSERT INTO `data_dict_original` (`field_number`, `field_label`, `field_type`, `datatype`, `label_number`, `list_code`, `is_repeat`, `clar_note`, `field_name`, `link_table`, `link_field`, `remove`, `chk_remove`, `num_levels`, `lite`, `bool_def`, `numb_def`, `entity`, `find`, `essential`, `enabled`, `visible_new`, `visible_edit`, `visible_view`, `visible_search`, `visible_search_display`, `visible_browse`, `visible_browse_editable`, `validation`, `required`, `visible_adv_search`, `visible_adv_search_display`) VALUES
('0173', 'Location', 'location', 'L', '0173', '0', 'N', 'n', 'event_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'event', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n'),
('0972', 'Location', 'location', 'L', '0972', '0', 'N', 'n', 'person_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'Person', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n'),
('2172', 'Location', 'location', 'L', '2172', '0', 'N', 'n', 'act_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'act', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n'),
('2672', 'Location', 'location', 'L', '2672', '0', 'N', 'n', 'intervention_location', NULL, NULL, 'Y', '1', 'N', '0', '0', NULL, 'intervention', 'Y', 'n', 'y', 'y', '', 'y', 'n', 'n', '', 'y', '', 'n', 'y', 'n');

ALTER TABLE `event` ADD `latitude` double NOT NULL DEFAULT '0';
ALTER TABLE `event` ADD `longitude` double NOT NULL DEFAULT '0';

ALTER TABLE `person` ADD `latitude` double NOT NULL DEFAULT '0';
ALTER TABLE `person` ADD `longitude` double NOT NULL DEFAULT '0';

ALTER TABLE `act` ADD `latitude` double NOT NULL DEFAULT '0';
ALTER TABLE `act` ADD `longitude` double NOT NULL DEFAULT '0';

ALTER TABLE `intervention` ADD `latitude` double NOT NULL DEFAULT '0';
ALTER TABLE `intervention` ADD `longitude` double NOT NULL DEFAULT '0';

