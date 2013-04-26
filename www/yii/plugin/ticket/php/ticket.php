<?php

function genTicket(
	$order_number,
	$vendor_id,
	$event_id,
	$ticket_type_area,
	$ticket_type_id,
	$ticket_type_qty,
	$ticket_type_price,
	$ticket_type_total,
	$total,
	&$ticket_numbers
)
{
	require_once('../../../tcpdf/config/lang/eng.php');
	require_once('../../../tcpdf/tcpdf.php');

	$vendorModel = Vendor::model()->findByPk($vendor_id); 
	$eventModel = Event::model()->findByPk($event_id);

	$dgLogo = CHtml::image(
		Yii::app()->baseUrl . '/img/DGLink_Box_Office_plus_web_address.jpg',
		'DG Image',
		array('style'=>'height:80px;')
	);

	$ticketLogo = CHtml::image(
		Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $eventModel->ticket_logo_path,
		'Logo Image',
		array('style'=>'height:80px;')
	);

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Wirefly Design');
	$pdf->SetTitle('Your tickets');
	$pdf->SetSubject('');
	$pdf->SetKeywords('PDF, ticket');

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 050', PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);

	// ---------------------------------------------------------

	// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

	$orderLines = count($ticket_type_id);
	$totalTickets = 0;
	for ($type = 0; $type < $orderLines; $type++)
	{
		for ($ticket = 0; $ticket < $ticket_type_qty[$type]; $ticket++)
		{
			$totalTickets++;
			$areaModel = Area::model()->findByPk($ticket_type_area[$type]); 
			$ticketTypeModel = TicketType::model()->findByPk($ticket_type_id[$type]);
			// add a page
			$pdf->AddPage();
			$pdf->SetFont('helvetica', '', 12);

// barcode
			// groups 00000 00000 00000 00   000
			//        vend# evnt# tick# type rand
			$rand = rand(10000,999999);
			$ticketRand = sprintf('%03d', $rand);
			$ticketContent = $vendorModel->id . $eventModel->id . $ticketRand;

			// set style for barcode
			$style = array(
				'border' => 0,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, //array(255,255,255)
				'module_width' => 1, // width of a single module in points
				'module_height' => 1 // height of a single module in points
			);

//end barcode

$tbl = <<<EOD
		<table border="0">
			<tr>
				<td width="70%">
					Tickets by: $vendorModel->name<br>
					DG Link Box Office powered by Wirefly Design
				</td>
				<td width="30%" style="text-align:right">
					$dgLogo
				</td>
			</tr>
		</table>
		<br/>
			<table border="5" style="border-color:gray;" cellpadding="15">
			<tr valign="center">
				<td width="520px" height="99px" style="font-size:27;">
					$eventModel->title
				</td>
				<td width="120px"rowspan="4">
					<table>
						<tr>
							<td style="text-align:center">
					$ticketLogo
							</td>
						</tr>
						<tr>
							<td>



							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>

				<td width="70px" rowspan="3">
				</td>

				<td width="450px" height="40px" style="height:35px" >
					Date $eventModel->date
				</td>
			</tr>
			<tr>
				<td height="40px" style="height:35px" >
					$areaModel->description
<br/>					
					$ticketTypeModel->description
				</td>
			</tr>
			<tr>
				<td height="30px" style="height:35px" >
					Ticket number $ticketContent
				</td>
			</tr>
		</table>
		<br>
		<b>$eventModel->address $eventModel->post_code</b>
		<br/><br/>
		$eventModel->ticket_text
		<p/>
		$eventModel->ticket_terms
EOD;
			$pdf->writeHTML($tbl, true, false, false, false, '');

			// QR
			$pdf->write2DBarcode($ticketContent, 'QRCODE,Q', 163, 92, 35, 35, $style, 'N');
			// EAN 13
			$pdf->StartTransform();
			$pdf->Rotate(-90);
			//                                              U-D  L-R
			$pdf->write1DBarcode($ticketContent, 'UPCA', -24, 105, '', 18, 0.4, $style, 'N');
			$pdf->StopTransform();
		}
	}


	// Print the 'receipt' page
	$pdf->AddPage();

	$pdf->SetFont("helvetica", "", 10);
	$pdf->writeHTML("<b>Vendor: " . $vendorModel->name . " " . $vendorModel->address . " " . $vendorModel->post_code);
	$pdf->writeHTML("<b>Event Date: " . $eventModel->date);
	$pdf->writeHTML("<b>Order Number: " . $order_number);
	$pdf->writeHTML("<b>Number of tickets on order: " . $totalTickets);
	$pdf->writeHTML("<b>Order Total: " . $total);
		$pdf->writeHTML("<p/>");
	
	$pdf->writeHTML("<br>$eventModel->ticket_terms");
	
	
	$pdf->SetFont('helvetica', '', 8);

	$tnc = '<br><p><h4>Standard Terms & Conditions:</h4><p>
	If an event is cancelled your booking will be refunded directly by the Event Organiser. If details of the Event are significantly changed after a booking is placed or if an event is moved from its advertised venue or the date is changed tickets already purchased remain valid should you wish to attend the revised Event. If you do not wish to attend the revised Event your booking will be refunded in full by the Event Organiser.
	<p>
	Some event organisers will explicitly stipulate that cancellations due to weather, act of God or any other unavoidable eventuality (force majeure) will not result in a refund. If such terms, or any other terms were applied at the time of booking and stated in the Additional Information section above and on the E-Ticket, they shall prevail over these Standard Terms & Conditions and may result in no refund being due.
	<p>

	Entry for Tickets Holders are purely at the discretion of the Event Organiser or their Security and is subject to their own Terms and Conditions as specified in the Additional Information section. If any Ticket Holder is found to be causing trouble or acting inappropriately to the point it is considered by the Event Organiser or their Security to be negatively affecting the enjoyment or safety of staff or visitors the Event Organiser reserves the right to refuse entry or remove the offending individual from the event and / or premises.  

	<p>
	Tickets cannot be exchanged, refunded or replaced if lost as the varying exchange and refund policies set forth by individual Event Organisers prohibit DG Link or Wirefly Design Ltd from doing so. Where a refund is sought you should contact the Event Organiser directly as only they may reply to your request, and at their discretion.
	<p>
	DG Link or Wirefly Design Ltd will under no circumstances be responsible for any remedy for inconvenience, consequential expenses incurred or any other loss or damage resulting from the cancellation of or change to any Events.
	<p>
	Booking Fees are VAT exempt as they are applied to cover card transaction fees. DG Link / Wirefly Design Ltd provides an online ticket booking service on behalf of the Event Organiser as stated on the Receipt and Ticket. Registered address for Wirefly Design Ltd: Crimond, Mavis Grove, Dumfries, DG2 8EP
	<p>
	Debit and credit card payments are processed on behalf of DG Link by Wirefly Design Ltd. Ticket bookings will appear on your statement as WWW.DGLINK.CO.UK.

	<p>

	If you would like to sell or allow ticket reservations / bookings for your next event, workshop or activity you can do this by uploading your event for free on www.dglink.co.uk. You will then have the option to use the box office facility. There are no fees for ticket reservations, please see www.dglink.co.uk for a full list of fees and Terms and Conditions.';


	$pdf->writeHTML($tnc, true, false, false, false, '');

	// ---------------------------------------------------------



	$pdf->Output('/tmp/' . $order_number . '.pdf', 'F');
}






//============================================================+
// File name   : example_050.php
//============================================================+

function generateTicket($friday, $saturday, $weekend, $vip, $orderNumber, &$ticketNumber)
{
//	require_once('../config/lang/eng.php');
//	require_once('../tcpdf.php');

	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Wirefly Design');
	$pdf->SetTitle('Your tickets');
	$pdf->SetSubject('');
	$pdf->SetKeywords('PDF, ticket');

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 050', PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);

	// ---------------------------------------------------------

	// Pick up the vendor record
	$dbhandle="";
   	_dbinit($dbhandle);
   	$sql = "SELECT * FROM vendor where id = 1";
   	$result = mysql_query($sql) or die(mysql_error());
   	if (($qv = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		$vendorName = $qv['name'];
   	else
       	$vendorName = 'Undefined';
   	_dbfin($dbhandle);

	// Pick up the event record
	$dbhandle="";
   	_dbinit($dbhandle);
   	$sql = "SELECT * FROM event where id = 1";
   	$result = mysql_query($sql) or die(mysql_error());
   	if (($qe = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
	{
		$eventTitle = $qe['title'];
		$startDate = $qe['start_date'];
	}
   	else
	{
       	$eventTitle = 'Undefined';
		$startDate = 'Undefined';
	}
   	_dbfin($dbhandle);



	// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

  for ($type = 0; $type < 4; $type++)
  {
    $typemax = $friday;
	if ($type == 1) $typemax = $saturday;
	if ($type == 2) $typemax = $weekend;
	if ($type == 3) $typemax = $vip;
	for ($i = 0; $i < $typemax; $i++)
	{
		// add a page
		$pdf->AddPage();

		$pdf->SetFont('helvetica', '', 12);

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// Update the ticket sequence record
		$dbhandle="";
		_dbinit($dbhandle);
// kim NOT THREAD SAFE!
		$sql = 'UPDATE sequencenumber set ticketnumber = (ticketnumber + 1) where id = 1; ';
		$result = mysql_query($sql) or die(mysql_error());
		$sql = "SELECT ticketnumber FROM sequencenumber where id = 1";
		$result = mysql_query($sql) or die(mysql_error());
		if (($qs = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
			$ticketNum = sprintf('%05d', $qs['ticketnumber']);
		else
			$ticketNum = 'IV' . rand(10000,99999);
		_dbfin($dbhandle);

		// groups 00000 00000 00000 00   000
		//        vend# evnt# tick# type rand
		$logo = "img/comlogo.jpg";
		$custId = "00001";
		$eventId = "00001";
		$seqNo = $ticketNum;	// from db
		$ticketType = sprintf('%02d', $type);
		$rand = rand(1,999);
		$ticketRand = sprintf('%03d', $rand);
		$ticketContent = $custId . $eventId . '-' . $seqNo . $ticketType . $ticketRand;

		// Print ticket heading lines
		$pdf->Text(47, 19, "Tickets by: " . $vendorName);
		$pdf->Text(47, 25, "DG Link Box Office powered by Wirefly Design");

		$tbl = '<br><br><br><table border="5" style="border-color:gray;" cellpadding="15">';
		$tbl .= '	<tr valign="center">';
		$tbl .= '		<td width="520px" height="99px" style="font-size:27;">';
		$tbl .=				$eventTitle;
		$tbl .= '		</td>';
		$tbl .= '		<td width="120px"rowspan="4">Logo</td>';
		$tbl .= '	</tr>';
		$tbl .= '	<tr>';
		$tbl .= '		<td style="height:33px" >';
		$tbl .= '			Date ' . $startDate;
		$tbl .= '		</td>';
		$tbl .= '	</tr>';
		$tbl .= '	<tr>';
		$tbl .= '		<td style="height:33px" >Ticket type</td>';
		$tbl .= '	</tr>';
		$tbl .= '	<tr>';
		$tbl .= '		<td style="height:33px" >Ticket number</td>';
		$tbl .= '	</tr>';
		$tbl .= ' </table>';

		$pdf->writeHTML($tbl, true, false, false, false, '');

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// set style for barcode
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);

		$pdf->Image($logo, 163, 37, 30, 30, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);

		// Print stuff to right of map
		$adlines = explode("\n", $qe['address']);
		$adpostcode = $qe['post_code'];
		$row = 125;
		foreach ($adlines as $value)
		{
			$pdf->Text(125, $row, $value);
			$row += 5;
		}
		$pdf->Text(125, $row, $adpostcode);

		// Print DG Logo under stuff to right of map
		$pdf->Image('img/DGLink_Box_Office_plus_web_address.jpg', 125, 155, 45, 25, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);

		// Print Standard T&C disclaimer, and the Ticket Text Custom Data below it
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Text(25, 202, "Additional information. See also the standard Terms & Conditions on your receipt");
		$row = 212;
		$pdf->SetFont('helvetica', '', 10);
		$ticklines = explode("\n", $qe['ticket_text']);
		foreach ($ticklines as $value)
		{
			$pdf->Text(25, $row, $value);
			$row += 5;
		}
		$pdf->SetFont('helvetica', '', 12);

		// QRCODE,L : QR-CODE Low error correction
		//$pdf->write2DBarcode($ticketContent, 'QRCODE,L', 20, 30, 50, 50, $style, 'N');
		//$pdf->Text(20, 25, 'QRCODE L');

		// QRCODE,M : QR-CODE Medium error correction
		//$pdf->write2DBarcode($ticketContent, 'QRCODE,M', 20, 90, 50, 50, $style, 'N');
		//$pdf->Text(20, 85, 'QRCODE M');

		// QRCODE,Q : QR-CODE Better error correction
		$pdf->write2DBarcode($ticketContent, 'QRCODE,Q', 163, 72, 35, 35, $style, 'N');
		//$pdf->Text(20, 145, 'QRCODE Q');

		// QRCODE,H : QR-CODE Best error correction
		//$pdf->write2DBarcode($ticketContent, 'QRCODE,H', 20, 210, 50, 50, $style, 'N');
		//$pdf->Text(20, 205, 'QRCODE H');

		// ---------------------------------------------------------

		// Write the barcoded number
		$pdf->Text(47, 95, $ticketContent);
		array_push($ticketNumber, $ticketContent);

		// Write Friday/Saturday/Weekend/Vip
		$typedesc = "* Invalid *";
		if ($type == 0) $typedesc = 'FRIDAY ENTRY'; 
		if ($type == 1) $typedesc = 'SATURDAY ENTRY';
		if ($type == 2) $typedesc = 'WEEKEND ENTRY';
		if ($type == 3) $typedesc = 'VIP WEEKEND ENTRY';
		$pdf->Text(41, 82, $typedesc);

		// Embed the google map
		$pdf->Image('img/map.jpg', 15, 120, 100, 70, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
		// This produces the link, great, but too high up.      $pdf->writeHTML('<a href="https://maps.google.co.uk/maps?q=comlongon+castle&hl=en&ll=55.004795,-3.421512&spn=0.013586,0.037036&hq=comlongon+castle&t=m&z=15" dir="ltr">See it on Google Maps</a>', true, false, false, false, '');
	}
  }

	// Print the 'receipt' page
	$pdf->AddPage();

	$totTaken = (($friday*1500) + ($saturday*1500) + ($weekend*2500) + ($vip*4000) + ($friday*30) + ($saturday*30) + ($weekend*30) + ($vip*30));
	$tot = number_format(($totTaken/100),2);

	$pdf->SetFont('helvetica', '', 10);
	$pdf->writeHTML('<b>Event: ' . $qe['title'], true, false, false, false, '');
	$pdf->writeHTML('<b>Venue: ' . $qe['address'], true, false, false, false, '');
	$pdf->writeHTML('<b>Date: ' . $qe['start_date'], true, false, false, false, '');
	$pdf->writeHTML('<b>Order number: ' . $orderNumber, true, false, false, false, '');
	$pdf->writeHTML('<b>' . $friday . ' Friday entry tickets @ £15.00' );
	$pdf->writeHTML('<b>' . $saturday . ' Saturday entry tickets @ £15.00' );
	$pdf->writeHTML('<b>' . $weekend . ' Weekend entry tickets @ £25.00' );
	$pdf->writeHTML('<b>' . $vip . ' Weekend VIP entry tickets @ £40.00' );
	$pdf->writeHTML('<b>Booking fee £' . number_format((($friday+$saturday+$weekend+$vip)*0.30), 2, '.', ''));
	$pdf->writeHTML('<b>Total ' . ($friday + $saturday + $weekend + $vip) . ' tickets on order ', true, false, false, false, '');
	$pdf->writeHTML('<b>Order total, including booking fee: £ ' . $tot, true, false, false, false, '');
	$pdf->writeHTML('<p><br><b>Additional information</b><br/>', true, false, false, false, '');
	$pdf->SetFont('helvetica', '', 10);
	$row = 85;
	foreach ($ticklines as $value)
	{
		$pdf->Text(13, $row, $value);
		$row += 5;
	}

	$pdf->SetFont('helvetica', '', 8);

	$tnc = '<br><p><h4>Standard Terms & Conditions:</h4><p>
	If an event is cancelled your booking will be refunded directly by the Event Organiser. If details of the Event are significantly changed after a booking is placed or if an event is moved from its advertised venue or the date is changed tickets already purchased remain valid should you wish to attend the revised Event. If you do not wish to attend the revised Event your booking will be refunded in full by the Event Organiser.
	<p>
	Some event organisers will explicitly stipulate that cancellations due to weather, act of God or any other unavoidable eventuality (force majeure) will not result in a refund. If such terms, or any other terms were applied at the time of booking and stated in the Additional Information section above and on the E-Ticket, they shall prevail over these Standard Terms & Conditions and may result in no refund being due.
	<p>

	Entry for Tickets Holders are purely at the discretion of the Event Organiser or their Security and is subject to their own Terms and Conditions as specified in the Additional Information section. If any Ticket Holder is found to be causing trouble or acting inappropriately to the point it is considered by the Event Organiser or their Security to be negatively affecting the enjoyment or safety of staff or visitors the Event Organiser reserves the right to refuse entry or remove the offending individual from the event and / or premises.  

	<p>
	Tickets cannot be exchanged, refunded or replaced if lost as the varying exchange and refund policies set forth by individual Event Organisers prohibit DG Link or Wirefly Design Ltd from doing so. Where a refund is sought you should contact the Event Organiser directly as only they may reply to your request, and at their discretion.
	<p>
	DG Link or Wirefly Design Ltd will under no circumstances be responsible for any remedy for inconvenience, consequential expenses incurred or any other loss or damage resulting from the cancellation of or change to any Events.
	<p>
	Booking Fees are VAT exempt as they are applied to cover card transaction fees. DG Link / Wirefly Design Ltd provides an online ticket booking service on behalf of the Event Organiser as stated on the Receipt and Ticket. Registered address for Wirefly Design Ltd: Crimond, Mavis Grove, Dumfries, DG2 8EP
	<p>
	Debit and credit card payments are processed on behalf of DG Link by Wirefly Design Ltd. Ticket bookings will appear on your statement as WWW.DGLINK.CO.UK.

	<p>

	If you would like to sell or allow ticket reservations / bookings for your next event, workshop or activity you can do this by uploading your event for free on www.dglink.co.uk. You will then have the option to use the box office facility. There are no fees for ticket reservations, please see www.dglink.co.uk for a full list of fees and Terms and Conditions.';


	$pdf->writeHTML($tnc, true, false, false, false, '');

	// ---------------------------------------------------------
	//Close and output PDF document
	unlink('/tmp/' . $orderNumber . '.pdf');
	$pdf->Output('/tmp/' . $orderNumber . '.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+

}



