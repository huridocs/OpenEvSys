
-- CREATE SCHEMA IF NOT EXISTS `openevsys` DEFAULT CHARACTER SET latin1 COLLATE utf8;
-- USE `openevsys`;

-- -----------------------------------------------------
-- Table  `mt_vocab`
-- -----------------------------------------------------

-- if needed to delete -----------------------


SET foreign_key_checks = 0;

-- -------------------------------------------
DROP TABLE IF EXISTS  `mlt_intervention_type_of_intervention` ;
DROP TABLE IF EXISTS  `mlt_information_local_language_of_source_material` ; 
DROP TABLE IF EXISTS  `mlt_information_type_of_source_material` ; 
DROP TABLE IF EXISTS  `mlt_information_language_of_source_material` ;
DROP TABLE IF EXISTS  `mlt_involvement_type_of_perpetrator` ; 
DROP TABLE IF EXISTS  `mlt_torture_intent` ;
DROP TABLE IF EXISTS  `mlt_torture_medical_attention` ;
DROP TABLE IF EXISTS  `mlt_torture_statement_signed` ;
DROP TABLE IF EXISTS  `mlt_arrest_type_of_language` ;
DROP TABLE IF EXISTS  `mlt_arrest_type_of_court` ; 
DROP TABLE IF EXISTS  `mlt_arrest_legal_counsel` ; 
DROP TABLE IF EXISTS  `mlt_arrest_whereabouts` ; 
DROP TABLE IF EXISTS  `mlt_person_national_origin` ;
DROP TABLE IF EXISTS  `mlt_person_local_language` ;
DROP TABLE IF EXISTS  `mlt_person_language` ;
DROP TABLE IF EXISTS  `mlt_person_general_characteristics` ;
DROP TABLE IF EXISTS  `mlt_person_other_background` ;
DROP TABLE IF EXISTS  `mlt_person_ethnic_background` ;
DROP TABLE IF EXISTS  `mlt_person_citizenship` ;
DROP TABLE IF EXISTS  `mlt_person_physical_description` ;
DROP TABLE IF EXISTS  `mlt_person_local_term_for_occupation` ;
DROP TABLE IF EXISTS  `mlt_person_occupation` ;
DROP TABLE IF EXISTS  `mlt_event_other_thesaurus` ;
DROP TABLE IF EXISTS  `mlt_event_local_geographical_area` ;
DROP TABLE IF EXISTS  `mlt_event_files` ;
DROP TABLE IF EXISTS  `mlt_event_supporting_documents` ;
DROP TABLE IF EXISTS  `mlt_event_local_index` ;
DROP TABLE IF EXISTS  `mlt_event_huridocs_index_terms` ;
DROP TABLE IF EXISTS  `mlt_event_rights_affected` ;
DROP TABLE IF EXISTS  `mlt_event_violation_index` ;
DROP TABLE IF EXISTS  `mlt_event_geographical_terms` ;
DROP TABLE IF EXISTS  `mlt_act_international_instruments` ;
DROP TABLE IF EXISTS  `mlt_act_victim_characteristics` ;
DROP TABLE IF EXISTS  `mlt_act_attribution` ;
DROP TABLE IF EXISTS  `mlt_act_method_of_violence` ;
DROP TABLE IF EXISTS mlt_act_national_legislation;
DROP TABLE IF EXISTS clari_notes;
DROP TABLE IF  EXISTS person_doc;
DROP TABLE IF  EXISTS event_doc;
DROP TABLE IF  EXISTS supporting_docs_meta;
DROP TABLE IF  EXISTS supporting_docs;
DROP TABLE IF EXISTS supporting_docs_links;
DROP TABLE IF EXISTS  `management` ;
DROP TABLE IF EXISTS  `intervention` ;
DROP TABLE IF EXISTS  `information` ;
DROP TABLE IF EXISTS  `involvement` ;
DROP TABLE IF EXISTS  `chain_of_events` ;
DROP TABLE IF EXISTS  `destruction` ;
DROP TABLE IF EXISTS  `torture` ;
DROP TABLE IF EXISTS  `killing` ;
DROP TABLE IF EXISTS  `arrest` ;
DROP TABLE IF EXISTS  `act` ;
DROP TABLE IF EXISTS  `address` ;
DROP TABLE IF EXISTS  `biographic_details` ;
DROP TABLE IF EXISTS  `person` ;
DROP TABLE IF EXISTS  `event` ;
DROP TABLE IF EXISTS  `mlt_geometry` ;

DROP TABLE IF EXISTS  mt_1_index_term ;


DROP TABLE IF EXISTS  mt_2_violation_typology_terms  ;
 


DROP TABLE IF EXISTS  mt_3_rights_typology   ;


DROP TABLE IF EXISTS  mt_4_types_of_acts  ;


DROP TABLE IF EXISTS  mt_5_methods_of_violence  ;


DROP TABLE IF EXISTS  mt_6_international_instruments  ;


DROP TABLE IF EXISTS  mt_7_counting_units  ;


DROP TABLE IF EXISTS  mt_8_civil_status ;


DROP TABLE IF EXISTS  mt_9_education;

DROP TABLE IF EXISTS  mt_10_occupation ;

DROP TABLE IF EXISTS  mt_11_physical_descriptors ;

DROP TABLE IF EXISTS  mt_12_religions ;

DROP TABLE IF EXISTS  mt_13_ethnic_groups ;

DROP TABLE IF EXISTS  mt_14_languages ;

DROP TABLE IF EXISTS  mt_15_geographical_terms  ;

DROP TABLE IF EXISTS  mt_16_types_of_source_material ;

DROP TABLE IF EXISTS  mt_17_types_of_locations  ;

DROP TABLE IF EXISTS  mt_18_degrees_of_involvement ;

DROP TABLE IF EXISTS  mt_19_source_connection_to_information ;

DROP TABLE IF EXISTS  mt_20_types_of_intervention ;

DROP TABLE IF EXISTS  mt_21_types_of_relationships ;

DROP TABLE IF EXISTS  mt_22_types_of_chain_of_events ;

DROP TABLE IF EXISTS  mt_23_relavant_characteristics ;

DROP TABLE IF EXISTS  mt_24_types_of_perpetrators ;

DROP TABLE IF EXISTS  mt_25_status_as_victim ;

DROP TABLE IF EXISTS  mt_26_status_as_perpetrator ;

DROP TABLE IF EXISTS  mt_27_types_of_responses ;

DROP TABLE IF EXISTS  mt_28_attribution ;

DROP TABLE IF EXISTS  mt_29_types_of_detention ;

DROP TABLE IF EXISTS  mt_30_whereabouts ;

DROP TABLE IF EXISTS  mt_31_legal_counsel  ;

DROP TABLE IF EXISTS  mt_32_types_of_courts ;

DROP TABLE IF EXISTS  mt_33_types_of_language_used_in_court ;

DROP TABLE IF EXISTS  mt_34_autopsy_results  ;

DROP TABLE IF EXISTS  mt_35_death_certificate ;

DROP TABLE IF EXISTS  mt_36_statements_signed ;

DROP TABLE IF EXISTS  mt_37_medical_attention ;

DROP TABLE IF EXISTS  mt_38_intent ;

DROP TABLE IF EXISTS  mt_39_sex ;

DROP TABLE IF EXISTS  mt_40_types_of_addresses ;

DROP TABLE IF EXISTS  mt_41_violation_status  ;

DROP TABLE IF EXISTS  mt_42_reliability ;

DROP TABLE IF EXISTS  mt_43_monitoring_status ;

DROP TABLE IF EXISTS  mt_44_impact_on_the_situation ;

DROP TABLE IF EXISTS  mt_45_intervention_status ;

DROP TABLE IF EXISTS  mt_46_priority ;

DROP TABLE IF EXISTS  mt_47_compensation ;

DROP TABLE IF EXISTS  mt_48_types_of_dates  ;


DROP TABLE IF EXISTS  mt_61_local_index  ;

DROP TABLE IF EXISTS  mt_62_national_legislation ;

DROP TABLE IF EXISTS  mt_63_local_geographical_area ;

DROP TABLE IF EXISTS  mt_64_local_terms_for_occupations ;

DROP TABLE IF EXISTS  mt_65_origins ;

DROP TABLE IF EXISTS  mt_66_local_languages ;

DROP TABLE IF EXISTS  mt_67_sexual_orientation ;

DROP TABLE IF EXISTS mt_68_other_thesaurus ;


DROP TABLE IF EXISTS mt_69_judicial_districts ;

DROP TABLE IF EXISTS mt_index;


DROP TABLE IF EXISTS mt_vocab_l10n;
DROP TABLE IF EXISTS mt_vocab;


CREATE TABLE IF NOT EXISTS  `mt_vocab` (
 `vocab_number` varchar(14) NOT NULL,
  `huri_code` varchar(14) DEFAULT NULL,
  `english` text,
  `french` text,
  `otherlanguages` text,
  `list_code` int(11) NOT NULL,
  `visible` varchar(1) NOT NULL DEFAULT 'y',
  `term_order` int(11) NOT NULL DEFAULT '0',
  `parent_vocab_number` varchar(14) NOT NULL,
  `term_level` int(11) NOT NULL,
  PRIMARY KEY (`vocab_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `mt_vocab_l10n` (
   `msgid` VARCHAR( 60 ) NOT NULL ,
   `locale` VARCHAR( 20 ) NOT NULL ,
   `msgstr` TEXT CHARACTER SET utf8 NOT NULL,
   PRIMARY KEY (`msgid`,`locale`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS mt_index(
no INT NOT NULL,
term varchar(100),
primary key (no)
);

CREATE TABLE IF NOT EXISTS mt_1_index_term(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_2_violation_typology_terms 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS mt_3_rights_typology (
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  

) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_4_types_of_acts 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_5_methods_of_violence 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_6_international_instruments 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_7_counting_units 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_8_civil_status
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_9_education
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_10_occupation
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_11_physical_descriptors
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_12_religions
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_13_ethnic_groups
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_14_languages
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_15_geographical_terms 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE  
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_16_types_of_source_material
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_17_types_of_locations 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_18_degrees_of_involvement
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_19_source_connection_to_information
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_20_types_of_intervention
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_21_types_of_relationships
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_22_types_of_chain_of_events
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_23_relavant_characteristics
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_24_types_of_perpetrators
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_25_status_as_victim
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_26_status_as_perpetrator
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_27_types_of_responses
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_28_attribution
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_29_types_of_detention
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_30_whereabouts
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_31_legal_counsel 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_32_types_of_courts
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_33_types_of_language_used_in_court
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_34_autopsy_results 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_35_death_certificate
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_36_statements_signed
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_37_medical_attention
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_38_intent
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_39_sex
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_40_types_of_addresses
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_41_violation_status 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_42_reliability
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_43_monitoring_status
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_44_impact_on_the_situation
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_45_intervention_status
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_46_priority
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_47_compensation
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_48_types_of_dates 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS mt_61_local_index 
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_62_national_legislation
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_63_local_geographical_area
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_64_local_terms_for_occupations
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_65_origins
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_66_local_languages
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_67_sexual_orientation
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mt_68_other_thesaurus
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS mt_69_judicial_districts
(
    vocab_number VARCHAR(14) NOT NULL ,
    PRIMARY KEY ( vocab_number ) ,
    FOREIGN KEY ( vocab_number ) REFERENCES  `mt_vocab` (`vocab_number` )  
    ON DELETE CASCADE
    ON UPDATE CASCADE 
) ENGINE = InnoDB DEFAULT CHARSET=utf8;




-- -----------------------------------------------------
-- Table  `event`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `event` (
  `event_record_number` VARCHAR(45) NOT NULL ,
  `event_title` VARCHAR(500) NOT NULL ,
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
  -- 15_*_C_geographical_term` VARCHAR(60) NULL DEFAULT NULL ,
  -- 63_*_C_local_geographical_area` VARCHAR(60) NULL DEFAULT NULL ,
  `initial_date` DATE NULL DEFAULT NULL ,
  `initial_date_type` VARCHAR(14) NULL ,
  `final_date` DATE NULL DEFAULT NULL ,
  `final_date_type` VARCHAR(14) NULL ,
  `event_description` TEXT NULL DEFAULT NULL ,
  `impact_of_event` VARCHAR(500) NULL DEFAULT NULL ,
  `remarks` TEXT NULL ,
  `violation_status` VARCHAR(14) NULL DEFAULT NULL ,
  -- 02_*_violation_index` VARCHAR(60) NULL DEFAULT NULL ,
  -- 03_*_rights_affected` VARCHAR(60) NULL DEFAULT NULL ,
  -- 01_*_huridocs_index` VARCHAR(60) NULL DEFAULT NULL ,
  -- 61_*_local_index` VARCHAR(60) NULL DEFAULT NULL ,
  -- 68_*_other_thesaurus` VARCHAR(60) NULL DEFAULT NULL ,
  -- `date_received` DATE NULL DEFAULT NULL ,
  -- `project_title` VARCHAR(60) NULL DEFAULT NULL ,
  -- `supporting_documents` VARCHAR(60) NULL DEFAULT NULL ,
  `files` TEXT NULL DEFAULT NULL ,
  `record_grouping` VARCHAR(500) NULL DEFAULT NULL ,
  -- `monitoring_status` VARCHAR(60) NULL DEFAULT NULL ,
  `event_location_latitude` double  NULL DEFAULT NULL,
  `event_location_longitude` double  NULL DEFAULT NULL,
  PRIMARY KEY (`event_record_number`) ,
  -- INDEX  (`initial_date_type` ASC) ,
  -- INDEX final_date_type_fk (`final_date_type` ASC) ,
  -- INDEX fk_violation_status (`violation_status` ASC) ,
  -- CONSTRAINT ``
    FOREIGN KEY (`initial_date_type` )
    REFERENCES  `mt_48_types_of_dates` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  -- CONSTRAINT `final_date_type_fk`
    FOREIGN KEY (`final_date_type` )
    REFERENCES  `mt_48_types_of_dates` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  -- CONSTRAINT `fk_violation_status`
    FOREIGN KEY (`violation_status` )
    REFERENCES  `mt_41_violation_status` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mlt_geometry` (
  `geometry_record_number` varchar(45) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` varchar(45) NOT NULL,
  `geometry` geometry NOT NULL,
  `field_name` varchar(100) NOT NULL,
  PRIMARY KEY (`geometry_record_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT CHARSET=utf8;




-- -----------------------------------------------------
-- Table  `person`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `person` (
  `person_record_number` VARCHAR(45) NOT NULL ,
  `counting_unit` VARCHAR(14)  , -- 07: Counting Units
  `person_name` VARCHAR(500) NULL DEFAULT NULL ,
  `other_names` VARCHAR(500) NULL DEFAULT NULL ,
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
   -- `address` VARCHAR(60) NULL DEFAULT NULL ,
   -- `address_type` VARCHAR(14) , -- 40: Types of Addresses 
  `date_of_birth` DATE NULL DEFAULT NULL ,
  `date_of_birth_type` VARCHAR(14) ,    -- 48_types_of_dates 
  `place_of_birth` VARCHAR(14)  ,   -- 15: Geographical Terms
  `locality_of_birth` VARCHAR(14) ,    -- 63: Local Geographical Area
  `sex` VARCHAR(14) ,                               -- 39: Sex
  `sexual_orientation` VARCHAR(14) ,  -- 67: Sexual Orientation.
  `identification_documents` TEXT NULL DEFAULT NULL ,
  `civil_status` VARCHAR(14)  ,              -- 08: Civil Status
  `dependants` INT NULL DEFAULT NULL ,
  `formal_education` VARCHAR(14)  ,     -- 09: Education
  `other_training` text NULL DEFAULT NULL ,
  -- `10_*_occupation` VARCHAR(60) NULL DEFAULT NULL ,                 -- 10: Occupations
  -- `64_*_local_term_for_occupation` VARCHAR(60) NULL DEFAULT NULL ,  -- 64: Local Terms for Occupations.
  `health` TEXT NULL DEFAULT NULL ,
  `medical_records` TEXT NULL DEFAULT NULL ,
  -- `11_*_physical_description` VARCHAR(60) NULL DEFAULT NULL ,       -- 11: Physical Descriptors.
  `deceased` VARCHAR(1) NULL DEFAULT NULL ,
  `date_deceased` DATE,
  `date_deceased_type`VARCHAR(14)  ,    -- 48: types of Dates  
  `group_description` TEXT NULL DEFAULT NULL ,
  `number_of_persons_in_group` INT NULL DEFAULT NULL ,
  `religion` VARCHAR(14)   ,      -- 12: Religions.
  -- `15_*_citizenship` VARCHAR(60) NULL DEFAULT NULL , -- 15: Geographical Terms.
  -- `13_*_ethnic_background` VARCHAR(60) NULL DEFAULT NULL , --  13: Ethnic Groups
  -- `65_*_other_background` VARCHAR(60) NULL DEFAULT NULL ,   -- 65: Origins

  -- `23_*_general_characteristics` VARCHAR(60) NULL DEFAULT NULL , -- 23: Relevant Characteristics
  -- `14_*_language` VARCHAR(60) NULL DEFAULT NULL , -- 14: Languages
  -- `66_*_local_language` VARCHAR(60) NULL DEFAULT NULL , -- 66: Local Languages.
  -- `15_*_national_origin` VARCHAR(60) NULL DEFAULT NULL , --  15: Geographical Terms
  `remarks` TEXT ,
  `reliability_as_source` VARCHAR(14)  ,    -- 42: Reliability.
  `reliability_as_intervening_party` VARCHAR(14)  , -- 42: Reliability.
  `files` TEXT,
  `person_location_latitude` double  NULL DEFAULT NULL,
  `person_location_longitude` double  NULL DEFAULT NULL,
  PRIMARY KEY (`person_record_number`),
  FOREIGN KEY (`counting_unit`) REFERENCES mt_7_counting_units (vocab_number) , -- 07: Counting Units

  FOREIGN KEY (`date_of_birth_type`)  REFERENCES mt_48_types_of_dates (vocab_number),    -- 48_types_of_dates 
  FOREIGN KEY (`place_of_birth`)  REFERENCES mt_15_geographical_terms (vocab_number) ,   -- 15: Geographical Terms
  FOREIGN KEY (`locality_of_birth`)  REFERENCES mt_63_local_geographical_area (vocab_number) ,    -- 63: Local Geographical Area
  FOREIGN KEY (`sex`)  REFERENCES mt_39_sex(vocab_number) ,    
  FOREIGN KEY (`sexual_orientation`)  REFERENCES mt_67_sexual_orientation (vocab_number),  -- 67: Sexual Orientation.
  FOREIGN KEY (`civil_status`)  REFERENCES mt_8_civil_status (vocab_number) ,              -- 08: Civil Status
  FOREIGN KEY (`formal_education`)  REFERENCES mt_9_education (vocab_number) ,     -- 09: Education                           -- 39: Sex
  FOREIGN KEY (`date_deceased_type`) REFERENCES mt_48_types_of_dates (vocab_number) ,    -- 48: types of Dates
  FOREIGN KEY (`religion`)  REFERENCES mt_12_religions (vocab_number) ,      -- 12: Religions.
  FOREIGN KEY (`reliability_as_source`)   REFERENCES mt_42_reliability (vocab_number)  ,    -- 42: Reliability.
  FOREIGN KEY (`reliability_as_intervening_party`)   REFERENCES mt_42_reliability (vocab_number)   -- 42: Reliability.
  
  )
ENGINE = InnoDB DEFAULT CHARSET=utf8;




-- -----------------------------------------------------
-- Table  `biographic_details`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `biographic_details` (
  `biographic_details_record_number` VARCHAR(45) NOT NULL ,
  `person` VARCHAR(45) NOT NULL ,
  `related_person` VARCHAR(45) NULL DEFAULT NULL,
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
  `type_of_relationship` VARCHAR(14) NULL DEFAULT NULL , -- 21: Types of Relationships.
  `initial_date` DATE NULL DEFAULT NULL ,
  `initial_date_type` VARCHAR(14), -- 48: Types of Dates
  `final_date` DATE NULL DEFAULT NULL ,
  `final_date_type` VARCHAR(14), -- 48: Types of Dates  
  `education_and_training` VARCHAR(14) NULL DEFAULT NULL , -- 09: Education.
  `employment` TEXT NULL DEFAULT NULL ,
  `affiliation` VARCHAR(300) NULL DEFAULT NULL ,
  `position_in_organisation` VARCHAR(300) NULL DEFAULT NULL ,
  `rank` TEXT NULL DEFAULT NULL ,
  `remarks` TEXT,
  PRIMARY KEY (`biographic_details_record_number`) ,
    FOREIGN KEY (`person` ) REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (`related_person` ) REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (`type_of_relationship`) REFERENCES mt_21_types_of_relationships(vocab_number),
    FOREIGN KEY (initial_date_type) REFERENCES mt_48_types_of_dates(vocab_number),
    FOREIGN KEY (final_date_type) REFERENCES mt_48_types_of_dates(vocab_number),
    FOREIGN KEY (education_and_training) REFERENCES mt_9_education(vocab_number)
    
)
ENGINE = InnoDB DEFAULT CHARSET=utf8;


CREATE  TABLE IF NOT EXISTS  `address` (
  `address_record_number` VARCHAR(45) NOT NULL ,
  `person` VARCHAR(45) NOT NULL ,
  `address_type` VARCHAR(14),  -- 40: Types of Addresses
  `address` TEXT NULL DEFAULT NULL ,
  `country` VARCHAR(200) NULL DEFAULT NULL ,
   phone VARCHAR(60) NULL DEFAULT NULL ,
   cellular VARCHAR(60) NULL DEFAULT NULL ,
   fax VARCHAR(60) NULL DEFAULT NULL ,
   email VARCHAR(100) NULL DEFAULT NULL ,
   web VARCHAR(200) NULL DEFAULT NULL ,  
  `start_date` DATE NULL DEFAULT NULL ,
  `end_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`address_record_number`, `person`) ,
    FOREIGN KEY (`person` ) REFERENCES  `person` (`person_record_number` )    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (address_type) REFERENCES mt_40_types_of_addresses(vocab_number)
    )
ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table  `act`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `act` (
  `act_record_number` VARCHAR(45) NOT NULL ,
  `event` VARCHAR(45) NOT NULL ,
  `victim` VARCHAR(45) NULL DEFAULT NULL ,
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
  `type_of_act` VARCHAR(14) NULL DEFAULT NULL ,  -- 04 types of Acts
  `initial_date` DATE NULL DEFAULT NULL ,
  `initial_date_type` VARCHAR(14),                  -- 48 types of dates
  `exact_location` TEXT NULL DEFAULT NULL ,
  `stated_reason` TEXT NULL DEFAULT NULL ,
  -- `05_*_method_of_violence` VARCHAR(60) NULL DEFAULT NULL , -- 05 method of Violence
  -- `28_*_attribution` VARCHAR(60) NULL DEFAULT NULL ,     -- 28 attribution
  `physical_consequences` TEXT NULL DEFAULT NULL ,
  `psychological_consequences` TEXT NULL DEFAULT NULL ,
  `age_at_time_of_victimisation` TEXT NULL DEFAULT NULL ,
  `final_date` DATE NULL DEFAULT NULL ,
  `final_date_type` VARCHAR(14) ,                           -- 48 types of dates
  `exact_location_at_end_of_act` TEXT NULL DEFAULT NULL ,
  `status_at_end_of_act` VARCHAR(14) NULL DEFAULT NULL ,    -- 25 Status as Victim
  `remarks` TEXT ,
  -- `23_*_victim_characteristics` VARCHAR(60) NULL DEFAULT NULL ,  -- 23 Relavant characteristics
  `type_of_location` VARCHAR(14) NULL DEFAULT NULL ,            -- 17 types of locations
  -- `62_*_national_legislation` VARCHAR(14) NULL DEFAULT NULL ,        -- 62 national legislation
  -- `06_*_international_instruments` VARCHAR(60) NULL DEFAULT NULL , -- 06 international instruments
  
   -- `supporting_documents` VARCHAR(60) NULL DEFAULT NULL ,
  `act_location_latitude` double  NULL DEFAULT NULL,
  `act_location_longitude` double NULL DEFAULT NULL,
  PRIMARY KEY (`act_record_number`, `event`) ,
  -- INDEX fk_Act_Event (`Event_event_record_number` ASC) ,
  -- INDEX fk_Act_Person (`victim_person_record_number` ASC) ,
  -- CONSTRAINT `fk_Act_Event`
    FOREIGN KEY (`event` )  REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_Act_Person`
    FOREIGN KEY (`victim` )    REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (type_of_act) REFERENCES mt_4_types_of_acts (vocab_number),
    FOREIGN KEY (initial_date_type) REFERENCES mt_48_types_of_dates (vocab_number),
    FOREIGN KEY (final_date_type) REFERENCES mt_48_types_of_dates (vocab_number),
    FOREIGN KEY (status_at_end_of_act) REFERENCES mt_25_status_as_victim (vocab_number),
    FOREIGN KEY (type_of_location) REFERENCES mt_17_types_of_locations (vocab_number)
    -- FOREIGN KEY (national_legislation) REFERENCES mt_6_international_instruments (vocab_number)
    )
    
    
ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Table  `arrest`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `arrest` (
  arrest_record_number VARCHAR(45) NOT NULL,
  -- `act_record_number` VARCHAR(45) NOT NULL ,
  `case_description` TEXT NULL DEFAULT NULL ,
  `type_of_detention` VARCHAR(14) NULL DEFAULT NULL ,
  `detention_conditions` TEXT NULL DEFAULT NULL ,
  -- `30_*_whereabouts_and_outside_contact_during_detention` VARCHAR(60) NULL DEFAULT NULL , -- 30_whereabouts_and_outside_contact_during_detention
  -- `31_*_legal_counsel` VARCHAR(14) NULL DEFAULT NULL ,    -- 31_legal_counsel
  -- `32_*_type_of_court` VARCHAR(14) NULL DEFAULT NULL ,    -- 32_type_of_court
  `type_of_language` VARCHAR(14)  ,  -- 33_types-of_language_used_in_court
  `court_case_code` VARCHAR(200) NULL DEFAULT NULL ,
  `court_case_name` VARCHAR(200) NULL DEFAULT NULL ,
  `judicial_district` VARCHAR(14) ,  -- 69_judicial_districts
  PRIMARY KEY (`arrest_record_number`) ,
  FOREIGN KEY (`arrest_record_number` ) REFERENCES  `act` (`act_record_number` )
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (type_of_detention) REFERENCES mt_29_types_of_detention(vocab_number),
  FOREIGN KEY (`type_of_language`) REFERENCES mt_33_types_of_language_used_in_court(vocab_number) ,  -- 33_types-of_language_used_in_court
  FOREIGN KEY (`judicial_district`) REFERENCES mt_69_judicial_districts(vocab_number)  -- 69_judicial_districts
    
)
ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table  `killing`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `killing` (
  killing_record_number VARCHAR(45) NOT NULL,
  -- `act_record_number` VARCHAR(45) NOT NULL ,
  `autopsy_results` VARCHAR(14) NULL DEFAULT NULL , -- 34_autopsy_results
  `death_certificate` VARCHAR(14) NULL DEFAULT NULL , -- 35: Death Certificate
  PRIMARY KEY (`killing_record_number`) ,
  FOREIGN KEY (`killing_record_number` ) REFERENCES  `act` (`act_record_number` )
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (autopsy_results) REFERENCES mt_34_autopsy_results(vocab_number),
  FOREIGN KEY (death_certificate) REFERENCES mt_35_death_certificate(vocab_number)
)
ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Table  `torture`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `torture` (
  `torture_record_number` VARCHAR(45) NOT NULL ,
  -- `act_record_number` VARCHAR(45) NOT NULL ,
  -- `36_*_statement_signed` VARCHAR(60) NULL DEFAULT NULL ,  -- 36: Statements Signed,
  -- `37_*_medical_attention` VARCHAR(60) NULL DEFAULT NULL ,          -- 37: Medical Attention
  -- `38_*_intent` VARCHAR(60) NULL DEFAULT NULL ,             -- 38: Intent.
  PRIMARY KEY (`torture_record_number`) ,
  FOREIGN KEY (`torture_record_number` ) REFERENCES  `act` (`act_record_number` )
  ON DELETE CASCADE
  ON UPDATE CASCADE
)
ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Table  `destruction`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `destruction` (
  destruction_record_number VARCHAR(45) NOT NULL ,
  -- `act_record_number` VARCHAR(45) NOT NULL ,
  `type_of_property_loss` TEXT NULL DEFAULT NULL ,
  `value_of_destroyed_property` DECIMAL(10,2) NULL DEFAULT NULL ,
  `compensation` VARCHAR(14) NULL DEFAULT NULL , -- 47 compensation
  PRIMARY KEY (`destruction_record_number`) ,
  FOREIGN KEY (`destruction_record_number` ) REFERENCES  `act` (`act_record_number` )
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (compensation) REFERENCES mt_47_compensation(vocab_number)
)
ENGINE = InnoDB DEFAULT CHARSET=utf8;





-- -----------------------------------------------------
-- Table  `chain_of_events`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `chain_of_events` (
  `chain_of_events_record_number` VARCHAR(45) NOT NULL ,
  `event` VARCHAR(45) NOT NULL ,
  `related_event` VARCHAR(45) NULL DEFAULT NULL ,
  `type_of_chain_of_events` VARCHAR(14) NULL DEFAULT NULL ,  -- 22: Types of Chain of Events
  remarks TEXT,
  PRIMARY KEY (`chain_of_events_record_number`) ,
  UNIQUE KEY `event` (`event`,`related_event`),
  KEY `related_event` (`related_event`),
  KEY `type_of_chain_of_events` (`type_of_chain_of_events`),
  FOREIGN KEY (`event` ) REFERENCES  `event` (`event_record_number` ) 
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (`related_event` ) REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (type_of_chain_of_events) REFERENCES mt_22_types_of_chain_of_events(vocab_number)
    
    )
ENGINE = InnoDB DEFAULT CHARSET=utf8;










-- -----------------------------------------------------
-- Table  `involvement`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `involvement` (
  `involvement_record_number` VARCHAR(45) NOT NULL ,
  `act` VARCHAR(45) NOT NULL ,
  `perpetrator` VARCHAR(45) NULL DEFAULT NULL ,
   event VARCHAR(45),
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
  `degree_of_involvement` VARCHAR(14) NULL DEFAULT NULL ,  -- 18: Degrees of Involvement.
  -- `24_*_type_of_perpetrator` VARCHAR(60) NULL DEFAULT NULL ,  -- 24: Types of Perpetrators
  `latest_status_as_perpetrator_in_the_act` VARCHAR(14) NULL DEFAULT NULL , -- 26: Status as Perpetrator
  remarks TEXT,
  PRIMARY KEY (`involvement_record_number`, `act`) ,
    FOREIGN KEY (`act` ) REFERENCES  `act` (`act_record_number` ) 
    ON DELETE CASCADE
    ON UPDATE CASCADE ,
    FOREIGN KEY (`perpetrator` ) REFERENCES  `person` (`person_record_number` ) 
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (`event` ) REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE    ,
    FOREIGN KEY (`degree_of_involvement` ) REFERENCES  mt_18_degrees_of_involvement (vocab_number ),
    FOREIGN KEY (latest_status_as_perpetrator_in_the_act) REFERENCES mt_26_status_as_perpetrator(vocab_number)
    )
    
ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table  `information`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `information` (
  `information_record_number` VARCHAR(45) NOT NULL ,
  `event` VARCHAR(45) NULL DEFAULT NULL,
  `source` VARCHAR(45) NULL DEFAULT NULL ,
  `related_person` VARCHAR(45) NULL DEFAULT NULL ,
  `related_event` VARCHAR(45) NULL DEFAULT NULL ,
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
  `source_connection_to_information` VARCHAR(14) NULL DEFAULT NULL , --  19: Source Connection to Information.
  -- `14_*_language_of_source_material` VARCHAR(60) NULL DEFAULT NULL ,    --  14: Languages
  `date_of_source_material` DATE NULL DEFAULT NULL ,
  -- `16_*_type_of_source_material` VARCHAR(60) NULL DEFAULT NULL ,        -- 16: Types of Source Material.
  -- `66_*_local_language_of_source_material` VARCHAR(60) NULL DEFAULT NULL , -- 66: Local Languages
  remarks TEXT,
  `reliability_of_information` VARCHAR(14) NULL DEFAULT NULL ,     -- 42: Reliability. T
  

  PRIMARY KEY (`information_record_number`) ,
    FOREIGN KEY (`event` ) REFERENCES  `event` (`event_record_number` ) 
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (`related_event` ) REFERENCES  `event` (`event_record_number` ) 
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (`source` ) REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (`related_person` ) REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (source_connection_to_information) REFERENCES mt_19_source_connection_to_information(vocab_number),
    FOREIGN KEY (reliability_of_information) REFERENCES mt_42_reliability(vocab_number)
)
ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table  `intervention`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `intervention` (
  `intervention_record_number` VARCHAR(45) NOT NULL ,
  `intervening_party` VARCHAR(45) NULL DEFAULT NULL ,  
  `event` VARCHAR(45) NULL DEFAULT NULL ,
  `victim` VARCHAR(45) NULL DEFAULT NULL ,
  `confidentiality` VARCHAR(1) NULL DEFAULT NULL ,
  -- `20_*_type_of_intervention` VARCHAR(60) NULL DEFAULT NULL , -- 20: Types of Intervention.
  `date_of_intervention` DATE NULL DEFAULT NULL ,
  `parties_requested` TEXT NULL DEFAULT NULL ,  
  `response` VARCHAR(14) NULL DEFAULT NULL ,               -- 27: Types of Responses.
  `impact_on_the_situation` VARCHAR(14) NULL DEFAULT NULL ,-- 44: Impact on the Situation
  remarks TEXT,
  `intervention_status` VARCHAR(14) NULL DEFAULT NULL ,      -- 45: Intervention Status 
  `priority` VARCHAR(14) NULL DEFAULT NULL ,                 -- 46: Priority,
  `intervention_location_latitude` double  NULL DEFAULT NULL,
  `intervention_location_longitude` double  NULL DEFAULT NULL,
  PRIMARY KEY (`intervention_record_number`) ,  
    FOREIGN KEY (`event` ) REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (`victim` ) REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    -- FOREIGN KEY (`intervening_by` ) REFERENCES  `person` (`person_record_number` ),
    FOREIGN KEY (`intervening_party` ) REFERENCES  `person` (`person_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
    FOREIGN KEY (response) REFERENCES mt_27_types_of_responses(vocab_number),
    FOREIGN KEY (impact_on_the_situation) REFERENCES mt_44_impact_on_the_situation(vocab_number),
    FOREIGN KEY (intervention_status) REFERENCES mt_45_intervention_status(vocab_number),
    FOREIGN KEY (priority) REFERENCES mt_46_priority(vocab_number)
    
    )
ENGINE = InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS  `management` (
  `entity_type` VARCHAR(100) NOT NULL ,
  `entity_id` VARCHAR(45) NOT NULL ,
  `date_received` DATE NULL ,
  `date_of_entry` DATE NULL DEFAULT NULL ,
  `entered_by` VARCHAR(60) NULL DEFAULT NULL ,
  `project_title` TEXT NULL ,
  `comments` TEXT NULL DEFAULT NULL ,
  -- `supporting_documents` TEXT NULL ,
  -- `files` TEXT NULL ,
  `record_grouping` VARCHAR(45) NULL ,
  `date_updated` DATE NULL DEFAULT NULL ,
  `updated_by` VARCHAR(50) NULL DEFAULT NULL ,
  `monitoring_status` VARCHAR(14) NULL ,
  -- `remarks` VARCHAR(60) NULL DEFAULT NULL ,
  PRIMARY KEY (`entity_type`, `entity_id`),
  FOREIGN KEY (monitoring_status) REFERENCES mt_43_monitoring_status(vocab_number) 
  
  )ENGINE = InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS supporting_docs(
    doc_id varchar(45) NOT NULL,
    uri varchar(255) NOT NULL,    
    PRIMARY KEY(doc_id)
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS supporting_docs_meta(
    doc_id varchar(45) NOT NULL,
    title varchar(255) NOT NULL,
    creator varchar(255) ,
    description TEXT ,
    datecreated DATE,
    datesubmitted DATE,
    format varchar(64),
    type varchar(14),	
    language varchar(64),
    subject varchar(64),
    PRIMARY KEY (doc_id),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (type) REFERENCES mt_16_types_of_source_material(vocab_number)
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS supporting_docs_links(
    doc_id varchar(45) NOT NULL,
    entity_id VARCHAR(45) NOT NULL,
    PRIMARY KEY (doc_id, entity_id)
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS event_doc(
    doc_id varchar(45),
    record_number   varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES event(event_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE

)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS picture_doc(
    doc_id varchar(45),
    record_number varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES person( person_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS person_doc(
    doc_id varchar(45),
    record_number varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES person( person_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS act_doc(
    doc_id varchar(45),
    record_number varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,    
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES act( act_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS information_doc(
    doc_id varchar(45),
    record_number varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,    
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES information( information_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS involvement_doc(
    doc_id varchar(45),
    record_number varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,    
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES involvement( involvement_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS intervention_doc(
    doc_id varchar(45),
    record_number varchar(45),
    linked_by varchar(45),
    linked_date TIMESTAMP,
    PRIMARY KEY (doc_id, record_number ),
    FOREIGN KEY (doc_id) REFERENCES supporting_docs(doc_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (record_number) REFERENCES intervention( intervention_record_number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- ---------------------------------------
-- Multivalue Attributes of ACT


CREATE  TABLE IF NOT EXISTS  `mlt_act_method_of_violence` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_5_methods_of_violence` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
    FOREIGN KEY (`record_number` )
    REFERENCES  `act` (`act_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_act_attribution` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_28_attribution` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
    FOREIGN KEY (`record_number` )
    REFERENCES  `act` (`act_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_act_victim_characteristics` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_23_relavant_characteristics` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
    FOREIGN KEY (`record_number` )
    REFERENCES  `act` (`act_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;



    
    

CREATE  TABLE IF NOT EXISTS  `mlt_act_international_instruments` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_6_international_instruments` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
    FOREIGN KEY (`record_number` )
    REFERENCES  `act` (`act_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE  TABLE IF NOT EXISTS  `mlt_act_national_legislation` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_62_national_legislation` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
    FOREIGN KEY (`record_number` )
    REFERENCES  `act` (`act_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- -----------------------------
-- Multivalue attributes of Event


-- -----------------------------------------------------
-- Table  `mlt_event_geographical_term`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_geographical_term` (
  vocab_number VARCHAR(14) NOT NULL ,
  record_number VARCHAR(45) NOT NULL ,
  PRIMARY KEY (vocab_number, record_number) ,
  -- INDEX fk_15_geographical_terms_has_Event_15_geographical_terms (`vocab_number` ASC) ,
  -- INDEX fk_15_geographical_terms_has_Event_Event (`record_number` ASC) ,
  -- CONSTRAINT `fk_15_geographical_terms_has_Event_15_geographical_terms`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_15_geographical_terms` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_15_geographical_terms_has_Event_Event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table  `mlt_event_violation_index`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_violation_index` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
  -- INDEX fk_2_violation_typology_terms_has_Event_2_violation_typology_terms (`vocab_number` ASC) ,
  -- INDEX fk_2_violation_typology_terms_has_Event_Event (`record_number` ASC) ,
  -- CONSTRAINT `fk_2_violation_typology_terms_has_Event_2_violation_typology_terms`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_2_violation_typology_terms` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_2_violation_typology_terms_has_Event_Event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table  `mlt_event_rights_affected`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_rights_affected` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
  -- INDEX fk_mt_3_rights_typology_has_event_mt_3_rights_typology (`vocab_number` ASC) ,
  -- INDEX fk_mt_3_rights_typology_has_event_event (`record_number` ASC) ,
  -- CONSTRAINT `fk_mt_3_rights_typology_has_event_mt_3_rights_typology`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_3_rights_typology` (`vocab_number`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_mt_3_rights_typology_has_event_event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table  `mlt_event_index_terms`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_huridocs_index_terms` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
  -- INDEX fk_mt_1_index_terms_has_event_mt_1_index_terms (`vocab_number` ASC) ,
  -- INDEX fk_mt_1_index_terms_has_event_event (`record_number` ASC) ,
  -- CONSTRAINT `fk_mt_1_index_terms_has_event_mt_1_index_terms`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_1_index_term` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_mt_1_index_terms_has_event_event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;




-- -----------------------------------------------------
-- Table  `mlt_event_local_index`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_local_index` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,
  -- INDEX fk_mt_61_local_index_has_event_mt_61_local_index (`vocab_number` ASC) ,
  -- INDEX fk_mt_61_local_index_has_event_event (`record_number` ASC) ,
  -- CONSTRAINT `fk_mt_61_local_index_has_event_mt_61_local_index`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_61_local_index` (`vocab_number`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_mt_61_local_index_has_event_event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table  `mlt_event_supporting_documents`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_supporting_documents` (
  `record_number` VARCHAR(45) NOT NULL ,
  `document_number` VARCHAR(45) NOT NULL ,
  `document` MEDIUMBLOB NULL ,
  PRIMARY KEY (`record_number`, `document_number`) ,
  -- INDEX fk_mlt_event_supporting_documents_event (`record_number` ASC) ,
  -- CONSTRAINT `fk_mlt_event_supporting_documents_event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table  `mlt_event_files`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_files` (
  `record_number` VARCHAR(45) NOT NULL ,
  `filename` VARCHAR(255) NOT NULL ,
  `file` MEDIUMBLOB NULL ,
  PRIMARY KEY (`record_number`, `filename`) ,
  -- INDEX fk_files_has_event_event (`record_number` ASC) ,
  -- CONSTRAINT `fk_files_has_event_event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table  `mlt_event_local_geographical_area`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_local_geographical_area` (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`record_number`, `vocab_number`) ,
  -- INDEX fk_Event_has_63_local_geographical_area_terms_Event (`record_number` ASC) ,
  -- INDEX fk_Event_has_63_local_geographical_area_terms_63_local_geographical_area_terms (`vocab_number` ASC) ,
  -- CONSTRAINT `fk_Event_has_63_local_geographical_area_terms_Event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_Event_has_63_local_geographical_area_terms_63_local_geographical_area_terms`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_63_local_geographical_area` (`vocab_number` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table  `mlt_event_other_thesaurus`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS  `mlt_event_other_thesaurus` (
  `record_number` VARCHAR(45) NOT NULL ,
  `vocab_number` VARCHAR(14) NOT NULL ,
  PRIMARY KEY (`record_number`, `vocab_number`) ,
  -- INDEX fk_event_has_mt_68_other_thesaurus_event (`record_number` ASC) ,
  -- INDEX fk_event_has_mt_68_other_thesaurus_mt_68_other_thesaurus (`vocab_number` ASC) ,
  -- CONSTRAINT `fk_event_has_mt_68_other_thesaurus_event`
    FOREIGN KEY (`record_number` )
    REFERENCES  `event` (`event_record_number` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  -- CONSTRAINT `fk_event_has_mt_68_other_thesaurus_mt_68_other_thesaurus`
    FOREIGN KEY (`vocab_number` )
    REFERENCES  `mt_68_other_thesaurus` (`vocab_number`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------


-- Multivalue attributes of PERSON



CREATE  TABLE IF NOT EXISTS  `mlt_person_occupation`  (
  `vocab_number` VARCHAR(14) NOT NULL, 
  `record_number` VARCHAR(45) NOT NULL,  
  PRIMARY KEY (`vocab_number`, `record_number`),     
  FOREIGN KEY (vocab_number) REFERENCES  `mt_10_occupation` (`vocab_number` )
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number` ) REFERENCES  `person` (`person_record_number` ) 
  ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_person_local_term_for_occupation`  (
  `vocab_number` VARCHAR(14) NOT NULL,
  `record_number` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`vocab_number`, `record_number`),     
  FOREIGN KEY (vocab_number)  REFERENCES  `mt_64_local_terms_for_occupations` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_person_physical_description`  (
  `vocab_number` VARCHAR(14) NOT NULL,
  `record_number` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`vocab_number`, `record_number`)   ,
  FOREIGN KEY (vocab_number)  REFERENCES  `mt_11_physical_descriptors` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE 
  
)ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE  TABLE IF NOT EXISTS  `mlt_person_citizenship`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`)   ,  
  FOREIGN KEY (vocab_number) REFERENCES  `mt_15_geographical_terms` (`vocab_number` )   ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE  TABLE IF NOT EXISTS  `mlt_person_ethnic_background`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) ,
   PRIMARY KEY (`vocab_number`, `record_number`),
  FOREIGN KEY (vocab_number) REFERENCES  `mt_13_ethnic_groups` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE 
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_person_other_background`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) ,
  PRIMARY KEY (`vocab_number`, `record_number`)  ,   
  FOREIGN KEY (vocab_number) REFERENCES  `mt_65_origins` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE 
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_person_general_characteristics`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,    
  FOREIGN KEY (vocab_number) REFERENCES  `mt_23_relavant_characteristics` (`vocab_number` )   ON DELETE RESTRICT
  ON UPDATE CASCADE,
 FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_person_language`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`),     
  FOREIGN KEY (vocab_number) REFERENCES  `mt_14_languages` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
 FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;





CREATE  TABLE IF NOT EXISTS  `mlt_person_local_language`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) ,    
  FOREIGN KEY (vocab_number) REFERENCES  `mt_66_local_languages` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE  TABLE IF NOT EXISTS  `mlt_person_national_origin`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`vocab_number`, `record_number`),     
  FOREIGN KEY (vocab_number) REFERENCES  `mt_15_geographical_terms` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE,
  FOREIGN KEY (`record_number`)  REFERENCES  `person` (`person_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------

-- Multivalue attribues of Arrest



CREATE  TABLE IF NOT EXISTS  `mlt_arrest_whereabouts`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) , 
  FOREIGN KEY (record_number) REFERENCES  `arrest` (`arrest_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_30_whereabouts` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE
  
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_arrest_legal_counsel`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`), 
  FOREIGN KEY (record_number) REFERENCES  `arrest` (`arrest_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE ,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_31_legal_counsel` (`vocab_number` )      ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE  TABLE IF NOT EXISTS  `mlt_arrest_type_of_court`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`) , 
  FOREIGN KEY (record_number) REFERENCES  `arrest` (`arrest_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_32_types_of_courts` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE   
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_arrest_type_of_language`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`)   , 
  FOREIGN KEY (record_number) REFERENCES  `arrest` (`arrest_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number)  REFERENCES  `mt_33_types_of_language_used_in_court` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------

-- Multivalue attribues of Torture


CREATE  TABLE IF NOT EXISTS  `mlt_torture_statements_signed`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL  ,
  PRIMARY KEY (`vocab_number`, `record_number`) , 
  FOREIGN KEY (record_number) REFERENCES  `torture` (`torture_record_number` ) ON DELETE CASCADE
  ON UPDATE CASCADE ,
  FOREIGN KEY (vocab_number)  REFERENCES  `mt_36_statements_signed` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_torture_medical_attention`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`), 
  FOREIGN KEY (record_number) REFERENCES  `torture` (`torture_record_number` )    ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number)  REFERENCES  `mt_37_medical_attention` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_torture_intent`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`vocab_number`, `record_number`)   , 
  FOREIGN KEY (record_number) REFERENCES  `torture` (`torture_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_38_intent` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------------------------

-- Multivalue attributes for  INVOLVEMENT


CREATE  TABLE IF NOT EXISTS  `mlt_involvement_type_of_perpetrator`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL  ,
  PRIMARY KEY (`vocab_number`, `record_number`), 
  FOREIGN KEY (record_number) REFERENCES  `involvement` (`involvement_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_24_types_of_perpetrators` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------------------------

-- Multivalue attributes for  INFORMATION



CREATE  TABLE IF NOT EXISTS  `mlt_information_language_of_source_material`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL  ,
  
  PRIMARY KEY (`vocab_number`, `record_number`)    , 
  FOREIGN KEY (record_number) REFERENCES  `information` (`information_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_14_languages` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE  TABLE IF NOT EXISTS  `mlt_information_type_of_source_material`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL  ,
  PRIMARY KEY (`vocab_number`, `record_number`)    , 
  FOREIGN KEY (record_number) REFERENCES  `information` (`information_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_16_types_of_source_material` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE  TABLE IF NOT EXISTS  `mlt_information_local_language_of_source_material`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL  ,

  PRIMARY KEY (`vocab_number`, `record_number`)    , 
  FOREIGN KEY (record_number) REFERENCES  `information` (`information_record_number` )  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_66_local_languages` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------------------------

-- Multivalue attributes for  INTERVENTION



CREATE  TABLE IF NOT EXISTS  `mlt_intervention_type_of_intervention`  (
  `vocab_number` VARCHAR(14) NOT NULL ,
  `record_number` VARCHAR(45) NOT NULL ,

  PRIMARY KEY (`vocab_number`, `record_number`)    , 
  FOREIGN KEY (record_number) REFERENCES  `intervention` (`intervention_record_number` )   ON DELETE CASCADE
  ON UPDATE CASCADE ,
  FOREIGN KEY (vocab_number) REFERENCES  `mt_20_types_of_intervention` (`vocab_number` )  ON DELETE RESTRICT
  ON UPDATE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS audit_log;

CREATE TABLE IF NOT EXISTS audit_log(
    log_record_number VARCHAR(45),
    module varchar(20),
    module_record_number varchar(45),
    `timestamp` timestamp ,
    entity VARCHAR(30) NOT NULL,
    record_number VARCHAR(45) NOT NULL,
    action VARCHAR(50),
    description VARCHAR(100),
    query VARCHAR(200),
    username VARCHAR(255),
    PRIMARY KEY ( log_record_number )

);

CREATE TABLE IF NOT EXISTS clari_notes(
    clari_notes_record_number VARCHAR(45),
    entity VARCHAR(30) NOT NULL,
    record_number VARCHAR(45) NOT NULL,
    field_name VARCHAR(50),
    vocab_number VARCHAR(14),
    value TEXT,
    PRIMARY KEY (entity , record_number, field_name , vocab_number )

)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS save_query(
    save_query_record_number VARCHAR(45),
    name VARCHAR(500),
    query_type VARCHAR(30),
    description TEXT,
    created_date DATE,
    created_by VARCHAR(60),
    query TEXT,
    PRIMARY KEY (save_query_record_number)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS sec_entity(
    entity VARCHAR(20),
    PRIMARY KEY ( entity)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sec_entity_entities(
    entity_key INT NOT NULL AUTO_INCREMENT,
    sec_entity VARCHAR(20),
    entity   VARCHAR(20),
    PRIMARY KEY (entity_key, sec_entity, entity),
    FOREIGN KEY (sec_entity) REFERENCES  `sec_entity` (`entity` )  ON DELETE CASCADE,
    UNIQUE KEY  (`sec_entity`,`entity`)

)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sec_entity_fields(
    entity_key INT NOT NULL,
    field_name VARCHAR(100),
    field_option VARCHAR(50), 
    field_value VARCHAR(50),
    PRIMARY KEY ( entity_key , field_name , field_option ),
    FOREIGN KEY (entity_key) REFERENCES  `sec_entity_entities` (`entity_key` )  ON DELETE CASCADE
    
    
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS import_log_report (
`file_name` VARCHAR( 255 ) ,
`file_path` VARCHAR( 255 ) ,
`date` DATE NOT NULL ,
`time` TIME NOT NULL,
`status` varchar(50) NOT NULL,
`export_instance` varchar(50) NOT NULL,
`export_date` date NOT NULL,
`export_time` time NOT NULL,
PRIMARY KEY (`time`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `sec_entity` (`entity`) VALUES
('intervening_party'),
('perpetrator'),
('source'),
('victim');


INSERT INTO `sec_entity_entities` (`entity_key`, `sec_entity`, `entity`) VALUES
(8, 'intervening_party', 'intervention'),
(7, 'intervening_party', 'person'),
(6, 'perpetrator', 'involvement'),
(5, 'perpetrator', 'person'),
(4, 'source', 'information'),
(3, 'source', 'person'),
(2, 'victim', 'act'),
(1, 'victim', 'person');


INSERT INTO `sec_entity_fields` (`entity_key`, `field_name`, `field_option`) VALUES
(1, 'counting_unit', 'search_view'),
(1, 'person_name', 'search'),
(1, 'person_name', 'search_view'),
(1, 'person_record_number', 'search'),
(1, 'person_record_number', 'search_view'),
(2, 'act_record_number', 'search'),
(2, 'act_record_number', 'search_view'),
(2, 'exact_location', 'search_view'),
(2, 'type_of_act', 'search'),
(2, 'type_of_act', 'search_view'),
(3, 'person_name', 'search'),
(3, 'person_name', 'search_view'),
(3, 'person_record_number', 'search'),
(4, 'information_record_number', 'search'),
(4, 'information_record_number', 'search_view'),
(4, 'source_connection_to_information', 'search'),
(4, 'source_connection_to_information', 'search_view'),
(5, 'person_name', 'search'),
(5, 'person_name', 'search_view'),
(5, 'person_record_number', 'search'),
(5, 'person_record_number', 'search_view'),
(6, 'degree_of_involvement', 'search'),
(6, 'degree_of_involvement', 'search_view'),
(6, 'involvement_record_number', 'search'),
(6, 'involvement_record_number', 'search_view'),
(6, 'latest_status_as_perpetrator_in_the_act', 'search'),
(6, 'latest_status_as_perpetrator_in_the_act', 'search_view'),
(6, 'type_of_perpetrator', 'search'),
(6, 'type_of_perpetrator', 'search_view'),
(7, 'counting_unit', 'search_view'),
(7, 'person_name', 'search'),
(7, 'person_name', 'search_view'),
(7, 'person_record_number', 'search'),
(7, 'person_record_number', 'search_view'),
(8, 'date_of_intervention', 'search'),
(8, 'date_of_intervention', 'search_view'),
(8, 'intervention_record_number', 'search'),
(8, 'intervention_record_number', 'search_view'),
(8, 'type_of_intervention', 'search'),
(8, 'type_of_intervention', 'search_view'),
(8, 'victim', 'search'),
(8, 'victim', 'search_view');

