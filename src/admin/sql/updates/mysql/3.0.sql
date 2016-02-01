drop procedure if exists upgrade_to_3_0;
create procedure upgrade_to_3_0 ()
begin
		declare continue handler for 1060 begin end;
		declare continue handler for 1061 begin end;
		declare continue handler for 1091 begin end;
		ALTER TABLE `#__jresearch_publication` DROP INDEX `#__jresearch_publication_title_keywords_index`;
		ALTER TABLE `#__jresearch_publication` DROP INDEX `#__jresearch_publication_full_index`;
		ALTER TABLE `#__jresearch_publication` ADD FULLTEXT INDEX `#__jresearch_publication_full_index`(`title`, `abstract`);
		ALTER TABLE `#__jresearch_publication_keyword` ADD FULLTEXT INDEX `#__jresearch_publication_keyword_keyword`(`keyword`);
		ALTER TABLE `#__jresearch_member` ADD COLUMN `link_to_member` tinyint(1) NOT NULL DEFAULT '1';
		ALTER TABLE `#__jresearch_member` ADD COLUMN `link_to_website` tinyint(1) NOT NULL DEFAULT '0';
		ALTER TABLE `#__jresearch_member` ADD COLUMN `google_scholar` varchar(256) default NULL;
end;;
call procedure upgrade_to_3_0();;
drop procedure upgrade_to_3_0;
