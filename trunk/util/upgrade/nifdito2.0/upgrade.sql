CREATE TABLE IF NOT EXISTS `#__jresearch_keyword` (
  `keyword` varchar(256) NOT NULL,
  PRIMARY KEY  (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jresearch_publication`
CHANGE `id_research_area` `id_research_area` VARCHAR( 1024 ) NOT NULL DEFAULT '1',
ADD `authors` text,
ADD `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
ADD FULLTEXT INDEX `#__jresearch_publication_title_index` (`title`),
ADD FULLTEXT INDEX `#__jresearch_publication_title_keywords_index` (`title`,`keywords`),
ADD FULLTEXT INDEX `#__jresearch_publication_full_index` (`title`,`keywords`,`abstract`);

DROP TABLE IF EXISTS `#__jresearch_publication_keyword`;
CREATE TABLE IF NOT EXISTS `#__jresearch_publication_keyword` (
  `id_publication` int(10) unsigned NOT NULL,
  `keyword` varchar(256) NOT NULL,
  PRIMARY KEY  (`id_publication`, `keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jresearch_project`
CHANGE `url_project_image` `logo` varchar(256) default NULL,
CHANGE `id_research_area` `id_research_area` VARCHAR( 1024 ) NOT NULL DEFAULT '1',
ADD `keywords` varchar(256) default NULL,
ADD `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
ADD `publications` TEXT,
ADD `ordering` int(11) unsigned NOT NULL default '0',
ADD FULLTEXT INDEX `#__jresearch_project_title_index`(`title`),
ADD FULLTEXT INDEX `#__jresearch_project_title_keywords_index`(`title`, `keywords`),
ADD FULLTEXT INDEX `#__jresearch_project_full_index`(`title`, `description`, `keywords`);

CREATE TABLE IF NOT EXISTS `#__jresearch_project_publication` (
  `id_project` int(10) unsigned NOT NULL,
  `id_publication` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`,`id_publication`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `#__jresearch_research_area`
ADD `ordering` int(11) unsigned NOT NULL default '0',
ADD `created` datetime NULL,
ADD `created_by` int(10) default NULL,
ADD `modified` datetime NULL,
ADD `modified_by` int(10) default NULL,
ADD `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
ADD FULLTEXT INDEX `#__jresearch_researcharea_name`(`name`),
ADD FULLTEXT INDEX `#__jresearch_researcharea_full`(`name`, `description`);


ALTER TABLE `#__jresearch_member`
CHANGE `id_research_area` `id_research_area` varchar(1024) NOT NULL DEFAULT '1',
ADD `created` datetime NULL,
ADD `created_by` int(10) default NULL,
ADD `modified` datetime NULL,
ADD `modified_by` int(10) default NULL,
ADD `access` int(10) unsigned NOT NULL DEFAULT '0',
ADD `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
DROP INDEX `name`,
ADD INDEX `#__jresearch_member_name` (`lastname`,`firstname`),
ADD FULLTEXT INDEX `#__jresearch_member_desc`(`description`);

CREATE TABLE IF NOT EXISTS `#__jresearch_publication_researcharea` (
  `id_publication` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_publication`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__jresearch_project_researcharea` (
  `id_project` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_project`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__jresearch_thesis_researcharea` (
  `id_thesis` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_thesis`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__jresearch_member_researcharea` (
  `id_member` int(11) unsigned NOT NULL,
  `id_research_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_member`, `id_research_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Added due to merge into single publications table
INSERT INTO `#__jresearch_property` (`name`) VALUES ('awards');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('comments');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('journal_acceptance_rate');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('impact_factor');

CREATE VIEW `#__jresearch_all_project_authors` AS SELECT DISTINCT `ia`.`id_staff_member` AS `mid`, `ia`.`id_project` AS `pid`, `ia`.`is_principal` AS `is_principal`, `ia`.`order` AS `order`, CONCAT(', ', `m`.`lastname`, `m`.`firstname`) as `member_name` 
FROM `#__jresearch_project_internal_author` `ia` JOIN `#__jresearch_member` `m` WHERE `m`.`id` = `ia`.`id_staff_member`  
UNION SELECT DISTINCT `ea`.`author_name` AS `mid`, `ea`.`id_project` AS `pid`, `ea`.`is_principal` AS `is_principal`, `ea`.`order` AS `order`, `ea`.`author_name` as `member_name`
FROM `#__jresearch_project_external_author` `ea` ORDER BY `member_name` ASC;

CREATE VIEW `#__jresearch_all_publication_authors` AS SELECT DISTINCT `ia`.`id_staff_member` AS `mid`, `ia`.`id_publication` AS `pid`, CONCAT(', ', `m`.`lastname`, `m`.`firstname`) as `member_name` FROM `#__jresearch_publication_internal_author` `ia` JOIN `#__jresearch_member` `m` WHERE `m`.`id` = `ia`.`id_staff_member`  
UNION SELECT DISTINCT `ea`.`author_name` AS `mid`, `ea`.`id_publication` AS `pid`, `ea`.`author_name` as `member_name`
FROM `#__jresearch_publication_external_author` `ea` ORDER BY `member_name` ASC;
