<?php

function util($domain, $location) {

	$dbCommands= <<<END_OF_COMMANDS
ALTER TABLE jelly_setting ADD analyticsUA TEXT AFTER email;
END_OF_COMMANDS;

	echo "$domain\n";

	dbExec($location, $dbCommands);

	echo "------------------------------------------\n";

}

?>
