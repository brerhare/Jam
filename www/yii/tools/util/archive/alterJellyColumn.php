<?php

function util($domain, $location) {

	$dbCommands= <<<END_OF_COMMANDS
alter table jelly_column add column_name varchar(255) after column_id;
alter table jelly_column drop column_id;
END_OF_COMMANDS;

/*
not sure why this check doesnt work
	if (($location == "test.wireflydesign.com")
	||  ($location == "absoluteclassics.co.uk"))
	{
		echo "skipping $domain\n";
		return;
	}
*/

	echo "$domain\n";

	//system("cd $location/protected/models/; ln -s ../../../common/protected/models/JellyColumn.php");
	//system("cd $location/protected/models; git add JellyColumn.php");

	//system("cd $location/protected/backend/controllers/; ln -s ../../../../common/protected/backend/controllers/JellyColumnController.php");
	//system("cd $location/protected/backend/controllers/; git add JellyColumnController.php");

	//system("cd $location/protected/backend/views/; ln -s ../../../../common/protected/backend/views/jellyColumn/");
	//system("cd $location/protected/backend/views/; git add jellyColumn");

	dbExec($location, $dbCommands);

//system("cd $location/protected/backend/controllers/; git pull");
//system("cd $location/protected/backend/controllers/; git commit -m 'jelly - add jellyColumn'");
//system("cd $location/protected/backend/controllers/; git push");

	echo "------------------------------------------\n";

}

?>
