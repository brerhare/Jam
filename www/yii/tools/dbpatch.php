<?php

$dbList = array (
	'beirc.co.uk'                => '/home/kim/dev/src/www/yii/beirc.co.uk | /home/beirc.co.uk/dev/src/www/yii/beirc.co.uk',
	'dumfriesfurniture.com'      => '/home/kim/dev/src/www/yii/dumfriesfurniture.com | /home/dumfriesfurniture.com/dev/src/www/yii/dumfriesfurniture.com',
	//'glitzaratti.com'            => '/home/kim/dev/src/www/yii/glitzaratti.com | /home/glitzaratti.com/dev/src/www/yii/glitzaratti.com',
	'jacquiesbeauty.co.uk'       => '/home/kim/dev/src/www/yii/jacquiesbeauty.co.uk | /home/jacquiesbeauty.co.uk/dev/src/www/yii/jacquiesbeauty.co.uk',
	'styleyourvenue.co.uk'       => '/home/kim/dev/src/www/yii/styleyourvenue.co.uk | /home/wireflydesign.com/domains/style.wireflydesign.com/dev/src/www/yii/styleyourvenue.co.uk',
	'test.wireflydesign.com'     => '/home/kim/dev/src/www/yii/test.wireflydesign.com | /home/wireflydesign.com/domains/test.wireflydesign.com/dev/src/www/yii/test.wireflydesign.com',
	'the-art-room.co.uk'         => '/home/kim/dev/src/www/yii/the-art-room.co.uk | /home/the-art-room.co.uk/dev/src/www/yii/the-art-room.co.uk',
);

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
