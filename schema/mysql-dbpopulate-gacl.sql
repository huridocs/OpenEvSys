-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2009 at 10:48 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6-2ubuntu4.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `openevsys`
--

--
-- Dumping data for table `gacl_acl`
--

INSERT INTO `gacl_acl` (`id`, `section_value`, `allow`, `enabled`, `return_value`, `note`, `updated_date`) VALUES(10, 'user', 1, 1, '', '', 1235376077);
INSERT INTO `gacl_acl` (`id`, `section_value`, `allow`, `enabled`, `return_value`, `note`, `updated_date`) VALUES(11, 'User', 1, 1, NULL, NULL, 1235564875);
INSERT INTO `gacl_acl` (`id`, `section_value`, `allow`, `enabled`, `return_value`, `note`, `updated_date`) VALUES(12, 'User', 1, 1, NULL, NULL, 1235564875);
INSERT INTO `gacl_acl` (`id`, `section_value`, `allow`, `enabled`, `return_value`, `note`, `updated_date`) VALUES(13, 'user', 1, 1, '', '', 1235564967);

--
-- Dumping data for table `gacl_acl_sections`
--

INSERT INTO `gacl_acl_sections` (`id`, `value`, `order_value`, `name`, `hidden`) VALUES(1, 'system', 1, 'System', 0);
INSERT INTO `gacl_acl_sections` (`id`, `value`, `order_value`, `name`, `hidden`) VALUES(2, 'user', 2, 'User', 0);

--
-- Dumping data for table `gacl_acl_seq`
--

INSERT INTO `gacl_acl_seq` (`id`) VALUES(13);

--
-- Dumping data for table `gacl_aco`
--

INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(10, 'modules', 'admin', 1, 'Admin', 0);
INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(11, 'modules', 'analysis', 2, 'Analysis', 0);
INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(12, 'modules', 'events', 3, 'Events', 0);
INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(13, 'modules', 'person', 4, 'Persons', 0);
INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(14, 'modules', 'docu', 5, 'Documents', 0);
INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(15, 'modules', 'home', 6, 'Home', 0);
INSERT INTO `gacl_aco` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(16, 'modules', 'help', 7, 'Help', 0);

--
-- Dumping data for table `gacl_aco_map`
--

INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'admin');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'analysis');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'docu');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'events');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'help');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'home');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(10, 'modules', 'person');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(11, 'modules', 'analysis');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(11, 'modules', 'docu');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(11, 'modules', 'events');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(11, 'modules', 'person');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(12, 'modules', 'docu');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(12, 'modules', 'events');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(12, 'modules', 'person');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(13, 'modules', 'help');
INSERT INTO `gacl_aco_map` (`acl_id`, `section_value`, `value`) VALUES(13, 'modules', 'home');

--
-- Dumping data for table `gacl_aco_sections`
--

INSERT INTO `gacl_aco_sections` (`id`, `value`, `order_value`, `name`, `hidden`) VALUES(10, 'modules', 1, 'Modules', 0);

--
-- Dumping data for table `gacl_aco_sections_seq`
--

INSERT INTO `gacl_aco_sections_seq` (`id`) VALUES(10);

--
-- Dumping data for table `gacl_aco_seq`
--

INSERT INTO `gacl_aco_seq` (`id`) VALUES(16);

--
-- Dumping data for table `gacl_aro`
--

INSERT INTO `gacl_aro` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES(10, 'users', 'admin', 1, 'admin', 0);

--
-- Dumping data for table `gacl_aro_groups`
--

INSERT INTO `gacl_aro_groups` (`id`, `parent_id`, `lft`, `rgt`, `name`, `value`) VALUES(10, 0, 1, 8, 'OpenEvsysUser', 'openevsys_user');
INSERT INTO `gacl_aro_groups` (`id`, `parent_id`, `lft`, `rgt`, `name`, `value`) VALUES(11, 10, 2, 3, 'Admin', 'admin');
INSERT INTO `gacl_aro_groups` (`id`, `parent_id`, `lft`, `rgt`, `name`, `value`) VALUES(12, 10, 4, 5, 'Analyst', 'analyst');
INSERT INTO `gacl_aro_groups` (`id`, `parent_id`, `lft`, `rgt`, `name`, `value`) VALUES(13, 10, 6, 7, 'Data Entry', 'data_entry');

--
-- Dumping data for table `gacl_aro_groups_id_seq`
--

INSERT INTO `gacl_aro_groups_id_seq` (`id`) VALUES(13);

--
-- Dumping data for table `gacl_aro_groups_map`
--

INSERT INTO `gacl_aro_groups_map` (`acl_id`, `group_id`) VALUES(10, 11);
INSERT INTO `gacl_aro_groups_map` (`acl_id`, `group_id`) VALUES(11, 12);
INSERT INTO `gacl_aro_groups_map` (`acl_id`, `group_id`) VALUES(12, 13);
INSERT INTO `gacl_aro_groups_map` (`acl_id`, `group_id`) VALUES(13, 10);

--
-- Dumping data for table `gacl_aro_map`
--


--
-- Dumping data for table `gacl_aro_sections`
--

INSERT INTO `gacl_aro_sections` (`id`, `value`, `order_value`, `name`, `hidden`) VALUES(10, 'users', 1, 'Users', 0);

--
-- Dumping data for table `gacl_aro_sections_seq`
--

INSERT INTO `gacl_aro_sections_seq` (`id`) VALUES(10);

--
-- Dumping data for table `gacl_aro_seq`
--

INSERT INTO `gacl_aro_seq` (`id`) VALUES(11);

--
-- Dumping data for table `gacl_axo`
--


--
-- Dumping data for table `gacl_axo_groups`
--


--
-- Dumping data for table `gacl_axo_groups_map`
--


--
-- Dumping data for table `gacl_axo_map`
--


--
-- Dumping data for table `gacl_axo_sections`
--


--
-- Dumping data for table `gacl_groups_aro_map`
--

INSERT INTO `gacl_groups_aro_map` (`group_id`, `aro_id`) VALUES(11, 10);

--
-- Dumping data for table `gacl_groups_axo_map`
--


--
-- Dumping data for table `gacl_phpgacl`
--

