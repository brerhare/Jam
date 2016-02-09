<?php
	// Will need to set these variables to valid a MerchantID and Password
	// These were obtained during sign up

// TEST gateway
// ------------
	//$MerchantID = "WIREFL-5188100";
	//$Password = "391F3GWBZ0";

// PRODUCTION gateway
// ------------------
	$MerchantID = "WIREFL-5578222";
	$Password = "Sbyzek538949";

// Set the gateway credentials if jacquies
if (!isset($WFH)) {
    include("WireflyHelper.php");
    $WFH = 1;
}
$dbhandle="";
_dbinit($dbhandle);
$sql = "SELECT * FROM product_order where ip = '" . getIP() . "'";
$result = mysql_query($sql) or die(mysql_error());
$q = mysql_fetch_array($result, MYSQL_ASSOC);
if ($q['gu'] == "JACQUI-1645722") {
    $MerchantID = $q['gu'];
    $Password = $q['gp'];
}


	// This is the domain (minus any host header or port number for your payment processor
	// e.g. for "https://gwX.paymentsensegateway.com:4430/", this should be "paymentsensegateway.com"
	// e.g. for "https://gwX.thepaymentgateway.net/", this should be "thepaymentgateway.net"
	$PaymentProcessorDomain = "paymentsensegateway.com";
   	// This is the port that the gateway communicates on -->
	// e.g. for "https://gwX.paymentsensegateway.com:4430/", this should be 4430
	// e.g. for "https://gwX.thepaymentgateway.net/", this should be 443
	$PaymentProcessorPort = 4430;

	// This is used to generate the Hash Keys that detect variable tampering
	// You should change this to something else
	$SecretKey = "43jiogjrtgjdE";

	if ($PaymentProcessorPort == 443)
	{
		$PaymentProcessorFullDomain = $PaymentProcessorDomain."/";
	}
	else
	{
		$PaymentProcessorFullDomain = $PaymentProcessorDomain.":".$PaymentProcessorPort."/";
	}
?>
