<?php
// +----------------+
// | Good reference | https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/formbasics/
// +----------------+
if ($this->test == 1)
{
	$business = "jo.seawright@hotmail.com";
	$shipping = "";
}
?>

<br/><br/><br/><br/><br/> <center> <h5 style="color:grey">Please wait, redirecting to Paypal...</h5> </center>

<div style="Xdisplay:none;">
    <form id="pp" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="charset" value="utf-8"> <!-- This gets rid of the weird A before the £ -->
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="business" value=<?php echo $business;?>>
        <input type="hidden" name="lc" value="GB">

		<?php
        // Add in all the order records
        $ip = "UNKNOWN";
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        $criteria = new CDbCriteria;
        $criteria->addCondition("ip = '" . $ip . "'");
		$criteria->order = 'http_product_id ASC';
        $orders = Order::model()->findAll($criteria);
        if ($orders)
        {
			$tId = array();
			$tQty = array();
			$tName = array();
			$tPrice = array();
            foreach ($orders as $order)
            {
                $criteria = new CDbCriteria;
                $criteria->addCondition("id = " . $order->http_product_id);
                $product = Product::model()->find($criteria);
                $name = "";
                if ($product)
                    $name = $product->name;
				for ($x = 0; $x < sizeof($tId); $x++)
				{
					if ($tId[$x] == $order->http_product_id)
					{
						$tQty[$x] += $order->http_qty;
						break;
					}
				}
				if ($x == sizeof($tId))
				{
					array_push($tId, $order->http_product_id);
					array_push($tQty, $order->http_qty);
					array_push($tName, $name);
if ($this->test == 0)
					array_push($tPrice, $order->http_price);
else array_push($tPrice, "0.01");

				}
            }

			$cnt = 1;
			for ($x = 0; $x < sizeof($tId); $x++)
			{
				echo "<input type='hidden' name='item_name_" . $cnt . "' value='" . $tName[$x] . "'>";
				echo "<input type='hidden' name='amount_" . $cnt . "' value='" . $tPrice[$x] . "'>";
				echo "<input type='hidden' name='quantity_" . $cnt . "' value='" . $tQty[$x] . "'>";
				$cnt++;
			}
			if (trim($shipping) != "")
			{
				echo "<input type='hidden' name='item_name_" . $cnt . "' value='Shipping'>";
				echo "<input type='hidden' name='amount_" . $cnt . "' value='" . $shipping . "'>";
				echo "<input type='hidden' name='quantity_" . $cnt . "' value='1'>";
			}
        }
		?>

        <input type="hidden" name="shipping" value="<?php echo $shipping;?>" >		<!-- shipping n/w with _cart, only _xclick -->

		<input type="hidden" name="return" value="https://plugin.wireflydesign.com/product/index.php/site/paid?sid=<?php echo Yii::app()->sess->get('sid');?>"   />
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">

<!--		<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"> -->
		<input type="hidden" name="currency_code" value="GBP">

	</form>
</div>

<script type="text/javascript">
document.getElementById("pp").submit();
</script>

