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
				case "checkout":
					return $this->checkout($val);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/*********************************************************************************************************/
	// Invoked by product.jel to show the product price options
	private function product_page_options_dropdown($val)
	{
		/*********************
		// Check cookie
		$cookieValue = (string)Yii::app()->request->cookies['wfcart'];
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
		$content .= "<a href='#' onClick=\"buy('" . $product->id . "','" . $product->name . "','" . "')\"	>" . "<img src=/product/img/add_to_cart.png></a>";
		$apiHtml = $content;
		$apiJs = "";

		$apiJs = <<<END_OF_API_JS_product_page_options_dropdown

			function buy(productId, productName)
			{
				// Get the selected price option
				var e = document.getElementById("choose_product_option");
				var optVal  = e.options[e.selectedIndex].value;
				var optText = e.options[e.selectedIndex].text;

				alert(productName+ ', ' + optText + " was added to your cart");

				cookieName = 'wfcart';
				cookieString = '';
				oldCookie = readCookie(cookieName);
				if (oldCookie)
				{
					// Pre-populate cookieString with existing details, and append '|'
					cookieString += oldCookie + '|';
				}
				cookieString += productId + '_' + optVal + '_' + 1;	// qty=1
				setCookie(cookieName, cookieString, 1);	// name, value, days=1
			}

			function setCookie(c_name,value,exdays)
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate() + exdays);
				var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
				document.cookie=c_name + "=" + value;
			}

			function readCookie(name)
			{
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0)
					{
						return c.substring(nameEQ.length,c.length);
					}
				}
				return null;
			}

END_OF_API_JS_product_page_options_dropdown;

		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	/*********************************************************************************************************/
	// Invoked by checkout.jel to handle the checkout, based on the cart cookie contents
	private function checkout($val)
	{
		$_imageDir = '/product/userdata/image/';

		$content = "";

		// Pick up the Cart cookie
		$cookieValue = (string)Yii::app()->request->cookies['wfcart'];
echo 'cookie=' . $cookieValue . '<br>';
		if (!$cookieValue)
			die('no cookie');
		$cartArr = explode('|', $cookieValue);
		if (count($cartArr) == 0)
		{
			$retArr = array();
			$retArr[0] = "You have no items in your cart.";
			$retArr[1] = "";
			return $retArr;
		}
		// Create product_option table for qtys, consolidating any cookie duplicates into single entries
		$productOptionArr = array();	// product '_' option are seen together as the key, qty as the value
		foreach ($cartArr as $cartItem)
		{
			$chkArr = explode('_', $cartItem);
			$chkProductOption = $chkArr[0] . '_' . $chkArr[1];
			$chkQty = $chkArr[2];

			if (array_key_exists($chkProductOption, $productOptionArr))
				$productOptionArr[$chkProductOption] += $chkQty;
			else
				$productOptionArr[$chkProductOption] = $chkQty;
		}

//echo '<br>';
//foreach ($productOptionArr as $key => $value) {
//	echo $key .':'. $value . '<br>';
//}
		$content .= "<div>";
		$content .= "<table>";
		$content .= "<thead>";
		$content .= "<tr>";
		$content .= "<th width=10%></th>";	// Image
		$content .= "<th align='left' width=40%>Description</th>";	// Description
		$content .= "<th align='left' width=20%>Option/Size</th>";	// Option/Size
		$content .= "<th align='left' width=10%>Each</th>";	// Price
		$content .= "<th align='left' width=10%>Qty</th>";	// Qty
		$content .= "<th align='left' width=10%>Total</th>";	// Total
		$content .= "</tr>";

		// Generate the product lines
		foreach ($productOptionArr as $key => $value)
		{
echo('1<br>');
			$cArr = explode('_', $key);
			$cProduct = $cArr[0];
			$cOption = $cArr[1];
			$cQty = $productOptionArr[$key];
//die( 'p='.$cProduct . ' o='.$cOption . ' q='.$cQty . '<br>' );	
echo('2<br>');
			// Pick up the product record
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $cProduct);
			if (!($cProduct))
				continue;
			$product = Product::model()->find($criteria);	
			if ($product)
			{
echo('3<br>');
				$content .= "<tr><tbody>";
				// Image
				$content .= "<td>";
				$criteria = new CDbCriteria;
				$criteria->addCondition("product_product_id = " . $cProduct);
				$image = Image::model()->find($criteria);	
				if ($image)
					$content .= "<img src='" . $_imageDir . $image->filename . "' style='height:40px; width:50px'>";
				$content .= "</td>";
				// Description
				$content .= "<td>" . $product->name . "</td>";
				// Price Option
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $cOption);
				$option = Option::model()->find($criteria);
				$content .= "<td>" . $option->name . "</td>";
				// Product Price Option
				$criteria = new CDbCriteria;
				$criteria->addCondition("product_product_id = " . $cProduct);
				$criteria->addCondition("product_option_id = " . $cOption);
				$productHasOption = ProductHasOption::model()->find($criteria);
				$content .= "<td>" . $productHasOption->price . "</td>";
				// Qty
				$content .= "<td>" . $cQty . "</td>";
				// Total
				$content .= "<td>" . ($cQty * $productHasOption->price). "</td>";
echo('99<br>');


				//$content .= "<td>" . $product->name . "</td>";

				$content .= "</tbody></tr>";
			}

		}
		$content .= "</table>";
		$content .= "</div>";
echo('100<br>');

die($content);




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
		$content .= "<a href='#' onClick=\"buy('" . $product->id . "','" . $product->name . "','" . "')\"	>" . "<img src=/product/img/add_to_cart.png></a>";
		$apiHtml = $content;
		$apiJs = "";

		$apiJs = <<<END_OF_API_JS_product_page_options_dropdown

			function buy(productId, productName)
			{
				// Get the selected price option
				var e = document.getElementById("choose_product_option");
				var optVal  = e.options[e.selectedIndex].value;
				var optText = e.options[e.selectedIndex].text;

				alert(productName+ ', ' + optText + " was added to your cart");

				cookieName = 'wfcart';
				cookieString = '';
				oldCookie = readCookie(cookieName);
				if (oldCookie)
				{
					// Pre-populate cookieString with existing details, and append '|'
					cookieString += oldCookie + '|';
				}
				cookieString += productId + '_' + optVal + '_' + 1;	// qty=1
				setCookie(cookieName, cookieString, 1);	// name, value, days=1
			}

			function setCookie(c_name,value,exdays)
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate() + exdays);
				var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
				document.cookie=c_name + "=" + value;
			}

			function readCookie(name)
			{
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0)
					{
						return c.substring(nameEQ.length,c.length);
					}
				}
				return null;
			}

END_OF_API_JS_product_page_options_dropdown;


		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}


}

?>
