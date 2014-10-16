<?php

if (!(defined('STDIN'))) 	// ie running in commandline mode
	die ("This needs to be run in commandline mode - aborting\n");

if (!isset($argv[1]))
{
	die("Needs argument - eg 'manifest.somesite.co.uk' - aborting\n");
}

// Read the manifest into an associative array
echo "Reading in manifest...\n";
$file = $argv[1];
if (!($handle = fopen($file,'r')))
	die("Cant open manifest file " . $argv[1] . " - aborting\n");
$manifest = array(); 
while (!feof($handle)) 
{ 
	$line = fgets($handle); 
	if ($line == "")
		break;
	$line = str_replace("\n", "", $line);
	$temp = explode('=', $line); 
	$manifest[trim($temp[0])] = trim($temp[1]); 
} 
fclose($handle); 
print_r($manifest);

// The 'site' parameter must NOT be blank
if (trim($manifest['site']) == "")
	die("The 'site' entry in the manifest can not be blank - aborting\n");
if (strstr(strtolower($manifest['site']), 'www'))
	die("The site name should now have 'www' in it - aborting\n");

// Check the target location is ok
echo "Checking target location is ok...\n";
$baseDir = "/home/" . $manifest['site'];
$baseDirDev = "/home/" . $manifest['site'] . "/dev";
if (is_dir($baseDirDev))
	die($baseDir . " already has a jelly site in it - aborting\n");

// Copy the jelly skeleton to the location created by Virtualmin if it exists
echo "Creating 'dev' and cloning git repositories 'src' and 'extern...'\n";
if (!(mkdir($baseDirDev)))
	die("Couldnt create " . $baseDirDev . " - aborting\n");
if (!(chdir($baseDirDev)))
	die("Couldnt cd to " . $baseDirDev . " - aborting\n");
if (getcwd() != $baseDirDev)
	die("Was unable to cd to " . $baseDirDev . " - aborting\n");

// Git clone
echo "Cloning 'src'...\n";
exec("git clone /opt/dev/src");
echo "Cloning 'extern'...\n";
exec("git clone /opt/dev/extern");

// Copy the jelly skeleton
echo "Copying jelly skeleton...\n";
$siteParentDir = $baseDirDev . "/src/www/yii/";
exec("cp -r " . $siteParentDir . "jelly_skeleton" . " " . $siteParentDir . $manifest['site']);
if (!(is_dir($siteParentDir . $manifest['site'])))
	die("Couldnt copy jelly skeleton to " . $manifest['site'] . " - aborting\n");

// Set permissions
echo "Setting permissions...\n";
exec("chown -R " . $manifest['site'] . " " . $baseDirDev);
exec("chgrp -R " . $manifest['site'] . " " . $baseDirDev);

// Create the apache conf file
echo "Creating Apache config...\n";
if (!($apache = file_get_contents($baseDirDev . "/src/www/yii/tools/createSite/apache.conf")))
	die("Failed to read apache.conf file - aborting\n");
$apache = str_replace("<site>", $manifest['site'], $apache);
if (!(file_put_contents("/etc/apache2/sites-available/" . $manifest['site'] . ".conf", $apache)))
	die("Failed to move the apache conf file to the apache location - aborting\n");

// Edit protected/data/dbinit.sh
echo "Importing Jelly basics ...\n";
$siteDir = $siteParentDir . $manifest['site'];
if (!($dbInit = file_get_contents($siteDir . "/protected/data/dbinit.sh")))
	die("Failed to read dbinit.sh file - aborting\n");
$dbInit = str_replace("<username>", $manifest['dbuser'], $dbInit);
$dbInit = str_replace("<dbname>", $manifest['dbname'], $dbInit);
$dbInit = str_replace("<password>", $manifest['dbpass'], $dbInit);
if (!(file_put_contents($siteDir . "/protected/data/dbinit.sh", $dbInit)))
    die("Failed to update dbinit.sh - aborting\n");
$temp = getcwd();
chdir($siteDir . "/protected/data/");
exec("./dbinit.sh");
chdir($temp);

// Edit protected/config/main.php
echo "Setting site parameters...\n";
$siteDir = $siteParentDir . $manifest['site'];
if (!($main = file_get_contents($siteDir . "/protected/config/main.php")))
	die("Failed to read protected/config/main.php file - aborting\n");
$main = str_replace("<sitetitle>", $manifest['sitetitle'], $main);
$main = str_replace("<dbname>", $manifest['dbname'], $main);
$main = str_replace("<dbuser>", $manifest['dbuser'], $main);
$main = str_replace("<dbpass>", $manifest['dbpass'], $main);
$main = str_replace("<sid>", $manifest['sid'], $main);
$main = str_replace("<checkoutname>", $manifest['checkoutname'], $main);
$main = str_replace("<checkoutemail>", $manifest['checkoutemail'], $main);
if (!(file_put_contents($siteDir . "/protected/config/main.php", $main)))
    die("Failed to update protect/config/main.php - aborting\n");

// Edit protected/backend/config/main.php
echo "Setting backend site parameters...\n";
$siteDir = $siteParentDir . $manifest['site'];
if (!($main = file_get_contents($siteDir . "/protected/backend/config/main.php")))
	die("Failed to read protected/backend/config/main.php file - aborting\n");
$main = str_replace("<sitetitle>", $manifest['sitetitle'] . " Backend", $main);
$main = str_replace("<dbname>", $manifest['dbname'], $main);
$main = str_replace("<dbuser>", $manifest['dbuser'], $main);
$main = str_replace("<dbpass>", $manifest['dbpass'], $main);
if (!(file_put_contents($siteDir . "/protected/backend/config/main.php", $main)))
    die("Failed to update protect/backend/config/main.php - aborting\n");




echo "\nDone. Dont forget to restart Apache\n"

?>
