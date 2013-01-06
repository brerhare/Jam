#!/usr/bin/python

import os
import sys
import getopt
import array
from subprocess import call



if (len(sys.argv) != 2):
	sys.exit('Usage: ' + sys.argv[0] + ' webappname')
if (os.environ.get('DEVROOT', 'None') == 'None'):
	sys.exit('DEVROOT environment variable not set, aborting');

#if (len(os.environ['DEVROOT'] == 0)):
#	print "xxxx"
#DEVROOT = os.environ['DEVROOT']
#print "src = " + DEVROOT
print len(sys.argv)
print str(sys.argv)
print(sys.argv[0]);

#call ([DEVROOT + "/www/yii/framework/yiic", "webapp", "xxx"])

print 'y'
sys.exit('done')

dbUser = []
dbPass = []
webDir = ''

# This is kept here in the main file
backupBase = '/opt/websitebackups'

def ensure_clean_dir(f):
    if os.path.isdir(f):
	print 'removing old directory ' + f
        os.system('rm -r ' + f);
    print 'creating new directory ' + f
    os.mkdir(f)

assert(len(backupBase) > 3)

print 'backing up using ' + sys.argv[1]
f = open(sys.argv[1], "r")
while 1:
    line = f.readline()
    if not line:
        break
    if len(line) < 2:
        break
    fld = line.split()
    if 'dbName' in fld[0]:
        dbName.append(fld[1])
    if 'dbUser' in fld[0]:
        dbUser.append(fld[1])
    if 'dbPass' in fld[0]:
        dbPass.append(fld[1])
    if fld[0] == 'webDir':
        webDir = fld[1]
f.close()
assert(len(webDir) > 0)

backupFullPath = backupBase + '/' + os.path.basename(webDir)
ensure_clean_dir(backupFullPath)

for i in range(len(dbName)):
	if (len(dbName[i]) > 0):
		dumpCommand = 'mysqldump -u' + dbUser[i] + ' -p' + dbPass[i] + ' ' + dbName[i]
		print 'backing up database ' + dbName[i]
		os.system(dumpCommand + ' >' + backupFullPath + '/' + dbName[i] + '.sql')

print 'backing up ' + webDir + ' to ' + backupFullPath
os.system('cp -a -r ' + webDir + ' ' + backupFullPath) 
print 'done'


