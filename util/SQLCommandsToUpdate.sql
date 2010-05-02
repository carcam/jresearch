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

UPDATE jos_jresearch_publication SET journal = (SELECT journal FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);

UPDATE jos_jresearch_publication SET number = (SELECT number FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);

UPDATE jos_jresearch_publication SET pages = (SELECT pages FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);

UPDATE jos_jresearch_publication SET month = (SELECT month FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);

UPDATE jos_jresearch_publication SET crossref = (SELECT crossref FROM jos_jresearch_article WHERE jos_jresearch_publication.id = jos_jresearch_article.id_publication);

UPDATE jos_jresearch_publication SET publisher= (SELECT publisher FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET editor = (SELECT editor FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET volume = (SELECT volume FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET number = (SELECT number FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET series = (SELECT series FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET address = (SELECT address FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET edition = (SELECT edition FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET month = (SELECT month FROM jos_jresearch_book WHERE jos_jresearch_publication.id = jos_jresearch_book.id_publication);

UPDATE jos_jresearch_publication SET howpublished= (SELECT howpublished FROM jos_jresearch_booklet WHERE jos_jresearch_publication.id = jos_jresearch_booklet.id_publication);

UPDATE jos_jresearch_publication SET address= (SELECT address FROM jos_jresearch_booklet WHERE jos_jresearch_publication.id = jos_jresearch_booklet.id_publication);

UPDATE jos_jresearch_publication SET month= (SELECT month FROM jos_jresearch_booklet WHERE jos_jresearch_publication.id = jos_jresearch_booklet.id_publication);