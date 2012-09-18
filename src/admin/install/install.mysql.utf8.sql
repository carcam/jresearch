-- File: install.sql
-- Installation SQL routine for component JResearch
-- Author: Luis Galarraga
-- Date: 27-05-2008 00:14:00


DROP TABLE IF EXISTS `#__jresearch_publication`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_research_area` VARCHAR( 1024 ) NOT NULL DEFAULT  '1',
  `id_team` text NULL,
  `alias` varchar(256) NOT NULL,
  `authors` text,
  `comments` text,
  `journal_acceptance_rate` float unsigned default NULL,
  `impact_factor` float unsigned default NULL,
  `pubtype` varchar(20) NOT NULL default 'book',
  `awards` text,
  `url` varchar(256) default NULL,
  `cover` varchar(256) default NULL,
  `files` text default NULL,
  `published` tinyint(4) NOT NULL default '1' ,
  `title` varchar(256) NOT NULL,
  `doi` varchar(256) default NULL,
  `year` smallint(4) unsigned NULL DEFAULT NULL ,	
  `citekey` varchar(256) NOT NULL,
  `abstract` text,
  `note` text,
  `internal` tinyint(4) NOT NULL default '1',
  `keywords` varchar(256) default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `modified` datetime NULL,
  `modified_by` int(10) default NULL,
  `hits` int(10) default 0,
  `issn` varchar(32) default NULL,
  `journal` varchar(256) NOT NULL,
  `number` varchar(10) default NULL,
  `pages` varchar(20) default NULL,
  `month` varchar(20) default NULL,
  `crossref` varchar(256) default NULL,
  `isbn` varchar(32) default NULL,	
  `publisher` varchar(256) NOT NULL,
  `editor` varchar(256) NOT NULL,
  `volume` varchar(30) default NULL,
  `series` varchar(256) default NULL,
  `address` varchar(256) default NULL,
  `edition` varchar(10) default NULL,
  `howpublished` varchar(256) default NULL,
  `booktitle` varchar(256) default NULL,
  `organization` varchar(256) default NULL,
  `chapter` varchar(10) default NULL,
  `type` varchar(20) default NULL,
  `key` varchar(256) default NULL,
  `patent_number` varchar(20) NOT NULL,
  `filing_date` date DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `claims` longtext DEFAULT NULL,
  `drawings_dir` varchar(256) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `office` varchar(256) DEFAULT NULL,
  `school` varchar(256) NOT NULL,
  `institution` varchar(256) NOT NULL,
  `day` varchar(2) default NULL,
  `extra` text default NULL,
  `online_source_type` enum('website', 'video', 'audio', 'image', 'blog') NOT NULL default 'website',
  `digital_source_type` enum('cdrom', 'film') NOT NULL default 'cdrom',
  `access_date` date default NULL,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  PRIMARY KEY  (`id`),
  FULLTEXT INDEX `#__jresearch_publication_title_index`(`title`),
  FULLTEXT INDEX `#__jresearch_publication_title_keywords_index`(`title`, `keywords`),
  FULLTEXT INDEX `#__jresearch_publication_full_index`(`title`, `keywords`, `abstract`),
  UNIQUE KEY `citekey` (`citekey`),
  INDEX `year` (`year`),
  INDEX `pubtype` (`pubtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__jresearch_financier`;
CREATE TABLE IF NOT EXISTS `#__jresearch_financier` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `url` varchar(256) DEFAULT NULL,
  `published` tinyint(4) NOT NULL default '1',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `#__jresearch_project`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alias` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `authors` text,
  `id_research_area` VARCHAR( 1024 ) NOT NULL DEFAULT  '1',
  `published` tinyint(4) NOT NULL default '1',
  `url` varchar(256) default NULL,
  `files` text default NULL,
  `status` enum('not_started','in_progress','finished') NOT NULL default 'not_started',
  `start_date` date default NULL,
  `end_date` date default NULL,
  `logo` varchar(256) default NULL,
  `description` text,
  `finance_value` decimal(12,2) default NULL,
  `finance_currency` varchar(5) default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `modified` datetime NULL,
  `modified_by` int(10) default NULL,
  `hits` int(10) default 0,
  `keywords` varchar(256) default NULL,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `publications` TEXT,
  `ordering` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  INDEX `id_research_area` (`id_research_area`),
  FULLTEXT INDEX `#__jresearch_project_title_index`(`title`),
  FULLTEXT INDEX `#__jresearch_project_title_keywords_index`(`title`, `keywords`),
  FULLTEXT INDEX `#__jresearch_project_full_index`(`title`, `description`, `keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_project_external_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_external_author` (
  `id_project` int(10) unsigned NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `is_principal` tinyint(4) NOT NULL default '0',
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`author_name`),
  FULLTEXT INDEX `#__jresearch_project_external_authors_index`(`author_name`)
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

DROP TABLE IF EXISTS `#__jresearch_project_publication`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_publication` (
  `id_project` int(10) unsigned NOT NULL,
  `id_publication` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__jresearch_publication_external_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_external_author` (
  `id_publication` int(10) unsigned NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id_publication`,`author_name`),
  FULLTEXT INDEX `#__jresearch_publication_external_authors_index`(`author_name`)
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
  `alias` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text,
  `published` tinyint(4) NOT NULL default '1',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) unsigned NOT NULL default '0',
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `modified` datetime NULL,
  `modified_by` int(10) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  FULLTEXT INDEX `#__jresearch_researcharea_name`(`name`),
  FULLTEXT INDEX `#__jresearch_researcharea_full`(`name`, `description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_research_area_team`;
CREATE TABLE IF NOT EXISTS `#__jresearch_research_area` (
  `id_research_area` int(10) unsigned NOT NULL,
  `id_team` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_research_area`, `id_team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_keyword`;
CREATE TABLE IF NOT EXISTS `#__jresearch_keyword` (
  `keyword` varchar(256) NOT NULL,
  PRIMARY KEY  (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project_keyword`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_keyword` (
  `id_project` int(10) unsigned NOT NULL,
  `keyword` varchar(256) NOT NULL,
  PRIMARY KEY  (`id_project`, `keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_publication_keyword`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_keyword` (
  `id_publication` int(10) unsigned NOT NULL,
  `keyword` varchar(256) NOT NULL,
  PRIMARY KEY  (`id_publication`, `keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__jresearch_member`;
CREATE TABLE IF NOT EXISTS `#__jresearch_member` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `former_member` tinyint(1) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `title` varchar(10) NOT NULL,
  `email` varchar(256) NULL,
  `username` varchar(150) default NULL,
  `id_research_area` VARCHAR( 1024 ) NOT NULL DEFAULT  '1',
  `position` int(10) unsigned default '0',
  `location` varchar(256) default NULL,
  `url_personal_page` varchar(256) default NULL,
  `published` tinyint(4) NOT NULL default '1',
  `ordering` int(11) unsigned NOT NULL default '0',
  `phone` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `url_photo` varchar(256) default NULL,
  `description` text,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  `created_by` int(10) default NULL,
  `modified` datetime NULL,
  `modified_by` int(10) default NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  PRIMARY KEY  (`id`),
  INDEX `#__jresearch_member_name` (`lastname`,`firstname`),
  FULLTEXT INDEX `#__jresearch_member_desc`(`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_member_position`;
CREATE TABLE IF NOT EXISTS `#__jresearch_member_position` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `position` varchar(50) NOT NULL,
  `published` tinyint(4) NOT NULL default '1',
  `ordering` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `#__jresearch_team`;
CREATE TABLE IF NOT EXISTS `#__jresearch_team` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent` int(11) unsigned default NULL,
  `id_leader` int(11) unsigned NOT NULL,
  `alias` varchar(256) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `published` tinyint(4) NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `#__jresearch_team_description_index` (`description`)
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
  `alias` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `degree` enum('bachelor','master','phd') NOT NULL default 'bachelor',
  `status` enum('not_started','in_progress','finished') NOT NULL default 'not_started',
  `start_date` date default NULL,
  `end_date` date default NULL,
  `published` tinyint(4) NOT NULL default '1',
  `description` text,
  `url` varchar(256) default NULL,
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
  PRIMARY KEY  (`id_thesis`,`author_name`),
  FULLTEXT INDEX `#__jresearch_thesis_external_authors_index`(`author_name`)
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
	`citekey` VARCHAR( 256 ) NOT NULL ,
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
  `alias` varchar(256) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `description` text NOT NULL,
  `url` varchar(256) DEFAULT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) unsigned NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `#__jresearch_cooperations_description_index` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_facilities`;
CREATE TABLE IF NOT EXISTS `#__jresearch_facilities` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `alias` varchar(256) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `description` text NOT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) unsigned NOT NULL default '0',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `#__jresearch_facilities_description_index` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_publication_researcharea`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_researcharea` (
  `id_publication` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_publication`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_project_researcharea`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_researcharea` (
  `id_project` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_thesis_researcharea`;
CREATE TABLE IF NOT EXISTS `#__jresearch_thesis_researcharea` (
  `id_thesis` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_thesis`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_member_researcharea`;
CREATE TABLE IF NOT EXISTS `#__jresearch_member_researcharea` (
  `id_member` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_member`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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

INSERT INTO `#__jresearch_research_area`(`name`, `alias` ,`description`, `published` ) VALUES('Uncategorized', 'Uncategorized' , '', 1);

DELETE FROM `#__categories` WHERE `extension` = 'com_jresearch';
INSERT INTO `#__categories` (`id`, `asset_id`, `parent_id`, `lft`, `rgt`, `level`, `path`, `extension`, `title`, `alias`, `note`, `description`, `published`, `checked_out`, `checked_out_time`, `access`, `params`, `metadesc`, `metakey`, `metadata`, `created_user_id`, `created_time`, `modified_user_id`, `modified_time`, `hits`, `language`)
VALUES
(NULL, '0', '1', '0', '0', '1', '', 'com_jresearch', 'J!Research', 'com_jresearch', 'J!Research parent content category', 'J!Research parent content category', '1', '0', '0000-00-00 00:00:00', '0', '{}', '', '', '', '42', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', '0', '*'),
(NULL, '0', LAST_INSERT_ID(), '0', '0', '1', '', 'com_jresearch', 'J!Research Cooperations', 'com_jresearch.cooperations', 'J!Research cooperations categories', 'J!Research cooperations categories', '1', '0', '0000-00-00 00:00:00', '0', '{}', '', '', '', '42', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', '0', '*');

CREATE VIEW `#__jresearch_all_project_authors` AS SELECT DISTINCT `ia`.`id_staff_member` AS `mid`, `ia`.`id_project` AS `pid`, `ia`.`is_principal` AS `is_principal`, `ia`.`order` AS `order`, CONCAT(', ', `m`.`lastname`, `m`.`firstname`) as `member_name` 
FROM `#__jresearch_project_internal_author` `ia` JOIN `#__jresearch_member` `m` WHERE `m`.`id` = `ia`.`id_staff_member`  
UNION SELECT DISTINCT `ea`.`author_name` AS `mid`, `ea`.`id_project` AS `pid`, `ea`.`is_principal` AS `is_principal`, `ea`.`order` AS `order`, `ea`.`author_name` as `member_name`
FROM `#__jresearch_project_external_author` `ea` ORDER BY `member_name` ASC;

CREATE VIEW `#__jresearch_all_publication_authors` AS SELECT DISTINCT `ia`.`id_staff_member` AS `mid`, `ia`.`id_publication` AS `pid`, CONCAT(', ', `m`.`lastname`, `m`.`firstname`) as `member_name` FROM `#__jresearch_publication_internal_author` `ia` JOIN `#__jresearch_member` `m` WHERE `m`.`id` = `ia`.`id_staff_member`  
UNION SELECT DISTINCT `ea`.`author_name` AS `mid`, `ea`.`id_publication` AS `pid`, `ea`.`author_name` as `member_name`
FROM `#__jresearch_publication_external_author` `ea` ORDER BY `member_name` ASC;
