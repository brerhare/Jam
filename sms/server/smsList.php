<?php

$dir = "./data/";
if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
			$fp = fopen($dir . $file,"r");
			if (!flock($fp, LOCK_EX)) {	// Note this is blocking - we wait until whoevers writing to it finishes
				throw new Exception(sprintf('Unable to obtain lock on file: %s', $file));
			}
			fclose($fp);
            echo "$file\n";
		}
    }
    closedir($handle);
}

?>
