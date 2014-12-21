#!/usr/bin/python

# http://scotch.io/tutorials/javascript/angularjs-best-practices-directory-structure

import os
import sys
import subprocess
import shutil
import array
import json
from pprint import pprint
import collections

skeletonDir = os.path.abspath(os.path.dirname(sys.argv[0]))

if not os.path.isdir(skeletonDir):
	sys.exit("Internal error - " + skeletonDir + " doesnt exist. Abortings")

if (len(sys.argv) != 2):
	sys.exit("Usage: " + sys.argv[0] + " [new project dir] ")

baseDir = sys.argv[1]
if os.path.isdir(baseDir):
	sys.exit("Directory " + baseDir + " already exists. Aborting")

#-------------------------------------------------------------------------------------------
# Setup dirs and config files

print "Creating structure in " + baseDir
shutil.copytree (skeletonDir + '/newbuild', baseDir)

buffer = open(baseDir + "/package.json", 'rU').read()
buffer = buffer.replace("newbuild", baseDir)
open(baseDir + "/package.json", "w").write(buffer)

buffer = open(baseDir + "/bower.json", 'rU').read()
buffer = buffer.replace("newbuild", baseDir)
open(baseDir + "/bower.json", "w").write(buffer)

buffer = open(baseDir + "/src/index.html", 'rU').read()
buffer = buffer.replace("newbuild", baseDir)
open(baseDir + "/src/index.html", "w").write(buffer)

buffer = open(baseDir + "/src/app/app.routes.js", 'rU').read()
buffer = open(baseDir + "/src/app/app.module.js", 'rU').read()
buffer = buffer.replace("newbuild", baseDir)
open(baseDir + "/src/app/app.module.js", "w").write(buffer)

buffer = open(baseDir + "/src/app/app.routes.js", 'rU').read()
buffer = buffer.replace("newbuild", baseDir)
open(baseDir + "/src/app/app.routes.js", "w").write(buffer)

print "Change dir to " + baseDir
startDir = os.getcwd()
os.chdir(baseDir)
if (os.getcwd() != (startDir + "/" + baseDir)):
	sys.exit("Cant cd to " + baseDir)

#-------------------------------------------------------------------------------------------
# Bower installs

def bower_install(manifest):
	print "Installing bower packages as per " + manifest
	jsonData = open(manifest)
	data = json.load(jsonData, object_pairs_hook=collections.OrderedDict)
	jsonData.close()
	for section, packages in data.items():
		print section
		for package in packages.items():
			if section in ("dependencies", "devDependencies"):
				save = "--save"
				if (section == "devDependencies"):
					save = "--save-dev"
				print "bower install " + package[0] + " " + save 
				callStr = "bower install " + package[0] + " " + save
				#subprocess.call([callStr, shell=True])
				os.system(callStr)

bower_install(skeletonDir + "/bower.manifest.core")
bower_install(skeletonDir + "/bower.manifest.additional")

#-------------------------------------------------------------------------------------------
# NPM installs

def npm_install(manifest):
	print "Installing npm packages as per " + manifest
	jsonData = open(manifest)
	data = json.load(jsonData, object_pairs_hook=collections.OrderedDict)
	jsonData.close()
	for section, packages in data.items():
		print section
		for package in packages.items():
			if section in ("dependencies", "devDependencies"):
				save = "--save"
				if (section == "devDependencies"):
					save = "--save-dev"
				print "npm install " + package[0] + " " + save 
				callStr = "npm install " + package[0] + " " + save
				#subprocess.call([callStr, shell=True])
				os.system(callStr)

npm_install(skeletonDir + "/npm.manifest")



# shared
# ------
# <!-- user a slider directive to loop over something -->
# <slider id="article-slider" ng-repeat="picture in pictures" size="large" type="square">
# </slider>

# assets
# ------
# img/      // Images and icons for your app
# css/      // All styles and style related files (SCSS or LESS files)
# js/       // JavaScript files written for your app that are not for angular
# libs/     // Third-party libraries such as jQuery, Moment, Underscore, etc.

def str2File(path, str):
	file = open(path, "w")
	file.write(str + "\n")
	file.close()

print
print "bower installed bower.manifest.core and updated /src/app/app.module.js"
print "bower installed bower.manifest.additional but did NOT update /src/app/app.module.js. Do it yourself. Now"
sys.exit("Fin")

