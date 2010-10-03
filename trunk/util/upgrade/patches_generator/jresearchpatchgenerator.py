#!/usr/bin/python

import os
import sys
from optparse import OptionParser

def parseOptions():
	#Arguments parsing
	optionParser = OptionParser()
	optionParser.add_option('--oldtag', action='store', dest='oldtag')
	optionParser.add_option('--newtag', action='store', dest='newtag')
	optionParser.add_option('--oldbranch', action='store', dest='oldbranch')
	optionParser.add_option('--newbranch', action='store', dest='newbranch')	
	return optionParser.parse_args()

def patchgenerator():
	oldPath = ''
	newPath = ''
	tagPath = 'http://joomlacode.org/svn/jresearch/tags/'
    	# Parameters definition
    	options, arguments = parseOptions()
	if options.oldtag is None or options.newtag is None: 
		 if options.oldbranch is None or options.newbranch is None:
			sys.exit(2);
		 else:
			oldPath = options.oldbranch
			newPath = options.newbranch
	else:
		oldPath = tagPath + options.oldtag
		newPath = tagPath + options.newtag

	#diff command
	print "Generating diff files......"
	command1 = 'java -jar svnexportdiff.jar diff ' + oldPath + ' ' + newPath + ' ./diff'
	print command1
	os.system(command1)
	print "Exporting the modified files.. this might take some time..."
	#export command
	command2 = 'java -jar svnexportdiff.jar export diff ' + oldPath + ' ' + newPath + ' ./output/'
	print command2
	os.system(command2)
	#zip command	
	print "Packaging the files...."
	command3 = 'java -jar svnexportdiff.jar zip ./output/'
	print command3
	os.system(command3)
	print "Update patch successfully generated :)"

if __name__ == '__main__':
	patchgenerator()
