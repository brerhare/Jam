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

	system("cd $location; cp /tmp/favicon.ico .");

	system("cd $location/protected/models; ln -s ../../../common/protected/models/JellySetting.php; git add JellySetting.php");
	system("cd $location/protected/backend/controllers; ln -s ../../../../common/protected/backend/controllers/JellySettingController.php; git add JellySettingController.php");
	system("cd $location/protected/backend/views; ln -s ../../../../common/protected/backend/views/jellySetting/; git add jellySetting");
	system("cd $location; git commit -m 'jelly - add settings table'; git pull; git push");

	dbExec($location, $dbCommands);

	echo "------------------------------------------\n";

}

?>
