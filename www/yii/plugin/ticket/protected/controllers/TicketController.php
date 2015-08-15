<?php

// TCPDF ticket file
require_once("php/ticket.php"); 
// PHPMailer
require_once('php/PHPMailer/class.phpmailer.php');

class TicketController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $isFreeEvent = false;
	public $isBackend = false;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','book','review','paid', 'ajaxDeleteTicket'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete', 'viewTicket'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'viewTicket'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Show the choice of events
	 */	 
	public function actionIndex()
	{
		Yii::log("ARE WE DEFAULT? INDEX LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		$this->render('index');
	}

	// Display the ticket booking form for the selected event
	public function actionBook($id)
	{
        Yii::log("EVENT BOOKING PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
        $model=$this->loadModel($id);

//echo "GET";
//print_r($_GET);
//echo "POST";
//print_r($_POST);

		if ((isset($_POST['ttotal'])) && ($_POST['ttotal'] != 0))
		{
			Yii::log("EVENT INDEX FORM FILLED: " . $_POST['ptotal'], CLogger::LEVEL_WARNING, 'system.test.kim');

            $ip = "UNKNOWN";
            if (getenv("HTTP_CLIENT_IP"))
                $ip = getenv("HTTP_CLIENT_IP");
            else if (getenv("HTTP_X_FORWARDED_FOR"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else if (getenv("REMOTE_ADDR"))
                $ip = getenv("REMOTE_ADDR");

			for ($x = 0; ; $x++)
			{
				if(!isset($_POST['line_' . $x . '_select']))	// ie no more lines
					break;
				$qty = $_POST['line_' . $x . '_select'];
				if ($qty == 0)	// ie no tickets for this line
					continue;

			Yii::log("EVENT INDEX LINE ITEM: " . $_POST['pline_' . $x . '_price'], CLogger::LEVEL_WARNING, 'system.test.kim');

				$order=new Order;
				$order->uid = Yii::app()->session['uid'];
				$order->sid = Yii::app()->session['sid'];
				$order->ip = $ip;
				$order->vendor_id = $model->ticket_vendor_id;
				$order->event_id = $model->id;
				$order->http_ticket_type_area = $_POST['pline_' . $x . '_area'];
				$order->http_ticket_type_id = $_POST['pline_' . $x . '_id'];
				$order->http_ticket_type_qty = $qty;
				$order->http_ticket_type_price = $_POST['pline_' . $x . '_price'];
				$order->http_ticket_type_total = $_POST['pline_' . $x . '_total'];
				$order->http_total = $_POST['ptotal'];

				$order->return_url = Yii::app()->baseUrl;
				if(!$order->save())
				{
					$this->redirect(array('admin'));
				}
			}

			//$this->actionReview();
			$this->redirect(array('review'));
			return;
		}

        // renders the view file 'protected/views/ticket/book.php'
        // using the default layout 'protected/views/layouts/main.php'
		if (!($model->active))
			die('Sorry, this ticket event is inactive now');
		else
        	$this->render('book',array(
                        	'model'=>$model,
                        	'somedata'=>array(1,2,3),
        ));
	}

	public function actionReview()
	{
        Yii::log("EVENT REVIEW PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');

        $ip = "UNKNOWN";
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");

		if ( (isset($_POST['rtotal'])) && (($_POST['rtotal'] != 0) || (isset($_POST['is_free_event']))    ) )
		{
			Yii::log("EVENT REVIEW FORM FILLED: " . $_POST['rtotal'], CLogger::LEVEL_WARNING, 'system.test.kim');

			// Is this order is online-paid, online-free, or backend manual? This determines whether the payment page is shown.
			$this->isFreeEvent = false;
			if (isset($_POST['is_free_event']))
				$this->isFreeEvent = true;
			$this->isBackend = false;
			if (isset($_POST['is_backend']))
				$this->isBackend = true;


			////////////////////////////////////////Order::model()->deleteAllByAttributes(array('ip' => $ip));

			// Update all the order records for this ip with the email and other contact details
			$criteria = new CDbCriteria;
        	//$criteria->addCondition("uid = " . Yii::app()->session['uid']);
        	$criteria->addCondition("ip = '" . $ip . "'");
        	$orders = Order::model()->findAll($criteria);
        	foreach ($orders as $order)
        	{
				$order->email_address = $_POST['email1'];
				$order->telephone = $_POST['telephone'];
				if (($this->isFreeEvent) || ($this->isBackend))
				{
					$order->order_number = Yii::app()->session['uid'] . '-' . time();
					if (isset($_POST['free_name'])) $order->free_name = $_POST['free_name'];
					if (isset($_POST['free_address1'])) $order->free_address1 = $_POST['free_address1'];
					if (isset($_POST['free_address2'])) $order->free_address2 = $_POST['free_address2'];
					if (isset($_POST['free_address3'])) $order->free_address3 = $_POST['free_address3'];
					if (isset($_POST['free_address4'])) $order->free_address4 = $_POST['free_address4'];
					if (isset($_POST['free_post_code'])) $order->free_post_code = $_POST['free_post_code'];
				}
				$order->save();
			}


/**********************
			for ($x = 0; ; $x++)
			{
				if(!isset($_POST['line_' . $x . '_select']))	// ie no more lines
					break;
				$qty = $_POST['line_' . $x . '_select'];
				if ($qty == 0)	// ie no tickets for this line
					continue;

			Yii::log("EVENT REVIEW LINE ITEM: " . $_POST['pline_' . $x . '_price'], CLogger::LEVEL_WARNING, 'system.test.kim');

				$order=new Order;
				$order->uid = Yii::app()->session['uid'];
				$order->sid = Yii::app()->session['sid'];
				$order->ip = $ip;
				$order->vendor_id = $model->ticket_vendor_id;
				$order->event_id = $model->id;
				$order->http_ticket_type_area = $_POST['pline_' . $x . '_area'];
				$order->http_ticket_type_id = $_POST['pline_' . $x . '_id'];
				$order->http_ticket_type_qty = $qty;
				$order->http_ticket_type_price = $_POST['pline_' . $x . '_price'];
				$order->http_ticket_type_total = $_POST['pline_' . $x . '_total'];
				$order->http_total = $_POST['rtotal'];
				$order->email_address = $_POST['email1'];
				$order->telephone = $_POST['telephone'];
				if (($this->isFreeEvent) || ($this->isBackend))
				{
					$order->order_number = Yii::app()->session['uid'] . '-' . time();
					if (isset($_POST['free_name'])) $order->free_name = $_POST['free_name'];
					if (isset($_POST['free_address1'])) $order->free_address1 = $_POST['free_address1'];
					if (isset($_POST['free_address2'])) $order->free_address2 = $_POST['free_address2'];
					if (isset($_POST['free_address3'])) $order->free_address3 = $_POST['free_address3'];
					if (isset($_POST['free_address4'])) $order->free_address4 = $_POST['free_address4'];
					if (isset($_POST['free_post_code'])) $order->free_post_code = $_POST['free_post_code'];
				}

				$order->return_url = Yii::app()->baseUrl;
				if(!$order->save())
				{
					$this->redirect(array('admin'));
				}
			}
**********************/

			// Go to paymentsense for payment
			if (!($this->isFreeEvent) && !($this->isBackend))
			{
        		Yii::log("GOING TO PAYMENTSENSE SCRIPT" , CLogger::LEVEL_WARNING, 'system.test.kim');

/*
$tmp = Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->session['sid'] . "&xid=" . rand(99999,999999) . "&rtotal=" . $_POST['rtotal'];
$tmp2 = str_replace("http://", "https://", $tmp);
$this->redirect($tmp2);
*/

				$this->redirect(Yii::app()->baseUrl . "/php/gw/EntryPoint.php?sid=" . Yii::app()->session['sid'] . "&xid=" . rand(99999,999999) . "&rtotal=" . $_POST['rtotal']);
			}
			else
			{
        		Yii::log("CALLING actionPaid()" , CLogger::LEVEL_WARNING, 'system.test.kim');
				$this->actionPaid();
				return;
			}
		}

        // renders the view file 'protected/views/ticket/book.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('review',array(
                        'ip'=>$ip,
        ));
	}

	public function actionPaid()
	{
        Yii::log("PAID PAGE ENTRYPOINT LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');

          $ip = "UNKNOWN";
          if (getenv("HTTP_CLIENT_IP"))
              $ip = getenv("HTTP_CLIENT_IP");
          else if (getenv("HTTP_X_FORWARDED_FOR"))
              $ip = getenv("HTTP_X_FORWARDED_FOR");
          else if (getenv("REMOTE_ADDR"))
              $ip = getenv("REMOTE_ADDR");

        $ticket_type_event_arr = array();
        $ticket_type_area_arr = array();
		$ticket_type_id_arr = array();
		$ticket_type_qty_arr = array();
		$ticket_type_price_arr = array();
		$ticket_type_total_arr = array();
		$ticketNumbers = array();
		if (isset($_GET['card_amount']))
			$ticket_card_amount = number_format( ($_GET['card_amount'] / 100), 2);
		else
			$ticket_card_amount = 0;

		$criteria = new CDbCriteria;
        //$criteria->addCondition("uid = " . Yii::app()->session['uid']);
        $criteria->addCondition("ip = '" . $ip . "'");

		// Retrieve the original order, now populated by paymentsense
        $orders = Order::model()->findAll($criteria);

		$orderNum = "";
        $orderCount = 0;
        foreach ($orders as $order)
        {
			// Check for duplicate Auth Code!!!!!
			if ($orderCount == 0)
			{
				if ((strstr($order->auth_code, "AuthCode:")) != false)
				{
					$file = Yii::app()->basePath . "/../tmp/ticketemail.dat";
					if (strpos(file_get_contents($file), $order->auth_code) !== false)
					{
    					Yii::log("********* PAID PAGE BAILING - DETECTED DUPLICATE AUTH:" . $order->auth_code, CLogger::LEVEL_WARNING, 'system.test.kim');
						die('Sorry, there has been a problem processing this order');
						return;
					}
					file_put_contents($file, $order->auth_code . "\n", FILE_APPEND);
				}
			}

			// Store this. Free and manual tickets dont come into this loop
			$orderNum = $order->order_number;

        	// Write a transaction
			$transaction=new Transaction;
			$transaction->uid = $order->uid;
			$transaction->ip = $order->ip;
			$transaction->timestamp = date("Y-m-d H:i:s");
			$transaction->order_number = $order->order_number;
			$transaction->auth_code = $order->auth_code;
			$transaction->email = $order->email_address;
			$transaction->telephone = $order->telephone;
			$transaction->vendor_id = $order->vendor_id;
			$transaction->event_id = $order->event_id;
			$transaction->http_area_id = $order->http_ticket_type_area;
			$transaction->http_ticket_type_id = $order->http_ticket_type_id;
			$transaction->http_ticket_qty = $order->http_ticket_type_qty;
			$transaction->http_ticket_price = $order->http_ticket_type_price;
			$transaction->http_ticket_total = $order->http_ticket_type_total;
			$transaction->http_total = $order->http_total;
			$transaction->save();

        	Yii::log("PAID PAGE WROTE TRANSACTION" , CLogger::LEVEL_WARNING, 'system.test.kim');

			if ($orderCount == 0)
			{
				if (($this->isFreeEvent) || ($this->isBackend))
				{
					// Record one 'auth' record with the address details. (Paymentsense script writes the Auth for pay events)
					$auth=new Auth;
					$auth->uid = $order->uid;
					$auth->order_number = $order->order_number;
					$auth->card_name = $order->free_name;
					$auth->card_number = '';
					$auth->address1 = $order->free_address1;
					$auth->address2 = $order->free_address2;
					$auth->address3 = $order->free_address3;
					$auth->address4 = $order->free_address4;
					$auth->post_code = $order->free_post_code;
					$auth->save();
        			Yii::log("PAID PAGE WROTE AUTH FOR FREE OR BACKEND ORDER" , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
			}

			Yii::log("PAID PAGE ABT TO UPDATE SEATING" , CLogger::LEVEL_WARNING, 'system.test.kim');

			// Update the used seating number
			$ticketType = TicketType::model()->findByPk($order->http_ticket_type_id);
			if ($ticketType)
			{
				$area = Area::model()->findByPk($order->http_ticket_type_area);
				if ($area)
				{
					$area->uid = $order->uid;
					$multiplier = 1;
					if ($ticketType->places_per_ticket > 0)
						$multiplier = $ticketType->places_per_ticket;
					$area->used_places += ($order->http_ticket_type_qty * $multiplier);
					$area->save();
				}
			}

			Yii::log("PAID PAGE FINISHED UPDATING SEATING" , CLogger::LEVEL_WARNING, 'system.test.kim');

			// Rebuild the array, for ticket printing
        	array_push($ticket_type_event_arr, $order->event_id);
        	array_push($ticket_type_area_arr,  $order->http_ticket_type_area);
			array_push($ticket_type_id_arr,    $order->http_ticket_type_id);
			array_push($ticket_type_qty_arr,   $order->http_ticket_type_qty);
			array_push($ticket_type_price_arr, $order->http_ticket_type_price);
			array_push($ticket_type_total_arr, $order->http_ticket_type_total);

			$orderCount++;

			Yii::log("PAID PAGE REBUILT TICKET ARRAY" , CLogger::LEVEL_WARNING, 'system.test.kim');
        }

		Yii::log("PAID PAGE FINISHED WITH ORDER LOOP. ORDERITEMS:" . $orderCount, CLogger::LEVEL_WARNING, 'system.test.kim');

		if ((!$this->isFreeEvent) && (!$this->isBackend))
		{
			// Pick up the Auth record (either created by Paymentsense or by 'free' above) for ticket name and card number printing
			$criteria = new CDbCriteria;
        	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
        	$criteria->addCondition("order_number = '" . $orderNum . "'");
        	$auth = Auth::model()->find($criteria);
			if (!$auth)
			{
				Yii::log("PAID PAGE *** COULD NOT RETRIEVE AUTH RECORD ***" , CLogger::LEVEL_WARNING, 'system.test.kim');
			}
			$crdNum = '************ ' . substr($auth->card_number, 12, 4);
		}
		else
		{
			$crdNum = 'No card details';
		}
  
		Yii::log("PAID PAGE ABOUT TO GENERATE TICKETS" , CLogger::LEVEL_WARNING, 'system.test.kim');

		// Print tickets
		$ticketNumbers = array();
		genTicket(
			$order->order_number,
			$auth->card_name,
			$crdNum,
			$order->vendor_id,
			$ticket_type_event_arr,
			$ticket_type_area_arr,
			$ticket_type_id_arr,
			$ticket_type_qty_arr,
			$ticket_type_price_arr,
			$ticket_type_total_arr,
			$ticket_card_amount,
			$ticketNumbers
		);

		Yii::log("PAID PAGE FINISHED GENERATING TICKETS" , CLogger::LEVEL_WARNING, 'system.test.kim');

		$pdf_filename = '/tmp/' . $order->order_number . '.pdf';

		// Pick up the vendor record to BCC them
		$bcc = "";
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->addCondition("id = " . $order->vendor_id);
		$vendor = Vendor::model()->find($criteria);
		if (($vendor) && (trim($vendor->email) != "") && ($vendor->notify_sale == 1))
			$bcc = $vendor->email;

		// Send email
		$to = $order->email_address;
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Your tickets purchased at DG Link";
			$message = '<b>Thank you for using the DG Link to order your ticket(s).</b> <br> The attached PDF file contains your ticket(s) and card receipt. Please print all pages and bring them with you to your event or activity. The barcode on each ticket can only be used once.<br> If you ever need to reprint your tickets you may login to the site and do so from your account page. If you have forgotten your log in details you can request a password reminder.<br> We hope you enjoy your event.  --  The DG Link Team';
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			$mail->AddAttachment($pdf_filename);
			if ($bcc != "")
				$mail->AddBCC($bcc);
			$mail->AddBCC("ticketorders@wireflydesign.com");
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

		// delete the temp file
		copy($pdf_filename, Yii::app()->basePath . '/../tkts/' . $order->order_number . '.pdf');
		$rnd = rand(10000,99999) . '_' . $order->order_number;
		copy($pdf_filename, Yii::app()->basePath . '/../tktp/' . $rnd . '.pdf');
		unlink($pdf_filename);

		// Write scan records
		foreach ($ticketNumbers as $ticketNumber)
		{
			$scan=new Scan;
			$scan->uid = $order->uid;
			$scan->order_number = $order->order_number;
			$scan->ticket_number = $ticketNumber;
			$scan->timestamp = '0000-00-00 00:00:00';
			$scan->save();
		}
        	
		// Delete the order records
		Order::model()->deleteAllByAttributes(array('ip' => $ip));

        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        Yii::log("PAID PAGE TICKET PRINT URL IS " . Yii::app()->baseUrl . '/tktp/' . $rnd . '.pdf', CLogger::LEVEL_WARNING, 'system.test.kim');
        $this->render('thankyou',array(
                        /*'model'=>$model,*/
                        'myticket'=>Yii::app()->baseUrl . '/tktp/' . $rnd . '.pdf',
                        'somedata'=>array(1,2,3),
        ));
	}

	// View a test ticket
	public function actionViewTicket()
	{
		//if (trim($_GET['event']) == "")
			//throw new CHttpException(500,'No event id was given for ticket view');
        $ticket_type_event_arr = array();
        $ticket_type_area_arr = array();
		$ticket_type_id_arr = array();
		$ticket_type_qty_arr = array();
		$ticket_type_price_arr = array();
		$ticket_type_total_arr = array();
		$ticketNumbers = array();
       	array_push($ticket_type_event_arr, $_GET['event']);
       	array_push($ticket_type_area_arr,  0);
		array_push($ticket_type_id_arr,    0);
		array_push($ticket_type_qty_arr,   1);
		array_push($ticket_type_price_arr, 0);
		array_push($ticket_type_total_arr, 0);
		genTicket(
			"test-ticket",				// order number
			"Test Name",				// card name
			"1234 5678 9012 3456",		// card number
			Yii::app()->session['uid'],	// vendor id
			$ticket_type_event_arr,
			$ticket_type_area_arr,
			$ticket_type_id_arr,
			$ticket_type_qty_arr,
			$ticket_type_price_arr,
			$ticket_type_total_arr,
			"£0.00",
			$ticketNumbers
		);
		//$data = file_get_contents("/tmp/test-ticket.pdf");
		//header("Content-type: application/octet-stream");
		//header("Content-disposition: attachment;filename=test-ticket.pdf");
		//echo $data;
rename("/tmp/test-ticket.pdf", "test-ticket.pdf");
echo "<embed src='/ticket/test-ticket.pdf' width='100%' height='100%' type='application/pdf'>";
//echo "<iframe src='/ticket/test-ticket.pdf' width='100%' style='height:100%'></iframe>";
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Event::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='event-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionAjaxDeleteTicket()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
			$status = 'false';

            $ip = "UNKNOWN";
            if (getenv("HTTP_CLIENT_IP"))
                $ip = getenv("HTTP_CLIENT_IP");
            else if (getenv("HTTP_X_FORWARDED_FOR"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else if (getenv("REMOTE_ADDR"))
                $ip = getenv("REMOTE_ADDR");

			$jsId = $_POST['id'];
			$eventId = $_POST['event'];
			$areaId = $_POST['area'];
			$ticketTypeId = $_POST['ttype'];
			Yii::log("AJAX CALL: EventId:" . $eventId . " and areaId:" . $areaId . " and ticketTypeID:" . $ticketTypeId, CLogger::LEVEL_WARNING, 'system.test.kim');

			// Delete the selection
			$criteria = new CDbCriteria;
			$criteria->addCondition("ip = '" . $ip . "'");
			//$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("event_id = " . $eventId);
			$criteria->addCondition("http_ticket_type_area = " . $areaId);
			$criteria->addCondition("http_ticket_type_id = " . $ticketTypeId);
			$orders = Order::model()->deleteAll($criteria);
			$status = 'true';

            echo CJSON::encode(array(
                'id' => $jsId,
                'status' => $status,
            ));
		}
	}

}
