<?php

	include("WireflyHelper.php");

	logMsg("Entrypoint to PaymentSense");

	foreach ($_POST as $field => $value)
	{
		$$field = $value;
		logMsg("(PaymentForm) got field - " . $field . " -> " . $value);
	}

	$dbhandle="";
	_dbinit($dbhandle);

	// Pick up the (potential) order record
	$sql = "SELECT * FROM ticket_order where ip = '" . getIP() . "'";
	logMsg("Retrieving (potential) order details using sql [" . $sql . "]");

	$result = mysql_query($sql) or die(mysql_error());
	if (($qOrder = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		logMsg("Matching passed sid " . $_GET['sid'] . " with db sid " . $qOrder['sid']);
	else
	{
		logMsg("Unsuccessful...aborting");
		die("Cannot retrieve order record");
	}

	// Pick up the vendor record
	$sql = "SELECT * FROM ticket_vendor where id = " . $qOrder['vendor_id'];
	logMsg("Retrieving vendor details using sql [" . $sql . "]");
	$result = mysql_query($sql) or die(mysql_error());
	if (($qVendor = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		logMsg("Retrieved vendor record " . $qVendor['name']);
	else
	{
		logMsg("Unsuccessful...aborting");
		die("Cannot retrieve vendor record");
	}

	// Pick up the event record
	$sql = "SELECT * FROM ticket_event where id = " . $qOrder['event_id'];
	logMsg("Retrieving event details using sql [" . $sql . "]");
	$result = mysql_query($sql) or die(mysql_error());
	if (($qEvent = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		logMsg("Retrieved event record " . $qEvent['title']);
	else
	{
		logMsg("Unsuccessful...aborting");
		die("Cannot retrieve event record");
	}



/*****
if (getIP() != '87.112.54.144') {
	die("Down for maintenance. Our apologies. Please use alternative payment methods for today");
}
*****/


	$ShoppingCartAmount = str_replace('.', '', $_GET['rtotal']);
	if ($ShoppingCartAmount == 0)
	{
		logMsg("Total amount is zero...aborting");
		die("Cannot process payment for zero amount");
	}

    $ShoppingCartCurrencyShort = "GBP";
    $ShoppingCartOrderID = $qOrder['uid'] . "-" . time();
	logMsg("Going with order number " . $ShoppingCartOrderID . " (based on uid-time)");
/*********************    $ShoppingCartOrderDescription = $qEvent['title']; ***********************/
	$ShoppingCartOrderDescription = 'Ticket order ' . substr($qVendor['name'], 0, 20) . ' : ' . substr($qEvent['title'], 0, 20);

    $ShoppingCartHashDigest = "123";

	// Update the (potential) order with the generated order number
	$sql = "UPDATE ticket_order set order_number = '" . $ShoppingCartOrderID . "' where ip = '" . getIP() . "'";
	logMsg("Updating order number using sql [" . $sql . "]");
	$result = mysql_query($sql) or die(mysql_error());

	// Set a variable so the next form knows whether our helper is already included
	$NewEntry=1;
	_dbfin($dbhandle);
include ("PaymentForm.php");

?>
