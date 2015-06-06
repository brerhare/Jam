<?php

$testMode = 0;

require 'jellySites.inc';

if (defined('STDIN')) 	// ie running in commandline mode
{
	// Running from CLI 
	if (!isset($argv[1]))
		die("Needs a util file as argument\n");
	$utilFile = $argv[1];
	if (!file_exists($utilFile))
		die("Util file $utilFile missing\n");
	require($utilFile);

	if ((isset($argv[2])) && ($argv[2] == "1"))
		$testMode = 1;

	foreach ($siteList as $domain => $location):
		if (($testMode) && (!strstr($location, "demo")))
			continue;
		//echo "Patching $domain\n";
		util($domain, $location);
	endforeach;
}
else
	echo "Nothing happening here - this needs to be run in commandline mode\n";

// -----------------------------------------------

function dbExec($location, $commands) {
// mysql -u wireflydesign.co test_wireflydesign_com -p"wireflydesign.com," < jelly.sql
	$dbInit = $location . "/protected/data/dbinit.sh";
	if (!$dbFile = file_get_contents($dbInit))
		die("Failed to open $dbInit\n");
	if (!strstr($dbFile, "<"))
		die("No '<' found in $dbInit\n");
	$newInit = substr($dbFile, 0, strpos($dbFile, " < "));
	// Write out the commands to a tmp file
	$tmpName = tempnam("/tmp", "db-");
	if (!file_put_contents($tmpName, $commands))
		die("Failed to create temporary db commands file $tmpName\n");
	$newInit .= " < " . $tmpName . "\n";
	system($newInit);
	unlink($tmpName);
}

?>

