<?php

//
// Email weekly statistics to all ticket event owners.
// Events that were active in the last week are included.
// It reports the last week ended yesterday. Ie if you want Sunday-Sunday then cron it for early morning Monday.
//

// PHPMailer
require_once('php/PHPMailer/class.phpmailer.php');

class StatCommand extends CConsoleCommand
{
	public function run($args)
	{
		$cr = "<br>";
		$fp = fopen('/tmp/ticketSales.csv', 'w');
		$heading = array('vendor', 'event', 'area', 'ticket_type', 'price_each', 'sales_qty', 'sales_value', 'timestamp');
		fputcsv($fp, $heading);
		
		// Report date range
		$fromdate = new DateTime('7 days ago');
		$todate = new DateTime('1 days ago');

		$gmsg = "";
		$criteria = new CDbCriteria;
		$criteria->order = 'name ASC';
		$vendors = Vendor::model()->findAll($criteria);

		// All vendors
		foreach ($vendors as $vendor)
		{
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
				$etbl = "<table  border='0' cellspacing='3' cellpadding='3' style='border: 15px solid #EEEEEE'><tr><td><u>Area</u></td><td><u>Ticket Type</u></td><td><u>Price Each</u></td><td><u>Sales Qty</u></td><td><u>Sales Value</u></td></tr>";
				foreach ($areas as $area)	// All ticket areas
				{
					$ticketTypes = $area->ticketTypes;
					foreach ($ticketTypes as $ticketType)	// All ticket types
					{
						$etbl .= "<tr>";
						$etbl .= "<td>" . $area->description . "</td>";	
						$etbl .= "<td>" . $ticketType->description . "</td>";
						$etbl .= "<td style='text-align:right'>" . $ticketType->price . "</td>";
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
						$transactions = Transaction::model()->findAll($criteria);
						foreach ($transactions as $transaction)	// All event transactions for the period
						{
							$qty += $transaction->http_ticket_qty;
							$val += $transaction->http_ticket_total;
							$eQty += $transaction->http_ticket_qty;
							$eVal += $transaction->http_ticket_total;
							if (strlen(trim($transaction->http_ticket_total)) > 0)
								$uQty += $transaction->http_ticket_qty;
							$uVal += $transaction->http_ticket_total;
							$line = array($vendor->name, $event->title, $area->description, $ticketType->description, $ticketType->price, $qty, sprintf("%01.2f", $val), $transaction->timestamp);
							fputcsv($fp, $line);
						}
						$etbl .= "<td style='text-align:right'>" . $qty . "</td>";
						$etbl .= "<td style='text-align:right'>" . sprintf("%01.2f", $val) . "</td>";
						$etbl .= "</tr>";
					}
				}
				$etbl .= "<tr><td></td><td></td><td style='text-align:right'><i>Total</i></td><td style='text-align:right'><i>" . $eQty . "</i></td><td style='text-align:right'><i>" . sprintf("%01.2f", $eVal) . "</i></td></table>";
				$umsg .= $etbl;
			}

			// Accumulate to global msg
			$umsg .= $cr . "<b>Total sales: " .  sprintf("%01.2f", $uVal) . "</b>" . $cr;
			$umsg .= "<b>Total paid tickets: " . $uQty . "</b>" . $cr;
			$umsg .= "</b>";
			if ($uVal != 0)
			{
				$umsg .= $cr . "2.5% of Total sales = <b>" . sprintf("%01.2f",($uVal * 2.5 / 100)) . "</b>" . $cr;
				$umsg .= "0.50p per (paid) ticket = <b>" . sprintf("%01.2f",($uQty * 0.5)) . "</b>" . $cr;
				$umsg .= "Amount to be invoiced using reference " . $event->uid . "-" . $todate->format('Ymd') . ": <b>" . sprintf("%01.2f", ($uVal * 2.5 / 100) + ($uQty * 0.5) ) . "</b" . $cr;
			}
			$umsg .= $cr . "<hr>" . $cr;
			if ($hasActiveEvent)
			{
				// Send email to vendor
				$to = $vendor->email;
				if (strlen($to) > 0)
				{
					$from = "admin@dglink.co.uk";
					$fromName = "Admin";
					$subject = "Your weekly event sales report";
					$message = $umsg; 
					// phpmailer
					$mail = new PHPMailer();
					$mail->AddAddress($to);
$mail->AddBCC("kim@wireflydesign.com");
					$mail->SetFrom($from, $fromName);
					$mail->AddReplyTo($from, $fromName);
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

		// Send summary email to jo
		$to = "jo@wireflydesign.com";
		$att_filename = "/tmp/ticketSales.csv";
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Weekly vendor event sales summary";
			$message = $gmsg; 
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
		echo $gmsg;
	}

}
?>
