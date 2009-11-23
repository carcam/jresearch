-- File: uninstall.sql
-- Uninstall SQL routine for component JResearch
-- Author: Luis Galarraga
-- Date: 28-05-2008 19:43:00

DROP TABLE IF EXISTS `#__jresearch_article`;
DROP TABLE IF EXISTS `#__jresearch_book`;
DROP TABLE IF EXISTS `#__jresearch_booklet`;
DROP TABLE IF EXISTS `#__jresearch_citing_style`;
DROP TABLE IF EXISTS `#__jresearch_conference`;
DROP TABLE IF EXISTS `#__jresearch_cooperations`;
DROP TABLE IF EXISTS `#__jresearch_facilities`;
DROP TABLE IF EXISTS `#__jresearch_financier`;
DROP TABLE IF EXISTS `#__jresearch_inbook`;
DROP TABLE IF EXISTS `#__jresearch_incollection`;
DROP TABLE IF EXISTS `#__jresearch_institutes`;
DROP TABLE IF EXISTS `#__jresearch_manual`;
DROP TABLE IF EXISTS `#__jresearch_mastersthesis`;
DROP TABLE IF EXISTS `#__jresearch_member`;
DROP TABLE IF EXISTS `#__jresearch_misc`;
DROP TABLE IF EXISTS `#__jresearch_patent`;
DROP TABLE IF EXISTS `#__jresearch_phdthesis`;
DROP TABLE IF EXISTS `#__jresearch_proceedings`;
DROP TABLE IF EXISTS `#__jresearch_project`;
DROP TABLE IF EXISTS `#__jresearch_project_external_author`;
DROP TABLE IF EXISTS `#__jresearch_project_financier`;
DROP TABLE IF EXISTS `#__jresearch_project_internal_author`;
DROP TABLE IF EXISTS `#__jresearch_publication`;
DROP TABLE IF EXISTS `#__jresearch_publication_comment`;
DROP TABLE IF EXISTS `#__jresearch_publication_config_custom_citing_style`;
DROP TABLE IF EXISTS `#__jresearch_publication_external_author`;
DROP TABLE IF EXISTS `#__jresearch_publication_internal_author`;
DROP TABLE IF EXISTS `#__jresearch_research_area`;
DROP TABLE IF EXISTS `#__jresearch_techreport`;
DROP TABLE IF EXISTS `#__jresearch_thesis`;
DROP TABLE IF EXISTS `#__jresearch_thesis_external_author`;
DROP TABLE IF EXISTS `#__jresearch_thesis_internal_author`;
DROP TABLE IF EXISTS `#__jresearch_unpublished`;
DROP TABLE IF EXISTS `#__jresearch_cited_records`;
DROP TABLE IF EXISTS `#__jresearch_publication_type`;
DROP TABLE IF EXISTS `#__jresearch_online_source`;
DROP TABLE IF EXISTS `#__jresearch_digital_source`;
DROP TABLE IF EXISTS `#__jresearch_earticle`;
DROP VIEW `#__jresearch_publication_article`;
DROP VIEW `#__jresearch_publication_earticle`;
DROP VIEW `#__jresearch_publication_digital_source`;
DROP VIEW `#__jresearch_publication_online_source`;
DROP VIEW `#__jresearch_publication_unpublished`;
DROP VIEW `#__jresearch_publication_proceedings`;
DROP VIEW `#__jresearch_publication_book`;
DROP VIEW `#__jresearch_publication_incollection`;
DROP VIEW `#__jresearch_publication_booklet`;
DROP VIEW `#__jresearch_publication_conference`;
DROP VIEW `#__jresearch_publication_inbook`;
DROP VIEW `#__jresearch_publication_patent`;
DROP VIEW `#__jresearch_publication_misc`;
DROP VIEW `#__jresearch_publication_phdthesis`;
DROP VIEW `#__jresearch_publication_mastersthesis`;
DROP VIEW `#__jresearch_publication_manual`;
DROP VIEW `#__jresearch_publication_techreport`;