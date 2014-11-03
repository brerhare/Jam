<?php
	require_once ("PaymentFormHelper.php");
	include ("Config.php");
	include ("ISOCurrencies.php");

	$Width = 800;
	$BodyAttributes = "";
	$FormAttributes = "";
	$FormAction = "PaymentForm.php";
	include ("Templates/FormHeader.tpl");

	$szAmount = "1000";
	$szCurrencyShort = "GBP";
	$szOrderID = "Order-1234";
	$szOrderDescription = "A Test Order";

	if ($iclISOCurrencyList->getISOCurrency($szCurrencyShort, $icISOCurrency))
	{
		$szDisplayAmount = $icISOCurrency->getAmountCurrencyString($szAmount, false);
	}

	$szHashDigest = PaymentFormHelper::calculateHashDigest(PaymentFormHelper::generateStringToHash($szAmount,
                        $szCurrencyShort,
                        $szOrderID,
                        $szOrderDescription,
                        $SecretKey));

	include ("Templates/StartHereForm.tpl");
	include ("Templates/FormFooter.tpl");
?>