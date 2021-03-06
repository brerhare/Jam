<?php

//
// Email weekly statistics to all ticket event owners.
// Events that were active in the last week are included.
// It reports the last week ended yesterday. Ie if you want Sunday-Sunday then cron it for early morning Monday.
//

// PHPMailer
require_once('php/PHPMailer/class.phpmailer.php');

class VendorReportCommand extends CConsoleCommand
{
	public function run($args)
	{
		$cr = "<br>";
		$fp = fopen('/tmp/ticketSales.csv', 'w');
		$heading = array('vendor', 'event', 'area', 'ticket_type', 'date', 'order_number', 'auth_code', 'name', 'email', 'telephone', 'address1', 'address2', 'address3', 'address4', 'city', 'county', 'post_code', 'price_each', 'sales_qty', 'sales_value');
		fputcsv($fp, $heading);
		
		// Report date range
		$fromdate = new DateTime('7 days ago');
		$todate = new DateTime('1 days ago');

		$ghtmlstart = "<html><style>html, table, div, tr, td, * { font-size: small !important; color: #3B0B0B !important; background-color: #F8ECE0 !important; font-family: Calibri, Verdana, Arial, Serif !important; } table td { border-left:solid 10px transparent; } table td:first-child { border-left:0; }</style>";
		$ghtmlend = "</html>";
		$gmsg = "";
		$gsummary = "<table>";
		$gsummarytotalsales = 0;
		$gsummarytotalfees = 0;
		$gsummarytotaldue = 0;
		$criteria = new CDbCriteria;
		$criteria->order = 'name ASC';
		$vendors = Vendor::model()->findAll($criteria);

		// All vendors
		foreach ($vendors as $vendor)
		{


//kim
//if ($vendor->email != 'alex@electricfieldsfestival.com')
// continue;



			if (file_exists('/tmp/ticketVendorSales.csv'))
				unlink('/tmp/ticketVendorSales.csv');
			$fp2 = fopen('/tmp/ticketVendorSales.csv', 'w');
			fputcsv($fp2, $heading);
			$umsg = "";	
			$hasActiveEvent = false;
			$umsg .= "<b><u>Event transactions for " . $vendor->name . ". Period " . $fromdate->format('l d F Y') . " to " . $todate->format('l d F Y') . "</u></b>" . $cr;
			$uQty = 0;
			$uVal = 0;

			$events = $vendor->events;
			foreach ($events as $event)	// All active events
			{
				if (!($event->active))
					continue;
				$umsg .= "<i>" . $cr . $event->title . " : " . $event->date . "</i>" . $cr;
				$hasActiveEvent = true;
				$eQty = 0;
				$eVal = 0;

				$areas = $event->areas;
				$etbl = "<table  border='0' cellspacing='3' cellpadding='3' style='border: 15px solid #3B0B0B'><tr><td><u>Area</u></td><td><u>Ticket Type</u></td><td><u>Sales Qty</u></td><td><u>Sales Value</u></td></tr>";
				foreach ($areas as $area)	// All ticket areas
				{
					$ticketTypes = $area->ticketTypes;
					foreach ($ticketTypes as $ticketType)	// All ticket types
					{
						$etbl .= "<tr>";
						$etbl .= "<td>" . $area->description . "</td>";	
						$etbl .= "<td>" . $ticketType->description . "</td>";
						$qty = 0;
						$val = 0;

						$criteria = new CDbCriteria;
						$criteria->addCondition("vendor_id = " . $vendor->id);
						$criteria->addCondition("event_id = " . $event->id);
						$criteria->addCondition("http_area_id = " . $area->id);
						$criteria->addCondition("http_ticket_type_id = " . $ticketType->id);
						$criteria->addCondition("timestamp >= '" . $fromdate->format('Y-m-d') . " 00:00:00'");
						$criteria->addCondition("timestamp <= '" . $todate->format('Y-m-d') . " 99:99:99'");
//echo $cr . $fromdate->format('Y-m-d') . " 00:00:00" . " ..... " . $todate->format('Y-m-d') . " 99:99:99" . $cr;
//$criteria->order = "timestamp, auth_code";
						$transactions = Transaction::model()->findAll($criteria);
						foreach ($transactions as $transaction)	// All event transactions for the period
						{
							$criteria = new CDbCriteria;
							$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
							$auth = Auth::model()->find($criteria);
							$name = "";
							$a1 = "";
							$a2 = "";
							$a3 = "";
							$a4 = "";
							$city = "";
							$state = "";
							$pc = "";
							if ($auth != null)
							{
								$name = $auth->card_name;
								$a1 = $auth->address1;
								$a2 = $auth->address2;
								$a3 = $auth->address3;
								$a4 = $auth->address4;
								$city = $auth->city;
								$state = $auth->state;
								$pc = $auth->post_code;
							}

							$line = array($vendor->name, $event->title, $area->description, $ticketType->description, $transaction->timestamp, $transaction->order_number, $transaction->auth_code, $name, $transaction->email, $transaction->telephone, $a1, $a2, $a3, $a4, $city, $state, $pc, sprintf("%01.2f", $transaction->http_ticket_price), $transaction->http_ticket_qty, sprintf("%01.2f", $transaction->http_ticket_total));
							fputcsv($fp, $line);
							fputcsv($fp2, $line);

							if ($transaction->auth_code == NULL)
								continue;	// We only want paymentsense sales on the report (not manual)

							$qty += $transaction->http_ticket_qty;
							$val += $transaction->http_ticket_total;
							$eQty += $transaction->http_ticket_qty;
							$eVal += $transaction->http_ticket_total;
							if ($transaction->http_ticket_price != "0.00")
								$uQty += $transaction->http_ticket_qty;
							$uVal += $transaction->http_ticket_total;
						}
						$etbl .= "<td style='text-align:right'>" . $qty . "</td>";
						$etbl .= "<td style='text-align:right'>" . sprintf("%01.2f", $val) . "</td>";
						$etbl .= "</tr>";
					}
				}
				$etbl .= "<tr><td></td><td style='text-align:right'><i>Total</i></td><td style='text-align:right'><i>" . $eQty . "</i></td><td style='text-align:right'><i>" . sprintf("%01.2f", $eVal) . "</i></td></table>";
				$umsg .= $etbl;
			}

			fclose($fp2);

			// Accumulate to global msg
			$umsg .= $cr . "<b>Total sales: " .  sprintf("%01.2f", $uVal) . "</b>" . $cr;
			$umsg .= "<b>Total paid tickets: " . $uQty . "</b>" . $cr;
			$umsg .= "</b>";
			if ($uVal != 0)
			{
				$umsg .= $cr . "2.5% of Total sales = <b>" . sprintf("%01.2f",($uVal * 2.5 / 100)) . "</b>" . $cr;
				$umsg .= "Add 0.50p per (paid) ticket = <b>" . sprintf("%01.2f",($uQty * 0.5)) . "</b>" . $cr;
				$umsg .= "Transaction fees = <b>" . sprintf("%01.2f", ($uVal * 2.5 / 100) + ($uQty * 0.5) ) . "</b>" . $cr;
				$umsg .= "Amount to be invoiced, using reference <b>" . $event->uid . "-" . $todate->format('Ymd') . "</b> = <b>" . sprintf("%01.2f", $uVal - (($uVal * 2.5 / 100) + ($uQty * 0.5) )) . "</b>" . $cr;
			}
			$umsg .= $cr . "<hr>" . $cr;

			$gsummary .= "<tr>";
			$gsummary .= "<td>" . $vendor->name . "</td><td>Ref: " . $event->uid . "-" . $todate->format('Ymd') . "</td>";
			$gsummary .= "<td>Total sales " . sprintf("%01.2f", $uVal) . "</td><td>Total fees: " . sprintf("%01.2f", ($uVal * 2.5 / 100) + ($uQty * 0.5) ) . "</td>";
			$gsummary .= "<td>Total due " . sprintf("%01.2f", $uVal - (($uVal * 2.5 / 100) + ($uQty * 0.5) )) . "</td><td>Paid: </td>";
			//$gsummary .= "<tr><td></td><td></td></tr>";
			$gsummarytotalsales += $uVal;
			$gsummarytotalfees += ($uVal * 2.5 / 100) + ($uQty * 0.5);
			$gsummarytotaldue += $uVal - (($uVal * 2.5 / 100) + ($uQty * 0.5) );

			if ($hasActiveEvent)
			{
				// Send email to vendor
				$to = $vendor->email;
				$att_filename = "/tmp/ticketVendorSales.csv";
				if (strlen($to) > 0)
				{
					$from = "admin@dglink.co.uk";
					$fromName = "Admin";
					$subject = "Your weekly event sales report";
					$message = $ghtmlstart . $umsg . $ghtmlend; 
					// phpmailer
					$mail = new PHPMailer();


//kim
//if ($to == 'alex@electricfieldsfestival.com')
//{
//$to = 'kim@wireflydesign.com';
//$subject = 'Alexs report';
//}



					$mail->AddAddress($to);
//$mail->AddBCC("kim@wireflydesign.com");
					$mail->SetFrom($from, $fromName);
					$mail->AddReplyTo($from, $fromName);
					$mail->AddAttachment($att_filename);
					$mail->Subject = $subject;
					$mail->MsgHTML($message);
					if (!$mail->Send())
						Yii::log("WEEKLY REPORT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
					else
						Yii::log("WEEKLY SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
				}

				// Accumulate to global
				$gmsg .= $umsg;
			}
		}

		// Final summary total line
		$gsummary .= "<tr><td></td><td>Total</td>";
		$gsummary .= "<td>Total sales " . sprintf("%01.2f", $gsummarytotalsales) . "</td><td>Total fees: " . sprintf("%01.2f", $gsummarytotalfees ) . "</td>";
        $gsummary .= "<td>Total due " . sprintf("%01.2f", $gsummarytotaldue) . "</td><td></td>";
		$gsummary .= "</table><br/><hr/><br/>";

		$gcontent = $ghtmlstart . $gsummary . $gmsg . $ghtmlend;

		// Send summary email to jo
		$to = "jo@wireflydesign.com";
		$att_filename = "/tmp/ticketSales.csv";
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Vendor Sales " . $fromdate->format('d/m') . " - " . $todate->format('d/m');
			$message = $gcontent; 
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
$mail->AddBCC("kim@wireflydesign.com");
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			$mail->AddAttachment($att_filename);
			$mail->Subject = $subject;
			$mail->MsgHTML($message);
			if (!$mail->Send())
				Yii::log("WEEKLY SUMMARY REPORT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
			else
				Yii::log("WEEKLY SUMMARY SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}
		/////echo $gmsg;
	}

}
?>
