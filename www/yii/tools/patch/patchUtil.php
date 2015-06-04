<?php

function dbExec($location, $commands) {
// mysql -u wireflydesign.co test_wireflydesign_com -p"wireflydesign.com," < jelly.sql
	$dbInit = $location . "/protected/data/dbinit.sh";
	if (!$dbFile = file_get_contents($dbInit))
		die("Failed to open $dbInit\n");
	if (!strstr($dbFile, "<"))
		die("No '<' found in $dbInit\n");
	$newInit = substr($dbFile, 0, strpos($dbFile, " < "));
	// Write out the commands to a tmp file
	$tmpName = tempnam("/tmp", "db-");
	if (!file_put_contents($tmpName, $commands))
		die("Failed to create temporary db commands file $tmpName\n");
	$newInit .= " < " . $tmpName . "\n";
	system($newInit);
	unlink($tmpName);
}

?>

