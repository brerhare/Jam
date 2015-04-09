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

// Report Detail
// -------------

$fst = 1;

$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$criteria->addCondition("order_number = '" . $order_number . "'");
$criteria->order = 'id ASC';
$orders = Order::model()->findAll($criteria);

foreach ($orders as $order)
{
	if ($fst == 1)
	{
		echo "<div id='printDiv'>";
		echo "<hr/>";
		$fst = 0;
		echo "<table><tr><td width=20%></td><td style='Xbackground-color:#dbdbdb' width=20%>";
		$name = $order->name;
		if (trim($order->card_name) != "")
			$name = $order->card_name;
		echo $name . "<br>";

		echo $order->delivery_address1 . "<br>";
		if (trim($order->delivery_address2) != "")
			echo $order->delivery_address2 . "<br>";
		if (trim($order->delivery_address3) != "")
			echo $order->delivery_address3 . "<br>";
		if (trim($order->delivery_address4) != "")
			echo $order->delivery_address4 . "<br>";
		if (trim($order->delivery_post_code) != "")
			echo $order->delivery_post_code . "<br>";
		echo "<br>";
		echo "<a href='mailto:" . trim($order->email_address) . "?Subject=Wee Target' target='_top'>" .$order->email_address . "</a>" . "<br>";
		echo "Tel : " . $order->telephone . "<br>";
		echo "<br>";
		if ($order->payment_type == 0)
			echo "Card ending ************ " . substr($order->card_number, 12, 4) . "<br>";
		else
			echo "Own payment method used" . "<br>";
		echo "<br> <button class='btn btn-primary' type='button' onclick='printDiv()'>Print</button>";
		echo "</td><td width=40%>";

		echo "<h5><i>";
		$dt = explode("-", $order->order_number);
		if (sizeof($dt) > 1)
			echo "Date : " . date('d/m/Y', $dt[1]) . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
		echo "Order number : " . $order->order_number;
		echo "</i></h5>";

		echo "<table>";
		echo '<tr style="background-color:#c3d9ff; color:#0088cc;">';
		echo '<td>Product</td>';
		echo '<td style="text-align:right">Qty</td>';
		echo '<td style="text-align:right; width:70px">Each</td>';
		echo '<td style="text-align:right; width:70px">Line total</td>';
		echo "</tr>";
	}
		echo "<tr>";
		echo "<td>";
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $order->http_product_id);
		$product = Product::model()->find($criteria);
		$name = $order->http_product_id;
		if ($product)
			$name = $product->name;
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $order->http_option_id);
		$option = Option::model()->find($criteria);
		$optionDesc = "";
		if ($option)
			$optionDesc = $option->name;
		echo $name . " " . $optionDesc;
		echo "</td>";
		echo '<td style="text-align:right">';
		echo $order->http_qty;
		echo "</td>";
		echo '<td style="text-align:right">';
		echo $order->http_price;
		echo "</td>";
		echo '<td style="text-align:right">';
		echo $order->http_line_total;
		echo "</td>";
		echo "</tr>";
}

if ($fst != 1)
{
	$shipAmount = "0.00";
	$criteria = new CDbCriteria;
	$criteria->addCondition("id = " . $order->http_shipping_id);
	$shipping = ShippingOption::model()->find($criteria);
	if ($shipping)
		$shipAmount = $shipping->price;
	echo "<tr>";
	echo '<td colspan=3 style="text-align:right">';
	echo $shipping->description;
	echo "</td>";
	echo '<td style="text-align:right">';
	echo $shipAmount;
	echo "</td></tr>";
	//if (trim($order->notes) != "")


	echo "<tr>";
	echo '<td colspan=3 style="text-align:right">';
	echo "<b>Order total</b>";
	echo "</td>";
	echo '<td style="text-align:right">';
	echo "<b>" . $order->http_total . "</b>";
	echo "</td></tr>";
	//if (trim($order->notes) != "")
	{
		echo "<tr><td colspan=4><br>" . "Notes : " . $order->notes . "</td><tr>";
	}
	//if (trim($order->promo_code) != "")
	{
		echo "<tr><td colspan=4><br>" . "Voucher code : " . $order->promo_code . "</td><tr>";
	}
	echo "</table>";
	echo "</td><td width=20%></td></tr>";
	echo "</table>";
	echo "<hr/>";
	echo "</div> <!-- printDiv -->";
}

?>

	<script>

	jQuery(document).ready(function($){
	});

	function printDiv()
	{
		content = '<style> div { font-family: Sans-Serif; } #header-date {margin-top:40px;}</style>';
		content += '<div>';
		content += document.getElementById('printDiv').innerHTML;
		content += '</div>';
		var WinPrint = window.open('', '', 'letf=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
		WinPrint.document.write(content);
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
	}

	</script>

