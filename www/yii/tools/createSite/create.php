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
	if ($line[0] == '#')
		continue;
	if (!(strstr($line, "=")))
		continue;
	if ($line == "")
		break;
	$line = str_replace("\n", "", $line);
	$temp = explode('=', $line); 
	if (count($temp) == 0)
		continue;
	$manifest[trim($temp[0])] = trim($temp[1]); 
} 
fclose($handle); 
print_r($manifest);

// The 'domain' parameter must NOT be blank
if (trim($manifest['domain']) == "")
	die("The 'domain' entry in the manifest can not be blank - aborting\n");
if (strstr(strtolower($manifest['domain']), 'www'))
	die("The domain name should now have 'www' in it - aborting\n");

// Check the target location is ok
echo "Checking target location is ok...\n";
$baseDir = "/home/" . $manifest['domain'];
if (trim($manifest['parentdomain']) != "") {
	$baseDir = "/home/" . $manifest['parentdomain'];
	$baseDir .= "/domains/" . $manifest['domain'];
}
$baseDirDev = $baseDir. "/dev";
if (is_dir($baseDirDev))
	die($baseDir . " already has a jelly site in it - aborting\n");

// Copy the jelly skeleton to the location created by Virtualmin if it exists
echo "Creating ($baseDir)" . "/'dev' and cloning git repositories 'src' and 'extern...'\n";
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
exec("cp -r " . $siteParentDir . "jelly_skeleton" . " " . $siteParentDir . $manifest['domain']);
if (!(is_dir($siteParentDir . $manifest['domain'])))
	die("Couldnt copy jelly skeleton to " . $manifest['domain'] . " - aborting\n");

// Set permissions
echo "Setting permissions...\n";
if (trim($manifest['parentdomain']) == "") {
	exec("chown -R " . $manifest['domain'] . " " . $baseDirDev);
	exec("chgrp -R " . $manifest['domain'] . " " . $baseDirDev);
} else {
	exec("chown -R " . $manifest['parentdomain'] . " " . $baseDirDev);
	exec("chgrp -R " . $manifest['parentdomain'] . " " . $baseDirDev);
}

// Patch the apache conf file to reflect the home location (in 2 places)
echo "Patching Apache config...\n";
if (!($apache = file_get_contents("/etc/apache2/sites-available/" . $manifest['domain'] . ".conf")))
	die("Failed to read apache.conf file - aborting\n");
$apache = str_replace("DocumentRoot " . $baseDir. "/public_html", "DocumentRoot " . $baseDir . "/dev/src/www/yii/" . $manifest['domain'], $apache);
$apache = str_replace("<Directory " . $baseDir . "/public_html>", "<Directory " . $baseDir . "/dev/src/www/yii/" . $manifest['domain'] . ">", $apache);
if (!(file_put_contents("/etc/apache2/sites-available/" . $manifest['domain'] . ".conf", $apache)))
	die("Failed to replace the patched apache conf file in the apache location - aborting\n");

// Edit protected/data/dbinit.sh
echo "Importing Jelly basics ...\n";
$siteDir = $siteParentDir . $manifest['domain'];
if (!($dbInit = file_get_contents($siteDir . "/protected/data/dbinit.sh")))
	die("Failed to read dbinit.sh file - aborting\n");
$dbInit = str_replace("<username>", $manifest['dbuser'], $dbInit);
$dbInit = str_replace("<dbname>", $manifest['dbname'], $dbInit);
$dbInit = str_replace("<dbpass>", $manifest['dbpass'], $dbInit);
if (!(file_put_contents($siteDir . "/protected/data/dbinit.sh", $dbInit)))
    die("Failed to update dbinit.sh - aborting\n");
$temp = getcwd();
chdir($siteDir . "/protected/data/");
exec("./dbinit.sh");
chdir($temp);

// Edit protected/backend/views/site/index.php (analytics)
echo "Setting up site analytics...\n";
$siteDir = $siteParentDir . $manifest['domain'];
if (!($index = file_get_contents($siteDir . "/protected/backend/views/site/index.php")))
	die("Failed to read protected/backend/views/site/index.php file - aborting\n");
if (trim($manifest['parentdomain']) == "")
	$index = str_replace("<site>", $manifest['domain'], $index);
else
	$index = str_replace("<site>", $manifest['parentdomain'], $index);
$index = str_replace("<pass>", $manifest['dbpass'], $index);
if (!(file_put_contents($siteDir . "/protected/backend/views/site/index.php", $index)))
    die("Failed to update protected/backend/views/site/index.php - aborting\n");

// Edit protected/config/main.php
echo "Setting site parameters...\n";
$siteDir = $siteParentDir . $manifest['domain'];
if (!($main = file_get_contents($siteDir . "/protected/config/main.php")))
	die("Failed to read protected/config/main.php file - aborting\n");
$main = str_replace("<sitetitle>", $manifest['sitetitle'], $main);
$main = str_replace("<dbname>", $manifest['dbname'], $main);
$main = str_replace("<dbuser>", $manifest['dbuser'], $main);
$main = str_replace("<dbpass>", $manifest['dbpass'], $main);
$main = str_replace("<sid>", $manifest['sid'], $main);
$main = str_replace("<checkoutname>", $manifest['checkoutname'], $main);
$main = str_replace("<checkoutemail>", $manifest['checkoutemail'], $main);
if (isset($manifest['checkoutpaypalemail'])) $main = str_replace("<checkoutpaypalemail>", $manifest['checkoutpaypalemail'], $main);
if (!(file_put_contents($siteDir . "/protected/config/main.php", $main)))
    die("Failed to update protected/config/main.php - aborting\n");

// Edit protected/backend/config/main.php
echo "Setting backend site parameters...\n";
$siteDir = $siteParentDir . $manifest['domain'];
if (!($main = file_get_contents($siteDir . "/protected/backend/config/main.php")))
	die("Failed to read protected/backend/config/main.php file - aborting\n");
$main = str_replace("<sitetitle>", $manifest['sitetitle'] . " Backend", $main);
$main = str_replace("<dbname>", $manifest['dbname'], $main);
$main = str_replace("<dbuser>", $manifest['dbuser'], $main);
$main = str_replace("<dbpass>", $manifest['dbpass'], $main);
$main = str_replace("<editorpagewidth>", $manifest['editorpagewidth'], $main);
$main = str_replace("<editorpageheight>", $manifest['editorpageheight'], $main);
if (!(file_put_contents($siteDir . "/protected/backend/config/main.php", $main)))
    die("Failed to update protected/backend/config/main.php - aborting\n");

echo "\nDone\n";
echo "Add this site into /root/.bashrc\n";
echo "Add this site into /root/setperms\n";
echo "Add this site into tools/util/jellySites.inc\n";
echo "Add apache lines\n";
echo " - ScriptAlias /jamcgi/ /home/SITE/dev/src/www/yii/SITE/jam/cgi/\n";
echo " - <Directory /home/SITE/dev/src/www/yii/SITE/jam/cgi> ..... </Directory>   (copy of the cgi-bin lines)\n";
echo "Dont forget to restart Apache\n";

?>
