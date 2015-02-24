<?php

$dir = "./data/";
$file = $_GET['file'];
//$file = "sms-4283900";

$fp = fopen($dir . $file, "r");
if (!flock($fp, LOCK_EX)) {	// Note this is blocking - we wait until whoevers writing to it finishes
	throw new Exception(sprintf('Unable to obtain lock on file: %s', $file));
}
fclose($fp);
readfile($dir . $file);

?>
