<?php
// @@NB: inserted until @@ENDNB tag

if (!isset($WFH)) {
	include("WireflyHelper.php");
	$WFH = 1;
}

logMsg("(PaymentForm) Start dump -------------------------------");
foreach ($_POST as $field => $value)
{
	$$field = $value;
	logMsg("(PaymentForm) got field - " . $field . " -> " . $value);
}
logMsg("(PaymentForm) End dump ---------------------------------");

/*
// Set the gateway credentials
$dbhandle="";
_dbinit($dbhandle);
$sql = "SELECT * FROM product_order where ip = '" . getIP() . "'";
$result = mysql_query($sql) or die(mysql_error());
$q = mysql_fetch_array($result, MYSQL_ASSOC);
$MerchantID = $q['gu'];
$Password = $q['gp'];
_dbfin($dbhandle);
*/

//header('Location: ' . $q['return_url'] . '/index.php/product/paid?sid=' . $q['sid'] , true, 303);
//die();

// @@ENDNB

	include ("PreProcessPaymentForm.php");
	$Width = 800;
	include ("Templates/FormHeader.tpl");


	// @@TODO: Added everything until the @@ENDTODO tag
	// Whenever we see card details, record them on the order. Regardless of anything else
	if ((isset($_POST['FormMode'])) && ($_POST['FormMode'] == 'PAYMENT_FORM'))
	{
		if (isset($_POST['CardNumber']))
		{
			$dbhandle="";
			_dbinit($dbhandle);

			// Pick up the order record for the adress lines from our form
			$sql = "SELECT * FROM product_order where ip = '" . getIP() . "'";
			logMsg("Retrieving order details to duplicate address1-4 using sql [" . $sql . "]");
			$result = mysql_query($sql) or die(mysql_error());
			$q = mysql_fetch_array($result, MYSQL_ASSOC);

			$sql = "UPDATE product_order set
				card_name = '" . str_replace("'", " ", $_POST['CardName']) . "',
				card_number = '" . $_POST['CardNumber'] . "', 
				card_expiry_month = '" . $_POST['ExpiryDateMonth'] . "',
				card_expiry_year = '" . $_POST['ExpiryDateYear'] . "',
				card_cv2 = '" . $_POST['CV2'] . "',
				card_address1 = '" . str_replace("'", " ", $_q['delivery_address1']) . "',
				card_address2 = '" . str_replace("'", " ", $_q['delivery_address2']) . "',
				card_address3 = '" . str_replace("'", " ", $_q['delivery_address3']) . "',
				card_address4 = '" . str_replace("'", " ", $_q['delivery_address4']) . "', 
				card_city = '" . $_POST['City'] . "',
				card_state = '" . $_POST['State'] . "',
				card_post_code = '" . $_q['delivery_post_code'] . "',
				card_country_short = '" . $_POST['CountryShort'] . "',
				card_currency_short = '" . $_POST['CurrencyShort'] . "',
				card_amount = '" . $_POST['Amount'] . "'
				where ip = '" . getIP() . "'";
			logMsg("Updating order details with payment info, using sql [" . $sql . "]");
			$result = mysql_query($sql) or die(mysql_error());

			_dbfin($dbhandle);
		}
	}
	// @@ENDTODO




	switch ($NextFormMode)
	{
		case "RESULTS":
			// @@ NB: Logging added
			logMsg("(PaymentForm) Transaction message is " . $Message);
			if (isset($DuplicateTransaction) != true)
			{
				$DuplicateTransaction = false;							
			}
			// @@TODO: Changed line below from a 1-condition check to a 2-condition check
			//if ($TransactionSuccessful == false)
			if (($TransactionSuccessful == false) || ($DuplicateTransaction == true))
			{
				$MessageClass = "ErrorMessage";
			}
			else
			{
				$MessageClass = "SuccessMessage";

				// @@TODO: Added everything until the @@ENDTODO tag
				$dbhandle="";
				_dbinit($dbhandle);

				// Pick up the (not potential anymore) order record and record the auth number
				$sql = "SELECT * FROM product_order where ip = '" . getIP() . "'";
				logMsg("Retrieving (not potential anymore) order details using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());
				$q = mysql_fetch_array($result, MYSQL_ASSOC);

				$sql = "UPDATE product_order set auth_code = '" . $Message . "' where ip = '" . getIP() . "'";
				logMsg("Updating auth number using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());

				$sql = "INSERT into product_auth (uid, order_number, card_name, card_number, expiry_month, expiry_year, cv2, address1, address2, address3, address4, city, state, post_code, country_short, amount, currency_short, auth_code) VALUES (" .
				"'" . $q['uid'] . "'," .
				"'" . $q['order_number'] . "'," .
				"'" . $q['card_name'] . "'," .
				"'" . $q['card_number'] . "'," .
				"'" . $q['card_expiry_month'] . "'," .
				"'" . $q['card_expiry_year'] . "'," .
				"'" . $q['card_cv2'] . "'," .
				"'" . $q['card_address1'] . "'," .
				"'" . $q['card_address2'] . "'," .
				"'" . $q['card_address3'] . "'," .
				"'" . $q['card_address4'] . "'," .
				"'" . $q['card_city'] . "'," .
				"'" . $q['card_state'] . "'," .
				"'" . $q['card_post_code'] . "'," .
				"'" . $q['card_country_short'] . "'," .
				"'" . $q['card_amount'] . "'," .
				"'" . $q['card_currency_short'] . "'," .
				"'" . $Message. "')";
				logMsg("Creating Auth record using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());

				_dbfin($dbhandle);

				// Redirect
				//header('Location: ' . $q['return_url'] . '/index.php/product/paid?sid=' . $q['sid'] , true, 303);
				//@@TODO:BUG: sort out the redirect...why hard coded???
				header('Location: ' . 'https://plugin.wireflydesign.com/product/index.php/site/paid?sid=' . $q['sid'] , true, 303);
				die();
				//@@ENDTODO

			}
			include ("Templates/FinalResultsPanel.tpl");
			break;
		case "THREE_D_SECURE":
			$SiteSecureBaseURL = PaymentFormHelper::getSiteSecureBaseURL();
			include ("Templates/3DSIFrame.tpl");
			break;
		case "PAYMENT_FORM":
			if (isset($Message) == true &&
				$Message != "")
			{
				include ("Templates/ProcessingErrorResultsPanel.tpl");
			}

			if ($SuppressFormDisplay == false)
			{
				include ("ISOCountries.php");
				include ("ISOCurrencies.php");

				if ($iclISOCurrencyList->getISOCurrency($CurrencyShort, $icISOCurrency))
				{
					$szDisplayAmount = $icISOCurrency->getAmountCurrencyString($Amount);
				}

				$szHashDigest = PaymentFormHelper::calculateHashDigest(PaymentFormHelper::generateStringToHash($Amount,
			                        $CurrencyShort,
			                        $OrderID,
			                        $OrderDescription,
			                        $SecretKey));

				$lilExpiryDateMonthList = PaymentFormHelper::createExpiryDateMonthListItemList($ExpiryDateMonth);
				$lilExpiryDateYearList = PaymentFormHelper::createExpiryDateYearListItemList($ExpiryDateYear);
				$lilStartDateMonthList = PaymentFormHelper::createStartDateMonthListItemList($StartDateMonth);
				$lilStartDateYearList = PaymentFormHelper::createStartDateYearListItemList($StartDateYear);
				
				$lilISOCountryList = PaymentFormHelper::createISOCountryListItemList($CountryShort, $iclISOCountryList);

				// Pick up the order record for name and address to pre-fill the payment form
				$dbhandle="";
				_dbinit($dbhandle);
				$sql = "SELECT * FROM product_order where ip = '" . getIP() . "'";
				logMsg("Retrieving order name/postcode for form prefill using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());
				$q = mysql_fetch_array($result, MYSQL_ASSOC);
			_dbfin($dbhandle);

				include ("Templates/PaymentForm.tpl");
			}
			break;
	}
	include ("Templates/FormFooter.tpl");
?>
