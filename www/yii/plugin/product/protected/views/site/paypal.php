<?php
if ($this->test == 1)
{
	$business = "jo.seawright@hotmail.com";
	$subtotal = "0.01";
	$shipping = "0.00";
}
?>

<br/><br/><br/><br/><br/> <center> <h5 style="color:grey">Please wait, redirecting to Paypal...</h5> </center>

<div style="display:none;">
    <form id="pp" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="charset" value="utf-8"> <!-- This gets rid of the weird A before the £ -->
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value=<?php echo $business;?>>
        <input type="hidden" name="lc" value="GB">
        <!-- Default item_name and amount follow, but could be overridden by the dropdown list -->
        <input type="hidden" name="item_name" value="<?php echo $description ;?>">
        <input type="hidden" name="amount" value="<?php echo $subtotal;?>">
        <input type="hidden" name="item_number" value="<?php echo '';?>">
        <input type="hidden" name="button_subtype" value="services">
        <input type="hidden" name="no_note" value="0">
        <input type="hidden" name="shipping" id="shipping" value=<?php echo $shipping;?>  >
<input type="hidden" name="return" value="https://plugin.wireflydesign.com/product/index.php/site/paid?sid=<?php echo Yii::app()->session['sid'];?>"   />

        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">

<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">

		<input type="hidden" name="currency_code" value="GBP">
		<input type="hidden" name="option_index" value="0">

	</form>
</div>

<script type="text/javascript">
document.getElementById("pp").submit();
</script>

