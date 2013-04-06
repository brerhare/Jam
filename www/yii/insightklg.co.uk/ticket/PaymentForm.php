<?php
    require_once ("PaymentFormHelper.php");
	//include ("PreProcessPaymentForm.php");
    foreach ($_POST as $field => $value)

    {
        $$field = $value;
        Log::msg("PaymentForm.php got field - " . $field . " -> " . $value);
    }

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

	// Generate tickets
	Log::msg('Generating ' . $FQty . ' tickets for order number ' . $orderNum);
	$ticketNumber = array();
	generateTicket($FQty, $orderNum, $ticketNumber);
	Log::msg('(result) ' . $sql . " returned " . $result);

	// Send email
	$from = "email@insightklg.co.uk";
	$fromName = "Admin";
	$to = $Email;
	$subject = "Your tickets purchased at Insight";
	$message = '<b>Thank you for your order.</b> <br> The attached PDF file contains your ticket(s) and card receipt. Please print all pages and bring them with you to your event or activity. The barcode on each ticket can only be used once.<br>';

	$pdf_filename = '/tmp/' . $orderNum . '.pdf';
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
	copy($pdf_filename, 'tkts/' . $orderNum . '.pdf');
	$rnd = rand(10000,99999) . '_' . $orderNum;
	copy($pdf_filename, 'tktp/' . $rnd . '.pdf');
	unlink($pdf_filename);

	// Create a transaction record from orderform
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "INSERT INTO transaction (timeStamp, ip, email, adults, children, telephone, orderNum, amount, cardName, address1, address2, address3, address4, city, state, postCode, countryShort, message) values (" . "'" . date("Y-m-d H:i:s") . "'," . "'" . Util::getIP() . "'," . "'" . $Email . "'," . $FQty . "," . 0 . ",'" . $Phone . "'," . "'" . $orderNum . "'," . 0 . "," . "'" . 'cardName' . "'," .  "'" . 'address1' . "'," . "'" . 'address2' . "'," . "'" . 'address3' . "'," . "'" . 'address4' . "'," . "'" . 'city' . "'," . "'" . 'state' . "'," . "'" . 'postCode' . "'," . "'" . 'countryShort' . "'," . "'" . 'Message' . "'" . ");";
	Log::msg("(PaymentForm) Creating a transaction record from orderform : " . $sql);
	if (!mysql_query($sql))
		die('Error: ' . mysql_error());

	// Create ticket record(s)
	foreach ($ticketNumber as $ti)
	{
		$sql = "INSERT INTO ticket (orderNum, ticketNumber, scanTimestamp) values (" . "'" . $orderNum . "'," . "'" . $ti . "'," . "'" . '0000-00-00 00:00:00' . "'" . ");";
		Log::msg("(PaymentForm) Creating a ticket record for ticket " . $ti . " : " . $sql);
		if (!mysql_query($sql))
			die('Error: ' . mysql_error());
	}

	_dbfin($dbhandle);

	// Make order number global for the Templates/FinalResultsPanel.tpl call (a few lines down) to access
	$globalOrderNum = $rnd;	// kim this is for the result form
	Log::msg("(PaymentForm) PAYMENT_FORM 2");
	include ("Templates/PaymentForm.tpl");
	Log::msg("(PaymentForm) PAYMENT_FORM 3");

	echo "<h2>Thank you. Your ticket(s) will be emailed to you</h2>";
	echo "<p>Should you not receive it in a few minutes please check your junk folder</p>";
	echo "<p>You may also print your tickets <a href='http://www.insightklg.co.uk/ticket/tktp/" . $globalOrderNum . ".pdf' target='_blank'>here</a> (if it does not open please use the refresh button)</p>";

	include ("Templates/FormFooter.tpl");
?>
