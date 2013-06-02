<?php
// @@NB: inserted until @@ENDNB tag
	if ($NewEntry != 1)
		include("WireflyHelper.php");

logMsg("(PaymentForm) ------------------------------------------");
foreach ($_POST as $field => $value)
{
	$$field = $value;
	logMsg("(PaymentForm) got field - " . $field . " -> " . $value);
}

/*
$dbhandle="";
_dbinit($dbhandle);
$sql = "SELECT * FROM ticket_order where ip = '" . getIP() . "'";
$result = mysql_query($sql) or die(mysql_error());
$q = mysql_fetch_array($result, MYSQL_ASSOC);
_dbfin($dbhandle);
header('Location: ' . $q['return_url'] . '/index.php/ticket/paid?sid=' . $q['sid'] , true, 303);
die();
*/

// @@ENDNB

	include ("PreProcessPaymentForm.php");
	$Width = 800;
	include ("Templates/FormHeader.tpl");

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
				$sql = "SELECT * FROM ticket_order where ip = '" . getIP() . "'";
				logMsg("Retrieving (not potential anymore) order details using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());
				$q = mysql_fetch_array($result, MYSQL_ASSOC);

				$sql = "UPDATE ticket_order set auth_code = '" . $Message . "' where ip = '" . getIP() . "'";
				logMsg("Updating auth number using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());

				$sql = "INSERT into ticket_auth (uid, order_number, card_name, card_number, expiry_month, expiry_year, cv2, address1, address2, address3, address4, city, state, post_code, country_short, amount, currency_short, auth_code) VALUES (" .
				"'" . $q['uid'] . "'," .
				"'" . $q['order_number'] . "'," .
				"'" . $_POST['CardName'] . "'," .
				"'" . $_POST['CardNumber'] . "'," .
				"'" . $_POST['ExpiryDateMonth'] . "'," .
				"'" . $_POST['ExpiryDateYear'] . "'," .
				"'" . $_POST['CV2'] . "'," .
				"'" . $_POST['Address1'] . "'," .
				"'" . $_POST['Address2'] . "'," .
				"'" . $_POST['Address3'] . "'," .
				"'" . $_POST['Address4'] . "'," .
				"'" . $_POST['City'] . "'," .
				"'" . $_POST['State'] . "'," .
				"'" . $_POST['PostCode'] . "'," .
				"'" . $_POST['CountryShort'] . "'," .
				"'" . $_POST['Amount'] . "'," .
				"'" . $_POST['CurrencyShort'] . "'," .
				"'" . $Message. "')";
				logMsg("Creating Auth record using sql [" . $sql . "]");
				$result = mysql_query($sql) or die(mysql_error());

				_dbfin($dbhandle);

				// Redirect
				//header('Location: ' . $q['return_url'] . '/index.php/ticket/paid?sid=' . $q['sid'] , true, 303);
				header('Location: ' . 'https://plugin.wireflydesign.com/ticket/index.php/ticket/paid?sid=' . $q['sid'] , true, 303);
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

				include ("Templates/PaymentForm.tpl");
			}
			break;
	}
	include ("Templates/FormFooter.tpl");
?>
