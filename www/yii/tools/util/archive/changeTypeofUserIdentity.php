<?php

function util($domain, $location) {

	$dbCommands= <<<END_OF_COMMANDS
CREATE  TABLE IF NOT EXISTS `jelly_setting` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
END_OF_COMMANDS;

	echo "$domain\n";

	//system("cd $location; cp protected/components/UserIdentity.php ../common/protected/components/");
	//system("cd $location; git add ../common/protected/components/UserIdentity.php");
	//system("cd $location; git rm protected/components/UserIdentity.php");
	//system("cd $location/protected/components/; ln -s ../../../common/protected/components/UserIdentity.php");
	//system("cd $location; git add protected/components/UserIdentity.php");
	//system("cd $location; git commit -m 'jelly - single login for plugins and sites, and can change password'; git pull; git push");

	//dbExec($location, $dbCommands);

	echo "------------------------------------------\n";

}

?>
