<?php

require ('Jelly.php');
require('ParseConfig.php');

$parseConfig = new ParseConfig();
$jellyArray = $parseConfig->parse('layout7.ini');
if (!($jellyArray))
	throw new Exception('Aborting');

$jelly = new Jelly;
$jelly->processData($jellyArray);
$jelly->outputData();

?>
