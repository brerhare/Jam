<?php

require('protected/extensions/PHPMailer/PHPMailer.php');

//
// Email weekly statistics to all product vendors.
// Events that were active in the last week are included.
// It reports the last week ended yesterday. Ie if you want Sunday-Sunday then cron it for early morning Monday.
//

// PHPMailer
//require_once('php/PHPMailer/class.phpmailer.php');

class VendorReportCommand extends CConsoleCommand
{
	public function run($args)
	{
		$cr = "<br>";
		$fp = fopen('/tmp/productSales.csv', 'w');
		$heading = array('vendor', 'date', 'order_number', 'name', 'email', 'telephone', 'address1', 'address2', 'address3', 'address4', 'post_code', 'sales_qty', 'sales_value');
		fputcsv($fp, $heading);
		
		// Report date range
		$fromdate = new DateTime('7 days ago');
		$todate = new DateTime('1 days ago');

		$ghtmlstart = "<html><style>html, table, div, tr, td, * { font-size: small !important; color: #150e00 !important; background-color: #e1dab9 !important; font-family: Calibri, Verdana, Arial, Serif !important; } table td { border-left:solid 10px transparent; } table td:first-child { border-left:0; }</style>";
		$ghtmlend = "</html>";
		$gmsg = "";
		$gsummary = "<table>";
		$gsummarytotalsales = 0;
		$gsummarytotalfees = 0;
		$gsummarytotaldue = 0;
		$criteria = new CDbCriteria;
		$criteria->order = 'display_name ASC';
		$users = User::model()->findAll($criteria);
		// All users
		foreach ($users as $user)
		{
			if ($user->id == 19)	// ignore Jacquies Beauty, they have their own merchant account
				continue;
			$criteria = new CDbCriteria;
			$criteria->addCondition("sid = '" . $user->sid . "'");
			$criteria->addCondition("payment_type = " . 0);
			$criteria->addCondition("timestamp >= '" . $fromdate->format('Y-m-d') . " 00:00:00'");
			$criteria->addCondition("timestamp <= '" . $todate->format('Y-m-d') . " 99:99:99'");
//echo $cr . $fromdate->format('Y-m-d') . " 00:00:00" . " ..... " . $todate->format('Y-m-d') . " 99:99:99" . $cr;
//$criteria->order = "timestamp, auth_code";
			$orders = Order::model()->findAll($criteria);
			if (!($orders))
				continue;

			if (file_exists('/tmp/productVendorSales.csv'))
				unlink('/tmp/productVendorSales.csv');
			$fp2 = fopen('/tmp/productVendorSales.csv', 'w');
			fputcsv($fp2, $heading);

			$umsg = "";	
			$hasActiveEvent = false;
			$umsg .= "<b><u>Product transactions for " . $user->display_name . ". Period " . $fromdate->format('l d F Y') . " to " . $todate->format('l d F Y') . "</u></b>" . $cr;
			$uQty = 0;
			$uVal = 0;

			$etbl = '<table>';
			$etbl .=     '<tr style="background-color:#c3d9ff; color:#0088cc;">';
			$etbl .=         '<td style="text-align:center">Date</td>';
			$etbl .=         '<td>Order Number</td>';
			$etbl .=         '<td>Name</td>';
			$etbl .=         '<td>Post Code</td>';
			$etbl .=         '<td style="text-align:right">Transactions</td>';
			$etbl .=         '<td style="text-align:right; width:70px">Sales Value</td>';
			$etbl .=     '</tr>';

			$order_number = "";

			foreach ($orders as $order)	// All x-orders for the period
			{
            	// Print each order line only once (each has the totals)
            	if ($order_number == $order->order_number)
                	continue;
            	if ($order_number == "")
                	$order_number = $order->order_number;
            	$order_number = $order->order_number;

				$etbl .= "<tr>";
				$etbl .= "  <td style='text-align:center'>";
				$dt = explode("-", $order->order_number);
				if (sizeof($dt) > 1)
					$etbl .= date('d/m/Y', $dt[1]);
				$etbl .= "  </td>";
				$etbl .= "  <td>";
				$etbl .= $order->order_number;
				$etbl .= "  </td>";
				$etbl .= "<td>";
				$name = $order->name;
				if (trim($order->card_name) != "")
					$name = $order->card_name;
				$etbl .= substr($name, 0, 30) . "<br>";
				$etbl .= "      </td>";
				$etbl .= "<td>";
				$etbl .= $order->delivery_post_code;
				$etbl .= "  </td>";
				$etbl .= '  <td style="text-align:right">';
				$etbl .= $order->http_total_qty;
				$etbl .= "  </td>";
				$etbl .= "  <td style='text-align:right'>";
				$etbl .= $order->http_total;
				$etbl .= "  </td>";
				$etbl .= "</tr>";

				//$heading = array('vendor', 'date', 'order_number', 'name', 'email', 'telephone', 'address1', 'address2', 'address3', 'address4', 'post_code', 'sales_qty', 'sales_value');
				$line = array($user->display_name, $order->timestamp, $order->order_number, $order->name, $order->email_address, $order->telephone, $order->delivery_address1, $order->delivery_address2, $order->delivery_address3, $order->delivery_address4, $order->delivery_post_code, $order->http_total_qty, sprintf("%01.2f", $order->http_total));
				fputcsv($fp, $line);
				fputcsv($fp2, $line);

				//$qty += 1;
				//$val += $order->http_total;
				$uQty += 1;
				$uVal += $order->http_total;
			}

			$etbl .= "<tr><td></td><td></td><td></td><td style='text-align:right'><i>Total</i></td><td style='text-align:right'><i>" . $uQty . "</i></td><td style='text-align:right'><i>" . sprintf("%01.2f", $uVal) . "</i></td></tr></table>";
			$umsg .= $etbl;

			fclose($fp2);

			// Accumulate to global msg
			$umsg .= $cr . "<b>Total sales: " .  sprintf("%01.2f", $uVal) . "</b>" . $cr;
			$umsg .= "<b>Transactions: " . $uQty . "</b>" . $cr;
			$umsg .= "</b>";
			if ($uVal != 0)
			{
				$umsg .= $cr . "2.5% of Total sales = <b>" . sprintf("%01.2f",($uVal * 2.5 / 100)) . "</b>" . $cr;
				$umsg .= "Add 0.50p per transaction = <b>" . sprintf("%01.2f",($uQty * 0.5)) . "</b>" . $cr;
				$umsg .= "Transaction fees = <b>" . sprintf("%01.2f", ($uVal * 2.5 / 100) + ($uQty * 0.5) ) . "</b>" . $cr;
				$umsg .= "Amount to be invoiced, using reference <b>" . $user->id . "-" . $todate->format('Ymd') . "</b> = <b>" . sprintf("%01.2f", $uVal - (($uVal * 2.5 / 100) + ($uQty * 0.5) )) . "</b>" . $cr;
			}
			$umsg .= $cr . "<hr>" . $cr;

			$gsummary .= "<tr>";
			$gsummary .= "<td>" . $user->display_name . "</td><td>Ref: " . $user->id . "-" . $todate->format('Ymd') . "</td>";
			$gsummary .= "<td>Total sales " . sprintf("%01.2f", $uVal) . "</td><td>Total fees: " . sprintf("%01.2f", ($uVal * 2.5 / 100) + ($uQty * 0.5) ) . "</td>";
			$gsummary .= "<td>Total due " . sprintf("%01.2f", $uVal - (($uVal * 2.5 / 100) + ($uQty * 0.5) )) . "</td><td>Paid: </td>";
			$gsummarytotalsales += $uVal;
			$gsummarytotalfees += ($uVal * 2.5 / 100) + ($uQty * 0.5);
			$gsummarytotaldue += $uVal - (($uVal * 2.5 / 100) + ($uQty * 0.5) );

			// Send email to vendor
			$to = $user->email_address;
			$att_filename = "/tmp/productVendorSales.csv";
			if (strlen($to) > 0)
			{
				$from = "admin@dglink.co.uk";
				$fromName = "Admin";
				$subject = "Your weekly product sales report";
				$message = $ghtmlstart . $umsg . $ghtmlend; 
				// phpmailer
				$mail = new PHPMailer();
				$mail->AddAddress($to);				// KIM
//				$mail->AddAddress("k@microboot.com");	// KIM
//$mail->AddBCC("info@wireflydesign.com");
				$mail->SetFrom($from, $fromName);
				$mail->AddReplyTo($from, $fromName);
				$mail->AddAttachment($att_filename);
				$mail->Subject = $subject;
				$mail->MsgHTML($message);
//				if (!$mail->Send())
//					Yii::log("WEEKLY REPORT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
//				else
//					Yii::log("WEEKLY SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
			}
			// Accumulate to global
			$gmsg .= $umsg;
		}

		// Final summary total line
		$gsummary .= "<tr><td></td><td>Total</td>";
		$gsummary .= "<td>Total sales " . sprintf("%01.2f", $gsummarytotalsales) . "</td><td>Total fees: " . sprintf("%01.2f", $gsummarytotalfees ) . "</td>";
        $gsummary .= "<td>Total due " . sprintf("%01.2f", $gsummarytotaldue) . "</td><td></td>";
		$gsummary .= "</table><br/><hr/><br/>";

		$gcontent = $ghtmlstart . $gsummary . $gmsg . $ghtmlend;

		// Send summary email to jo
		$to = "info@wireflydesign.com";				// KIM jo
		$att_filename = "/tmp/productSales.csv";
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Vendor Product Sales " . $fromdate->format('d/m') . " - " . $todate->format('d/m');
			$message = $gcontent; 
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
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
