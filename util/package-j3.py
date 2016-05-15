#!/usr/bin/python

import sys
import os
import re
from os.path import expanduser
from os.path import isfile
from os.path import exists
import xml.etree.ElementTree as ET

output = expanduser("~")
branch = 'master'
repository = '/home/luis/Documents/JResearch/nifdi2016/jresearch/'

if len(sys.argv) >= 2:
	repository = sys.argv[1]
	os.chdir(repository)

if len(sys.argv) >= 3 :
    output = sys.argv[2]

if len(sys.argv) > 4 :
	branch = sys.argv[3]

exportPath = output + '/jresearch'
os.chdir(repository)
# Export the repository
finalBranch = 'trunk' if branch == 'master' else 'branches/' + branch
command = 'svn export http://github.com/carcam/jresearch/' + finalBranch + "/src " + exportPath + " --force"
print 'Running ' + command
os.system(command)
print 'Done ' + command
# Package the plugins
os.chdir(exportPath)
## Get the version tag
tree = ET.parse('pkg_jresearch_j3.xml')
root = tree.getroot()
versionTag = ''
for child in root :
	if child.tag == 'version' :
		print 'Version ' + child.text + " detected"
		versionTag = re.sub('\W', '_', child.text).lower()

if exists('./packages') :
	os.system('rm -R packages')

print 'Running mkdir packages'
os.system('mkdir packages')
os.chdir('./plugins')
pluginsFolders = os.listdir(os.getcwd())
print pluginsFolders
for plugin in pluginsFolders :
	os.chdir(exportPath + '/plugins/' + plugin)
	command = 'zip -r ' + plugin + '.zip .'
	print 'Running ' + command
	os.system(command)
	print 'Done ' + command
	# Move the package to the packages folder
	command = 'mv ' + plugin + '.zip ' + exportPath + '/packages/'
	print 'Running command ' + command
	os.system(command)

# Package the main component
print 'Packaging...'
os.chdir(exportPath)
command = 'zip -r com_jresearch_' + versionTag + '.zip . -x ./modules/\* -x plugins/\* -x packages/\* -x otherlanguages/\* -x pkg_jresearch_j3.xml -x LICENSE.txt -x ./sef_ext/\*'
print 'Running ' + command
os.system(command)
# Move it to the packages section
command = 'mv com_jresearch_' + versionTag + '.zip packages/'
print 'Running ' + command
os.system(command)
command = 'zip -r jresearch_' + versionTag + '.zip . -x jresearch.xml -x index.html -x ./modules/\* -x ./plugins/\* -x ./otherlanguages/\* -x ./admin/\* -x ./site/\* -x ./sef_ext/\* -x ./languages/\*';
print 'Running ' + command
os.system(command)
print 'Installation package generated at ' + exportPath + '/jresearch_' + versionTag + '.zip'
