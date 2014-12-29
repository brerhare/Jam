<?php

//$sql = "SELECT * FROM ticket_order where ip = '" . getIP() . "'";
//$result = mysql_query($sql) or die(mysql_error());
//$q = mysql_fetch_array($result, MYSQL_ASSOC);

//$sql = "UPDATE ticket_order set auth_code = '" . $Message . "' where ip = '" . getIP() . "'";
//$result = mysql_query($sql) or die(mysql_error());

/*
       "customerId":647,
        "companyName":"7 Days 2",
        "rate":"5%",
        "contactTitle":"",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"Calle Sabandeos",
        "billingAddress2":"Edificio Marte,No.7",
        "billingAddress3":"Los Cristianos",
        "postCode":"",
        "phoneNumber":"922862289",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"F-38619771",
        "notes":"",
        "formaDePago":"1 por otro",
        "mobile:":"",
        "inactive":"0"

| id                    | int(11)       | NO   | PRI | NULL    | auto_increment |
| uid                   | int(11)       | NO   |     | NULL    |                |
| name                  | varchar(255)  | NO   |     | NULL    |                |
| address1              | varchar(255)  | YES  |     | NULL    |                |
| address2              | varchar(255)  | YES  |     | NULL    |                |
| address3              | varchar(255)  | YES  |     | NULL    |                |
| post_code             | varchar(255)  | YES  |     | NULL    |                |
| contact_title         | varchar(255)  | YES  |     | NULL    |                |
| contact_first_name    | varchar(255)  | YES  |     | NULL    |                |
| contact_last_name     | varchar(255)  | YES  |     | NULL    |                |
| telephone             | varchar(255)  | YES  |     | NULL    |                |
| mobile                | varchar(255)  | YES  |     | NULL    |                |
| fax                   | varchar(255)  | YES  |     | NULL    |                |
| email                 | varchar(255)  | YES  |     | NULL    |                |
| discount_percent      | decimal(10,2) | YES  |     | NULL    |                |
| balance               | varchar(255)  | YES  |     | NULL    |                |
| link_field            | varchar(255)  | YES  |     | NULL    |                |
| notes                 | text          | YES  |     | NULL    |                |
| status                | int(11)       | YES  |     | NULL    |                |
| CIF                   | varchar(255)  | YES  |     | NULL    |                |
| forma_de_pago         | varchar(255)  | YES  |     | NULL    |                |
| stock_markup_group_id | int(11)       | NO   | MUL | NULL    |                |
| stock_area_id         | int(11)       | NO   | MUL | NULL    |                |
*/

$dbhandle="";
_dbinit($dbhandle);

$rawData = file_get_contents('customer.json.full');
$data = json_decode($rawData);

// Delete previous data
$sql = "delete from stock_customer";
$result = mysql_query($sql) or die(mysql_error());
$sql = "delete from stock_area";
$result = mysql_query($sql) or die(mysql_error());
$sql = "delete from stock_markup_group";
$result = mysql_query($sql) or die(mysql_error());

// Dummy area record
$sql = "INSERT into stock_area (uid, name) VALUES (1, 'Dummy area')";
$result = mysql_query($sql) or die(mysql_error());

// Dummy markup group record
$sql = "INSERT into stock_markup_group (uid, description, percent, is_default) VALUES (1, 'Dummy markup group', 2.75, 1)";
$result = mysql_query($sql) or die(mysql_error());

$rec = 0;
foreach ($data as $nvp)
{
	$nvp->companyName = str_replace("'", "\\'", $nvp->companyName);
	$nvp->billingAddress1 = str_replace("'", "\\'", $nvp->billingAddress1);
	$nvp->billingAddress2 = str_replace("'", "\\'", $nvp->billingAddress2);
	$nvp->billingAddress3 = str_replace("'", "\\'", $nvp->billingAddress3);
	$nvp->contactTitle = str_replace("'", "\\'", $nvp->contactTitle);
	$nvp->contactFirstName = str_replace("'", "\\'", $nvp->contactFirstName);
	$nvp->contactLastName = str_replace("'", "\\'", $nvp->contactLastName);

	$sql = "INSERT into stock_customer (uid, name, address1, address2, address3, post_code, contact_title, contact_first_name, contact_last_name, telephone, mobile, fax, email, discount_percent, balance, link_field, notes, CIF, forma_de_pago, stock_markup_group_id, stock_area_id) VALUES (" .
	      "1" .  "," .
	"'" . $nvp->companyName . "'," .
	"'" . $nvp->billingAddress1 . "'," .
	"'" . $nvp->billingAddress2 . "'," .
	"'" . $nvp->billingAddress3 . "'," .
	"'" . $nvp->postCode . "'," .
	"'" . $nvp->contactTitle . "'," .
	"'" . $nvp->contactFirstName . "'," .
	"'" . $nvp->contactLastName . "'," .
	"'" . $nvp->phoneNumber . "'," .
	"'" . $nvp->mobile . "'," .
	"'" . $nvp->faxNumber . "'," .
	"'" . $nvp->emailAddress . "'," .
	      intval($nvp->rate) . "," .
          "0" . "," .
	"'" . " " . "'," .
	"'" . $nvp->notes . "'," .
	"'" . $nvp->CIF . "'," .
	"'" . $nvp->formaDePago . "'," .
	      "11" . "," .
	      "13" . ")";
$rec++;
echo "\n\nrec:" . $rec . "   " . $sql . "\n\n";
	$result = mysql_query($sql) or die(mysql_error());
}

_dbfin($dbhandle);

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
