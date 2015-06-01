<?php

$logFile = "log.txt";

function logWrite($str)
{
	$log = fopen($GLOBALS['logFile'], 'a') or die("can't open log file");
	fwrite($log, $str . "\n");
	fclose($log);
}

function logClear()
{
	$log = fopen($GLOBALS['logFile'], 'w') or die("can't open log file");
	fclose($log);
}

?>
