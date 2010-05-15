CREATE TABLE  `labs`.`jos_jresearch_member_position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE  `labs`.`jos_jresearch_project_cooperation` (
  `id_project` int(10) unsigned NOT NULL,
  `id_cooperation` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_project`,`id_cooperation`)
);

ALTER TABLE jos_jresearch_cooperations
ADD (	 `alias` varchar(255),
`catid` int(11)
);

ALTER TABLE jos_jresearch_facilities
ADD (	 `alias` varchar(255),
);

ALTER TABLE jos_jresearch_member
ADD (	 `location` varchar(50),
);

ALTER TABLE jos_jresearch_project
ADD (	 `alias` varchar(255),
`files` text 
);

ALTER TABLE jos_jresearch_publication
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

ALTER TABLE jos_jresearch_research_area
ADD (	 `alias` varchar(255),
);

ALTER TABLE jos_jresearch_team
ADD (	 `alias` varchar(255) NOT NULL,
`parent` int(11) unsigned DEFAULT NULL
);

ALTER TABLE jos_jresearch_thesis
ADD (	 `alias` varchar(255) NOT NULL,
`files` text,
);

UPDATE jos_jresearch_publication SET journal = (SELECT journal FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);
UPDATE jos_jresearch_publication SET number = (SELECT number FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);
UPDATE jos_jresearch_publication SET pages = (SELECT pages FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);
UPDATE jos_jresearch_publication SET month = (SELECT month FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);
UPDATE jos_jresearch_publication SET crossref = (SELECT crossref FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);

UPDATE jos_jresearch_publication SET publisher= (SELECT publisher FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);
UPDATE jos_jresearch_publication SET editor = (SELECT editor FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);
UPDATE jos_jresearch_publication SET volume = (SELECT volume FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication)
UPDATE jos_jresearch_publication SET number = (SELECT number FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);
UPDATE jos_jresearch_publication SET series = (SELECT series FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);
UPDATE jos_jresearch_publication SET address = (SELECT address FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);
UPDATE jos_jresearch_publication SET edition = (SELECT edition FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);
UPDATE jos_jresearch_publication SET month = (SELECT month FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET howpublished= (SELECT howpublished FROM jos_jresearch_booklet WHERE jos_jresearch_publication.id = jos_jresearch_booklet.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_booklet WHERE jos_jresearch_publication.id = jos_jresearch_booklet.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_booklet WHERE jos_jresearch_publication.id = jos_jresearch_booklet.id_publication);

UPDATE jos_jresearch_publication SET editor= (SELECT editor FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET volume= (SELECT volume FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET booktitle= (SELECT booktitle FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET number= (SELECT number FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET series= (SELECT series FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET pages= (SELECT pages FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET publisher= (SELECT publisher FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET organization= (SELECT organization FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);
UPDATE jos_jresearch_publication SET crossref= (SELECT crossref FROM jos_jresearch_conference WHERE jos_jresearch_publication.id = jos_jresearch_conference.id_publication);


UPDATE jos_jresearch_publication SET howpublished= (SELECT howpublished FROM jos_jresearch_inbook WHERE jos_jresearch_publication.id = jos_jresearch_inbook.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_inbook WHERE jos_jresearch_publication.id = jos_jresearch_inbook.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_inbook WHERE jos_jresearch_publication.id = jos_jresearch_inbook.id_publication);

UPDATE jos_jresearch_publication SET booktitle= (SELECT booktitle FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET publisher= (SELECT publisher FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET editor= (SELECT editor FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET organization= (SELECT organization FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET pages= (SELECT pages FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET key= (SELECT key FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);
UPDATE jos_jresearch_publication SET crossref= (SELECT crossref FROM jos_jresearch_incollection WHERE jos_jresearch_publication.id = jos_jresearch_incollection.id_publication);

UPDATE jos_jresearch_publication SET organization= (SELECT organization FROM jos_jresearch_manual WHERE jos_jresearch_publication.id = jos_jresearch_manual.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_manual WHERE jos_jresearch_publication.id = jos_jresearch_manual.id_publication);
UPDATE jos_jresearch_publication SET edition= (SELECT edition FROM jos_jresearch_manual WHERE jos_jresearch_publication.id = jos_jresearch_manual.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_manual WHERE jos_jresearch_publication.id = jos_jresearch_manual.id_publication);

UPDATE jos_jresearch_publication SET school= (SELECT school FROM jos_jresearch_mastherthesis WHERE jos_jresearch_publication.id = jos_jresearch_mastherthesis.id_publication);
UPDATE jos_jresearch_publication SET type= (SELECT type FROM jos_jresearch_mastherthesis WHERE jos_jresearch_publication.id = jos_jresearch_mastherthesis.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_mastherthesis WHERE jos_jresearch_publication.id = jos_jresearch_mastherthesis.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_mastherthesis WHERE jos_jresearch_publication.id = jos_jresearch_mastherthesis.id_publication);

UPDATE jos_jresearch_publication SET id_member = (SELECT id_member FROM jos_jresearch_mdm WHERE jos_jresearch_publication.id = jos_jresearch_mdm.id_publication);
UPDATE jos_jresearch_publication SET month = (SELECT month FROM jos_jresearch_mdm WHERE jos_jresearch_publication.id = jos_jresearch_mdm.id_publication);
UPDATE jos_jresearch_publication SET description = (SELECT description FROM jos_jresearch_mdm WHERE jos_jresearch_publication.id = jos_jresearch_mdm.id_publication);
UPDATE jos_jresearch_publication SET published = (SELECT published FROM jos_jresearch_mdm WHERE jos_jresearch_publication.id = jos_jresearch_mdm.id_publication);
UPDATE jos_jresearch_publication SET checked_out = (SELECT checked_out FROM jos_jresearch_mdm WHERE jos_jresearch_publication.id = jos_jresearch_mdm.id_publication);
UPDATE jos_jresearch_publication SET checked_out_time = (SELECT checked_out_time FROM jos_jresearch_mdm WHERE jos_jresearch_publication.id = jos_jresearch_mdm.id_publication);

UPDATE jos_jresearch_publication SET howpublished= (SELECT howpublished FROM jos_jresearch_misc WHERE jos_jresearch_publication.id = jos_jresearch_misc.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_misc WHERE jos_jresearch_publication.id = jos_jresearch_misc.id_publication);

UPDATE jos_jresearch_publication SET patent_number = (SELECT patent_number FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET filing_date = (SELECT filing_date FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET issue_date = (SELECT issue_date FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET claims = (SELECT claims FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET drawings_dir = (SELECT drawings_dir FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET address = (SELECT address FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET country = (SELECT country FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);
UPDATE jos_jresearch_publication SET office = (SELECT office FROM jos_jresearch_patent WHERE jos_jresearch_publication.id = jos_jresearch_patent.id_publication);

UPDATE jos_jresearch_publication SET school= (SELECT school FROM jos_jresearch_phdthesis WHERE jos_jresearch_publication.id = jos_jresearch_phdthesis.id_publication);
UPDATE jos_jresearch_publication SET type= (SELECT type FROM jos_jresearch_phdthesis WHERE jos_jresearch_publication.id = jos_jresearch_phdthesis.id_publication);
UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_phdthesis WHERE jos_jresearch_publication.id = jos_jresearch_phdthesis.id_publication);
UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_phdthesis WHERE jos_jresearch_publication.id = jos_jresearch_phdthesis.id_publication);

UPDATE jos_jresearch_publication SET editor = (SELECT editor FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);
UPDATE jos_jresearch_publication SET volume = (SELECT volume FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication)
UPDATE jos_jresearch_publication SET number = (SELECT number FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);
UPDATE jos_jresearch_publication SET series = (SELECT series FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);
UPDATE jos_jresearch_publication SET address = (SELECT address FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);
UPDATE jos_jresearch_publication SET month = (SELECT month FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);
UPDATE jos_jresearch_publication SET publisher= (SELECT publisher FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);
UPDATE jos_jresearch_publication SET organization = (SELECT organization FROM jos_jresearch_proceedings WHERE jos_jresearch_publication.id = jos_jresearch_proceedings.id_publication);


