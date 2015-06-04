<?php

require 'patchUtil.php';

function patch($domain, $location) {

	$commands= <<<END_OF_COMMANDS
CREATE  TABLE IF NOT EXISTS `jelly_setting` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
END_OF_COMMANDS;

	//system("ls -d $location");
	echo "$domain\n";
	dbExec($location, $commands);
	echo "------------------------------------------\n";
	//system("cat $name");

}

?>
