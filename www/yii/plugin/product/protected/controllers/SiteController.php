<?php

class SiteController extends Controller
{
	// @@EG p3p example code here. Needed for IE
	private $p3p = 'P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"';

	public $test = 0;

	/**
	 * Get the jelly script root (as defined in /protected/config/main.php)
	 */
	private function getJellyRoot()
	{
		return Yii::app()->params['jellyRoot'];
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
header($this->p3p);

$clk='off';
if (isset($_GET['click'])) $clk='true';
Yii::log(".................... in at top.................. click=".$clk , CLogger::LEVEL_WARNING, 'system.test.kim');

Yii::app()->sess->set("key", "value");

		// If unset, initialise the product page cookie (only way to tell if we're back-paging from it or going to it)
		if (!(Yii::app()->sess->exists('productdetail')))
			Yii::app()->sess->set('productdetail', "0");

		// Get hosting site's params
		$util = new Util;
		if (isset($_GET['ge']))
			Yii::app()->sess->set('checkoutEmail', $_GET['ge']);
		if (isset($_GET['gn']))
			Yii::app()->sess->set('checkoutName', $_GET['gn']);
		if (isset($_GET['gu']))
			Yii::app()->sess->set('checkoutGatewayUser', urldecode($util->decrypt($_GET['gu'])));
		if (isset($_GET['gp']))
			Yii::app()->sess->set('checkoutGatewayPassword', urldecode($util->decrypt($_GET['gp'])));
		if (isset($_GET['pp']))
			Yii::app()->sess->set('checkoutPaypalEmail', urldecode($util->decrypt($_GET['pp'])));

		// Get the hosting site (referer)
		if (isset($_SERVER['HTTP_REFERER']))
		{
			if (!strstr($_SERVER['HTTP_REFERER'], "plugin.wireflydesign.com"))
			{
				$urlParts = explode("://", $_SERVER['HTTP_REFERER']);	// 0 = http or https
				$urlSubParts = explode("/", $urlParts[1]);				// 0 = www.example.com
				Yii::app()->sess->set('http_referer',  $urlParts[0] . "://" . $urlSubParts[0]);
Yii::log(".................... setting http_referer to [" . Yii::app()->sess->get('http_referer') . "] .................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
			}
		}
Yii::log(".................... http_referer is [" . Yii::app()->sess->get('http_referer') . "] .................. " , CLogger::LEVEL_WARNING, 'system.test.kim');

		// Get iframe params
		if (isset($_GET['sid']))
			Yii::app()->sess->set('sid', $_GET['sid']);
		if (isset($_GET['page']))
			Yii::app()->sess->set('page', $_GET['page']);
		if (isset($_GET['product']))
			Yii::app()->sess->set('product', $_GET['product']);
		if (isset($_GET['department']))
			Yii::app()->sess->set('department', $_GET['department']);

		// Are we redirecting the parent to the product page?
		if ((isset($_GET['show'])) && ($_GET['show'] == 'product'))
		{
			if (Yii::app()->sess->get('productdetail') == "0")
			{
				$target = Yii::app()->sess->get('http_referer') . "/?page=" . Yii::app()->sess->get('page') . "&product=" . Yii::app()->sess->get('product');
Yii::log(".................... redirecting parent to product page [$target] .................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
				echo
					"<html><script>
					// @@NB START POSTMESSAGE
	   					parent.postMessage('redirect^" . $target . "', '*');
					// @@NB END POSTMESSAGE
					</script></html>";
				return;
			}
		}

		// Or is the parent sending us to the product page?
		else if ((isset($_GET['page'])) && (isset($_GET['product']))) 
		{
			if ( (Yii::app()->sess->get('productdetail') == "0") || (isset($_GET['cartproduct'])) )
			{
Yii::log(".................... parent is sending us to product page.................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
				$parseConfig = new ParseConfig();
				$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . "product" . '.jel');
				if (!($jellyArray))
					throw new Exception('Aborting');
				$jelly = new Jelly;
				$jelly->processData($jellyArray,$this->getJellyRoot());
				$jelly->outputData();
				return;
			}
		}

		// We've just back-paged from the product page
		if (Yii::app()->sess->get('productdetail') == "1")
		{
			if (!(isset($_GET['checkoutButton'])))	// This is only set in the initial checkout-iframe call, ie when the checkout button is clicked
			{
				Yii::app()->sess->set('productdetail',"0");
				$target = Yii::app()->sess->get('http_referer') . "/?page=" . Yii::app()->sess->get('page') . "&department=" . Yii::app()->sess->get('department');
Yii::log(".................... we've just back-paged from product page. redirecting parent to [$target].................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
               	echo
                   	"<html><script>
                   	// @@NB START POSTMESSAGE
                        	parent.postMessage('redirect^" . $target . "', '*');
                    	// @@NB END POSTMESSAGE
                    	</script></html>";
				return;
			}
		}


$pageParam = '';
if (isset($_GET['page']))
 $pageParam = $_GET['page'];
Yii::log(".................... still here.................. page is " . $pageParam , CLogger::LEVEL_WARNING, 'system.test.kim');
	
		// Are we going to the 'shop' page?
		if ((isset($_GET['shop'])) && (!(isset($_GET['product']))))
		{
//print_r($_GET);
//die('xx');
			$this->actionShop($pageParam);
			return;
		}

		// Otherwise by default the initial call goes to here
		$layout = "index";
		if (isset($_GET['layout']))
			$layout = $_GET['layout'];
		$parseConfig = new ParseConfig();
		$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . $layout . ".jel");
		if (!($jellyArray))
			throw new Exception('Aborting');
		$jelly = new Jelly;
		$jelly->processData($jellyArray,$this->getJellyRoot());
		$jelly->outputData();
	}

	/**
	 *
	 */
	public function actionPlay($page)
	{
header($this->p3p);
		$parseConfig = new ParseConfig();
		$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . $page . '.jel');
		if (!($jellyArray))
			throw new Exception('Aborting');

		$jelly = new Jelly;
		$jelly->processData($jellyArray,$this->getJellyRoot());
		$jelly->outputData();
	}

	public function actionShop($page)
	{
header($this->p3p);
		$this->renderPartial('shop',array(
		));
	}

	// Invoke the payment module: Paymentsense or Paypal
	public function actionPay()
	{
		Yii::log("Checkout - a payment button has been clicked" , CLogger::LEVEL_WARNING, 'system.test.kim');
		if (isset($_GET['ptype']))
		{
			Yii::log("Checkout - payment type requested is " . $_GET['ptype'] , CLogger::LEVEL_WARNING, 'system.test.kim');
		}

		// Check vendor cart details exist
		if ((trim(Yii::app()->sess->get('checkoutEmail')) == "")
		|| (trim(Yii::app()->sess->get('checkoutName')) == ""))
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because vendor cart details arent present" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because vendor cart details dont exist');
		}

		// Check vendor has at least one payment type
		if ((trim(Yii::app()->sess->get('checkoutGatewayUser')) == "")
		&& (trim(Yii::app()->sess->get('checkoutPaypalUser')) == ""))
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because no payment processor details are present" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because No payment processor details exist');
		}

		if (isset($_GET['shipid']))
			$shipId = $_GET['shipid'];
		else
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because 'shipid' wasnt passed" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because shipid wasnt passed by caller');
		}
		if (isset($_GET['cartid']))
		{
			$cartId = $_GET['cartid'];
			Yii::app()->sess->set('cartid', $cartId);
		}
		else
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because 'cartid' wasnt passed" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because cartid wasnt passed by caller');
		}
		Yii::log("Checkout - PAYMENT PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		if (trim(Yii::app()->sess->get('sid')) == "")
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because SID is unset!" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because SID is not set or expired. (Has this session been idle a long time?)');
		}
		if (trim(Yii::app()->sess->get('uid')) == "")
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because UID is unset!" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because UID is not set or expired. (Has this session been idle a long time?)');
		}

		$cartContent = Yii::app()->sess->get($cartId);
		if ((!($cartContent)) || ($cartContent == ''))
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because 'cartid' " . $cartId . " although seemingly valid, does not contain a usable session var" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because cart details werent accessible. (Has this session been idle a long time?)');
		}

		// Get the passed fields (This is a @@TODO as its crap comms between page and here...)
		$sn = "";
		$a1 = "";
		$a2 = "";
		$a3 = "";
		$a4 = "";
		$pc = "";
		$e = "";
		$t = "";
		$n = "";
		$prm = "";
		if (isset($_GET['sn'])) $sn = $_GET['sn'];
		if (isset($_GET['a1'])) $a1 = $_GET['a1'];
		if (isset($_GET['a2'])) $a2 = $_GET['a2'];
		if (isset($_GET['a3'])) $a3 = $_GET['a3'];
		if (isset($_GET['a4'])) $a4 = $_GET['a4'];
		if (isset($_GET['pc'])) $pc = $_GET['pc'];
		if (isset($_GET['e'])) $e = $_GET['e'];
		if (isset($_GET['t'])) $t = $_GET['t'];
		if (isset($_GET['n'])) $n = nl2br($_GET['n']);
		if (isset($_GET['prm'])) $prm = $_GET['prm'];

		$ip = "UNKNOWN";
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");

		Order::model()->deleteAllByAttributes(array('ip' => $ip));	

		$totalQty = 0;
		$totalGoods = 0.00;
		$cartArr = explode('|', $cartContent);
		if (count($cartArr) < 1)
		{
			Yii::log("Checkout - Nothing in cart array" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because cart has nothing in it');
		}
		for ($i = 0; $i < count($cartArr); $i++)
		{
			Yii::log("Checkout - about to add a line to orders" , CLogger::LEVEL_WARNING, 'system.test.kim');

			$itemArr = explode('_', $cartArr[$i]);
			if (count($itemArr) != 4)
			{
				Yii::log("Checkout - ERROR! itemArr count is not 4" , CLogger::LEVEL_WARNING, 'system.test.kim');
				continue;
			}
			// Get the values we will be using
			$productId = $itemArr[0];
			$optionId = $itemArr[1];
			$qty = $itemArr[2];
			// Pick up the product option for the price
			$criteria = new CDbCriteria;
			$criteria->addCondition("product_product_id = " . $productId);
			$criteria->addCondition("product_option_id = " . $optionId);
			$productHasOption = ProductHasOption::model()->find($criteria);
			if ($productHasOption)
			{
				$price = $productHasOption->price;
			}
			else
			{
				$price = 0.00;
				Yii::log("Checkout - ERROR! product option " . $optionId . " does not exist" , CLogger::LEVEL_WARNING, 'system.test.kim');
				continue;
			}
			// Create a (potential) order
			$order=new Order;
			$order->uid = Yii::app()->sess->get('uid');
			$order->sid = Yii::app()->sess->get('sid');
			$order->ip = $ip;
			$order->order_number = trim(Yii::app()->sess->get('uid')) . "-" . time();	// for paypal
			$order->vendor_gateway_id = "@@TODO gateway id";
			$order->vendor_gateway_password = "@@TODO gateway password";
			$order->http_product_id = $productId;
			$order->http_option_id = $optionId;
			$order->http_qty = $qty;
			$order->http_price = $price;
			$order->http_line_total = number_format(($qty * $price), 2, '.', '');
			$order->http_shipping_id = $shipId;
			$order->email_address = $e;
			$order->name = $sn;
			$order->delivery_address1 = $a1;
			$order->delivery_address2 = $a2;
			$order->delivery_address3 = $a3;
			$order->delivery_address4 = $a4;
			$order->delivery_post_code = $pc;
			$order->telephone = $t;
			$order->notes = $n;
			$order->promo_code = $prm;
			$totalGoods += ($qty * $price);
			$totalQty += $qty;
			$order->return_url = Yii::app()->baseUrl;
			$order->gu = Yii::app()->sess->get('checkoutGatewayUser');
			$order->gp = Yii::app()->sess->get('checkoutGatewayPassword');
			if(!$order->save())
			{
				Yii::log("Checkout - Write error on order reord!" , CLogger::LEVEL_WARNING, 'system.test.kim');
				throw new CHttpException(400,'Error creating order');
			}
			Yii::log("Checkout - added a line to orders" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}
		$subtotalGoods = $totalGoods;
		// Add shipping to total
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $shipId);
		$shipping = ShippingOption::model()->find($criteria);
		if ($shipping)
			$totalGoods += $shipping->price;

		// Update all the order records we just created with the total
		$criteria = new CDbCriteria;
		$criteria->addCondition("ip = '" . $ip . "'");
		$orders = Order::model()->findAll($criteria);
		if ($order)
		{
			foreach ($orders as $order)
			{
				$order->payment_type = $_GET['ptype'];	// Paymentsense=0, Paypal=1, ...
				$order->http_total = number_format($totalGoods, 2, '.', '');
				$order->http_total_qty = number_format($totalQty);
				$order->save();
			}
		}
//$this->actionPaid();
//die('done');

		// Going to foreign payment sites will set a http-referer different to our own host site, so store this for the return redirect
		Yii::app()->sess->set('http_payment_return', Yii::app()->sess->get('http_referer'));

		Yii::app()->sess->set('ptype', $_GET['ptype']);

		if ($_GET['ptype'] == 0)
		{
			// Go to paymentsense for payment
			Yii::log("Checkout - going to paymentsense" , CLogger::LEVEL_WARNING, 'system.test.kim');
			$this->redirect(Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->sess->get('sid') . "&xid=" . rand(99999,999999) );
		}
		else if  ($_GET['ptype'] == 1)
		{
//$this->actionPaid();
//die('done');

			// Paypal
			Yii::log("Checkout - going to paypal" , CLogger::LEVEL_WARNING, 'system.test.kim');
			//$this->renderPartial('paypal');
			$this->layout = "nosuchlayout";			// Needs this to connect to Paypal
			$this->render('paypal',array(
				'business'=>Yii::app()->sess->get('checkoutPaypalEmail'),
				'description'=>'Purchase from ' . Yii::app()->sess->get('checkoutName'),
            	'subtotal'=>$subtotalGoods,
            	'shipping'=>$shipping->price,
        	));
/**/
			//$this->redirect(Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->sess->get('sid') . "&xid=" . rand(99999,999999) );
		}
	}

	// Return from payment module
	public function actionPaid()
	{
header($this->p3p);
		Yii::log("PAID PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		Yii::log("PAID PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');

		$ip = "UNKNOWN";
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");

		if (trim(Yii::app()->sess->get('uid')) == "")
		{
			Yii::log("Checkout - NOT LOADING PAID PAGE because UID is unset!" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to paid page because UID is not set or expired. Please contact the supplier. (Has this session been idle a long time?)');
		}

		$criteria = new CDbCriteria;
		//$criteria->addCondition("uid = " . Yii::app()->sess->get('uid'));
		$criteria->addCondition("ip = '" . $ip . "'");

		// Retrieve the original order, now populated by paymentsense
		$orders = Order::model()->findAll($criteria);
		$orderCount = 0;
		$message = "<html><style>html, table, div, tr, td, * { font-size: small !important; color: #3B0B0B !important; background-color: #F8ECE0 !important; font-family: Calibri, Verdana, Arial, Serif !important; } table td { border-left:solid 10px transparent; } table td:first-child { border-left:0; }</style>";
		$message .= "<h2>Thank you for shopping with " . Yii::app()->sess->get('checkoutName') . "</h2><br>";
		$message .= "<table style='border:1px solid black'><tr>";
		$message .= "<b><td style='padding:15px' >Description</td><td style='padding:15px' >Option/Size</td><td style='padding:15px' >Each</td><td style='padding:15px' >Quantity</td><td style='padding:15px' >Total</td></b>";
		$message .= "</tr>";
		foreach ($orders as $order)
		{
			$message .= "<tr>";

			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $order->http_product_id);
			$product = Product::model()->find($criteria);	
			$name = "";
			if ($product)
				$name = $product->name;
			$message .= "<td style='padding:15px' align='left'>" . $name . "</td>";

			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $order->http_option_id);
			$option = Option::model()->find($criteria);
			$optionDesc = "";
			if ($option)
				$optionDesc = $option->name;
			$message .= "<td style='padding:15px' align='left'>" . $optionDesc . "</td>";

			$criteria = new CDbCriteria;
			$criteria->addCondition("product_product_id = " . $order->http_product_id);
			$criteria->addCondition("product_option_id = " . $order->http_option_id);
			$productHasOption = ProductHasOption::model()->find($criteria);
			$each = "0.00";
			if ($productHasOption)
			{
				$each = $productHasOption->price;
				if ($productHasOption->is_poa)
					$each = "POA";
			}
			$message .= "<td style='padding:15px' align='right'>" . $each . "</td>";

			$message .= "<td style='padding:15px' align='right'>" . $order->http_qty . "</td>";

			$message .= "<td style='padding:15px' align='right'>" . $order->http_line_total . "</td>";

			$message .= "</tr>";
		}
		$message .= "</table>";
		$message .= "</br>";

		$message .= "<table>";
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $order->http_shipping_id);
		$shipping = ShippingOption::model()->find($criteria);
		$shippingAmount = "0.00";
		if ($shipping)
			$shippingAmount = $shipping->price;
		$message .= "<tr><td><b>Shipping: </b>&pound " . $shippingAmount . "</td></tr>";
		$message .= "<tr><td><b>Total Paid: </b>&pound " . $order->http_total . "</td></tr>";
		$message .= "</table>";
		$message .= "</br>";

		$message .= "<table style='border:none'>";
		if (trim($order->card_number) != "")
		{
			// Card details (if any)
			$message .= "<tr><td><b>Name on card: </b></td><td>" . $order->card_name . "</td></tr>";
			$message .= "<tr><td><b>Card number: </b></td><td>" . '************ ' . substr($order->card_number, 12, 4) . "</td></tr>";
		}
		$message .= "<tr><td><b>Telephone: </b></td><td>" . $order->telephone . "</td></tr>";
		$message .= "<tr><td><b>Email: </b></td><td>" . $order->email_address . "</td></tr>";
		if (trim($order->promo_code) != "")
			$message .= "<tr><td><b>Promotion code: </b></td><td>" . $order->promo_code . "</td></tr>";
		$message .= "</table>";

		$message .= "<br><br><hr><br>";	// Solid line separator

		// Delivery address

		$message .= "<b>Deliver to</b><br>";
		if (trim($order->name) != "")
			$message .= $order->name . "<br>";
		if (trim($order->delivery_address1) != "")
			$message .= $order->delivery_address1 . "<br>";
		if (trim($order->delivery_address2) != "")
			$message .= $order->delivery_address2 . "<br>";
		if (trim($order->delivery_address3) != "")
			$message .= $order->delivery_address3 . "<br>";
		if (trim($order->delivery_address4) != "")
			$message .= $order->delivery_address4 . "<br>";
		if (trim($order->delivery_post_code) != "")
			$message .= $order->delivery_post_code . "<br>";
		$message .= "<br>";

		// Notes

		$message .= "<b>Notes</b><br>";
		$message .= $order->notes . "<br><br>";

		$message .= "</html>";

        // Send email
        $to = $order->email_address;
        if (strlen($to) > 0)
        {
            $from = Yii::app()->sess->get('checkoutEmail');
            $fromName = Yii::app()->sess->get('checkoutName');
            $subject = "Your order from " . Yii::app()->sess->get('checkoutName');
            // phpmailer
            $mail = new PHPMailer();

            	$mail->AddAddress($to);
				$mail->AddBCC($from);

            $mail->SetFrom($from, $fromName);
            $mail->AddReplyTo($from, $fromName);
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            if (!$mail->Send())
            {
                Yii::log("PAID PAGE COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
                echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
            }
            else
                Yii::log("PAID PAGE SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
        }

		// Remove cookie stuff
		Yii::app()->sess->clear('cartid');

		// Delete all the cart info
		Cart::model()->deleteAllByAttributes(array('ip' => $ip));
		Yii::app()->sess->clear('cartid');

		// Update all the order records we just created with the 'x' in front of the ip
		$criteria = new CDbCriteria;
		$criteria->addCondition("ip = '" . $ip . "'");
		$orders = Order::model()->findAll($criteria);
		if ($order)
		{
			foreach ($orders as $order)
			{
				$order->ip = 'x-' . $ip;
				$order->timestamp = date("Y-m-d H:i:s");
				$order->save();
			}
		}

		$thanks = file_get_contents(dirname(Yii::app()->request->scriptFile) . "/protected/controllers/ThankYou.template");
		$thanks = str_replace("<return-url>", Yii::app()->sess->get('http_payment_return'), $thanks);
		if (Yii::app()->sess->get('ptype') == 0)			// Paymentsense
			$thanks = str_replace("<display>", "none", $thanks);
		else if (Yii::app()->sess->get('ptype') == 1)		// Paypal
			$thanks = str_replace("<display>", "inline", $thanks);
		die($thanks);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    private function getCartByIP()
    {
        $ip = "UNKNOWN";
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        $criteria = new CDbCriteria;
        $criteria->addCondition("ip = '" . $ip . "'");
        $cart = Cart::model()->find($criteria);
        if ($cart)
            return $cart->content;
        else
            return "";
    }

}
