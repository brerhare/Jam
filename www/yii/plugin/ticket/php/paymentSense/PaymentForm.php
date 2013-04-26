<?php
	include ("PreProcessPaymentForm.php");
	// @@TODO: Wirefly include added
	include ("WireflyHelper.php");

	$Width = 800;
	include ("Templates/FormHeader.tpl");

	switch ($NextFormMode)
	{
		case "RESULTS":
			// @@ TODO: Logging added
			logMsg("(PaymentForm) Transaction message is " . $Message);
			if (isset($DuplicateTransaction) != true)
			{
				$DuplicateTransaction = false;							
			}
			if ($TransactionSuccessful == false)
			{
				$MessageClass = "ErrorMessage";
			}
			else
			{
				$MessageClass = "SuccessMessage";

				// @@TODO: Redirect inserted here
				header('Location: ' . "http://www.google.com", true, 303);
				die();

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
