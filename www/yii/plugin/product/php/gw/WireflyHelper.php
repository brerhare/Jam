<?php

function getIP()
{
	$ip = "UNKNOWN";
	if (getenv("HTTP_CLIENT_IP"))
    	$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR"))
    	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR"))
    	$ip = getenv("REMOTE_ADDR");
	return $ip;
}

function logMsg($msg)
{
	$logHandle = fopen("log.dat", "a");
	$date = date("d-m-y H:i:s");
	fwrite($logHandle, $date . ' ' . getIP() . ' ' . $msg . "\n");
	fclose($logHandle);
}

function _dbinit(&$dbhandle)
{
        $dbname = 'plugin';
        $dbuser = 'plugin';
        $dbpass = 'plugin,';
        $dbhandle = mysql_connect("localhost", $dbuser, $dbpass) or die(mysql_error());
        mysql_select_db($dbname, $dbhandle) or die(mysql_error());
}

function _dbfin(&$dbhandle)
{
        mysql_close ($dbhandle);
        //adodb_connect();
}

?>
