#!/bin/sh
find ./ -type f -name *.zip | xargs rm -f
rsync -av --progress ../src ./
cd src
VERSION=`xml_grep version pkg_jresearch_j3.xml --text`
VERSION=${VERSION// /_}
PACKAGE_FILENAME="pkg_jresearch_$VERSION.zip"
mkdir packages
zip  -r --exclude=*.svn* com_jresearch.zip jresearch.xml install.php admin site languages
mv com_jresearch.zip packages
cd plugins
zip  -r --exclude=*.svn* plg_jresearch_automatic_bibliography_generation.zip plg_jresearch_automatic_bibliography_generation
zip  -r --exclude=*.svn* plg_jresearch_automatic_citation.zip plg_jresearch_automatic_citation
zip  -r --exclude=*.svn* plg_jresearch_entities_load_cited_records.zip plg_jresearch_entities_load_cited_records
zip  -r --exclude=*.svn* plg_jresearch_entities_save_cited_records.zip plg_jresearch_entities_save_cited_records
zip  -r --exclude=*.svn* plg_jresearch_load_cited_records.zip plg_jresearch_load_cited_records
zip  -r --exclude=*.svn* plg_jresearch_offline_citation.zip plg_jresearch_offline_citation
zip  -r --exclude=*.svn* plg_jresearch_persistent_cited_records.zip plg_jresearch_persistent_cited_records
zip  -r --exclude=*.svn* plg_jresearch_search.zip plg_jresearch_search
cd ..
mv plugins/*.zip ./packages
zip  -r --exclude=*.svn* $PACKAGE_FILENAME install.php LICENSE.txt pkg_jresearch_j3.xml packages
mv $PACKAGE_FILENAME ..
cd ..
rm -r src
#find . \! -name "createpackage.sh" \! -name $PACKAGE_FILENAME | xargs rm -rf
