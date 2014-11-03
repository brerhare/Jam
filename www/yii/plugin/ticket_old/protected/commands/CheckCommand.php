<?php

// DEBUG program. NOT production

class CheckCommand extends CConsoleCommand
{
	public function run($args)
	{
$recs = 0;
$tkts = 0;

		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = 21");
        $auths = Auth::model()->findAll($criteria);
		foreach ($auths as $auth)
		{
$recs++;
echo ".";
//echo $auth->order_number;
			$criteria = new CDbCriteria;
			$criteria->addCondition("order_number = '" . $auth->order_number . "'");
			$scans = Scan::model()->findAll($criteria);
			foreach ($scans as $scan)
			{
				echo "+";
$tkts++;
			}

//			if ($transaction->auth_code == NULL)
//				continue;	// We only want paymentsense sales on the report (not manual)

			//$qty += $transaction->http_ticket_qty;
			//$val += $transaction->http_ticket_total;
			//if ($transaction->http_ticket_price != "0.00")
				//$uQty += $transaction->http_ticket_qty;
			//$uVal += $transaction->http_ticket_total;
		}

echo "\nAuths=".$recs."\n";;
echo "\nTkts=".$tkts."\n";;
	}

}
?>
