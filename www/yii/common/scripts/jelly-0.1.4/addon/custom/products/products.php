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

		// Confirm added to cart
		$cartConfirm = "";
		if ((isset($_GET['cartproduct'])) && (isset($_GET['cartoption'])) && (isset($_GET['cartqty'])))
		{
			$cartContent = Yii::app()->session['cart'];
//echo('original cart=['.$cartContent.']<br>');

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
					$cartContent .= $product->id . '_' . $option->id . '_' . $_GET['cartqty'];
					$cartConfirm = "<div style='color:gray'>" . $_GET['cartqty'] . " " . $product->name . " " . $option->name . " added to your cart</div>";
				}
			}	
//echo('new cart=['.$cartContent.']<br>');
			Yii::app()->session['cart'] = $cartContent;
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
				$content .= "<option value='" . $option->id . "'>£" . $productHasOption->price . "&nbsp" . $option->name . "</option>";
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
				sel = selWithHash.replace('#', '');
				sel += '&cartproduct=' + productId + '&cartoption=' + optVal + '&cartqty=1';
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
		$_imgDir = '/product/img/';

		$content = "";

		// Pick up the Cart cookie
		$cartContent = Yii::app()->session['cart'];
//echo 'cart=' . $cartContent . '<br>';
		if (!$cartContent)
			die('nothing in cart');
		$cartArr = explode('|', $cartContent);
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

		$content .= "<style>table {  border-collapse: collapse;}tr {   border: solid;  border-width: 1px 0;}</style>";
		//$content .= "<style>tr:first-child {  border-top: none;}tr:last-child {  border-bottom: none;} </style>";

		$content .= '<center><h3>Shopping cart</h3><center>';	
		$content .= "<table style='width:100%'>";
		$content .= "<thead>";
		$content .= "<tr>";
		$content .= "<th width=10%></th>";	// Image
		$content .= "<th align='left' width=35%>Description</th>";	// Description
		$content .= "<th align='left' width=20%>Option/Size</th>";	// Option/Size
		$content .= "<th align='left' width=10%>Each</th>";	// Price
		$content .= "<th align='left' width=5%>Qty</th>";	// Qty
		$content .= "<th align='left' width=10%>Total</th>";	// Total
		$content .= "<th align='left' width=5%></th>";	// Total
		$content .= "</tr>";

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
				$content .= "<tr><tbody>";
				// Image
				$content .= "<td>";
				$content .= '<br>&nbsp';
				$criteria = new CDbCriteria;
				$criteria->addCondition("product_product_id = " . $cProduct);
				$image = Image::model()->find($criteria);	
				if ($image)
					$content .= "<img border=0 src='" . $_imageDir . $image->filename . "' style='height:40px; width:50px'>";
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
				$content .= "<td>" . $productHasOption->price . "</td>";
				// Qty
				$content .= "<td>" . $cQty . "</td>";
				// Total
				$content .= "<td>" . ($cQty * $productHasOption->price). "</td>";

				$content .= "<td>";
				$content .= "<img border=0 src='" . $_imgDir . 'remove_from_cart.jpg' . "' style='height:40px; width:40px'>";

				//$content .= "<td>" . $product->name . "</td>";

				$content .= "</tbody></tr>";
			}

		}
		$content .= "</table>";
		$content .= "</div>";

		$apiHtml = $content;
		$apiJs = "";

		$apiJs = <<<END_OF_API_JS_checkout

END_OF_API_JS_checkout;


		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}


}

?>
