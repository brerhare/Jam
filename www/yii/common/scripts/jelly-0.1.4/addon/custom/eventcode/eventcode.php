<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Products custom code
 *
 * Notes
 * -----
 * None
 */

class eventcode
{
	// Globals
	private $uid = "";
	private $sid = "";
	private $jellyRootUrl = "";

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
	  //var_dump( $options );
		$this->jellyRootUrl = $jellyRootUrl;

		$this->uid = Yii::app()->session['uid'];
		$this->sid = Yii::app()->session['sid'];

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "fill_headers":
					return $this->fill_headers($val);
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
	private function fill_headers($val)
	{
		$content = "";

		$content .= "<div id='accordion'>";

		$criteria = new CDbCriteria;
		$criteria->order = 'start ASC';
		$events = Event::model()->findAll($criteria);
		foreach ($events as $event)
		{
			// Pick up the program
			$criteria = new CDbCriteria;
			$criteria->condition = 'id = ' . $event->program_id;
			$program = Program::model()->find($criteria);

			// The header block
			$content .= "<div>";
			$content .= "<table><tr><td width=80% height=90px>";
			$content .= "<b>" . $event->title . "</b><br>";
			$content .= "<i>Start: " . $event->start . "&nbsp&nbsp&nbsp&nbspEnd:" . $event->end . "</i><br><br>";
			$content .= $event->address . " " . $event->post_code . "<br>";
			$content .= "</td><td>";
			if (trim($event->thumb_path) != '')
				$content .= "<img style='margin-top:-10px; margin-right:-5px' title='Event Thumb' src='userdata/event/thumb/" . $event->thumb_path . "' width='120' height='95'>";
			else if ($program)
				$content .= "<img style='margin-top:-10px; margin-right:-5px' title='Program Thumb' src='userdata/program/thumb/" . $program->thumb_path . "' width='120' height='95'>";
			$content .= "</td></tr></table>";
			$content .= "</div>";

			// The content block
			$content .= "<div>";
			$content .= 'Content from server<br><br>';
			$content .= "</div>";
		}
		$content .= "</div>";

		//$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
        //$apiHtml = str_replace("<substitute-data>", $content, $apiHtml);


		$apiHtml = <<<END_OF_API_HTML_fill_headers

			<div id="jelly-fill_headers-container">
				<link rel="stylesheet" href="<substitute-path>/eventcode.css" type="text/css">
				<substitute-data>
			</div>

END_OF_API_HTML_fill_headers;


		$apiJs = <<<END_OF_API_JS_fill_headers

			$(function() {
			    $( "#accordion" ).accordion({
					//header: 'a' ,
					//header: '> div.wrap' ,
					heightStyle: 'content',
			        collapsible: true,
        			active: true,
			        /*activate: function (event, ui) { alert("activate"); }, */
        			beforeActivate: function (event, ui) {
            			if($('.accordion_header').hasClass('ui-state-active')) {
            			    //alert('open');
			            }
            			else {
                			//alert('closed');
			            }
            			//alert("before activate");
			        }
			    });
			});

			$( document ).ready(function() {
			});

END_OF_API_JS_fill_headers;

		$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
        $apiHtml = str_replace("<substitute-data>", $content, $apiHtml);

		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	private function product_stuff($val)
	{
		/*********************
		// Check cookie
		$cookieValue = (string)Yii::app()->request->cookies['wfcart'];
		*********************/

		$productId = $val;
		$content = "";

		// Confirm added to cart
		$cartConfirm = "";
		if ((isset($_GET['cartproduct'])) && (isset($_GET['cartoption'])) && (isset($_GET['cartqty'])) && (isset($_GET['cartref'])))
		{

			$cartContent = Yii::app()->session['cart'];
//echo('original cart=['.$cartContent.']<br>');

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
//echo('new cart=['.$cartContent.']<br>');
			Yii::app()->session['cart'] = $cartContent;
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


// For debugging - empty the cart
if ((isset($_GET['reset'])) && ($_GET['reset'] == '1'))			Yii::app()->session['cart'] = '';


		// Pick up the Cart info
		$cartContent = Yii::app()->session['cart'];
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
				// now save the modified cart array back to the session variable
				$cartContent = "";
				for ($i = 0; $i < count($cartArr); $i++)
				{
					if ($cartContent != "")
							$cartContent .= '|';
					$cartContent .= $cartArr[$i];
				}
				Yii::app()->session['cart'] = $cartContent;
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
		$content .= "<style> table.itemgrid {  border-collapse: collapse;} .itemgrid tr {   border: solid;  border-width: 1px 0;}</style>";
		//$content .= "<style>tr:first-child {  border-top: none;}tr:last-child {  border-bottom: none;} </style>";
		$content .= '<center><h3>Shopping cart</h3><center>';	
		$content .= "<table class='itemgrid' style='width:80%; float:left'>";
		$content .= "<thead><tr>";
		$content .= "<th width=10%></th>";	// Image
		$content .= "<th align='left' width=35%>Description</th>";	// Description
		$content .= "<th align='left' width=25%>Option/Size</th>";	// Option/Size
		$content .= "<th align='right' width=7%>Each</th>";	// Price
		$content .= "<th align='right' width=5%>Qty</th>";	// Qty
		$content .= "<th align='right' width=10%>Total</th>";	// Total
		$content .= "<th align='rght' width=8%></th>";	// Total
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
				$content .= "<td align='right'>" . $productHasOption->price . "</td>";
				// Qty
				$content .= "<td align='right'>" . $cQty . "</td>";
				// Total
				$content .= "<td align='right'>" . number_format(($cQty * $productHasOption->price), 2, '.', '') . "</td>";
				$totalGoods += ($cQty * $productHasOption->price);
				// Delete
				$content .= "<td align='right'>";
				//$content .= "<img border=0 src='" . $_imgDir . 'remove_from_cart.jpg' . "' style='height:40px; width:40px'>";
				$content .= "<a href='#' onClick=\"deleteItem('" . $product->id . "','" . $option->id . "','" . "')\"	>" . "<img src=/product/img/remove_from_cart.jpg height=40px width=40px></a>";
				$content .= "</td>";
				$content .= "</tbody></tr>";
			}
		}
		$content .= "</table>";

		// Now the middle bit
		$content .= "<table style='width:80%; float:left'>";
		$content .= "<thead><tr>";
		$content .= "<th align='left' width=10%></th>";
		$content .= "<th align='left' width=25%></th>";	// Buttons
		$content .= "<th align='left' width=25%></th>";
		$content .= "<th align='left' width=19%></th>";	// Delivery dropdown
		$content .= "<th align='right' width=10%></th>";	// Total
		$content .= "<th align='right' width=7%></th>";
		$content .= "</tr></thead>";

		$content .= "<tr><td>&nbsp</td></tr>";

		$content .= "<tr><tbody>";
		$content .= "<td></td>";
		$content .= "<td></td>";
		$content .= "<td>Choose delivery method</td>";
		$content .= "<td>";

		$content .= "<script> totalShipping = 0; totalGoods = " . $totalGoods . ";</script>";
		$content .= "<select id='choose_shipping_option' onChange=updateTotal()>";
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . $this->uid);
		$criteria->order = 'price ASC';
		$shippings = ShippingOption::model()->findall($criteria);	
		if ($shippings)
		{
			$done = 0;
			$totalShipping = 0.00;
			foreach ($shippings as $shipping)
			{
				if ($done++ == 0)
				{
					$totalShipping = $shipping->price;
					$content .= "<script> totalShipping = " . $shipping->price . "; </script>";
				}
				$content .= "<option value='" . $shipping->id . "'>" . $shipping->description . "&nbsp&nbsp&nbsp&nbsp" . '£ ' . $shipping->price . "</option>";
			}
		}
		$content .= "</select>";

		$content .= "</td>";
		$content .= "<td></td>";
		$content .= "<td></td>";
		$content .= "</tbody></tr>";

		$content .= "<tr><tbody>";
		$content .= "<td><br/><br/><br/></td>";
		$content .= "<td></td>";
		$content .= "<td></td>";
		$content .= "<td align='right'><b>Total to pay</b></td>";
		$content .= "<td align='right' id='showTotal' style='font-weight:bold'>£ " . number_format(($totalGoods + $totalShipping), 2, '.','') . "</td>";
		$content .= "<td></td>";
		$content .= "</tbody></tr>";

		$content .= "</table>";

		$content .= "</div>";
		$content .= "<div style='clear:both'></div>";

		// Customer contact details
		$content .= "<div style=padding:20px; position:relative>";
		$content .= "Please enter your delivery address<br />";
		$content .= "<input id='address1' type='text' value='' size='40'/> <br />";
		$content .= "<input id='address2' type='text' value='' size='40'/> <br />";		
		$content .= "<input id='address3' type='text' value='' size='40'/> <br />";
		$content .= "<input id='address4' type='text' value='' size='40'/> <br />";
		$content .= "<input id='post_code' type='text' value='' size='15'/> <br /><br/>";
		$content .= " An email address is required for your order confirmation<br />";
		$content .= "<input id='email1' type='text' value='' size='30'/> <br />";
		$content .= "<input id='email2' type='text' value='' size='30'/> <br /><br/>";
		$content .= " Phone number (recommended)<br />";
		$content .= "<input id='telephone' type='text' value='' size='20'/> <br />";
		$content .= "<span style='position:absolute; margin-left:400px; margin-top:-270px'>Notes</span>";
		$content .= "<textarea style='position:absolute; margin-left:400px;margin-top:-254px' name='message' rows='7' cols='30'> </textarea> <br />";
		$content .= "<a style='position:absolute; margin-left:400px; margin-top:-57px' href='#' onClick=\"proceed()\"	>" . "<img src=/product/img/proceed_to_checkout.png></a>";

		$content .= "</div>";

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

			function proceed()
			{
				window.location.href = '/product/index.php/site/pay';
			}
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
