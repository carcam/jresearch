ALTER TABLE `#__jresearch_project` ADD `id_research_area` VARCHAR( 1024 ) NOT NULL DEFAULT  '1';
ALTER TABLE `#__jresearch_research_area` ADD `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.';
ALTER TABLE `#__jresearch_member` ADD `files` text default NULL;
INSERT INTO `#__jresearch_property` (`name`) VALUES ('awards');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('comments');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('journal_acceptance_rate');
INSERT INTO `#__jresearch_property` (`name`) VALUES ('impact_factor');
DROP VIEW IF EXISTS `#__jresearch_all_project_authors`;
CREATE VIEW `#__jresearch_all_project_authors` AS SELECT DISTINCT `ia`.`id_staff_member` AS `mid`, `ia`.`id_project` AS `pid`, `ia`.`is_principal` AS `is_principal`, `ia`.`order` AS `order`, CONCAT_WS(', ', `m`.`lastname`, `m`.`firstname`) as `member_name` 
FROM `#__jresearch_project_internal_author` `ia` JOIN `#__jresearch_member` `m` WHERE `m`.`id` = `ia`.`id_staff_member`  
UNION SELECT DISTINCT `ea`.`author_name` AS `mid`, `ea`.`id_project` AS `pid`, `ea`.`is_principal` AS `is_principal`, `ea`.`order` AS `order`, `ea`.`author_name` as `member_name`
FROM `#__jresearch_project_external_author` `ea` ORDER BY `member_name` ASC;

DROP VIEW IF EXISTS `#__jresearch_all_publication_authors`;
CREATE VIEW `#__jresearch_all_publication_authors` AS SELECT DISTINCT `ia`.`id_staff_member` AS `mid`, `ia`.`id_publication` AS `pid`, CONCAT_WS(', ', `m`.`lastname`, `m`.`firstname`) as `member_name` FROM `#__jresearch_publication_internal_author` `ia` JOIN `#__jresearch_member` `m` WHERE `m`.`id` = `ia`.`id_staff_member`  
UNION SELECT DISTINCT `ea`.`author_name` AS `mid`, `ea`.`id_publication` AS `pid`, `ea`.`author_name` as `member_name`
FROM `#__jresearch_publication_external_author` `ea` ORDER BY `member_name` ASC;
