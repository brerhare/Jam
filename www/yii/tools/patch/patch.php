<?php

$testMode = 0;

require 'jellySites.inc';

if (defined('STDIN')) 	// ie running in commandline mode
{
	// Running from CLI 
	if (!isset($argv[1]))
		die("Needs patch file as argument\n");
	$patchFile = $argv[1];
	if (!file_exists($patchFile))
		die("Patch file $patchFile missing\n");
	require($patchFile);

	foreach ($siteList as $domain => $location):
		if (($testMode) && (!strstr($location, "demo")))
			continue;
		//echo "Patching $domain\n";
		patch($domain, $location);
	endforeach;
}
else
	echo "Nothing happening here - this needs to be run in commandline mode\n";

?>
