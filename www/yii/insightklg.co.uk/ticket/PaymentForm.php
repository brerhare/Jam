<?php
    require_once ("PaymentFormHelper.php");
	//include ("PreProcessPaymentForm.php");

	$Width = 800;
	include ("Templates/FormHeader.tpl");
    Log::msg("PaymentForm.php entry");

	// kim tcpdf ticket code
	include ('tcpdf/production/ticket.php');

    Log::msg("PaymentForm.php included tcpdf");
	// PHPMailer
	require_once('PHPMailer/class.phpmailer.php');

	$globalOrderNum = "";	// kim this is for the result form

	Log::msg("(PaymentForm) Transaction successful");

	// kim added next bit. Success unless duplicate

	// Retrieve the orderform request details
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "SELECT * FROM orderform where ip = '" . Util::getIP() . "'";
	Log::msg("(PaymentForm) Query : " . $sql);
	$result = mysql_query($sql); // kim or die(mysql_error());
	if (($q = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
	{
		// Generate tickets
		Log::msg('Generating ' . $q['friday'] . ' friday and ' . $q['saturday'] . ' saturday and ' .  $q['weekend'] . ' weekend and ' .  $q['vip'] . ' vip tickets for order number ' . $q['orderNum']);
		$ticketNumber = array();
		generateTicket($q['friday'], $q['saturday'], $q['weekend'], $q['vip'], $q['orderNum'], $ticketNumber);
	}
	Log::msg('(result) ' . $sql . " returned " . $result);
	_dbfin($dbhandle);

	// Send email
	$from = "admin@dglink.co.uk";
	$fromName = "Admin";
	$to = $q['email'];
	$subject = "Your tickets purchased at DG Link";
	$message = '<b>Thank you for using the DG Link to order your ticket(s).</b> <br> The attached PDF file contains your ticket(s) and card receipt. Please print all pages and bring them with you to your event or activity. The barcode on each ticket can only be used once.<br> If you ever need to reprint your tickets you may login to the site and do so from your account page. If you have forgotten your log in details you can request a password reminder.<br> We hope you enjoy your event.  --  The DG Link Team';

	$pdf_filename = '/tmp/' . $q['orderNum'] . '.pdf';
	Log::msg('Attaching ticket ' . $pdf_filename);

	// using the phpmailer class
	$mail = new PHPMailer();
	$mail->AddAddress($to);
	$mail->SetFrom($from, $fromName);
	$mail->AddReplyTo($from, $fromName);

	$mail->AddAttachment($pdf_filename);
	$mail->Subject = $subject;

	//$mail->Body = $message;
	$mail->MsgHTML($message);

	if (!$mail->Send())
		echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";

	// delete the temp file
	copy($pdf_filename, 'tkts/' . $q['orderNum'] . '.pdf');
	unlink($pdf_filename);

	// Create a transaction record from orderform
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "INSERT INTO transaction (ip, timeStamp, email, friday, saturday, weekend, vip, telephone, orderNum, amount, cardName, address1, address2, address3, address4, city, state, postCode, countryShort, message) values (" . "'" . $q['ip'] . "'," . "'" . date("Y-m-d H:i:s") . "'," . "'" . $q['email'] . "'," . $q['friday'] . "," . $q['saturday'] . "," . $q['weekend'] . "," . $q['vip'] . "," . "'" . $q['telephone'] . "'," . "'" . $q['orderNum'] . "'," . $q['amount'] . "," . "'" . $q['cardName'] . "'," .  "'" . $q['address1'] . "'," . "'" . $q['address2'] . "'," . "'" . $q['address3'] . "'," . "'" . $q['address4'] . "'," . "'" . $q['city'] . "'," . "'" . $q['state'] . "'," . "'" . $q['postCode'] . "'," . "'" . $q['countryShort'] . "'," . "'" . $Message . "'" . ");";
	Log::msg("(PaymentForm) Creating a transaction record from orderform : " . $sql);
	if (!mysql_query($sql))
		die('Error: ' . mysql_error());

	// Create ticket record(s)
	foreach ($ticketNumber as $ti)
	{
		$sql = "INSERT INTO ticket (orderNum, ticketNumber, scanTimestamp) values (" . "'" . $q['orderNum'] . "'," . "'" . $ti . "'," . "'" . '0000-00-00 00:00:00' . "'" . ");";
		Log::msg("(PaymentForm) Creating a ticket record for ticket " . $ti . " : " . $sql);
		if (!mysql_query($sql))
			die('Error: ' . mysql_error());
	}

	_dbfin($dbhandle);

	// Make order number global for the Templates/FinalResultsPanel.tpl call (a few lines down) to access
	$globalOrderNum = $q['orderNum'];	// kim this is for the result form
	Log::msg("(PaymentForm) PAYMENT_FORM 2");
	include ("Templates/PaymentForm.tpl");
	Log::msg("(PaymentForm) PAYMENT_FORM 3");

	include ("Templates/FormFooter.tpl");
?>
