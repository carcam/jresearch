-- File: install.sql
-- Installation SQL routine for component JResearch
-- Author: Luis Galarraga
-- Date: 27-05-2008 00:14:00

DROP TABLE IF EXISTS `#__jresearch_article`;
CREATE TABLE IF NOT EXISTS `#__jresearch_article` (
  `id_publication` int(11) NOT NULL,
  `issn` varchar(32) default NULL,
  `journal` varchar(255) NOT NULL,
  `volume` varchar(30) default NULL,
  `number` varchar(10) default NULL,
  `pages` varchar(20) default NULL,
  `month` varchar(20) default NULL,
  `crossref` varchar(255) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_book`;
CREATE TABLE IF NOT EXISTS `#__jresearch_book` (
  `id_publication` int(10) unsigned NOT NULL,
  `isbn` varchar(32) default NULL,	
  `publisher` varchar(60) NOT NULL,
  `editor` varchar(255) NOT NULL,
  `volume` varchar(30) default NULL,
  `number` varchar(20) default NULL,
  `series` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `edition` varchar(10) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_booklet`;
CREATE TABLE IF NOT EXISTS `#__jresearch_booklet` (
  `id_publication` int(10) unsigned NOT NULL,
  `howpublished` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_citing_style`;
CREATE TABLE IF NOT EXISTS `#__jresearch_citing_style` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `#__jresearch_conference`;
CREATE TABLE IF NOT EXISTS `#__jresearch_conference` (
  `id_publication` int(10) unsigned NOT NULL,
  `issn` varchar(32) default NULL,
  `isbn` varchar(32) default NULL,
  `editor` varchar(255) default NULL,
  `volume` varchar(30) default NULL,
  `booktitle` varchar(255) default NULL,
  `number` varchar(10) default NULL,
  `series` varchar(255) default NULL,
  `pages` varchar(20) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  `publisher` varchar(60) default NULL,
  `organization` varchar(255) default NULL,
  `crossref` varchar(255) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

DROP TABLE IF EXISTS `#__jresearch_inbook`;
CREATE TABLE IF NOT EXISTS `#__jresearch_inbook` (
  `id_publication` int(10) unsigned NOT NULL,
  `isbn` varchar(32) default NULL,
  `editor` varchar(255) default NULL,
  `chapter` varchar(10) default NULL,
  `pages` varchar(20) default NULL,
  `publisher` varchar(60) NOT NULL,
  `volume` varchar(30) default NULL,
  `number` varchar(10) default NULL,
  `series` varchar(255) default NULL,
  `type` varchar(20) default NULL,
  `address` varchar(255) default NULL,
  `edition` varchar(10) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_incollection`;
CREATE TABLE IF NOT EXISTS `#__jresearch_incollection` (
  `id_publication` int(11) NOT NULL,
  `isbn` varchar(32) default NULL,
  `booktitle` varchar(255) NOT NULL,
  `publisher` varchar(60) NOT NULL,
  `editor` varchar(255) default NULL,
  `organization` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `pages` varchar(20) default NULL,
  `month` varchar(20) default NULL,
  `key` varchar(255) default NULL,
  `crossref` varchar(255) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_manual`;
CREATE TABLE IF NOT EXISTS `#__jresearch_manual` (
  `id_publication` int(10) unsigned NOT NULL,
  `organization` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `edition` varchar(10) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_mastersthesis`;
CREATE TABLE IF NOT EXISTS `#__jresearch_mastersthesis` (
  `id_publication` int(10) unsigned NOT NULL,
  `school` varchar(255) NOT NULL,
  `type` varchar(20) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_misc`;
CREATE TABLE IF NOT EXISTS `#__jresearch_misc` (
  `id_publication` int(10) unsigned NOT NULL,
  `howpublished` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_patent`;
CREATE TABLE IF NOT EXISTS `#__jresearch_patent` (
  `id_publication` int(10) unsigned NOT NULL,
  `patent_number` varchar(10) NOT NULL,
  `filing_date` date DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `claims` longtext DEFAULT NULL,
  `drawings_dir` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_phdthesis`;
CREATE TABLE IF NOT EXISTS `#__jresearch_phdthesis` (
  `id_publication` int(10) unsigned NOT NULL,
  `school` varchar(255) NOT NULL,
  `type` varchar(20) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_proceedings`;
CREATE TABLE IF NOT EXISTS `#__jresearch_proceedings` (
  `id_publication` int(10) unsigned NOT NULL,
  `isbn` varchar(32) default NULL,
  `issn` varchar(32) default NULL,
  `editor` varchar(255) default NULL,
  `volume` varchar(30) default NULL,
  `number` varchar(10) default NULL,
  `series` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  `publisher` varchar(60) default NULL,
  `organization` varchar(255) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project` (
  `id` int(10) unsigned NOT NULL auto_increment,
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
  `comments` text,
  `journal_acceptance_rate` float unsigned default NULL,
  `impact_factor` float unsigned default NULL,
  `pubtype` varchar(20) NOT NULL default 'book',
  `awards` text,
  `url` varchar(255) default NULL,
  `files` text default NULL,
  `published` tinyint(4) NOT NULL default '1' ,
  `title` varchar(255) NOT NULL,
  `doi` varchar(255) default NULL,
  `year` year(4) NULL,	
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

DROP TABLE IF EXISTS `#__jresearch_techreport`;
CREATE TABLE IF NOT EXISTS `#__jresearch_techreport` (
  `id_publication` int(10) unsigned NOT NULL,
  `institution` varchar(255) NOT NULL,
  `type` varchar(20) default NULL,
  `number` varchar(10) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_team`;
CREATE TABLE IF NOT EXISTS `#__jresearch_team` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent` int(11) unsigned default NULL,
  `id_leader` int(11) unsigned NOT NULL,
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

DROP TABLE IF EXISTS `#__jresearch_unpublished`;
CREATE TABLE IF NOT EXISTS `#__jresearch_unpublished` (
  `id_publication` int(10) unsigned NOT NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
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
  `name` varchar(100) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `description` tinytext NOT NULL,
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
  `name` varchar(50) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `description` tinytext NOT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) unsigned NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_online_source`;
CREATE TABLE IF NOT EXISTS `#__jresearch_online_source` (
  `id_publication` int(10) unsigned NOT NULL,
  `month` varchar(20) default NULL,
  `day` varchar(2) default NULL,
  `access_date` date default NULL,
  `extra` text default NULL,
  `source_type` enum('website', 'video', 'audio', 'image', 'blog') NOT NULL default 'website',
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_digital_source`;
CREATE TABLE IF NOT EXISTS `#__jresearch_digital_source` (
  `id_publication` int(10) unsigned NOT NULL,
  `address` varchar(20) default NULL,
  `publisher` varchar(60) default NULL,
  `source_type` enum('cdrom', 'film') NOT NULL default 'cdrom',
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_earticle`;
CREATE TABLE IF NOT EXISTS `#__jresearch_earticle` (
  `id_publication` int(10) unsigned NOT NULL,
  `access_date` date default NULL,
  `journal` varchar(255) NOT NULL,
  `volume` varchar(30) default NULL,
  `number` varchar(10) default NULL,
  `month` varchar(20) default NULL,
  `day` varchar(2) default NULL,
  PRIMARY KEY  (`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE OR REPLACE VIEW `#__jresearch_publication_article` AS SELECT * FROM `#__jresearch_article` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_earticle` AS SELECT * FROM `#__jresearch_earticle` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_digital_source` AS SELECT * FROM `#__jresearch_digital_source` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_online_source` AS SELECT * FROM `#__jresearch_online_source` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_unpublished` AS SELECT * FROM `#__jresearch_unpublished` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_proceedings` AS SELECT * FROM `#__jresearch_proceedings` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_book` AS SELECT * FROM `#__jresearch_book` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_incollection` AS SELECT * FROM `#__jresearch_incollection` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_booklet` AS SELECT * FROM `#__jresearch_booklet` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_conference` AS SELECT * FROM `#__jresearch_conference` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_inbook` AS SELECT * FROM `#__jresearch_inbook` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_patent` AS SELECT * FROM `#__jresearch_patent` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_misc` AS SELECT * FROM `#__jresearch_misc` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_phdthesis` AS SELECT * FROM `#__jresearch_phdthesis` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_mastersthesis` AS SELECT * FROM `#__jresearch_mastersthesis` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_manual` AS SELECT * FROM `#__jresearch_manual` JOIN `#__jresearch_publication` ON `id` = `id_publication`;
CREATE OR REPLACE VIEW `#__jresearch_publication_techreport` AS SELECT * FROM `#__jresearch_techreport` JOIN `#__jresearch_publication` ON `id` = `id_publication`;

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
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('mastersthesis');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('misc');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('patent');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('phdthesis');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('proceedings');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('techreport');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('unpublished');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('online_source');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('earticle');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('digital_source');

INSERT INTO `#__jresearch_research_area`(`name`, `description`, `published` ) VALUES('Uncategorized', '', 1);

DELETE FROM `#__categories` WHERE `section` = 'com_jresearch_cooperations';
INSERT INTO `#__categories` (`title`, `name`, `alias`, `image`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`, `params`) VALUES
('Uncategorized', '', 'cooperations-category-uncategorized', '', 'com_jresearch_cooperations', 'left', 'Holds uncategorized cooperations of the component J!Research', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, '');