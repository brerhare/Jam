<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Products custom code
 *
 * Notes
 * -----
 * None
 */

class products
{
	// Globals
	private $uid = "";
	private $sid = "";

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
	  //var_dump( $options );

		$this->uid = Yii::app()->session['uid'];
		$this->sid = Yii::app()->session['sid'];

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "product_page_options_dropdown":
					return $this->product_page_options_dropdown($val);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	// Invoked by product.jel to show the product options for 
	private function product_page_options_dropdown($val)
	{

		/*********************
		// Check cookies
		for ($i = 0; $i < 10; $i++)
		{
			$cookieValue = (string)Yii::app()->request->cookies['wfcart'];
			if ($cookieValue)
				echo '<br>cooie=' . $cookieValue . '<br>';
			else
				echo '<br>noee<br>';
		}
		*********************/

		$productId = $val;
		$content = "";

		// Pick up the product record
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $productId);
		$product = Product::model()->find($criteria);

		// each product option
		$criteria = new CDbCriteria;
		$criteria->addCondition("product_product_id = " . $productId);
		$productHasOptions = ProductHasOption::model()->findAll($criteria);

		$content .= "<select id='choose_product_option'>";
		foreach ($productHasOptions as $productHasOption)
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $productHasOption->product_option_id);
			$option = Option::model()->find($criteria);
			if ($option)
			{
				$content .= "<option value='" . $option->id . "'>£" . $productHasOption->price . "&nbsp" . $option->name . "</option>";
			}
		}
		$content .= "</select>";

		$content .= "<br/><br/>";
		$content .= "<a href='#' onClick=buy('" . $product->id . "','" . $product->name . "','" . $option->id . "','" . $option->name . "','" . $productHasOption->price . "')	>" . "<img src=/product/img/add_to_cart.png></a>";
		$apiHtml = $content;
		$apiJs = "";

		$apiJs = <<<END_OF_API_JS_product_page_options_dropdown

			function buy(productId, productName, optionId, optionName, optionPrice)
			{
				// Get the selected price option
				var e = document.getElementById("choose_product_option");
				var optVal  = e.options[e.selectedIndex].value;
				var optText = e.options[e.selectedIndex].text;

				//alert(productId + ':' + productName + ':' + optionId + ':' + optionName + ':' + optionPrice + " was added to your cart");
				alert(productName+ ', ' + optText + " was added to your cart");
				cookieName = 'wfcart_' + productId + '_' + optVal;
				setCookie(cookieName, 1, 1);	// name, value, days
			}

			function setCookie(c_name,value,exdays)
			{
				cookieValue = parseInt(value);
				oldCookieValue = readCookie(c_name);
				if (oldCookieValue)
					cookieValue = parseInt(oldCookieValue) + parseInt(value);
				var exdate=new Date();
				exdate.setDate(exdate.getDate() + exdays);
				var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
				document.cookie=c_name + "=" + cookieValue;
			}

			function readCookie(name)
			{
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
				return null;
			}

END_OF_API_JS_product_page_options_dropdown;


		$clipBoard = "";

/*
<select name="mydropdown">
<option value="Milk">Fresh Milk</option>
<option value="Cheese">Old Cheese</option>
<option value="Bread">Hot Bread</option>
</select>
*/

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}


}

?>
