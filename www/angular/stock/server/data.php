<?php

$maxRecs = 999999;

//$inData = json_decode(file_get_contents("php://input"));
//if ($inData.table == 'customer')
	//$maxRecs = 10;

/*
$inData = json_decode(file_get_contents("php://input"));
$data = json_encode(file_get_contents("customer.json"));
header('Content-Type: application/json');
$data = file_get_contents("customer.json");
echo $data;
*/

$dbhandle="";
_dbinit($dbhandle);

header('Content-Type: application/json');

$sql = "select * from stock_customer";
$result = mysql_query($sql) or die(mysql_error());

$ix = 0;
$customers = array();
while($data = mysql_fetch_array($result, MYSQL_ASSOC))
{
    $customers[$ix]['id'] = $data['id'];
    $customers[$ix]['name'] = $data['name'];
    $customers[$ix]['discount_percent'] = $data['discount_percent'];
    $customers[$ix]['telephone'] = $data['telephone'];
    $customers[$ix]['forma_de_pago'] = $data['forma_de_pago'];
	$ix++;
if ($ix == 200) break;
}
echo json_encode($customers, JSON_NUMERIC_CHECK);

function _dbinit(&$dbhandle)
{
//return;
        $dbname = 'stock';
        $dbuser = 'stock';
        $dbpass = 'stock,';
        $dbhandle = mysql_connect("localhost", $dbuser, $dbpass) or die(mysql_error());
        mysql_select_db($dbname, $dbhandle) or die(mysql_error());
}

function _dbfin(&$dbhandle)
{
//return;
        mysql_close ($dbhandle);
}

?>
