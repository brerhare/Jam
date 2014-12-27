<?php

// 'table'  : 'customer',
// 'action' : 'get' | 'put' | 'delete'
// 'id'     : n | 'a' | '*'

$maxRecs = 100;

$inData = json_decode(file_get_contents("php://input"));
header('Content-Type: application/json');

$dbhandle="";
_dbinit($dbhandle);

if ($inData->table == 'customer')
{
	if ($inData->action == 'get')
	{
		if ($inData->id == '*')
		{
			$sql = "select * from stock_customer";
			$result = mysql_query($sql) or die(mysql_error());
			$customers = array();
			$ix = 0;
			while($data = mysql_fetch_array($result, MYSQL_ASSOC))
			{
    			$customers[$ix]['id'] = $data['id'];
    			$customers[$ix]['name'] = $data['name'];
    			$customers[$ix]['discount_percent'] = $data['discount_percent'];
    			$customers[$ix]['telephone'] = $data['telephone'];
    			$customers[$ix]['forma_de_pago'] = $data['forma_de_pago'];
				$ix++;
				if ($ix == $maxRecs) break;
			}
			echo json_encode($customers, JSON_NUMERIC_CHECK);
		}
		else	// single record
		{
		}
	}
	else if ($inData->action == 'put')
	{
	}
}


function _dbinit(&$dbhandle)
{
	$dbname = 'stock';
	$dbuser = 'stock';
	$dbpass = 'stock,';
	$dbhandle = mysql_connect("localhost", $dbuser, $dbpass) or die(mysql_error());
	mysql_select_db($dbname, $dbhandle) or die(mysql_error());
}

function _dbfin(&$dbhandle)
{
	mysql_close ($dbhandle);
}

?>
