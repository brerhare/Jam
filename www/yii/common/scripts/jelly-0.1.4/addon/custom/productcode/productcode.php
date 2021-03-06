<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Products custom code
 *
 * Notes
 * -----
 * None
 */

class productcode
{
	// Globals
	private $uid = "";
	private $sid = "";
	private $cartId = "";

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
	  //var_dump( $options );

		$this->uid = Yii::app()->sess->get('uid');
		$this->sid = Yii::app()->sess->get('sid');

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "set_sid":
					$this->sid = $val;
					break;
				case "product_page_options_dropdown":
					$errStr = $this->setCartId();
					if ($errStr != "")
						return array($errStr, "");
					return $this->product_page_options_dropdown($val);
					break;
				case "grid_display_get_default_option":
					return $this->grid_display_get_default_option($val);
					break;
				case "checkout":
					$errStr = $this->setCartId();
					if ($errStr != "")
						return array($errStr, "");
					return $this->checkout($val);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/*********************************************************************************************************/
	// Invoked by index.jel to get the default product price option
	private function grid_display_get_default_option($val)
	{
		$defaultOption = " Not set";

		// Find the default product option
		$criteria = new CDbCriteria;
		$criteria->addCondition("product_product_id = " . $val);
    	$criteria->addCondition("is_default = " . 1);
		$productHasOption = ProductHasOption::model()->find($criteria);
		if ($productHasOption)
		{
			if ($productHasOption->is_poa == 1)
				$defaultOption = ' POA';
			else
				$defaultOption = $productHasOption->price;
		}
		else
		{
			// Find the cheapest product option
			$criteria = new CDbCriteria;
			$criteria->addCondition("product_product_id = " . $val);
    		//$criteria->order("price");
			$productHasOptions = ProductHasOption::model()->findAll($criteria);
			foreach ($productHasOptions as $productHasOption)
			{
				$defaultOption = $productHasOption->price;
				if ($productHasOption->is_poa == 1)
					$defaultOption = ' POA';
				break;
			}
		}
		$apiHtml = "";
		$apiJs = "";
		$clipBoard = $defaultOption;

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	/*********************************************************************************************************/
	// Invoked by product.jel to show the product price options
	private function product_page_options_dropdown($val)
	{
		$productId = $val;
		$content = "";

		// Confirm added to cart
		$cartConfirm = "";
		if ((isset($_GET['cartproduct'])) && (isset($_GET['cartoption'])) && (isset($_GET['cartqty'])) && (isset($_GET['cartref'])))
		{
			$cartContent = Yii::app()->sess->get($this->cartId);

			if (!(strstr($cartContent, '_' . $_GET['cartref'])))
			{
				// Add this item to the cart
				// Pick up the product record
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $_GET['cartproduct']);
				$product = Product::model()->find($criteria);
				if ($product)
				{
					$criteria = new CDbCriteria;
					$criteria->addCondition("id = " . $_GET['cartoption']);
					$option = Option::model()->find($criteria);
					if ($option)
					{
						if ($cartContent != '')
							$cartContent .= '|';
						$cartContent .= $product->id . '_' . $option->id . '_' . $_GET['cartqty'] . '_' . $_GET['cartref'];
						$cartConfirm = "<div style='color:gray'>" . $_GET['cartqty'] . " " . $product->name . " " . $option->name . " added to your cart</div>";
					}
				}
				Yii::app()->sess->set($this->cartId, $cartContent);
			}
		}

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
				$selected = "";
				if ($productHasOption->is_default)
					$selected = " selected ";
				$showPrice = "£" . $productHasOption->price;
				if ($productHasOption->is_poa)
					$showPrice = "POA";
				$content .= "<option " . $selected . " value='" . $option->id . "'>" . $showPrice . "&nbsp" . $option->name . "</option>";
			}
		}
		$content .= "</select>";
		$content .= "<br/><br/>";
		$content .= "<a href='#' onClick=\"buy('" . $product->id . "','" . $product->name . "','" . "')\"	>" . "<img src=/product/img/add_to_cart.png></a>";
		$content .= $cartConfirm;
		$apiHtml = $content;
		$apiJs = "";

		$apiJs = <<<END_OF_API_JS_product_page_options_dropdown

			function buy(productId, productName)
			{
				// Get the selected price option
				var e = document.getElementById("choose_product_option");
				var optVal  = e.options[e.selectedIndex].value;
				var optText = e.options[e.selectedIndex].text;

				selWithHash = document.URL;
				selWithDups = selWithHash.replace('#', '');
				selArr = selWithDups.split("&cartproduct=");
				sel = selArr[0];

				sel += '&cartproduct=' + productId + '&cartoption=' + optVal + '&cartqty=1' + '&cartref=' + Math.floor((Math.random()*128000)+1);
//alert(sel);
				window.location.href = sel;
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

		$totalGoods = 0.00;

		$content = "";

		$content .= "<script>var cartId='" . $this->cartId . "';</script>";


// For debugging - empty the cart
if ((isset($_GET['reset'])) && ($_GET['reset'] == '1'))			Yii::app()->sess->set('cart', '');


		// Pick up the Cart info
			$cartContent = Yii::app()->sess->get($this->cartId);
//echo 'cart=[' . $cartContent . ']<br>';
		if ($cartContent == '')
			$cartArr = array();
		else
			$cartArr = explode('|', $cartContent);

		if ( (isset($_GET['cartref'])) && (!(strstr($cartContent, '_' . $_GET['cartref']))) )
		{
			// Check for deletion (qty=0)
			if ((isset($_GET['cartqty'])) && ($_GET['cartqty'] == '0'))
			{
				$newArr = array();
				for ($i = 0; $i < count($cartArr); $i++)
				{
					$itemArr = explode('_', $cartArr[$i]);
					if (($itemArr[0] == $_GET['cartproduct']) && ($itemArr[1] == $_GET['cartoption']))
						continue;	// Skip this one (exclude)
					array_push($newArr, $cartArr[$i]);
				}
				$cartArr = $newArr;
//echo 'deleted='.$cartArr[$i].". There are now " . count($cartArr) . " items in cart<br>";
				// now save the modified cart array back to the sess variable
				$cartContent = "";
				for ($i = 0; $i < count($cartArr); $i++)
				{
					if ($cartContent != "")
							$cartContent .= '|';
					$cartContent .= $cartArr[$i];
				}
					Yii::app()->sess->set($this->cartId, $cartContent);
			}
		}

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

		$content .= "<div>";
		$content .= "<style> table.itemgrid {  border-collapse: collapse;} .itemgrid tr {   border: solid;  border-width: 1px 0; border-color:#bdbdbd}</style>";



// @@TODO: Need to do a proper css file for this for theming....

$content .= "<style> * { color: grey;} </style>";



		//$content .= "<style>tr:first-child {  border-top: none;}tr:last-child {  border-bottom: none;} </style>";
		$content .= "<center><h3 style='padding-bottom:10px;xcolor:grey;'>Shopping cart</h3><center>";	
		$content .= "<table class='itemgrid' style='width:80%; float:left'>";
		$content .= "<thead><tr>";
		$content .= "<th width=10%></th>";	// Image
		$content .= "<th align='left' width=35%>Description</th>";	// Description
		$content .= "<th align='left' width=25%>Option/Size</th>";	// Option/Size
		$content .= "<th align='right' width=7%>Each</th>";	// Price
		$content .= "<th align='right' width=5%>Qty</th>";	// Qty
		$content .= "<th align='right' width=10%>Total</th>";	// Total
		$content .= "<th align='right' width=8%></th>";	// Total
		$content .= "</tr></thead>";

		// Generate the product lines
		foreach ($productOptionArr as $key => $value)
		{
			$cArr = explode('_', $key);
			$cProduct = $cArr[0];
			$cOption = $cArr[1];
			$cQty = $productOptionArr[$key];
//die( 'p='.$cProduct . ' o='.$cOption . ' q='.$cQty . '<br>' );	
			// Pick up the product record
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $cProduct);
			if (!($cProduct))
				continue;
			$product = Product::model()->find($criteria);	
			if ($product)
			{
				$content .= "<tbody>";
				$content .= "<tr>";
				// Image
				$content .= "<td>";
				$content .= '<br>&nbsp';
				$criteria = new CDbCriteria;
				$criteria->addCondition("product_product_id = " . $cProduct);
				$image = Image::model()->find($criteria);	
				if ($image)
					$content .= "<img border=0 src='" . $_imageDir . $image->filename . "' style='max-height:40px; max-width:50px'>";
				$content .= '<br>&nbsp';
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
				// Each
				if ($productHasOption->is_poa)
					$content .= "<td align='right'>" . "POA" . "</td>";
				else
					$content .= "<td align='right'>" . $productHasOption->price . "</td>";
				// Qty
				$content .= "<td align='right'>" . $cQty . "</td>";
				// Total
				if ($productHasOption->is_poa)
					$content .= "<td align='right'>" . "POA" . "</td>";
				else
					$content .= "<td align='right'>" . number_format(($cQty * $productHasOption->price), 2, '.', '') . "</td>";
				if (!$productHasOption->is_poa)
					$totalGoods += ($cQty * $productHasOption->price);
				// Delete
				$content .= "<td align='right'>";
				$content .= "<a href='#' onClick=\"deleteItem('" . $product->id . "','" . $option->id . "','" . "')\"	>" . "<img src=/product/img/delete-cross.png height=25px width=25px></a>";
				$content .= "</td>";
				$content .= "</tr>";
				$content .= "</tbody>";
			}
		}
		$content .= "</table>";

		// Now the middle bit
		$content .= "<table style='width:80%; float:left'>";
		$content .= "<thead><tr>";
		$content .= "<th align='left' width=10%></th>";
		$content .= "<th align='left' width=35%></th>";	// Buttons
		$content .= "<th align='right' width=25%></th>";
		$content .= "<th align='right' width=22%></th>";	// Total
		$content .= "<th align='right' width=8%></th>";	// Total
		$content .= "</tr></thead>";

		$content .= "<tbody>";
		$content .= "<tr><td>&nbsp</td></tr>";
		$content .= "<tr>";
		$content .= "<td></td>";
		$content .= "<td></td>";
		$content .= "<td align='left'><b>Delivery method</b></td>";
		$content .= "<td align='right'>";

				$content .= "<script> var shipId = 0; var totalShipping = 0; var totalGoods = " . $totalGoods . ";</script>";
				$content .= "<select id='choose_shipping_option' onChange=updateTotal()>";
				$totalShipping = 0.00;
				$criteria = new CDbCriteria;
				$criteria->addCondition("uid = " . $this->uid);
				$criteria->order = 'price ASC';
				$shippings = ShippingOption::model()->findall($criteria);	
				if ($shippings)
				{
					$done = 0;
					foreach ($shippings as $shipping)
					{
						if ($done++ == 0)
						{
							$totalShipping = $shipping->price;
							$content .= "<script> shipId = " . $shipping->id . "; totalShipping = " . $shipping->price . "; </script>";
						}
						$content .= "<option value='" . $shipping->id . "'>" . $shipping->description . "&nbsp&nbsp&nbsp&nbsp" . '£ ' . $shipping->price . "</option>";
					}
				}
				$content .= "</select>";

				$content .= "</td>";
				$content .= "<td><br><br></td>";
				$content .= "</tr>";

				$content .= "<tr>";
				$content .= "<td></td>";
				$content .= "<td></td>";
				$content .= "<td align='left'><b>Total to pay</b></td>";
				$content .= "<td align='right' id='showTotal' style='font-weight:bold'>£ " . number_format(($totalGoods + $totalShipping), 2, '.','') . "</td>";
				$content .= "<td></td>";
				$content .= "</tr>";
				$content .= "</tbody>";

				$content .= "</table>";

				$content .= "</div>";

				$content .= "<div style='clear:both'></div>";

				$content .= "<br><br>";

				$content .= "<style>input {margin-bottom:3px; height:16px;}</style>";

				// Customer contact details
				$content .= "<table style='width:80%; float:left'>";
				$content .= "<thead><tr>";
				$content .= "<th align='left' width=45%></th>";
				$content .= "<th align='left' width=5%></th>";
				$content .= "<th align='left' width=50%></th>";
				$content .= "</tr></thead>";
				$content .= "<tbody><tr><td valign='top'>";
				$content .= "<span>Name </span><input id='shipping_name' name='shipping_name' type='text' value='' size='30'/> <br /><br/>";
				$content .= "Shipping address<br />";
				$content .= "<input id='address1' name='address1' type='text' value='' size='40'/> <br />";
				$content .= "<input id='address2' name='address2' type='text' value='' size='40'/> <br />";		
				$content .= "<input id='address3' name='address3' type='text' value='' size='40'/> <br />";
				$content .= "<input id='address4' name='address4' type='text' value='' size='40'/> <br />";
				$content .= "<span>Post code </span><input id='post_code' name='post_code' type='text' value='' size='10'/> <br /><br/>";
				$content .= "Enter your email address (twice please)<br />";
				$content .= "<input id='email1' name='email1' type='text' value='' size='30'/> <br />";
				$content .= "<input id='email2' name='email2' type='text' value='' size='30'/> <br /><br/>";
				if ($this->uid == 76)	// Wee target
					$content .= " Phone number<br />";
				else
					$content .= " Phone number (recommended)<br />";
				$content .= "<input id='telephone' name='telephone' type='text' value='' size='20'/> <br />";
				$content .= "</td><td>&nbsp</td><td valign='top'>";
				$content .= "Notes<br>";
				$content .= "<textarea id='message' name='message' rows='7' cols='38'> </textarea> <br><br><br/>";
				$content .= "<span>Got a promotion code? </span><input id='promo_code' name='promo_code' type='text' value='' size='15'/> <br /><br/>";

				// Paymentsense is ONLY for Jacquies
				if ($this->sid == 'hrhgr5ko21gn08a3e22jeaers5')
					$content .= "<a href='#' onClick=\"proceed(0)\"	>" . "<img src=/product/img/paymentsense-checkout.png></a>";

				if ((Yii::app()->sess->exists('checkoutPaypalEmail')) && (trim(Yii::app()->sess->get('checkoutPaypalEmail')) != ""))
					$content .= "<br><br><a href='#' onClick=\"proceed(1)\"	>" . "<img src=/product/img/paypal-checkout.png></a>";
				$content .= "</td></tr></tbody>";
				$content .= "</table>";

				$content .= "<div style='clear:both'></div>";

				$apiHtml = $content;
				$apiJs = "";

				$apiJs = <<<END_OF_API_JS_checkout

					// var totalGoods
					// var totalShipping

					function updateTotal()
					{
						var e = document.getElementById("choose_shipping_option");
						var optVal  = e.options[e.selectedIndex].value;
						var optText = e.options[e.selectedIndex].text;
						var val = optText.split('£');
						shipId = optVal;
						totalShipping = parseFloat(totalGoods) + parseFloat(val[1]);
						document.getElementById("showTotal").innerHTML = '£ ' + totalShipping.toFixed(2);
					}

					function deleteItem(productId, optionId)
					{
						selWithHash = document.URL;
						selWithDups = selWithHash.replace('#', '');
						selArr = selWithDups.split("&cartproduct=");
						sel = selArr[0];
						sel += '&cartproduct=' + productId + '&cartoption=' + optionId + '&cartqty=0' + '&cartref=' + Math.floor((Math.random()*128000)+1);
						window.location.href = sel;
					}

					function proceed(ptype) /* 0=payment gateway, 1=paypal */
					{
						if (document.getElementById("shipping_name").value == "")
						{
							alert('Name cant be empty');
							return(false);
						}
						if (document.getElementById("address1").value == "")
						{
							alert('Address line 1 and 2 cant be empty');
							return(false);
						}
						if (document.getElementById("address2").value == "")
						{
							alert('Address line 1 and 2 cant be empty');
							return(false);
						}
						if (document.getElementById("post_code").value == "")
						{
							alert('Post code cant be empty');
							return(false);
						}
						if (document.getElementById("email1").value == "")
						{
							alert('Email address cant be empty');
							return(false);
						}
						if (document.getElementById("email1").value != document.getElementById("email2").value)
						{
							alert('Email addresses dont match');
							return(false);
						}
						if (document.getElementById("telephone").value == "")
						{
							if ($this->uid == 76)	// Wee target
							{
								alert('Phone number cant be empty');
								return(false);
							}
						}
						var e = document.getElementById("choose_shipping_option");
						shipId = e.options[e.selectedIndex].value;
						prm = document.getElementById("promo_code").value;
						sn = document.getElementById("shipping_name").value;
						a1 = document.getElementById("address1").value;
						a2 = document.getElementById("address2").value;
						a3 = document.getElementById("address3").value;
						a4 = document.getElementById("address4").value;
						pc = document.getElementById("post_code").value;
						n = encodeURIComponent(document.getElementById("message").value);
						e = document.getElementById("email1").value;
						t = document.getElementById("telephone").value;
						window.location.href = '/product/index.php/site/pay?cartid='+cartId+'&shipid='+shipId+'&sn='+sn+'&a1='+a1+'&a2='+a2+'&a3='+a3+'&a4='+a4+'&e='+e+'&pc='+pc+'&n='+n+'&t='+t+'&prm='+prm+'&ptype='+ptype;
			}
END_OF_API_JS_checkout;

		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

/**********************************************************************************************/
/* Common functions */
/* ---------------- */

	// Set the cart id and make sure it is valid
	private function setCartId()
	{
		if (trim($this->sid) == "")
			return "Missing sid parameter - cant continue";
		$this->cartId = "wireflycart-" . str_replace('"', '', $_GET['sid']);
		return "";
	}

}

?>
