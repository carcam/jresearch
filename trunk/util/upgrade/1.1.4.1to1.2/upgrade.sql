DELETE FROM `dev_components` WHERE `name` = 'J!Research';
 
INSERT INTO `dev_components` (`name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
('JRESEARCH_COOPERATION_CATEGORIES', '', 0, 0, 'option=com_categories&section=com_jresearch_cooperations', 'JRESEARCH_COOPERATION_CATEGORIES', 'com_jresearch', 1, 'js/ThemeOffice/component.png', 0, '', 1);

INSERT INTO `dev_components` (`name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
('J!Research', 'option=com_jresearch', 0, 0, 'option=com_jresearch', 'J!Research', 'com_jresearch', 0, '../administrator/components/com_jresearch/assets/jresearch_logomini.png', 0, '', 1);

UPDATE `dev_components` SET `parent` = LAST_INSERT_ID() WHERE `option` = 'com_jresearch' ;
UPDATE `dev_components` SET `parent` = 0 WHERE `name` = 'J!Research' ;


CREATE TABLE  `dev_jresearch_member_position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE  `dev_jresearch_project_cooperation` (
  `id_project` int(10) unsigned NOT NULL,
  `id_cooperation` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_project`,`id_cooperation`)
);

ALTER TABLE `dev_jresearch_cooperations` ADD `alias` varchar(255);
ALTER TABLE `dev_jresearch_cooperations` ADD `catid` int(11);

ALTER TABLE `dev_jresearch_facilities` ADD `alias` varchar(255);

ALTER TABLE `dev_jresearch_member` ADD `location` varchar(50);

ALTER TABLE `dev_jresearch_project`
ADD `alias` varchar(255),
ADD `files` text;

ALTER TABLE `dev_jresearch_publication`
ADD `alias` varchar(255),
ADD `cover` varchar(255),
ADD `files` text,
ADD `doi` varchar(255),
ADD `hits` int(10),
ADD `issn` varchar(32),
ADD `journal` varchar(255),
ADD `number` varchar(10),
ADD `pages` varchar(20),
ADD `month` varchar(20),
ADD `crossref` varchar(255),
ADD `isbn` varchar(32),
ADD `publisher` varchar(60),
ADD `editor` varchar(255),
ADD `volume` varchar(30),
ADD `series` varchar(255),
ADD `address` varchar(255),
ADD `edition` varchar(10),
ADD `howpublished` varchar(255),
ADD `booktitle` varchar(255),
ADD `organization` varchar(255),
ADD `chapter` varchar(10),
ADD `type` varchar(20),
ADD `key` varchar(255),
ADD `patent_number` varchar(10),
ADD `filing_date` date,
ADD `issue_date` date,
ADD `claims` longtext,
ADD `drawings_dir` varchar(255),
ADD `country` varchar(60),
ADD `office` varchar(255),
ADD `school` varchar(255),
ADD `institution` varchar(255),
ADD `day` varchar(2),
ADD `extra` text,
ADD `online_source_type` enum('website','video','audio','image','blog'),
ADD `digital_source_type` enum('cdrom','film'),
ADD `access_date` date;

ALTER TABLE `dev_jresearch_research_area` ADD `alias` varchar(255);

ALTER TABLE `dev_jresearch_team`
ADD `alias` varchar(255) NOT NULL,
ADD `parent` int(11) unsigned DEFAULT NULL;

ALTER TABLE `dev_jresearch_thesis` ADD `alias` varchar(255) NOT NULL,
ADD `files` text;

UPDATE `dev_jresearch_publication` SET journal = (SELECT journal FROM dev_jresearch_article WHERE `dev_jresearch_publication`.`id` = dev_jresearch_article.`id_publication`);
UPDATE `dev_jresearch_publication` SET `number` = (SELECT `number` FROM dev_jresearch_article WHERE `dev_jresearch_publication`.`id` = dev_jresearch_article.`id_publication`);
UPDATE `dev_jresearch_publication` SET pages = (SELECT pages FROM dev_jresearch_article WHERE `dev_jresearch_publication`.`id` = dev_jresearch_article.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month` = (SELECT `month` FROM dev_jresearch_article WHERE `dev_jresearch_publication`.`id` = dev_jresearch_article.`id_publication`);
UPDATE `dev_jresearch_publication` SET crossref = (SELECT crossref FROM dev_jresearch_article WHERE `dev_jresearch_publication`.`id` = dev_jresearch_article.`id_publication`);

UPDATE `dev_jresearch_publication` SET publisher= (SELECT publisher FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET editor = (SELECT editor FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET volume = (SELECT volume FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `number` = (SELECT `number` FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET series = (SELECT series FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address = (SELECT address FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET edition = (SELECT edition FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month` = (SELECT `month` FROM `dev_jresearch_book` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_book`.`id_publication`);

UPDATE `dev_jresearch_publication` SET howpublished= (SELECT howpublished FROM `dev_jresearch_booklet` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_booklet`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_booklet` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_booklet`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_booklet` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_booklet`.`id_publication`);

UPDATE `dev_jresearch_publication` SET editor= (SELECT editor FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET volume= (SELECT volume FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET booktitle= (SELECT booktitle FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `number` = (SELECT `number` FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET series = (SELECT series FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET pages= (SELECT pages FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET publisher= (SELECT publisher FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET organization= (SELECT organization FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);
UPDATE `dev_jresearch_publication` SET crossref= (SELECT crossref FROM `dev_jresearch_conference` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_conference`.`id_publication`);


UPDATE `dev_jresearch_publication` SET howpublished= (SELECT howpublished FROM `dev_jresearch_inbook` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_inbook`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_inbook` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_inbook`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_inbook` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_inbook`.`id_publication`);

UPDATE `dev_jresearch_publication` SET booktitle= (SELECT booktitle FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET publisher= (SELECT publisher FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET editor= (SELECT editor FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET organization= (SELECT organization FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET pages= (SELECT pages FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `key`= (SELECT `key` FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);
UPDATE `dev_jresearch_publication` SET crossref= (SELECT crossref FROM `dev_jresearch_incollection` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_incollection`.`id_publication`);

UPDATE `dev_jresearch_publication` SET organization= (SELECT organization FROM `dev_jresearch_manual` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_manual`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_manual` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_manual`.`id_publication`);
UPDATE `dev_jresearch_publication` SET edition= (SELECT edition FROM `dev_jresearch_manual` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_manual`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_manual` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_manual`.`id_publication`);

UPDATE `dev_jresearch_publication` SET school= (SELECT school FROM `dev_jresearch_mastersthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_mastersthesis`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `type`= (SELECT `type` FROM `dev_jresearch_mastersthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_mastersthesis`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_mastersthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_mastersthesis`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_mastersthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_mastersthesis`.`id_publication`);

UPDATE `dev_jresearch_publication` SET howpublished= (SELECT howpublished FROM `dev_jresearch_misc` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_misc`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_misc` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_misc`.`id_publication`);

UPDATE `dev_jresearch_publication` SET patent_number = (SELECT patent_number FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET filing_date = (SELECT filing_date FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET issue_date = (SELECT issue_date FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET claims = (SELECT claims FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET drawings_dir = (SELECT drawings_dir FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address = (SELECT address FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET country = (SELECT country FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);
UPDATE `dev_jresearch_publication` SET office = (SELECT office FROM `dev_jresearch_patent` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_patent`.`id_publication`);

UPDATE `dev_jresearch_publication` SET school= (SELECT school FROM `dev_jresearch_phdthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_phdthesis`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `type`= (SELECT `type` FROM `dev_jresearch_phdthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_phdthesis`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address= (SELECT address FROM `dev_jresearch_phdthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_phdthesis`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month`= (SELECT `month` FROM `dev_jresearch_phdthesis` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_phdthesis`.`id_publication`);

UPDATE `dev_jresearch_publication` SET editor = (SELECT editor FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);
UPDATE `dev_jresearch_publication` SET volume = (SELECT volume FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`)
UPDATE `dev_jresearch_publication` SET `number` = (SELECT `number` FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);
UPDATE `dev_jresearch_publication` SET series = (SELECT series FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);
UPDATE `dev_jresearch_publication` SET address = (SELECT address FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);
UPDATE `dev_jresearch_publication` SET `month` = (SELECT `month` FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);
UPDATE `dev_jresearch_publication` SET publisher= (SELECT publisher FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);
UPDATE `dev_jresearch_publication` SET organization = (SELECT organization FROM `dev_jresearch_proceedings` WHERE `dev_jresearch_publication`.`id` = `dev_jresearch_proceedings`.`id_publication`);

INSERT INTO `dev_jresearch_publication_type`(`name`) VALUES('online_source');
INSERT INTO `dev_jresearch_publication_type`(`name`) VALUES('earticle');
INSERT INTO `dev_jresearch_publication_type`(`name`) VALUES('digital_source');

DELETE FROM `dev_categories` WHERE `section` = 'com_jresearch_cooperations';
INSERT INTO `dev_categories` (`title`, `name`, `alias`, `image`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`, `params`) VALUES
('Uncategorized', '', 'cooperations-category-uncategorized', '', 'com_jresearch_cooperations', 'left', 'Holds uncategorized cooperations of the component J!Research', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, '');

DROP TABLE IF EXISTS `dev_jresearch_article`;
DROP TABLE IF EXISTS `dev_jresearch_book`;
DROP TABLE IF EXISTS `dev_jresearch_booklet`;
DROP TABLE IF EXISTS `dev_jresearch_conference`;
DROP TABLE IF EXISTS `dev_jresearch_inbook`;
DROP TABLE IF EXISTS `dev_jresearch_incollection`;
DROP TABLE IF EXISTS `dev_jresearch_manual`;
DROP TABLE IF EXISTS `dev_jresearch_mastersthesis`;
DROP TABLE IF EXISTS `dev_jresearch_misc`;
DROP TABLE IF EXISTS `dev_jresearch_patent`;
DROP TABLE IF EXISTS `dev_jresearch_phdthesis`;
DROP TABLE IF EXISTS `dev_jresearch_proceedings`;
DROP TABLE IF EXISTS `dev_jresearch_techreport`;
DROP TABLE IF EXISTS `dev_jresearch_unpublished`;

DROP VIEW IF EXISTS `dev_jresearch_publication_article`;
DROP VIEW IF EXISTS `dev_jresearch_publication_book`;
DROP VIEW IF EXISTS `dev_jresearch_publication_booklet`;
DROP VIEW IF EXISTS `dev_jresearch_publication_conference`;
DROP VIEW IF EXISTS `dev_jresearch_publication_inbook`;
DROP VIEW IF EXISTS `dev_jresearch_publication_incollection`;
DROP VIEW IF EXISTS `dev_jresearch_publication_manual`;
DROP VIEW IF EXISTS `dev_jresearch_publication_mastersthesis`;
DROP VIEW IF EXISTS `dev_jresearch_publication_misc`;
DROP VIEW IF EXISTS `dev_jresearch_publication_patent`;
DROP VIEW IF EXISTS `dev_jresearch_publication_phdthesis`;
DROP VIEW IF EXISTS `dev_jresearch_publication_proceedings`;
DROP VIEW IF EXISTS `dev_jresearch_publication_techreport`;
DROP VIEW IF EXISTS `dev_jresearch_publication_unpublished`;