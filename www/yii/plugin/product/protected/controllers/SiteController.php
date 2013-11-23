<?php

class SiteController extends Controller
{
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
		$util = new Util;
		if (isset($_GET['ge']))
			Yii::app()->session['checkoutEmail'] = $_GET['ge'];
		if (isset($_GET['gn']))
			Yii::app()->session['checkoutName'] = $_GET['gn'];
		if (isset($_GET['gu']))
			Yii::app()->session['checkoutGatewayUser'] = urldecode($util->decrypt($_GET['gu']));
		if (isset($_GET['gp']))
			Yii::app()->session['checkoutGatewayPassword'] = urldecode($util->decrypt($_GET['gp']));

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

		//$this->render('index');
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionPlay($page)
	{
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
		if ((trim(Yii::app()->session['checkoutEmail']) == "")
		|| (trim(Yii::app()->session['checkoutName']) == "")
		|| (trim(Yii::app()->session['checkoutGatewayUser']) == "")
		|| (trim(Yii::app()->session['checkoutGatewayPassword']) == ""))
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because gateway details arent present" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because Gateway details dont exist');
		}

		if (isset($_GET['shipid']))
			$shipId = $_GET['shipid'];
		else
		{
			Yii::log("Checkout - NOT LOADING PAYMENT PAGE because 'shipid' wasnt passed" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(400,'Cannot proceed to payment because shipid wasnt passed by caller');
		}
		if (isset($_GET['cartid']))
			$cartId = $_GET['cartid'];
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

		$cartContent = Yii::app()->session[$cartId];
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
		if (isset($_GET['a1'])) $a1 = $_GET['a1'];
		if (isset($_GET['a2'])) $a2 = $_GET['a2'];
		if (isset($_GET['a3'])) $a3 = $_GET['a3'];
		if (isset($_GET['a4'])) $a4 = $_GET['a4'];
		if (isset($_GET['pc'])) $pc = $_GET['pc'];
		if (isset($_GET['e'])) $e = $_GET['e'];
		if (isset($_GET['t'])) $t = $_GET['t'];

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
			$order->http_line_total = ($qty * $price);
			$order->http_shipping_id = $shipId;
			$order->email_address = $e;
			$order->delivery_address1 = $a1;
			$order->delivery_address2 = $a2;
			$order->delivery_address3 = $a3;
			$order->delivery_address4 = $a4;
			$order->delivery_post_code = $pc;
			$order->telephone = $t;
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
				$order->http_total = $totalGoods;
				$order->save();
			}
		}

		// Go to paymentsense for payment
		$this->redirect(Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->session['sid'] . "&xid=" . rand(99999,999999) );
	}

	// Return from Paymentsense
	public function actionPaid()
	{
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
		foreach ($orders as $order)
		{
//die('ok');
		}

        // Send email
        $to = $order->email_address;
        if (strlen($to) > 0)
        {
            $from = Yii::app()->session['checkoutEmail'];
            $fromName = Yii::app()->session['checkoutName'];
            $subject = "Your order from " . Yii::app()->session['checkoutName'];
            $message = "<b>Thank you for your order, total £" . $order->http_total . "</b><br><br>";
            // phpmailer
            $mail = new PHPMailer();
            $mail->AddAddress($to);
//			$mail->AddBCC($param->cc_email_address);
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

		die('Thank you');
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
}
