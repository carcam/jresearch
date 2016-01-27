ALTER TABLE `#__jresearch_publication` DROP INDEX `#__jresearch_publication_title_keywords_index`;
ALTER TABLE `#__jresearch_publication` DROP INDEX `#__jresearch_publication_full_index`;
ALTER TABLE `#__jresearch_publication` CREATE FULLTEXT INDEX `#__jresearch_publication_full_index`(`title`, `abstract`);
ALTER TABLE `#__jresearch_publication_keyword` CREATE FULLTEXT INDEX `#__jresearch_publication_keyword_keyword`(`keyword`);
ALTER TABLE `#__jresearch_member` ADD COLUMN `link_to_member` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `#__jresearch_member` ADD COLUMN `link_to_website` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__jresearch_member` ADD COLUMN `google_scholar` varchar(256) default NULL;
