<?php

function util($domain, $location) {

	$dbCommands= <<<END_OF_COMMANDS
END_OF_COMMANDS;

	echo "Processing $domain\n";
	system("cd $location; git add robots.txt");
	//dbExec($location, $dbCommands);
	echo "------------------------------------------\n";

}

?>
