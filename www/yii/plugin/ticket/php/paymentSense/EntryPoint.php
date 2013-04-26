<?php

	include("WireflyHelper.php");

	logMsg("Entrypoint to PaymentSense");

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

    $ShoppingCartAmount = str_replace('.', '', $qOrder['http_total']);
	if ($ShoppingCartAmount == 0)
	{
		logMsg("Total amount is zero...aborting");
		die("Cannot process payment for zero amount");
	}

    $ShoppingCartCurrencyShort = "GBP";
    $ShoppingCartOrderID = $qOrder['uid'] . "-" . time();
	logMsg("Going with order number " . $ShoppingCartOrderID . " (based on uid-time)");
    $ShoppingCartOrderDescription = $qEvent['title'];
    $ShoppingCartHashDigest = "123";

include ("PaymentForm.php");

?>
