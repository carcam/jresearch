-- File: install.sql
-- Installation SQL routine for component JResearch
-- Author: Luis Galarraga
-- Date: 27-05-2008 00:14:00


DROP TABLE IF EXISTS `#__jresearch_citing_style`;
CREATE TABLE IF NOT EXISTS `#__jresearch_citing_style` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `#__jresearch_financier`;
CREATE TABLE IF NOT EXISTS `#__jresearch_financier` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `published` tinyint(4) NOT NULL default '1',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `#__jresearch_pthesis`;
CREATE TABLE IF NOT EXISTS `#__jresearch_pthesis` (
  `id_publication` int(10) unsigned NOT NULL,
  `school` varchar(255) NOT NULL,
  `type` ENUM('phd', 'masters', 'diploma', 'bsc') default 'bsc',
  `address` varchar(255) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `#__jresearch_project`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `published` tinyint(4) NOT NULL default '1',
  `url` varchar(255) default NULL,
  `files` text default NULL,
  `status` enum('not_started','in_progress','finished') NOT NULL default 'not_started',
  `start_date` date default NULL,
  `end_date` date default NULL,
  `url_project_image` varchar(255) default NULL,
  `description` text,
  `finance_value` decimal(12,2) default NULL,
  `finance_currency` varchar(5) default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `hits` int(10) default 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  INDEX `id_research_area` (`id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_project_external_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_external_author` (
  `id_project` int(10) unsigned NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `is_principal` tinyint(4) NOT NULL default '0',
  `order` smallint(5) unsigned NOT NULL,
  `author_email` varchar(60) NOT NULL,
  PRIMARY KEY  (`id_project`,`author_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project_financier`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_financier` (
  `id_project` int(10) unsigned NOT NULL,
  `id_financier` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`id_financier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project_cooperation`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_cooperation` (
  `id_project` int(10) unsigned NOT NULL,
  `id_cooperation` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`id_cooperation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project_internal_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_internal_author` (
  `id_project` int(10) unsigned NOT NULL,
  `id_staff_member` int(10) unsigned NOT NULL,
  `is_principal` tinyint(4) NOT NULL default '0',
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`id_staff_member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_publication`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `alias` varchar(255) NOT NULL,
  `comments` text,
  `journal_acceptance_rate` float unsigned default NULL,
  `impact_factor` float unsigned default NULL,
  `pubtype` varchar(20) NOT NULL default 'book',
  `awards` text,
  `url` varchar(255) default NULL,
  `cover` varchar(255) default NULL,
  `files` text default NULL,
  `published` tinyint(4) NOT NULL default '1' ,
  `title` varchar(255) NOT NULL,
  `doi` varchar(255) default NULL,
  `year` SMALLINT( 4 ) UNSIGNED NULL DEFAULT NULL ,	
  `citekey` varchar(255) NOT NULL,
  `abstract` text,
  `note` text,
  `internal` tinyint(4) NOT NULL default '1',
  `keywords` varchar(255) default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `hits` int(10) default 0,
  `id_language` int(10) default NULL,
  `id_country` int(10) default NULL,
  `status` enum('in_progress','finished','protocol') NOT NULL default 'in_progress',
  `recommended` BOOL default false,
  `month` varchar(20) default NULL,
  `original_title` varchar(255) default NULL,
  `headings` text default NULL,
  `npages` int(10) default NULL,
  `nimages` int(10) default NULL,
  `source` enum('ORW','WSO') NOT NULL default 'ORW',
  `hidden` BOOL default false,
  `issued` BOOL default false,
  `original_abstract` text default NULL,
  `issn` varchar(32) default NULL,
  `journal` varchar(255) NOT NULL,
  `number` varchar(10) default NULL,
  `pages` varchar(20) default NULL,
  `crossref` varchar(255) default NULL,
  `isbn` varchar(32) default NULL,	
  `publisher` varchar(60) NOT NULL,
  `editor` varchar(255) NOT NULL,
  `volume` varchar(30) default NULL,
  `series` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `edition` varchar(10) default NULL,
  `howpublished` varchar(255) default NULL,
  `booktitle` varchar(255) default NULL,
  `organization` varchar(255) default NULL,
  `chapter` varchar(10) default NULL,
  `type` ENUM('phd', 'masters', 'diploma', 'bsc') default 'bsc',
  `key` varchar(255) default NULL,
  `patent_number` varchar(10) NOT NULL,
  `filing_date` date DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `claims` longtext DEFAULT NULL,
  `drawings_dir` varchar(255) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `school` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `day` varchar(2) default NULL,
  `extra` text default NULL,
  `online_source_type` enum('website', 'video', 'audio', 'image', 'blog') NOT NULL default 'website',
  `digital_source_type` enum('cdrom', 'film') NOT NULL default 'cdrom',
  `access_date` date default NULL,  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `citekey` (`citekey`),
  INDEX `year` (`year`),
  INDEX `pubtype` (`pubtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_publication_comment`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_publication` int(10) unsigned NOT NULL,
  `subject` varchar(255) default NULL,
  `content` text NOT NULL,
  `datetime` datetime NOT NULL,
  `author` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_publication_config_custom_citing_style`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_config_custom_citing_style` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `publication_type` varchar(20) NOT NULL,
  `cite_format` text,
  `complete_reference_format` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `publication_type` (`publication_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_publication_external_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_external_author` (
  `id_publication` int(10) unsigned NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `author_email` varchar(60) NOT NULL,
  PRIMARY KEY  (`id_publication`,`author_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_publication_internal_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_internal_author` (
  `id_publication` int(10) unsigned NOT NULL,
  `id_staff_member` int(10) unsigned NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY  (`id_publication`,`id_staff_member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_research_area`;
CREATE TABLE IF NOT EXISTS `#__jresearch_research_area` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `published` tinyint(4) NOT NULL default '1',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_member`;
CREATE TABLE IF NOT EXISTS `#__jresearch_member` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `former_member` tinyint(1) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(255) NULL,
  `username` varchar(150) NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `position` int(10) unsigned default '0',
  `location` varchar(50) default NULL,
  `url_personal_page` varchar(255) default NULL,
  `published` tinyint(4) NOT NULL default '1',
  `ordering` int(11) unsigned NOT NULL default '0',
  `phone_or_fax` varchar(15) default NULL,
  `url_photo` varchar(255) default NULL,
  `description` text,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  INDEX `name` (`lastname`,`firstname`),
  INDEX `id_research_area` (`id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_member_position`;
CREATE TABLE IF NOT EXISTS `#__jresearch_member_position` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `position` varchar(50) NOT NULL,
  `published` tinyint(4) NOT NULL default '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `#__jresearch_team`;
CREATE TABLE IF NOT EXISTS `#__jresearch_team` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent` int(11) unsigned default NULL,
  `id_leader` int(11) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `published` tinyint(4) NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_team_member`;
CREATE TABLE IF NOT EXISTS `#__jresearch_team_member` (
  `id_team` int(11) unsigned NOT NULL auto_increment,
  `id_member` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id_team`, `id_member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_thesis`;
CREATE TABLE IF NOT EXISTS `#__jresearch_thesis` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `degree` enum('bachelor','master','phd') NOT NULL default 'bachelor',
  `status` enum('not_started','in_progress','finished') NOT NULL default 'not_started',
  `start_date` date default NULL,
  `end_date` date default NULL,
  `published` tinyint(4) NOT NULL default '1',
  `description` text,
  `url` varchar(255) default NULL,
  `files` text default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `hits` int(10) default 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  INDEX `id_research_area` (`id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_thesis_external_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_thesis_external_author` (
  `id_thesis` int(10) unsigned NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `is_director` tinyint(4) NOT NULL default '0',
  `author_email` varchar(60) NOT NULL,
  PRIMARY KEY  (`id_thesis`,`author_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_thesis_internal_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_thesis_internal_author` (
  `id_thesis` int(10) unsigned NOT NULL,
  `id_staff_member` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `is_director` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_thesis`,`id_staff_member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__jresearch_cited_records`;
CREATE TABLE `#__jresearch_cited_records` (
	`id_record` INT UNSIGNED NOT NULL ,
	`record_type` VARCHAR( 60 ) NOT NULL ,
	`citekey` VARCHAR( 255 ) NOT NULL ,
	PRIMARY KEY ( `id_record` , `record_type`, `citekey` )
) ENGINE = MYISAM DEFAULT CHARSET=utf8; 

DROP TABLE IF EXISTS `#__jresearch_property`;
CREATE TABLE `#__jresearch_property` (
	`name` VARCHAR( 40 ) NOT NULL ,
	PRIMARY KEY ( `name` )
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_cooperations`;
CREATE TABLE IF NOT EXISTS `#__jresearch_cooperations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `description` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) unsigned NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_facilities`;
CREATE TABLE IF NOT EXISTS `#__jresearch_facilities` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `alias` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `description` text NOT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) unsigned NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `#__jresearch_language`;
CREATE TABLE IF NOT EXISTS `#__jresearch_language` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) default NULL,
  `native_name` varchar(30) NOT NULL,
  `isocode` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_country`;
CREATE TABLE IF NOT EXISTS `#__jresearch_country` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) default NULL,
  `land_acronym` varchar(10) default NULL,
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (17, 'Czech', 'česky', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (16, 'Danish', 'dansk', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (15, 'Dutch', 'Nederlands', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (1, 'English', 'English', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (6, 'French', 'Français', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (2, 'German', 'Deutsch', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (10, 'Italian', 'Italiano', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (11, 'Japanese', '日本語 (にほんご／にっぽんご)', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (18, 'Norwegian', 'Norsk bokmål', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (19, 'Finnish', 'suomen kieli', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (12, 'Russian', 'русский язык', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (14, 'Serbo-Croatian', 'hrvatski', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (13, 'Spanish', 'Español', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (5, 'Swedish', 'svenska', '');
INSERT INTO `#__jresearch_language` (`id`, `name`, `native_name`, `isocode`) VALUES (4, 'Ukraine', 'Українська', '');

INSERT INTO `#__jresearch_country` (`id`, `name`, `land_acronym`) VALUES
(34, 'United Kingdom', 'UK'),
(3, 'Germany', 'DE'),
(7, 'Belgium', NULL),
(6, 'Sweden', NULL),
(5, 'United States of America', 'US'),
(4, 'Ukraine', NULL),
(8, 'Australia', 'AU'),
(9, 'France', 'FR'),
(10, 'Japan', 'JP'),
(98, 'Egypt', NULL),
(13, 'Yugoslavia', NULL),
(14, 'Scotland', NULL),
(15, 'Netherlands', NULL),
(16, 'Canada', NULL),
(17, 'China', NULL),
(18, 'New Zealand', NULL),
(19, 'Italy', NULL),
(20, 'Denmark', NULL),
(21, 'Czech Republic', NULL),
(117, 'Guadeloupe', NULL),
(23, 'Norway', NULL),
(24, 'Ireland', NULL),
(25, 'Austria', NULL),
(116, 'Grenada', NULL),
(27, 'Switzerland', NULL),
(113, 'Ghana', NULL),
(29, 'Finland', NULL),
(118, 'Guam', NULL),
(115, 'Greenland', NULL),
(114, 'Gibralta', NULL),
(33, 'Greece', NULL),
(35, 'Portugal', NULL),
(37, 'Afghanistan', NULL),
(38, 'Albania', NULL),
(39, 'Algeria', NULL),
(40, 'Andorra', NULL),
(41, 'Angola', NULL),
(42, 'Anguilla', NULL),
(43, 'Antigua & Barbuda', NULL),
(44, 'Argentina', NULL),
(45, 'Armenia', NULL),
(46, 'Aruba', NULL),
(47, 'Ascension Island', NULL),
(48, 'Azerbaijan', NULL),
(49, 'Azores', NULL),
(50, 'Bahamas', NULL),
(51, 'Bahrain', NULL),
(52, 'Balearic Islands', NULL),
(53, 'Bangladesh', NULL),
(54, 'Barbados', NULL),
(55, 'Belarus', NULL),
(59, 'Benin', NULL),
(58, 'Belize', NULL),
(60, 'Bermuda', NULL),
(61, 'Bhutan', NULL),
(62, 'Bolivia', NULL),
(63, 'Bosnia-Herzegovina', NULL),
(64, 'Botswana', NULL),
(65, 'Brazil', NULL),
(66, 'British Virgin Islands', NULL),
(67, 'Brunei Darussalam', NULL),
(68, 'Bulgaria', NULL),
(69, 'Burkina Faso', NULL),
(70, 'Burundi', NULL),
(71, 'Cambodia', NULL),
(72, 'Cameroon', NULL),
(73, 'Canary Islands', NULL),
(74, 'Cape Verde', NULL),
(75, 'Cayman Islands', NULL),
(76, 'Central African Republic', NULL),
(77, 'Chad', NULL),
(78, 'Channel Islands', NULL),
(79, 'Chile', NULL),
(80, 'Christmas Island', NULL),
(81, 'Cocos (Keeling) Island', NULL),
(82, 'Colombia', NULL),
(83, 'Comoros', NULL),
(84, 'Congo (Rep)', NULL),
(85, 'Congo Dem. Rep.', NULL),
(86, 'Corsica', NULL),
(87, 'Costa Rica', NULL),
(88, 'Croatia', NULL),
(89, 'Cuba', NULL),
(90, 'Cuba (Guantanamo Bay)', NULL),
(91, 'Cyprus', NULL),
(95, 'Dominican Republic', NULL),
(93, 'Djibouti', NULL),
(94, 'Dominica', NULL),
(96, 'East Timor', NULL),
(97, 'Ecuador', NULL),
(99, 'El Salvador', NULL),
(100, 'Equatorial Guinea', NULL),
(101, 'Eritrea', NULL),
(102, 'Estonia', NULL),
(103, 'Ethiopia', NULL),
(104, 'Falkland Islands', NULL),
(105, 'Faroe Islands', NULL),
(106, 'Fiji', NULL),
(107, 'French Guinea', NULL),
(108, 'French Polynesia', NULL),
(109, 'Gabon', NULL),
(110, 'Gambia', NULL),
(111, 'Gaza & Khan Yunis', NULL),
(112, 'Georgia', NULL),
(119, 'Guatemala', NULL),
(120, 'Guinea', NULL),
(121, 'Guinea-Bisau', NULL),
(122, 'Guyana', NULL),
(123, 'Haiti', NULL),
(124, 'Honduras', NULL),
(125, 'Hong Kong', NULL),
(126, 'Hungary', NULL),
(127, 'Iceland', NULL),
(128, 'India', NULL),
(129, 'Indonesia', NULL),
(130, 'Iran', NULL),
(131, 'Iraq', NULL),
(132, 'Israel', NULL),
(133, 'Ivory Coast', NULL),
(134, 'Jamaica', NULL),
(135, 'Jordan', NULL),
(136, 'Kazakhstan', NULL),
(137, 'Kenya', NULL),
(138, 'Kirghizstan', NULL),
(139, 'Kiribati', NULL),
(140, 'Korea (North)', NULL),
(141, 'Korea (South)', NULL),
(142, 'Kuwait', NULL),
(143, 'Laos', NULL),
(144, 'Latvia', NULL),
(145, 'Lebanon', NULL),
(146, 'Lesotho', NULL),
(147, 'Liberia', NULL),
(148, 'Libya', NULL),
(149, 'Liechtenstein', NULL),
(150, 'Lithuania', NULL),
(151, 'Luxembourg', NULL),
(152, 'Macao', NULL),
(153, 'Macedodia (former Yug. Rep)', NULL),
(154, 'Madagascar', NULL),
(155, 'Madeira', NULL),
(156, 'Malawi', NULL),
(157, 'Malaysia', NULL),
(158, 'Maldives', NULL),
(159, 'Mali', NULL),
(160, 'Malta', NULL),
(161, 'Marshall Islands', NULL),
(162, 'Martinique', NULL),
(163, 'Mauritania', NULL),
(164, 'Mauritius', NULL),
(165, 'Mexico', NULL),
(166, 'Micronesia', NULL),
(167, 'Moldova', NULL),
(168, 'Monaco', NULL),
(169, 'Mongolia', NULL),
(170, 'Montserrat', NULL),
(171, 'Morocco', NULL),
(172, 'Mozambique', NULL),
(173, 'Myanmar (Burma)', NULL),
(174, 'Namibia', NULL),
(175, 'Nauru Island', NULL),
(176, 'Nepal', NULL),
(177, 'Netherland Antilles', NULL),
(178, 'New Caledonia', NULL),
(179, 'Nicaragua', NULL),
(180, 'Niger Republic', NULL),
(181, 'Nigeria', NULL),
(182, 'Norfolk Island', NULL),
(183, 'Northern Mariana Islands', NULL),
(184, 'Oman', NULL),
(185, 'Pakistan', NULL),
(186, 'Panama', NULL),
(187, 'Papua New Guinea', NULL),
(188, 'Paraguay', NULL),
(189, 'Peru', NULL),
(190, 'Philippines', NULL),
(191, 'Pitcairn Island', NULL),
(192, 'Poland', NULL),
(193, 'Puerto Rico', NULL),
(194, 'Qatar', NULL),
(195, 'Reunion', NULL),
(196, 'Romania', NULL),
(197, 'Russia', NULL),
(198, 'Rwanda', NULL),
(199, 'Samoa (American)', NULL),
(200, 'San Marino', NULL),
(201, 'Sao Tome & Principe', NULL),
(202, 'Sardinia', NULL),
(203, 'Saudia Arabia', NULL),
(204, 'Senegal', NULL),
(205, 'Seychelles', NULL),
(206, 'Sicily', NULL),
(207, 'Sierra Leone', NULL),
(208, 'Singapore', NULL),
(209, 'Slovak Republic', NULL),
(210, 'Slovenia', NULL),
(211, 'Solomon Islands', NULL),
(212, 'Somalia', NULL),
(213, 'South Africa', NULL),
(214, 'Spain', NULL),
(215, 'Spanish North African Terr', NULL),
(216, 'Spitzbergen', NULL),
(217, 'Sri Lanka', NULL),
(218, 'St Kitts & Nevis', NULL),
(219, 'St Helena', NULL),
(220, 'St Lucia', NULL),
(221, 'St Pierre & Miquelon', NULL),
(222, 'St Vincent & Grenadines', NULL),
(223, 'Sudan', NULL),
(224, 'Suriname', NULL),
(225, 'Swaziland', NULL),
(226, 'Syria', NULL),
(227, 'Taiwan', NULL),
(228, 'Tajikistan', NULL),
(229, 'Tanzania', NULL),
(230, 'Thailand', NULL),
(231, 'Togo', NULL),
(232, 'Tonga', NULL),
(233, 'Trinidad & Tobago', NULL),
(234, 'Tristan da Cunha', NULL),
(235, 'Tunisia', NULL),
(236, 'Turkey', NULL),
(237, 'Turkmenistan', NULL),
(238, 'Turks & Caicos Islands', NULL),
(239, 'Tuvalu', NULL),
(240, 'Uganda', NULL),
(241, 'United Arab Emirates', NULL),
(242, 'Uruguay', NULL),
(243, 'Uzbekistan', NULL),
(244, 'Vanuatu', NULL),
(245, 'Vatican City', NULL),
(246, 'Venezuela', NULL),
(247, 'Vietnam', NULL),
(248, 'Virgin Islands (USA)', NULL),
(249, 'Wake Island', NULL),
(250, 'Wallis & Futuna Islands', NULL),
(251, 'Western Samoa', NULL),
(252, 'Yemen', NULL),
(253, 'Zambia', NULL),
(254, 'Zimbabwe', NULL);




INSERT INTO `#__jresearch_property` (`name`) VALUES ('abstract');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('address');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('annote');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('author');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('booktitle');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('chapter');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('crossref');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('edition');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('editor');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('howpublished');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('institution');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('isbn');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('issn');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('doi');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('journal');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('key');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('month');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('note');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('number');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('organization');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('pages');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('publisher');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('school');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('series');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('title');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('type');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('url');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('volume');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('year');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('keywords');
-- Additional fields (non standard, defined by J!Research)
INSERT INTO `#__jresearch_property` (`name`) VALUES ('access_date');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('day');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('source_type');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('extra');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('patent_number');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('filing_date');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('issue_date');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('claims');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('drawings_dir');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('country');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('office');

DROP TABLE IF EXISTS `#__jresearch_publication_type`;
CREATE TABLE `#__jresearch_publication_type` (
	`name` VARCHAR( 20 ) NOT NULL,
	PRIMARY KEY (`name`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8; 


INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('article');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('book');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('booklet');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('conference');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('inbook');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('incollection');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('inproceedings');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('manual');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('misc');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('patent');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('pthesis');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('proceedings');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('techreport');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('unpublished');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('online_source');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('earticle');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('digital_source');

INSERT INTO `#__jresearch_research_area`(`name`, `description`, `published` ) VALUES('Osteopathy', '', 1);

DELETE FROM `#__categories` WHERE `section` = 'com_jresearch_cooperations';
INSERT INTO `#__categories` (`title`, `name`, `alias`, `image`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`, `params`) VALUES
('Uncategorized', '', 'cooperations-category-uncategorized', '', 'com_jresearch_cooperations', 'left', 'Holds uncategorized cooperations of the component J!Research', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, '');
