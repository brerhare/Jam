<?php

//
// Email weekly statistics to all ticket event owners.
// Events that were active in the last week are included.
// It reports the last week ended yesterday. Ie if you want Sunday-Sunday then cron it for early morning Monday.
//

// PHPMailer
require_once('php/PHPMailer/class.phpmailer.php');

class TicketReminderCommand extends CConsoleCommand
{
	public function run($args)
	{

/**********************************************
$file = fopen("/tmp/cronkim.txt","w");
fwrite($file,"Ticket Reminder3\n");
fclose($file);
**********************************************/

		$datetime = new DateTime('tomorrow');
		$tmw = trim($datetime->format('Y-m-d'));
//$tmw = '2014-04-08';

		// Get all events happening tomorrow
		$criteria = new CDbCriteria;
		$criteria->addCondition("date = '" . $tmw . "'");
		$events = Event::model()->findAll($criteria);
		foreach ($events as $event)
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("event_id = " . $event->id);
			$transactions = Transaction::model()->findAll($criteria);
			foreach ($transactions as $transaction)	// All event transactions for the period
			{
				// Send email to purchaser
				$to = $transaction->email;
//$to="k@microboot.com";
				if (strlen($to) > 0)
				{
					$from = "admin@dglink.co.uk";
					$fromName = "Admin";
					$subject = $event->title;
					$message = "<p>This is a reminder that An event you have tickets for is on tomorrow.</p>";
					$message .= "<p>Address: " . $event->address . " " . $event->post_code . "</p>";
					// phpmailer
					$mail = new PHPMailer();
					$mail->AddAddress($to);
					$mail->SetFrom($from, $fromName);
					$mail->AddReplyTo($from, $fromName);
					$mail->Subject = $subject;
					$mail->MsgHTML($message);
					if (!$mail->Send())
						Yii::log("TICKET REMINDER COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
					else
						Yii::log("TICKET REMINDER SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
			}
		}

	}

}
?>
