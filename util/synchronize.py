#!/usr/bin/python
## This scripts keeps my dev-sandbox and the repository synchronized.
## It copies the files from my dev-sandbox to the git repository
import sys
import shutil
import os
import distutils
from distutils import dir_util

#Default values
gitPrefix = '/home/luis/JResearch/jresearch/src/'
joomlaPrefix = '/opt/lampp/htdocs/jresearch/'

if len(sys.argv) > 1 :
	gitPrefix = sys.argv[1]

if len(sys.argv) > 2 :
	joomlaPrefix = sys.argv[2]

mappings = {'components/com_jresearch' : 'site',
'administrator/components/com_jresearch' : 'admin',
'plugins/editors-xtd/jresearch_automatic_bibliography_generation' : 'plugins/plg_jresearch_automatic_bibliography_generation',
'plugins/editors-xtd/jresearch_automatic_citation' : 'plugins/plg_jresearch_automatic_citation',
'plugins/system/jresearch_entities_load_cited_records' : 'plugins/plg_jresearch_entities_load_cited_records',
'plugins/system/jresearch_entities_save_cited_records' : 'plugins/plg_jresearch_entities_save_cited_records',
'plugins/system/jresearch_load_cited_records' : 'plugins/plg_jresearch_load_cited_records',
'plugins/content/jresearch_offline_citation' : 'plugins/plg_jresearch_offline_citation',
'plugins/content/jresearch_persistent_cited_records' : 'plugins/plg_jresearch_persistent_cited_records',
'plugins/search/jresearch_search' : 'plugins/plg_jresearch_search',
'administrator/components/com_jresearch/install.php' : 'install.php',
'administrator/components/com_jresearch/jresearch.xml' : 'jresearch.xml',
'language/en-GB/en-GB.com_jresearch.financiers.ini' : 'languages/site/en-GB.com_jresearch.financiers.ini',
'language/en-GB/en-GB.com_jresearch.ini' : 'languages/site/en-GB.com_jresearch.ini',
'language/en-GB/en-GB.com_jresearch.projects.ini' : 'languages/site/en-GB.com_jresearch.projects.ini',
'language/en-GB/en-GB.com_jresearch.publications.ini' : 'languages/site/en-GB.com_jresearch.publications.ini',
'language/en-GB/en-GB.com_jresearch.researchareas.ini' : 'languages/site/en-GB.com_jresearch.researchareas.ini',
'language/en-GB/en-GB.com_jresearch.staff.ini' : 'languages/site/en-GB.com_jresearch.staff.ini',
'language/en-GB/en-GB.mod_jresearch_keywords_cloud.ini' : 'modules/mod_jresearch_keywords_cloud/en-GB.mod_jresearch_keywords_cloud.ini',
'language/en-GB/en-GB.mod_jresearch_keywords_cloud.sys.ini' : 'modules/mod_jresearch_keywords_cloud/en-GB.mod_jresearch_keywords_cloud.sys.ini',
'administrator/language/en-GB/en-GB.com_jresearch.financiers.ini' : 'languages/admin/en-GB.com_jresearch.financiers.ini',
'administrator/language/en-GB/en-GB.com_jresearch.ini' : 'languages/admin/en-GB.com_jresearch.ini',
'administrator/language/en-GB/en-GB.com_jresearch.projects.ini' : 'languages/admin/en-GB.com_jresearch.projects.ini',
'administrator/language/en-GB/en-GB.com_jresearch.publications.ini' : 'languages/admin/en-GB.com_jresearch.publications.ini',
'administrator/language/en-GB/en-GB.com_jresearch.researchareas.ini' : 'languages/admin/en-GB.com_jresearch.researchareas.ini',
'administrator/language/en-GB/en-GB.com_jresearch.staff.ini' : 'languages/admin/en-GB.com_jresearch.staff.ini',
'administrator/language/en-GB/en-GB.com_jresearch.member_positions.ini'	: 'languages/admin/en-GB.com_jresearch.member_positions.ini',
'administrator/language/en-GB/en-GB.com_jresearch.sys.ini' : 'languages/admin/en-GB.com_jresearch.sys.ini',
'administrator/language/en-GB/en-GB-jresearch_help.html' : 'languages/admin/en-GB-jresearch_help.html',
'modules/mod_jresearch_keywords_cloud' : 'modules/mod_jresearch_keywords_cloud'
}

## TODO. Add mappings for every file in the languages folders.

toRemove = ['admin/install.php', 'admin/jresearch.xml']

for key, value in mappings.iteritems() :
	src = joomlaPrefix + key
	dst = gitPrefix + value
	if os.path.exists(src) :
		if os.path.isdir(src) and os.path.isdir(dst) :
			print 'Merging ' + src + ' and ' + dst
			distutils.dir_util.copy_tree(src, dst)
		else:
			print 'Copying ' + src + ' to ' + dst
			shutil.copy(src, dst)
	else :
		print 'Skipping', src

for fremove in toRemove :
	ftr = gitPrefix + fremove
	if os.path.exists(ftr) :
		print 'Removing ' + ftr
		os.remove(ftr)
