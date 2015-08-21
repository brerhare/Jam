<style>
table td, table th {
	padding: 0;
}
table tr {
	background-color:#f8f8f8;
	padding: 2px;
}
</style>

<?php

$this->pageTitle=Yii::app()->name;

echo "<h2>Welcome to <i>"; echo CHtml::encode(Yii::app()->name); echo "</i></h2>";
echo "<h5>Sales report. Click on an order number for more detail</h5>";

if (Yii::app()->user->isGuest):
	echo "<h5>Please login to continue</h5>";
else:


// Report
// ------

$order_number = "";

echo '<div class="row">';
echo '<div class="span12">';
	echo '<table id="reportTable">';
		echo '<tr style="background-color:#c3d9ff; color:#0088cc;">';
			echo '<td style="text-align:center">Date</td>';
			echo '<td>Order Number</td>';
			echo '<td>Name</td>';
			echo '<td>Post Code</td>';
			echo '<td style="text-align:right">Qty</td>';
			echo '<td style="text-align:right; width:70px">£</td>';
			echo '<td style="padding-left:20px">Payment Method</td>';
		echo '</tr>';

		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->order = 'id DESC';
		$orders = Order::model()->findAll($criteria);
		foreach ($orders as $order):

//            // Suppress values for non-paymentsense tickets
//            if (!($transaction->auth_code))
//                $transaction->http_ticket_total = 0;
			if (trim($order->order_number) == "")
				continue;
			if (!(strstr($order->ip, "x-")))
				continue;

			if (($order->card_name == "Geoff Wayne")
			||  ($order->card_name == "Kim")
			||  ($order->card_name == "Geoff Wayne")
			||  ($order->card_name == "name")
			||  ($order->card_name == "Kim Hancock")
			||  ($order->card_name == "Timothy Taylor")
			||  ($order->card_name == "James Jackson")
			||  ($order->card_name == "John Watson"))
//if ($order->order_number != "76-1425808212")
				continue;

			// Print each order line only once (each has the totals)
			if ($order_number == $order->order_number)
				continue;
			if ($order_number == "")
				$order_number = $order->order_number;
			$order_number = $order->order_number;

			echo "<tr>";
			echo "	<td style='text-align:center'>";
			$dt = explode("-", $order->order_number);
			if (sizeof($dt) > 1)
				echo date('d/m/Y', $dt[1]);
//				echo sprintf("%02s/%02s/%02s", substr($date,8,2),substr($date,5,2),substr($date,2,2));
			echo "	</td>";
			echo "	<td>";
			echo '<a title="View this customers order" target="_blank"  href="' . Yii::app()->controller->createUrl("index2") . "/?o=" . $order->order_number . '">' . $order->order_number . '</a>';
			echo "	</td>";
			echo "<td>";
			$name = $order->name;
			if (trim($order->card_name) != "")
				$name = $order->card_name;
			echo substr($name, 0, 30) . "<br>";
			echo "		</td>";
			echo "<td>";
			echo $order->delivery_post_code;
			echo "	</td>";
			echo '	<td style="text-align:right">';
			echo $order->http_total_qty;
			echo "	</td>";
			echo "	<td style='text-align:right'>";
			echo $order->http_total;
			echo "	</td>";
			echo "	<td style='padding-left:40px'>";
			if ($order->payment_type == 0)
				echo "DG Link";
			else
				echo "Own";
			echo "	</td>";
			echo "</tr>";
		endforeach;

	echo "</table>";
echo "</div>";
echo "</div>";

echo "<script>";
echo "$(document).ready(function() {";
echo "});";
echo "</script>";


endif ?>
