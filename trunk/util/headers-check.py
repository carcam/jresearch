#!/usr/bin/python

import os
import sys
import re

rootdir = sys.argv[1]
license = "@license"
jexec = "_JEXEC"

for root, subFolders, files in os.walk(rootdir):

	for filename in files:
		filePath = os.path.join(root, filename)
		if filePath.endswith('.php') :

			with open( filePath, 'r' ) as f:
				content = f.read()
				if not license in content :
					print "%s does not contain license notice." %(filePath)
				if not jexec in content :
					print "%s does not contain _JEXEC verification." %(filePath)
