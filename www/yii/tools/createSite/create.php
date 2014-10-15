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












die;

foreach ($dbList as $dbItem => $dbValue):
	echo "Patching " . $dbItem . " ... ";
	$dbInitPaths = explode("|", $dbValue);
	if (gethostname() == "spleen")
		$dbInitPath = trim($dbInitPaths[0]);	
	else
		$dbInitPath = trim($dbInitPaths[1]);
	$dbInitFile = $dbInitPath . "/protected/data/dbinit.sh";
	if (file_exists($dbInitFile))
	{
		$dbLine = file($dbInitFile);
		$dbCommandArray = explode("<", $dbLine[0]);
		$dbCommand = trim($dbCommandArray[0]) . " < " . $argv[1];
		exec($dbCommand);
		echo "done\n";
	}
	else
	{
		echo "cant find file ". $dbInitFile . "\n";
	}
endforeach;


?>
