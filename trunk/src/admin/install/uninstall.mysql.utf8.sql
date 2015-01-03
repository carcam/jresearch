-- File: uninstall.sql
-- Uninstall SQL routine for component JResearch
-- Author: Luis Galarraga
-- Date: 28-05-2008 19:43:00

DROP VIEW IF EXISTS `#__jresearch_all_project_authors`;
DROP VIEW IF EXISTS `#__jresearch_all_publication_authors`;
DROP TABLE IF EXISTS `#__jresearch_cooperations`;
DROP TABLE IF EXISTS `#__jresearch_facilities`;
DROP TABLE IF EXISTS `#__jresearch_financier`;
DROP TABLE IF EXISTS `#__jresearch_member`;
DROP TABLE IF EXISTS `#__jresearch_project`;
DROP TABLE IF EXISTS `#__jresearch_project_external_author`;
DROP TABLE IF EXISTS `#__jresearch_project_financier`;
DROP TABLE IF EXISTS `#__jresearch_project_internal_author`;
DROP TABLE IF EXISTS `#__jresearch_publication`;
DROP TABLE IF EXISTS `#__jresearch_publication_external_author`;
DROP TABLE IF EXISTS `#__jresearch_publication_internal_author`;
DROP TABLE IF EXISTS `#__jresearch_research_area`;
DROP TABLE IF EXISTS `#__jresearch_thesis`;
DROP TABLE IF EXISTS `#__jresearch_thesis_external_author`;
DROP TABLE IF EXISTS `#__jresearch_thesis_internal_author`;
DROP TABLE IF EXISTS `#__jresearch_cited_records`;
DROP TABLE IF EXISTS `#__jresearch_publication_type`;
DROP TABLE IF EXISTS `#__jresearch_publication_researcharea`;
DROP TABLE IF EXISTS `#__jresearch_project_researcharea`;
DROP TABLE IF EXISTS `#__jresearch_thesis_researcharea`;
DROP TABLE IF EXISTS `#__jresearch_member_researcharea`;
DROP TABLE IF EXISTS `#__jresearch_research_area_team`;
DROP TABLE IF EXISTS `#__jresearch_publication_keyword`;
DROP TABLE IF EXISTS `#__jresearch_project_keyword`;
DROP TABLE IF EXISTS `#__jresearch_keyword`;
DROP TABLE IF EXISTS `#__jresearch_project_publication`;
DROP TABLE IF EXISTS `#__jresearch_member_position`;
DROP TABLE IF EXISTS `#__jresearch_project_cooperation`;
DROP TABLE IF EXISTS `#__jresearch_property`;
DROP TABLE IF EXISTS `#__jresearch_team`;
DROP TABLE IF EXISTS `#__jresearch_team_member`;

