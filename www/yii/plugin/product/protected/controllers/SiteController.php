<?php

class SiteController extends Controller
{
	// @@EG p3p example code here. Needed for IE
	private $p3p = 'P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"';
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
Yii::log("In at top.................. click=".$clk , CLogger::LEVEL_WARNING, 'system.test.kim');

		// If unset, initialise the product page cookie (only way to tell if we're back-paging from it or going to it)
		if (!isset(Yii::app()->session['productdetail']))
			Yii::app()->session['productdetail'] = "0";

		// Get hosting site's params
		$util = new Util;
		if (isset($_GET['ge']))
			Yii::app()->session['checkoutEmail'] = $_GET['ge'];
		if (isset($_GET['gn']))
			Yii::app()->session['checkoutName'] = $_GET['gn'];
		if (isset($_GET['gu']))
			Yii::app()->session['checkoutGatewayUser'] = urldecode($util->decrypt($_GET['gu']));
		if (isset($_GET['gp']))
			Yii::app()->session['checkoutGatewayPassword'] = urldecode($util->decrypt($_GET['gp']));

		// Get the hosting site (referer)
		if (isset($_SERVER['HTTP_REFERER']))
		{
			if (!strstr($_SERVER['HTTP_REFERER'], "plugin.wireflydesign.com"))
			{
				$urlParts = explode("://", $_SERVER['HTTP_REFERER']);	// 0 = http or https
				$urlSubParts = explode("/", $urlParts[1]);				// 0 = www.example.com
				Yii::app()->session['http_referer'] = $urlParts[0] . "://" . $urlSubParts[0];
			}
		}

		// Get iframe params
		if (isset($_GET['sid']))
			Yii::app()->session['sid'] = $_GET['sid'];
		if (isset($_GET['page']))
			Yii::app()->session['page'] = $_GET['page'];
		if (isset($_GET['product']))
			Yii::app()->session['product'] = $_GET['product'];
		if (isset($_GET['department']))
			Yii::app()->session['department'] = $_GET['department'];

		// Are we redirecting the parent to the product page?
		if ((isset($_GET['show'])) && ($_GET['show'] == 'product'))
		{
			if (Yii::app()->session['productdetail'] == "0")
			{
Yii::log("redirecting parent to product page.................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
				$target = Yii::app()->session['http_referer'] . "/?page=" . Yii::app()->session['page'] . "&product=" . Yii::app()->session['product'];
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
			if ( (Yii::app()->session['productdetail'] == "0") || (isset($_GET['cartproduct'])) )
			{
Yii::log("parent is sending us to product page.................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
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
		if (Yii::app()->session['productdetail'] == "1")
if (!isset($_GET['click']))
		{
Yii::log("we've just back-paged from product page. redirecting parent.................. " , CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::app()->session['productdetail'] = "0";
			$target = Yii::app()->session['http_referer'] . "/?page=" . Yii::app()->session['page'] . "&department=" . Yii::app()->session['department'];
               echo
                   "<html><script>
                   // @@NB START POSTMESSAGE
                        parent.postMessage('redirect^" . $target . "', '*');
                    // @@NB END POSTMESSAGE
                    </script></html>";
			return;
		}


$pageParam = '';
if (isset($_GET['page']))
 $pageParam = $_GET['page'];
Yii::log("Still here.................. page is " . $pageParam , CLogger::LEVEL_WARNING, 'system.test.kim');
	

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

	// Invoke the Paymentsense module
	public function actionPay()
	{
header($this->p3p);
		if (isset($_GET['ptype']))
		{
			Yii::log("Checkout - payment type requested is " . $_GET['ptype'] , CLogger::LEVEL_WARNING, 'system.test.kim');
		}

		// Check vendor cart details exist
		if ((trim(Yii::app()->session['checkoutEmail']) == "")
		|| (trim(Yii::app()->session['checkoutName']) == ""))
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because vendor cart details arent present" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because vendor cart details dont exist');
		}

		// Check vendor has at least one payment type
		if ((trim(Yii::app()->session['checkoutGatewayUser']) == "")
		&& (trim(Yii::app()->session['checkoutPaypalUser']) == ""))
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
			Yii::app()->session['cartid'] = $cartId;
		}
		else
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because 'cartid' wasnt passed" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because cartid wasnt passed by caller');
		}
		Yii::log("Checkout - PAYMENT PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		if (trim(Yii::app()->session['sid']) == "")
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because SID is unset!" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because SID is not set or expired. (Has this session been idle a long time?)');
		}
		if (trim(Yii::app()->session['uid']) == "")
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because UID is unset!" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because UID is not set or expired. (Has this session been idle a long time?)');
		}

		//@@TODO: Remove the cookie stuff
		//$cartContent = Yii::app()->session[$cartId];
		$cartContent = $this->getCartByIP();
		if ((!($cartContent)) || ($cartContent == ''))
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because 'cartid' " . $cartId . " although seemingly valid, did not return that session var" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because cart details werent accessible. (Has this session been idle a long time?)');
		}

		// Get the passed fields (This is a @@TODO as its crap comms between page and here...)
		$a1 = "";
		$a2 = "";
		$a3 = "";
		$a4 = "";
		$pc = "";
		$e = "";
		$t = "";
		$n = "";
		if (isset($_GET['a1'])) $a1 = $_GET['a1'];
		if (isset($_GET['a2'])) $a2 = $_GET['a2'];
		if (isset($_GET['a3'])) $a3 = $_GET['a3'];
		if (isset($_GET['a4'])) $a4 = $_GET['a4'];
		if (isset($_GET['pc'])) $pc = $_GET['pc'];
		if (isset($_GET['e'])) $e = $_GET['e'];
		if (isset($_GET['t'])) $t = $_GET['t'];
		if (isset($_GET['n'])) $n = nl2br($_GET['n']);

		$ip = "UNKNOWN";
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");

		Order::model()->deleteAllByAttributes(array('ip' => $ip));	

		$totalGoods = 0.00;
		$cartArr = explode('|', $cartContent);
		if (count($cartArr) < 1)
		{
			Yii::log("Checkout - Nothing in cart array" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because cart has nothing in it');
		}
		for ($i = 0; $i < count($cartArr); $i++)
		{
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
			$order->uid = Yii::app()->session['uid'];
			$order->sid = Yii::app()->session['sid'];
			$order->ip = $ip;
			$order->vendor_gateway_id = "@@TODO gateway id";
			$order->vendor_gateway_password = "@@TODO gateway password";
			$order->http_product_id = $productId;
			$order->http_option_id = $optionId;
			$order->http_qty = $qty;
			$order->http_price = $price;
			$order->http_line_total = number_format(($qty * $price), 2, '.', '');
			$order->http_shipping_id = $shipId;
			$order->email_address = $e;
			$order->delivery_address1 = $a1;
			$order->delivery_address2 = $a2;
			$order->delivery_address3 = $a3;
			$order->delivery_address4 = $a4;
			$order->delivery_post_code = $pc;
			$order->telephone = $t;
			$order->notes = $n;
			$totalGoods += ($qty * $price);
			$order->return_url = Yii::app()->baseUrl;
			$order->gu = Yii::app()->session['checkoutGatewayUser'];
			$order->gp = Yii::app()->session['checkoutGatewayPassword'];
			if(!$order->save())
			{
				Yii::log("Checkout - Write error on order reord!" , CLogger::LEVEL_WARNING, 'system.test.kim');
				throw new CHttpException(400,'Error creating order');
			}
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
				$order->http_total = number_format($totalGoods, 2, '.', '');
				$order->save();
			}
		}
//$this->actionPaid();
//die('done');

		if ($_GET['ptype'] == 0)
		{
			// Go to paymentsense for payment
			$this->redirect(Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->session['sid'] . "&xid=" . rand(99999,999999) );
		}
		else if  ($_GET['ptype'] == 1)
		{
			// Paypal
			//$this->renderPartial('paypal');
			$this->layout = "nosuchlayout";			// Needs this to connect to Paypal
			$this->render('paypal',array(
				'description'=>'Purchase from ' . Yii::app()->session['checkoutName'],
            	'subtotal'=>$subtotalGoods,
            	'shipping'=>$shipping->price,
            	'total'=>$totalGoods,
        	));
/**/
			//$this->redirect(Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->session['sid'] . "&xid=" . rand(99999,999999) );
		}
	}

	// Return from Paymentsense
	public function actionPaid()
	{
header($this->p3p);
		Yii::log("PAID PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');

		$ip = "UNKNOWN";
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");

		if (trim(Yii::app()->session['uid']) == "")
		{
			Yii::log("Checkout - NOT LOADING PAID PAGE because UID is unset!" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to paid page because UID is not set or expired. Please contact the supplier. (Has this session been idle a long time?)');
		}

		$criteria = new CDbCriteria;
		//$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->addCondition("ip = '" . $ip . "'");

		// Retrieve the original order, now populated by paymentsense
		$orders = Order::model()->findAll($criteria);
		$orderCount = 0;
		$message = "";
		$message .= "<h2>Thank you for shopping with " . Yii::app()->session['checkoutName'] . "</h2><br>";
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

		$message .= "<table>";

		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $order->http_shipping_id);
		$shipping = ShippingOption::model()->find($criteria);
		$shippingAmount = "0.00";
		if ($shipping)
			$shippingAmount = $shipping->price;
		$message .= "<tr><td style='padding:15px'>Shipping: &pound " . $shippingAmount . "</td></tr>";
		$message .= "<tr><td style='padding:15px'>Total Paid: &pound " . $order->http_total . "</td></tr>";

		$message .= "</table>";

		$message .= "<br><hr><br>";	// Solid line separator

		// Delivery address

		$message .= "<b>Delivery address</b><br>";
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
		$message .= $order->notes . "<br>";

        // Send email
        $to = $order->email_address;
        if (strlen($to) > 0)
        {
            $from = Yii::app()->session['checkoutEmail'];
            $fromName = Yii::app()->session['checkoutName'];
            $subject = "Your order from " . Yii::app()->session['checkoutName'];
            // phpmailer
            $mail = new PHPMailer();
            $mail->AddAddress($to);
			$mail->AddBCC($from);
            $mail->SetFrom($from, $fromName);
            $mail->AddReplyTo($from, $fromName);
//            $mail->AddAttachment($pdf_filename);
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

		//@@ TODO: remove cookie stuff
		$cartId = Yii::app()->session['cartid'];
		Yii::app()->session[$cartId] = "";

		// Delete all the cart info
		Cart::model()->deleteAllByAttributes(array('ip' => $ip));

		// Update all the order records we just created with the 'x' in front of the ip
		$criteria = new CDbCriteria;
		$criteria->addCondition("ip = '" . $ip . "'");
		$orders = Order::model()->findAll($criteria);
		if ($order)
		{
			foreach ($orders as $order)
			{
				$order->ip = 'x-' . $ip;
				$order->save();
			}
		}

		$thanks = "<h2>Thank you for shopping with " . Yii::app()->session['checkoutName'] . "</h2><br>";
		$thanks .= "<p>An email will be sent to you shortly</p>";
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
