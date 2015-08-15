<?php
	require_once ("PaymentFormHelper.php");
	include ("Config.php");
	include ("ISOCurrencies.php");

	$Width = 800;
	$BodyAttributes = "";
	$FormAttributes = "";
	$FormAction = "PaymentForm.php";
    
	include ("Templates/FormHeader.tpl");

	$Amount = "1000";
	$CurrencyShort = "GBP";
	$OrderID = "Order-1234";
	$OrderDescription = "A Test Order";

	if ($iclISOCurrencyList->getISOCurrency($CurrencyShort, $icISOCurrency))
	{
		$DisplayAmount = $icISOCurrency->getAmountCurrencyString($Amount, false);
	}

	$HashDigest = PaymentFormHelper::calculateHashDigest(PaymentFormHelper::generateStringToHash($Amount,
                        $CurrencyShort,
                        $OrderID,
                        $OrderDescription,
                        $SecretKey));

	include ("Templates/StartHereForm.tpl");
	include ("Templates/FormFooter.tpl");
?>