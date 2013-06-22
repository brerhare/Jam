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

		// Report date range
		$fromdate = new DateTime('8 days ago');
		$todate = new DateTime('1 days ago');

		$gmsg = "";
		$criteria = new CDbCriteria;
		$criteria->order = 'display_name ASC';
		$users = User::model()->findAll($criteria);

		// All users
		foreach ($users as $user)
		{
			$umsg = "";	
			$hasActiveEvent = false;
			$umsg .= "<b><u>Event transactions for " . $user->display_name . ". Period " . $fromdate->format('l d F Y') . " to " . $todate->format('l d F Y') . "</u></b>" . $cr;
			$uQty = 0;
			$uVal = 0;

			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . $user->id);
			$criteria->addCondition("active = 1");
			$criteria->order = 'date ASC';
			$events = Event::model()->findAll($criteria);
			foreach ($events as $event)	// All active events
			{
				$umsg .= "<i>" . $cr . $event->title . " on " . $event->date . "</i>" . $cr;
				$hasActiveEvent = true;
				$eQty = 0;
				$eVal = 0;

				$areas = $event->areas;
				$etbl = "<table  border='0' cellspacing='3' cellpadding='3' style='border: 1px solid #000000'><tr><td><u>Area</u></td><td><u>Ticket Type</u></td><td><u>Price Each</u></td><td><u>Sales Qty</u></td><td><u>Sales Value</u></td></tr>";
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
						$criteria->addCondition("uid = " . $user->id);
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
							$uQty += $transaction->http_ticket_qty;
							$uVal += $transaction->http_ticket_total;
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
			$umsg .= $cr . "<b>" .  sprintf("%01.2f", $uVal) . " total sales, all events for the period (" . $uQty . " tickets)</b>" . $cr;
			$umsg .= $cr . "<hr>" . $cr;
			if ($hasActiveEvent)
			{
				// Send email
				$to = "jo@wireflydesign.com";
				if (strlen($to) > 0)
				{
					$from = "admin@dglink.co.uk";
					$fromName = "Admin";
					$subject = "Your weekly event report (Redirected to jo for testing)";
					$message = $umsg; 
					// phpmailer
					$mail = new PHPMailer();
					$mail->AddAddress($to);
					$mail->SetFrom($from, $fromName);
					$mail->AddReplyTo($from, $fromName);
					//$mail->AddAttachment($pdf_filename);
					$mail->Subject = $subject;
					$mail->MsgHTML($message);
					if (!$mail->Send())
					{
						Yii::log("WEEKLY REPORT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
					}
					else
						Yii::log("WEEKLY SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
				}

				// Accumulate to global
				$gmsg .= $umsg;
			}
		}

		// Send email
		$to = "jo@wireflydesign.com";
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Weekly event vendor summary";
			$message = $gmsg; 
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			//$mail->AddAttachment($pdf_filename);
			$mail->Subject = $subject;
			$mail->MsgHTML($message);
			if (!$mail->Send())
			{
				Yii::log("WEEKLY SUMMARY REPORT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
			}
			else
				Yii::log("WEEKLY SUMMARY SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}
		
		//echo $gmsg;
	}



	public function lrun($args)
	{
		$criteria = new CDbCriteria;
		//$criteria->addCondition("event_id = " . $model->id);
		$criteria->addCondition("uid = " . 4);
		$transactions = Transaction::model()->findAll($criteria);
		foreach ($transactions as $transaction)
		{
		 	$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . 4);
			$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
			$auth = Auth::model()->find($criteria);
			if ($auth)
				echo $transaction->id . " - " . $auth->id . " - " . $transaction->timestamp . "\n";;



		// Send email
		$to = $order->email_address;
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Your tickets purchased at DG Link";
			$message = '<b>Thank you for using the DG Link to order your ticket(s).</b> <br> The attached PDF file contains your ticket(s) and card receipt. Please print all pages and bring them with you to your event or activity. The barcode on each ticket can only be used once.<br> If you ever need to reprint your tickets you may login to the site and do so from your account page. If you have forgotten your log in details you can request a password reminder.<br> We hope you enjoy your event.  --  The DG Link Team';
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			$mail->AddAttachment($pdf_filename);
			$mail->Subject = $subject;
			$mail->MsgHTML($message);
			if (!$mail->Send())
			{
				Yii::log("PAID PAGE COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
				echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
			}
			else
				Yii::log("PAID PAGE SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}





		}
	}
}
?>
