-- File: install.sql
-- Installation SQL routine for component JResearch
-- Author: Luis Galarraga
-- Date: 27-05-2008 00:14:00

DROP TABLE IF EXISTS `#__jresearch_article`;
CREATE TABLE IF NOT EXISTS `#__jresearch_article` (
  `id_publication` int(11) NOT NULL,
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
  `status` enum('not_started','in_progress','finished') NOT NULL default 'not_started',
  `start_date` date default NULL,
  `end_date` date default NULL,
  `url_project_image` varchar(255) default NULL,
  `description` text,
  `funding` decimal(12,2) default NULL,
  `funding_currency` varchar(5) default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  INDEX `id_research_area` (`id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__jresearch_project_external_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_external_author` (
  `id_project` int(10) unsigned NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`author_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project_funder`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_funder` (
  `id_project` int(10) unsigned NOT NULL,
  `id_financier` int(10) unsigned NOT NULL
  PRIMARY KEY  (`id_project`,`id_financier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_project_internal_author`;
CREATE TABLE IF NOT EXISTS `#__jresearch_project_internal_author` (
  `id_project` int(10) unsigned NOT NULL,
  `id_staff_member` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`id_staff_member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__jresearch_publication`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_research_area` int(10) unsigned NOT NULL default '1',
  `comments` text,
  `journal_acceptance_rate` float unsigned default NULL,
  `pubtype` varchar(20) NOT NULL default 'book',
  `awards` text,
  `url` varchar(255) default NULL,
  `published` tinyint(4) NOT NULL default '1' ,
  `title` varchar(255) NOT NULL,
  `year` year(4) NULL,	
  `citekey` varchar(255) NOT NULL,
  `abstract` text,
  `note` text,
  `internal` tinyint(4) NOT NULL default '1',
  `keywords` varchar(255) default NULL,
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
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
  `name` varchar(60) NOT NULL,
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
  `position` varchar(30) default NULL,
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

DROP TABLE IF EXISTS `#__jresearch_research_techreport`;
CREATE TABLE IF NOT EXISTS `#__jresearch_techreport` (
  `id_publication` int(10) unsigned NOT NULL,
  `institution` varchar(255) NOT NULL,
  `type` varchar(20) default NULL,
  `number` varchar(10) default NULL,
  `address` varchar(255) default NULL,
  `month` varchar(20) default NULL,
  PRIMARY KEY  (`id_publication`)
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
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created` datetime NULL,
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

DROP TABLE IF EXISTS `#__jresearch_mdm`;
CREATE TABLE IF NOT EXISTS `#__jresearch_mdm` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_member` int(19) unsigned NOT NULL,
  `month` date NOT NULL,
  `description` tinytext NOT NULL,
  `published` tinyint(4) NOT NULL default '1',
  `checked_out` tinyint(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__jresearch_cooperations`;
CREATE TABLE IF NOT EXISTS `#__jresearch_cooperations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
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
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('phdthesis');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('proceedings');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('techreport');
INSERT INTO `#__jresearch_publication_type`(`name`) VALUES('unpublished');


INSERT INTO `#__jresearch_research_area`(`name`, `description`, `published` ) VALUES('Uncategorized', '', 1);
