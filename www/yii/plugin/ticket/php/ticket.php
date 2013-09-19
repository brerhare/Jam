<?php

function genTicket(
	$order_number,
	$order_name,
	$order_card,
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

	if (strlen($eventModel->ticket_logo_path) > 0)
	{
		if (file_exists(Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $eventModel->ticket_logo_path))
			$logo = Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $eventModel->ticket_logo_path;
		else
			$logo = Yii::app()->baseUrl . '/img/default_logo.jpg';
	}
	
	$ticketLogo = CHtml::image(
		$logo,
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
			if ($eventModel->optional_start_ticket_number == 0)
				$ticketContent = $vendorModel->id . $eventModel->id . $ticketRand;
			else
			{
				$ticketContent = $eventModel->optional_next_ticket_number;
				$eventModel->optional_next_ticket_number++;
				$eventModel->save();
			}
			$ticketName = '     ' . substr($order_name, 0, 5);

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
					<table>
						<tr>
							<td>
								Ticket number $ticketContent
							</td>
							<td>
								$ticketName
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<b>$eventModel->address $eventModel->post_code</b>
		<br/><br/>

		<pre>$eventModel->ticket_text</pre>

		<p/>
		$eventModel->ticket_terms
EOD;

		$deadlink =  '<img alt="" src="http://">';
		if (strstr($tbl, $deadlink))
			$tbl = str_replace($deadlink, "", $tbl);

		$pdf->writeHTML($tbl, true, false, false, false, '');

			// QR
			$pdf->write2DBarcode($ticketContent, 'QRCODE,Q', 163, 92, 35, 35, $style, 'N');
			// EAN 13
			$pdf->StartTransform();
			$pdf->Rotate(-90);
			//                                              U-D  L-R
			$pdf->write1DBarcode($ticketContent, 'UPCA', -24, 105, '', 18, 0.4, $style, 'N');
			$pdf->StopTransform();

			// Send back each ticket number for scanning			
			array_push($ticket_numbers, $ticketContent);
		}
	}


	// Print the 'receipt' page
	$pdf->AddPage();

	$pdf->SetFont("helvetica", "", 10);
	$pdf->writeHTML("<b>Vendor: " . $vendorModel->name . " " . $vendorModel->address . " " . $vendorModel->post_code);
	$pdf->writeHTML("<b>Event Date: " . $eventModel->date);
	$pdf->writeHTML("<b>Order Number: " . $order_number);
	$pdf->writeHTML("<b>Number of tickets on order: " . $totalTickets);
	$pdf->writeHTML("<b>Name: " . $order_name);
	$pdf->writeHTML("<b>Card number: " . $order_card);
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
