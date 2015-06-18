<?php

function util($domain, $location) {

	$dbCommands= <<<END_OF_COMMANDS
DROP TABLE IF EXISTS `jelly_column` ;
CREATE  TABLE IF NOT EXISTS `jelly_column` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `column` INT NULL ,
  `sequence` INT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `sequence` (`sequence` ASC) )
ENGINE = InnoDB;
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

	//dbExec($location, $dbCommands);

system("cd $location/protected/backend/controllers/; git pull");
system("cd $location/protected/backend/controllers/; git commit -m 'jelly - add jellyColumn'");
system("cd $location/protected/backend/controllers/; git push");

	echo "------------------------------------------\n";

}

?>
