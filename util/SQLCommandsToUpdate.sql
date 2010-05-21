CREATE TABLE  `#__jresearch_member_position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE  `#__jresearch_project_cooperation` (
  `id_project` int(10) unsigned NOT NULL,
  `id_cooperation` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_project`,`id_cooperation`)
);

ALTER TABLE `#__jresearch_cooperations`
ADD (	 `alias` varchar(255),
`catid` int(11)
);

ALTER TABLE `#__jresearch_facilities`
ADD (	 `alias` varchar(255),
);

ALTER TABLE `#__jresearch_member`
ADD (	 `location` varchar(50),
);

ALTER TABLE `#__jresearch_project`
ADD (	 `alias` varchar(255),
`files` text 
);

ALTER TABLE ``#__jresearch_publication``
ADD (	 `alias` varchar(255),
`cover` varchar(255),
`files` text,
`doi` varchar(255),
`hits` int(10),
`issn` varchar(32),
`journal` varchar(255),
`number` varchar(10),
`pages` varchar(20),
`month` varchar(20),
`crossref` varchar(255),
`isbn` varchar(32),
`publisher` varchar(60),
`editor` varchar(255),
`volume` varchar(30),
`series` varchar(255),
`address` varchar(255),
`edition` varchar(10),
`howpublished` varchar(255),
`booktitle` varchar(255),
`organization` varchar(255),
`chapter` varchar(10),
`type` varchar(20),
`key` varchar(255),
`patent_number` varchar(10),
`filing_date` date,
`issue_date` date,
`claims` longtext,
`drawings_dir` varchar(255),
`country` varchar(60),
`office` varchar(255),
`school` varchar(255),
`institution` varchar(255),
`day` varchar(2),
`extra` text,
`online_source_type` enum('website','video','audio','image','blog'),
`digital_source_type` enum('cdrom','film'),
`access_date` date
);

ALTER TABLE `#__jresearch_research_area`
ADD (	 `alias` varchar(255),
);

ALTER TABLE `#__jresearch_team`
ADD (	 `alias` varchar(255) NOT NULL,
`parent` int(11) unsigned DEFAULT NULL
);

ALTER TABLE `#__jresearch_thesis`
ADD (	 `alias` varchar(255) NOT NULL,
`files` text,
);

UPDATE `#__jresearch_publication` SET journal = (SELECT journal FROM #__jresearch_article WHERE `#__jresearch_publication`.`id` = #__jresearch_article.`id`_publication);
UPDATE `#__jresearch_publication` SET number = (SELECT number FROM #__jresearch_article WHERE `#__jresearch_publication`.`id` = #__jresearch_article.`id`_publication);
UPDATE `#__jresearch_publication` SET pages = (SELECT pages FROM #__jresearch_article WHERE `#__jresearch_publication`.`id` = #__jresearch_article.`id`_publication);
UPDATE `#__jresearch_publication` SET month = (SELECT month FROM #__jresearch_article WHERE `#__jresearch_publication`.`id` = #__jresearch_article.`id`_publication);
UPDATE `#__jresearch_publication` SET crossref = (SELECT crossref FROM #__jresearch_article WHERE `#__jresearch_publication`.`id` = #__jresearch_article.`id`_publication);

UPDATE `#__jresearch_publication` SET publisher= (SELECT publisher FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);
UPDATE `#__jresearch_publication` SET editor = (SELECT editor FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);
UPDATE `#__jresearch_publication` SET volume = (SELECT volume FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication)
UPDATE `#__jresearch_publication` SET number = (SELECT number FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);
UPDATE `#__jresearch_publication` SET series = (SELECT series FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);
UPDATE `#__jresearch_publication` SET address = (SELECT address FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);
UPDATE `#__jresearch_publication` SET edition = (SELECT edition FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);
UPDATE `#__jresearch_publication` SET month = (SELECT month FROM `#__jresearch_book` WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`.`id`_publication);

UPDATE `#__jresearch_publication` SET howpublished= (SELECT howpublished FROM `#__jresearch_book`let WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`let.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_book`let WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`let.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_book`let WHERE `#__jresearch_publication`.`id` = `#__jresearch_book`let.`id`_publication);

UPDATE `#__jresearch_publication` SET editor= (SELECT editor FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET volume= (SELECT volume FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET booktitle= (SELECT booktitle FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET number= (SELECT number FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET series= (SELECT series FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET pages= (SELECT pages FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET publisher= (SELECT publisher FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET organization= (SELECT organization FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);
UPDATE `#__jresearch_publication` SET crossref= (SELECT crossref FROM `#__jresearch_conference` WHERE `#__jresearch_publication`.`id` = `#__jresearch_conference`.`id`_publication);


UPDATE `#__jresearch_publication` SET howpublished= (SELECT howpublished FROM `#__jresearch_inbook` WHERE `#__jresearch_publication`.`id` = `#__jresearch_inbook`.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_inbook` WHERE `#__jresearch_publication`.`id` = `#__jresearch_inbook`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_inbook` WHERE `#__jresearch_publication`.`id` = `#__jresearch_inbook`.`id`_publication);

UPDATE `#__jresearch_publication` SET booktitle= (SELECT booktitle FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET publisher= (SELECT publisher FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET editor= (SELECT editor FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET organization= (SELECT organization FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET pages= (SELECT pages FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET key= (SELECT key FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);
UPDATE `#__jresearch_publication` SET crossref= (SELECT crossref FROM `#__jresearch_incollection` WHERE `#__jresearch_publication`.`id` = `#__jresearch_incollection`.`id`_publication);

UPDATE `#__jresearch_publication` SET organization= (SELECT organization FROM `#__jresearch_manual` WHERE `#__jresearch_publication`.`id` = `#__jresearch_manual`.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_manual` WHERE `#__jresearch_publication`.`id` = `#__jresearch_manual`.`id`_publication);
UPDATE `#__jresearch_publication` SET edition= (SELECT edition FROM `#__jresearch_manual` WHERE `#__jresearch_publication`.`id` = `#__jresearch_manual`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_manual` WHERE `#__jresearch_publication`.`id` = `#__jresearch_manual`.`id`_publication);

UPDATE `#__jresearch_publication` SET school= (SELECT school FROM `#__jresearch_mastersthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_mastersthesis`.`id`_publication);
UPDATE `#__jresearch_publication` SET type= (SELECT type FROM `#__jresearch_mastersthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_mastersthesis`.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_mastersthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_mastersthesis`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_mastersthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_mastersthesis`.`id`_publication);

UPDATE `#__jresearch_publication` SET id_member = (SELECT id_member FROM #__jresearch_mdm WHERE `#__jresearch_publication`.`id` = #__jresearch_mdm.`id`_publication);
UPDATE `#__jresearch_publication` SET month = (SELECT month FROM #__jresearch_mdm WHERE `#__jresearch_publication`.`id` = #__jresearch_mdm.`id`_publication);
UPDATE `#__jresearch_publication` SET description = (SELECT description FROM #__jresearch_mdm WHERE `#__jresearch_publication`.`id` = #__jresearch_mdm.`id`_publication);
UPDATE `#__jresearch_publication` SET published = (SELECT published FROM #__jresearch_mdm WHERE `#__jresearch_publication`.`id` = #__jresearch_mdm.`id`_publication);
UPDATE `#__jresearch_publication` SET checked_out = (SELECT checked_out FROM #__jresearch_mdm WHERE `#__jresearch_publication`.`id` = #__jresearch_mdm.`id`_publication);
UPDATE `#__jresearch_publication` SET checked_out_time = (SELECT checked_out_time FROM #__jresearch_mdm WHERE `#__jresearch_publication`.`id` = #__jresearch_mdm.`id`_publication);

UPDATE `#__jresearch_publication` SET howpublished= (SELECT howpublished FROM `#__jresearch_misc` WHERE `#__jresearch_publication`.`id` = `#__jresearch_misc`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_misc` WHERE `#__jresearch_publication`.`id` = `#__jresearch_misc`.`id`_publication);

UPDATE `#__jresearch_publication` SET patent_number = (SELECT patent_number FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET filing_date = (SELECT filing_date FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET issue_date = (SELECT issue_date FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET claims = (SELECT claims FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET drawings_dir = (SELECT drawings_dir FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET address = (SELECT address FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET country = (SELECT country FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);
UPDATE `#__jresearch_publication` SET office = (SELECT office FROM `#__jresearch_patent` WHERE `#__jresearch_publication`.`id` = `#__jresearch_patent`.`id`_publication);

UPDATE `#__jresearch_publication` SET school= (SELECT school FROM `#__jresearch_phdthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_phdthesis`.`id`_publication);
UPDATE `#__jresearch_publication` SET type= (SELECT type FROM `#__jresearch_phdthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_phdthesis`.`id`_publication);
UPDATE `#__jresearch_publication` SET address= (SELECT address FROM `#__jresearch_phdthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_phdthesis`.`id`_publication);
UPDATE `#__jresearch_publication` SET month= (SELECT month FROM `#__jresearch_phdthesis` WHERE `#__jresearch_publication`.`id` = `#__jresearch_phdthesis`.`id`_publication);

UPDATE `#__jresearch_publication` SET editor = (SELECT editor FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);
UPDATE `#__jresearch_publication` SET volume = (SELECT volume FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication)
UPDATE `#__jresearch_publication` SET number = (SELECT number FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);
UPDATE `#__jresearch_publication` SET series = (SELECT series FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);
UPDATE `#__jresearch_publication` SET address = (SELECT address FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);
UPDATE `#__jresearch_publication` SET month = (SELECT month FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);
UPDATE `#__jresearch_publication` SET publisher= (SELECT publisher FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);
UPDATE `#__jresearch_publication` SET organization = (SELECT organization FROM `#__jresearch_proceedings` WHERE `#__jresearch_publication`.`id` = `#__jresearch_proceedings`.`id`_publication);


