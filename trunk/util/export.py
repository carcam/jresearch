#!/usr/bin/python
# ##############
# Create a zip forder for the Jresearch component
# Website : http://joomla-research.com
# ##############

from zipfile import ZipFile, ZIP_DEFLATED
import os, pysvn
import sys

def RemoveLanguage(OutputFolder):
    # select lg  Fr  Es  De  It  Ro
    language = [ 0 , 0 , 0 , 0 , 0 ]
    os.chdir(OutputFolder)
    print os.getcwd()
    if language[0] == 0:
        RemoveRecurse("fr-FR")  
    if language[1] == 0:
        RemoveRecurse("es-ES")  
    if language[2] == 0:
        RemoveRecurse("de-DE")  
    if language[3] == 0:
        RemoveRecurse("it-IT")  
    # if language[4] == 0:
        # RemoveRecurse("ro-RO")  
    
    
def zippy(path, archive):
    paths = os.listdir(path)
    for p in paths:
        p = os.path.join(path, p) # Make the path relative
        if os.path.isdir(p): # Recursive case
            zippy(p, archive)
        else:
            archive.write(p) # Write the file to the zipfile
    return

def zipit(path, archname):
    # Create a ZipFile Object primed to write
    archive = ZipFile(archname, "a", ZIP_DEFLATED) # "a" to append, "r" to read
    # Recurse or not, depending on what path is
    if os.path.isdir(path):
        zippy(path, archive)
    else:
        archive.write(path)
    archive.close()

def RemoveRecurse(path):
    # """equivalent to rm -rf path"""
    for i in os.listdir(path):
        full_path = path + "/" + i
        if os.path.isdir(full_path):
            RemoveRecurse(full_path)
        else:
            os.remove(full_path)
      
    os.rmdir(path)
    
def CheckOutTrunk(OutputFolder):
    #check out the current version of the pysvn project
    client = pysvn.Client()
    client.export('http://joomlacode.org/svn/jresearch/trunk/src',OutputFolder)
    
if __name__=="__main__":
    directory = os.getcwd()
    OutputFolder = "export"
    directory = os.path.join(directory, OutputFolder)
    out = 0
    if len(sys.argv) >= 2:
        name = sys.argv[1]
    else:
        print "No imput parameter has been set. You need to add at list"
        print "one parameter, the release number A-Z,a-z,0-9, - , . , _ "
        name = raw_input("Please insert the release name or number ")
        out = 1
        

    zip_name = "com_jresearch_"+name+".zip"
    print "Step 1. Verify is a zip archive with the name "+zip_name+" exist"
    if os.path.exists(zip_name):
       print "Remove the zip arhive with the name "+zip_name
       os.remove(zip_name)
       
    zip_name = "../"+zip_name
    if os.path.exists(OutputFolder):
       RemoveRecurse(OutputFolder) # move to the end also
           
    print "Step 2. Check out from the trunk"
    CheckOutTrunk(OutputFolder)
    RemoveLanguage(OutputFolder)
    paths = os.listdir(directory)
    print "Step 3. Create the zip archive"
    for p in paths:
       os.chdir(directory)
       zipit(p,zip_name)
    
    print "Step 4. Clean the workfolder"
    if os.path.exists(OutputFolder):
       RemoveRecurse(OutputFolder)
       
    if out == 0:
        print "\nFinish the zip arhive"   
    else:
        print "\nFinish the zip arhive"   
        raw_input( '\nPress Enter to exit...' )