<?php

require 'jellySites.inc';

if (defined('STDIN')) 	// ie running in commandline mode
{
	// Running from CLI 
	if (!isset($argv[1]))
	{
		//print_r($argv);
		die("Needs argument - sql file to apply to all the databases\n");
	}
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
}
else
	echo "Nothing happening - this needs to be run in commandline mode\n";


?>
