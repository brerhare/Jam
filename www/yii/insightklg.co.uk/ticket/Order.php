<?php
	require_once ("PaymentFormHelper.php");
	include ("Config.php");
	/////include ("ISOCurrencies.php");

    foreach ($_POST as $field => $value)
    {
        $$field = $value;
        Log::msg("Order got field - " . $field . " -> " . $value);
    }

	// $ShoppingCartAmount was posted with the form
	$ShoppingCartCurrencyShort = "GBP";


	// Update the order sequence record
	$dbhandle="";
	_dbinit($dbhandle);
// kim NOT THREAD SAFE!
	$sql = 'UPDATE sequencenumber set ordernumber = (ordernumber + 1) where id = 1; ';
	$result = mysql_query($sql) or die(mysql_error());
	$sql = "SELECT ordernumber FROM sequencenumber where id = 1";
	$result = mysql_query($sql) or die(mysql_error());
	if (($qe = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		$orderNum = sprintf('%05d', $qe['ordernumber']);
	else
		$orderNum = 'IV' . rand(10000,99999);
	_dbfin($dbhandle);
	Log::msg("Got a (potential) order number from table sequencenumber. Using " . $orderNum);
	$ShoppingCartOrderID = $orderNum;

	// Pick up the event record for some descriptions
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "SELECT * FROM event where id = 1";
	$result = mysql_query($sql) or die(mysql_error());
	if (($q = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		$ShoppingCartOrderDescription = $q['title'];
	else
		$ShoppingCartOrderDescription = 'Undefined';
	_dbfin($dbhandle);

//	if ($iclISOCurrencyList->getISOCurrency($szCurrencyShort, $icISOCurrency))
//	{
//		$szDisplayAmount = $icISOCurrency->getAmountCurrencyString($szAmount, false);
//	}

	// Record the orderform request before going to the payment form
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "DELETE FROM orderform where ip = '" . Util::getIP() . "'";
	Log::msg("(post orderform) " . $sql);
	$result = mysql_query($sql) or die(mysql_error());
	$sql = "INSERT INTO orderform (ip, email, friday, saturday, weekend, vip, telephone, orderNum, amount, cardName, address1, address2, address3, address4, city, state, postCode, countryShort) values (" . "'" . Util::getIP() . "'," . "'" . $Email . "'," . $FQty . "," . $SQty . "," . $WQty . "," . $VQty . "," . "'" . $Phone . "'," . "'" . $orderNum . "'," . $ShoppingCartAmount . "," . "'" . $CardName . "'," .  "'" . $Address1 . "'," . "'" . $Address2 . "'," . "'" . $Address3 . "'," . "'" . $Address4 . "'," . "'" . $City . "'," . "'" . $State . "'," . "'" . $PostCode . "'," . "'" . $CountryShort . "'" . ");";
	Log::msg("Storing details from orderform: " . $sql);
	if (!mysql_query($sql))
		die('Error: ' . mysql_error());
	_dbfin($dbhandle);

	Log::msg('Calculating hash digest from Order fields ' . $ShoppingCartAmount . ', ' . $ShoppingCartCurrencyShort . ', ' . $ShoppingCartOrderID . ', ' . $ShoppingCartOrderDescription . ', ' . $SecretKey);

	$ShoppingCartHashDigest = PaymentFormHelper::calculateHashDigest(PaymentFormHelper::generateStringToHash(
		$ShoppingCartAmount,
		$ShoppingCartCurrencyShort,
		$ShoppingCartOrderID,
		$ShoppingCartOrderDescription,
		$SecretKey));

	Log::msg("Going to Payment form with hashed fields:");
	Log::msg("  -> Amount:".$ShoppingCartAmount);
	Log::msg("  -> CurrShort:".$ShoppingCartCurrencyShort);
	Log::msg("  -> OrderID:".$ShoppingCartOrderID);
	Log::msg("  -> Desc:".$ShoppingCartOrderDescription);
	Log::msg("  -> Hash:".$ShoppingCartHashDigest);

	include ("PaymentForm.php");
?>
