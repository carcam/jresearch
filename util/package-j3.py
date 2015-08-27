#!/usr/bin/python

import sys
import os
from os.path import expanduser
from os.path import isfile

home = None
if len(sys.argv) < 2 :
    home = expanduser("~")
else :
    home = sys.argv[1]

exportPath = sys.argv[1] + '/jresearch'
# Export the repository
command = 'svn export ../src/ ' + exportPath + ' --force'
print 'Running ' + command
os.system(command)
# Package the plugins
os.chdir(exportPath)
if not isfile('./packages') :
    print 'Running mkdir packages'
    os.system('mkdir packages') 

os.chdir('./plugins')
pluginsFolders = os.listdir(os.getcwd())

for plugin in pluginsFolders :
    os.chdir(exportPath + '/plugins/' + plugin)
    command = 'zip -r ' + plugin + '.zip .'
    print 'Running ' + command
    os.system(command)
    # Move the package to the packages folder
    command = 'mv ' + plugin + '.zip ../packages'
    os.system(command)

# Package the main component
os.chdir(exportPath)
versionTag = sys.argv[2]
command = 'zip -r com_jresearch_' + versionTag + '.zip . -x modules -x plugins -x packages -x otherlanguages -x pkg_jresearch_j3.xml -x LICENSE.txt -x sef_ext'
print 'Running ' + command
os.system(command)
# Move it to the packages section
command = 'mv com_jresearch_' + versionTag + '.zip packages/'
print 'Running ' + command
os.system(command)
command = 'zip -r jresearch_' + versionTag + '.zip . -x modules -x plugins -x otherlanguages -x admin -x site -x sef_ext -x languages';
print 'Running ' + command
os.system(command)
print 'Installation package generated at ' + exportPath + '/jresearch_' + versionTag + '.zip'; 